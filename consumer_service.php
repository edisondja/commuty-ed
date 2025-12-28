<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$conn = new AMQPStreamConnection(
    host_rabbit_mq, port_rabbit_mq, user_rabbit_mq, password_rabbit_mq, vhost_rabbit_mq
);

$channel = $conn->channel();
$channel->queue_declare('procesar_multimedia', false, true, false, false);
$channel->queue_declare('multimedia_resultado', false, true, false, false);
$channel->basic_qos(null, 1, null);

echo " [*] Worker FFmpeg con lógica de cortes activo...\n";

$callback = function ($msg) use ($channel) {
    $data = json_decode($msg->body, true);
    
    try {
        if (empty($data['ruta_tmp']) || empty($data['board_id'])) {
            throw new Exception("Datos insuficientes");
        }

        $rutaOriginal = $data['ruta_tmp'];
        $board_id     = (int)$data['board_id'];
        $fecha        = date('YmdHis');
        
        if (!file_exists($rutaOriginal)) {
            throw new Exception("Archivo no encontrado en: $rutaOriginal");
        }

        // 1. Obtener información de duración
        $duracionRaw = shell_exec("ffprobe -v error -show_entries format=duration -of default=nw=1:nk=1 -sexagesimal " . escapeshellarg($rutaOriginal));
        // Formato esperado de ffprobe con -sexagesimal: 00:05:30.123456
        $partesTiempo = explode(':', $duracionRaw);
        $tiempo_cut   = (int)$partesTiempo[1]; // Minutos
        $tiempo_cut_s = (int)explode('.', $partesTiempo[2])[0]; // Segundos
        
        // 2. Definir rutas de salida
        $rutaImagen      = "imagenes_tablero/{$fecha}_{$board_id}.jpg";
        $reciduo_video   = "previa/{$fecha}_{$board_id}.mp4";
        $rutaTemVideo    = escapeshellarg($rutaOriginal);
        $prefix          = "tmp_{$board_id}_"; // Prefijo para archivos temporales .ts

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

        // 4. Ejecución de Procesamiento FFmpeg
        $ts_files = [];
        foreach ($cortes as $i => $corte) {
            $tmp_name = "{$prefix}{$i}.ts";
            $ts_files[] = $tmp_name;
            exec("ffmpeg -y -ss {$corte[0]} -t {$corte[1]} -i {$rutaTemVideo} -c copy -bsf:v h264_mp4toannexb -f mpegts {$tmp_name} 2>&1");
        }

        if (!empty($ts_files)) {
            $concat_string = implode('|', $ts_files);
            exec("ffmpeg -y -i \"concat:{$concat_string}\" -c copy -bsf:a aac_adtstoasc " . escapeshellarg($reciduo_video) . " 2>&1");
            
            // Limpiar temporales .ts
            foreach ($ts_files as $f) { @unlink($f); }
        }

        // 5. Generar Miniatura
        exec("ffmpeg -y -i {$rutaTemVideo} -ss {$ss_imagen} -vframes 1 " . escapeshellarg($rutaImagen));

        // 6. Respuesta y Limpieza Final
        @unlink($rutaOriginal);

        $channel->basic_publish(
            new AMQPMessage(json_encode([
                'board_id' => $board_id,
                'preview' => $reciduo_video,
                'thumbnail' => $rutaImagen,
                'status' => 'ok'
            ])), '', 'multimedia_resultado'
        );

        echo "✔ Board {$board_id} procesado correctamente.\n";
        $msg->ack();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
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