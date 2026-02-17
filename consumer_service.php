<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Obtener directorio base del proyecto de forma absoluta
$BASE_DIR = dirname(__FILE__);
if (!is_dir($BASE_DIR)) {
    die("❌ ERROR: Directorio base no existe: $BASE_DIR\n");
}

// Cambiar al directorio base para asegurar rutas correctas
chdir($BASE_DIR);

// Función helper para asegurar que un directorio existe
function ensureDirectory($dir) {
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            throw new Exception("No se pudo crear directorio: $dir");
        }
        echo "✅ Directorio creado: $dir\n";
    }
    return realpath($dir);
}

// Asegurar que los directorios necesarios existen
try {
    ensureDirectory($BASE_DIR . '/uploads');
    ensureDirectory($BASE_DIR . '/videos');
    ensureDirectory($BASE_DIR . '/previa');
    ensureDirectory($BASE_DIR . '/imagenes_tablero');
    ensureDirectory($BASE_DIR . '/logs');
} catch (Exception $e) {
    die("❌ ERROR: " . $e->getMessage() . "\n");
}

require_once 'vendor/autoload.php';
require_once 'config/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// Límites para VPS 8GB: menos hilos = menos RAM; nice evita acaparar CPU
$FFMPEG_THREADS = defined('FFMPEG_THREADS') ? (int) FFMPEG_THREADS : 2;
$FFMPEG_NICE    = defined('FFMPEG_NICE')    ? (int) FFMPEG_NICE    : 5;
$NICE_CMD       = ($FFMPEG_NICE >= 0 && $FFMPEG_NICE <= 19) ? "nice -n {$FFMPEG_NICE} " : '';
use PhpAmqpLib\Message\AMQPMessage;





$conn = new AMQPStreamConnection(
    host_rabbit_mq, port_rabbit_mq, user_rabbit_mq, password_rabbit_mq, vhost_rabbit_mq
);

$channel = $conn->channel();
$channel->queue_declare('procesar_multimedia', false, true, false, false);
$channel->queue_declare('multimedia_resultado', false, true, false, false);
$channel->basic_qos(null, 1, null);

echo " [*] Worker FFmpeg con lógica de cortes activo...\n";

$callback = function ($msg) use ($channel, $BASE_DIR) {
    $data = json_decode($msg->body, true);
    
    try {
        if (empty($data['ruta_tmp']) || empty($data['board_id'])) {
            throw new Exception("Datos insuficientes");
        }

        $rutaOriginal = $data['ruta_tmp'];
        $board_id     = (int)$data['board_id'];
        $fecha        = date('YmdHis');
        $tipo_archivo = $data['tipo_archivo'] ?? 'video';
        $es_url_externa = false;
        $archivo_local = $rutaOriginal;
        
        // Verificar si es una URL externa
        if (filter_var($rutaOriginal, FILTER_VALIDATE_URL)) {
            $es_url_externa = true;
            echo "🌐 URL externa detectada: $rutaOriginal\n";
            
            // Verificar que la URL es accesible
            $ch = curl_init($rutaOriginal);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code !== 200) {
                throw new Exception("URL no accesible (HTTP $http_code): $rutaOriginal");
            }
            
            echo "✅ URL verificada (HTTP $http_code)\n";
            
            // Descargar el archivo a un directorio temporal
            // Extraer nombre del archivo de la URL, limpiar caracteres especiales
            $url_path = parse_url($rutaOriginal, PHP_URL_PATH);
            $nombre_archivo = basename($url_path);
            
            // Si no hay nombre en la URL, usar extensión detectada o .mp4 por defecto
            if (empty($nombre_archivo) || $nombre_archivo === '/') {
                $nombre_archivo = "video_{$board_id}.mp4";
            } else {
                // Limpiar nombre de archivo (quitar caracteres especiales)
                $nombre_archivo = preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombre_archivo);
                // Asegurar extensión .mp4 si no tiene
                if (!preg_match('/\.(mp4|avi|mov|mkv|webm)$/i', $nombre_archivo)) {
                    $nombre_archivo .= '.mp4';
                }
            }
            
            $archivo_local = $BASE_DIR . "/uploads/temp_{$board_id}_{$fecha}_{$nombre_archivo}";
            echo "📥 Descargando archivo a: $archivo_local\n";
            
            // Asegurar que el directorio uploads existe
            ensureDirectory($BASE_DIR . '/uploads');
            
            $ch = curl_init($rutaOriginal);
            $fp = fopen($archivo_local, 'wb');
            
            if (!$fp) {
                throw new Exception("No se pudo crear archivo temporal: $archivo_local");
            }
            
            curl_setopt_array($ch, [
                CURLOPT_FILE => $fp,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 300, // 5 minutos para descargas grandes
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; Commuty-ED/1.0)',
                CURLOPT_HTTPHEADER => ['Accept: */*']
            ]);
            
            $curl_result = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            $downloaded_size = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
            curl_close($ch);
            fclose($fp);
            
            if ($curl_result === false || !empty($curl_error)) {
                @unlink($archivo_local); // Limpiar archivo parcial
                throw new Exception("Error cURL descargando: $curl_error - URL: $rutaOriginal");
            }
            
            if ($http_code !== 200) {
                @unlink($archivo_local); // Limpiar archivo parcial
                throw new Exception("Error HTTP descargando (HTTP $http_code): $rutaOriginal");
            }
            
            if (!file_exists($archivo_local) || filesize($archivo_local) === 0) {
                @unlink($archivo_local); // Limpiar archivo vacío
                throw new Exception("Archivo descargado está vacío o no existe: $archivo_local");
            }
            
            $tamano_descargado = filesize($archivo_local);
            echo "✅ Archivo descargado: " . round($tamano_descargado / 1024 / 1024, 2) . " MB (HTTP $http_code)\n";
            
        } else {
            // Es un archivo local
            if (!file_exists($rutaOriginal)) {
                throw new Exception("Archivo no encontrado en: $rutaOriginal");
            }
            echo "📁 Archivo local: $rutaOriginal\n";
        }
        
        echo "Ruta original: $rutaOriginal\n";
        echo "Tipo archivo: " . ($data['tipo_archivo'] ?? 'video') . "\n";
        // 2. Definir rutas de salida (absolutas)
        $rutaImagen      = $BASE_DIR . "/imagenes_tablero/{$fecha}_{$board_id}.jpg";
        $reciduo_video   = $BASE_DIR . "/previa/{$fecha}_{$board_id}.mp4";
        $video_completo  = $BASE_DIR . "/videos/{$fecha}_{$board_id}.mp4";
        
        // Asegurar que los directorios existen
        ensureDirectory($BASE_DIR . '/imagenes_tablero');
        ensureDirectory($BASE_DIR . '/previa');
        ensureDirectory($BASE_DIR . '/videos');

        // 3. SIEMPRE comprimir y convertir a MP4 (sin importar el formato original)
        echo "🔄 Comprimiendo y convirtiendo video a MP4...\n";
        
        // Verificar que el archivo de entrada existe y es accesible
        if (!file_exists($archivo_local)) {
            throw new Exception("Archivo de entrada no existe: $archivo_local");
        }
        
        if (!is_readable($archivo_local)) {
            throw new Exception("Archivo de entrada no es legible: $archivo_local");
        }
        
        $file_size = filesize($archivo_local);
        echo "📊 Tamaño archivo entrada: " . round($file_size / 1024 / 1024, 2) . " MB\n";
        
        // Verificar que el directorio de salida existe y es escribible
        $video_dir = dirname($video_completo);
        ensureDirectory($video_dir);
        
        if (!is_writable($video_dir)) {
            throw new Exception("Directorio de salida no es escribible: $video_dir");
        }
        
        // FFmpeg comprimir: -threads limita RAM en VPS; nice evita OOM por competir con otros procesos
        // -preset fast = balance velocidad/compresión; -crf 23 = calidad estándar
        $cmd_compress = $NICE_CMD . "ffmpeg -y -threads {$FFMPEG_THREADS} -i " . escapeshellarg($archivo_local)
            . " -c:v libx264 -preset fast -crf 23 -c:a aac -b:a 128k -movflags +faststart "
            . escapeshellarg($video_completo) . " 2>&1";
        echo "Ejecutando compresión (threads={$FFMPEG_THREADS}, nice={$FFMPEG_NICE})...\n";
        $output_compress = [];
        exec($cmd_compress, $output_compress, $return_code);
        
        if ($return_code !== 0) {
            echo "⚠ FFmpeg retornó código de error: $return_code\n";
            echo "Salida de FFmpeg:\n" . implode("\n", array_slice($output_compress, -30)) . "\n";
        }
        
        if ($return_code !== 0 || !file_exists($video_completo)) {
            echo "⚠ Compresión falló, intentando copia directa (sin re-encode, menos RAM)...\n";
            $cmd_copy = $NICE_CMD . "ffmpeg -y -threads {$FFMPEG_THREADS} -i " . escapeshellarg($archivo_local)
                . " -c copy " . escapeshellarg($video_completo) . " 2>&1";
            $output_copy = [];
            exec($cmd_copy, $output_copy, $return_code_copy);
            
            if ($return_code_copy !== 0) {
                echo "❌ Copia directa también falló (código: $return_code_copy)\n";
                echo "Salida de FFmpeg:\n" . implode("\n", array_slice($output_copy, -30)) . "\n";
            }
        }
        
        if (!file_exists($video_completo)) {
            $error_details = [
                "Archivo entrada: $archivo_local",
                "Archivo entrada existe: " . (file_exists($archivo_local) ? 'Sí' : 'No'),
                "Archivo entrada legible: " . (is_readable($archivo_local) ? 'Sí' : 'No'),
                "Directorio salida: $video_dir",
                "Directorio salida existe: " . (is_dir($video_dir) ? 'Sí' : 'No'),
                "Directorio salida escribible: " . (is_writable($video_dir) ? 'Sí' : 'No'),
                "Archivo salida esperado: $video_completo"
            ];
            throw new Exception("No se pudo crear el video comprimido: $video_completo\n" . implode("\n", $error_details));
        }
        
        $filesize_original = filesize($archivo_local);
        $filesize_compressed = filesize($video_completo);
        $ratio = round(($filesize_compressed / $filesize_original) * 100, 1);
        echo "✅ Video comprimido: " . round($filesize_original/1024/1024, 2) . "MB → " . round($filesize_compressed/1024/1024, 2) . "MB ({$ratio}%)\n";
        
        // Liberar espacio y presión en disco/RAM: borrar original ya no necesario (trabajamos con video_completo)
        if (file_exists($archivo_local)) {
            @unlink($archivo_local);
            echo "🗑️ Archivo original eliminado para liberar recursos.\n";
        }
        
        // Obtener duración del video comprimido
        $duracionRaw = shell_exec("ffprobe -v error -show_entries format=duration -of default=nw=1:nk=1 -sexagesimal " . escapeshellarg($video_completo));
        $partesTiempo = explode(':', trim($duracionRaw));
        $tiempo_cut   = isset($partesTiempo[1]) ? (int)$partesTiempo[1] : 0; // Minutos
        $tiempo_cut_s = isset($partesTiempo[2]) ? (int)explode('.', $partesTiempo[2])[0] : 0; // Segundos
        
        echo "Duración: {$tiempo_cut} minutos, {$tiempo_cut_s} segundos\n";
        
        // Usar el video comprimido para generar preview y thumbnail
        $rutaTemVideo = escapeshellarg($video_completo);
        $tmp_dir = $BASE_DIR . '/tmp';
        ensureDirectory($tmp_dir);
        $prefix       = $tmp_dir . "/tmp_{$board_id}_"; // Prefijo para archivos temporales .ts (ruta absoluta)

        // 3. Lógica de Cortes de Video (Tu lógica integrada)
        $cortes = [];
        $ss_imagen = "00:00:01";

        if ($tiempo_cut >= 2 && $tiempo_cut < 6) {
            $cortes = [['0:01:00', 2], ['0:02:10', 2], ['0:03:30', 2], ['0:05:10', 2], ['0:05:12', 2], ['0:05:20', 2]];
            $ss_imagen = "00:01:20";
        } elseif ($tiempo_cut >= 6 && $tiempo_cut < 15) {
            $cortes = [['0:01:00', 2], ['0:02:10', 2], ['0:04:30', 2], ['0:10:30', 4]];
            $ss_imagen = "00:02:30";
        } elseif ($tiempo_cut >= 15 && $tiempo_cut <= 26) {
            $cortes = [['0:05:00', 2], ['0:08:10', 2], ['0:10:30', 2], ['0:14:30', 4]];
            $ss_imagen = "00:03:30";
        } elseif ($tiempo_cut < 1) {
            // Lógica por segundos
            if ($tiempo_cut_s >= 10 && $tiempo_cut_s <= 20) {
                $cortes = [['0:00:03', 2], ['0:00:02', 2], ['0:00:08', 2], ['0:00:05', 4]];
                $ss_imagen = "00:00:05";
            } elseif ($tiempo_cut_s > 20 && $tiempo_cut_s <= 40) {
                $cortes = [['0:00:02', 2], ['0:00:05', 2], ['0:00:10', 2], ['0:00:19', 4]];
                $ss_imagen = "00:00:15";
            } elseif ($tiempo_cut_s >= 3 && $tiempo_cut_s < 10) {
                $cortes = [['0:00:01', 2], ['0:00:02', 2], ['0:00:03', 2], ['0:00:08', 4]];
                $ss_imagen = "00:00:01";
            } elseif ($tiempo_cut_s > 40) {
                $cortes = [['0:00:30', 2], ['0:00:10', 2], ['0:00:20', 2], ['0:00:55', 4]];
                $ss_imagen = "00:00:15";
            } else {
                $cortes = [['0:00:01', 2], ['0:00:02', 2], ['0:00:03', 2], ['0:00:05', 4]];
                $ss_imagen = "00:00:01";
            }
        } elseif ($tiempo_cut >= 1 && $tiempo_cut <= 2) {
            $cortes = [['0:00:40', 2], ['0:00:30', 2], ['0:01:30', 2], ['0:00:50', 4]];
            $ss_imagen = "00:00:07";
        } elseif ($tiempo_cut >= 27 && $tiempo_cut <= 50) {
            $cortes = [['0:10:40', 2], ['0:15:30', 2], ['0:18:30', 2], ['0:25:50', 4]];
            $ss_imagen = "00:03:30";
        }

        // 4. Cortes para preview: -c copy = bajo uso de RAM; 1 thread por proceso
        $ts_files = [];
        foreach ($cortes as $i => $corte) {
            $tmp_name = "{$prefix}{$i}.ts";
            $ts_files[] = $tmp_name;
            exec($NICE_CMD . "ffmpeg -y -threads 1 -ss {$corte[0]} -t {$corte[1]} -i {$rutaTemVideo} -c copy -bsf:v h264_mp4toannexb -f mpegts " . escapeshellarg($tmp_name) . " 2>&1");
        }

        if (!empty($ts_files)) {
            $concat_string = implode('|', $ts_files);
            exec($NICE_CMD . "ffmpeg -y -threads 1 -i \"concat:{$concat_string}\" -c copy -bsf:a aac_adtstoasc " . escapeshellarg($reciduo_video) . " 2>&1");
            foreach ($ts_files as $f) { @unlink($f); }
        }

        // 5. Miniatura (1 frame, poco uso de RAM)
        exec($NICE_CMD . "ffmpeg -y -threads 1 -i {$rutaTemVideo} -ss {$ss_imagen} -vframes 1 " . escapeshellarg($rutaImagen) . " 2>&1");

        // 6. Respuesta y limpieza (archivo original ya borrado tras compresión; solo borrar upload local si no era URL)
        if (!$es_url_externa && file_exists($rutaOriginal) && $rutaOriginal !== $video_completo) {
            @unlink($rutaOriginal);
        }

        // Guardar en BD solo rutas relativas (desde la raíz del proyecto), no absolutas
        $preview_relativo       = "previa/{$fecha}_{$board_id}.mp4";
        $video_completo_relativo = "videos/{$fecha}_{$board_id}.mp4";
        $thumbnail_relativo     = "imagenes_tablero/{$fecha}_{$board_id}.jpg";

        $channel->basic_publish(
            new AMQPMessage(json_encode([
                'board_id' => $board_id,
                'preview' => $preview_relativo,
                'video_completo' => $video_completo_relativo,
                'thumbnail' => $thumbnail_relativo,
                'status' => 'ok'
            ])), '', 'multimedia_resultado'
        );

        echo "✔ Board {$board_id} procesado correctamente.\n";
        $msg->ack();

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $channel->basic_publish(
            new AMQPMessage(json_encode([
                'board_id' => $data['board_id'] ?? null,
                'status' => 'error',
                'message' => $e->getMessage()
            ])), '', 'multimedia_resultado'
        );
        $msg->nack(false, false);
    }
};

$channel->basic_consume('procesar_multimedia', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}