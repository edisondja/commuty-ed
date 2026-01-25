<?php

class Config extends EncryptToken
{

    public  $conection;
    public  $configuracion_id;
    public  $dominio;
    public  $nombre_sitio;
    public  $descripcion_slogan;
    public  $descripcion_sitio;
    public  $favicon_url;
    public  $sitio_logo_url;
    public  $copyright_descripcion;
    public  $publicar_sin_revision;
    public  $email_sitio;
    public  $busqueda_descripcion;
    public  $pagina_descripcion;
    public  $titulo_descripcion;
    public  $busqueda_hastag;
    public  $email_remitente;
    public  $nombre_remitente;
    public  $servidor_smtp;
    public  $puerto_smtp;
    public  $usuario_smtp;
    public  $clave_smtp;
    public  $autenticacion_ssl;
    public  $verificar_cuenta;
    public  $rabbit_mq;
    public  $ffmpeg;
    public  $redis_cache;



    public function __construct()
    {
        $this->SetConection();

    }

    public function VerificarConfiguracion()
    {
        /*
            Este meotod se encarga de validar si ya
            existe una configuracion creada y retornar
            el id de ella
        */

        $sql = 'SELECT * FROM configuracion LIMIT 1';

        try{
            $cargar = $this->conection;
            $data = $cargar->query($sql);
            return $data->num_rows;
            
        }catch(Exception $e){

            echo $e;
        }
    }

    public function Cargar_configuracion($config)
    {
        $sql = 'select * from configuracion limit 1';
        $stmt = $this->conection->prepare($sql);
        
        if ($stmt === false) {
            // Si no se puede preparar, retornar valores por defecto
            if ($config == 'json') {
                echo json_encode(["error" => "Error al preparar la consulta: " . $this->conection->error]);
                return;
            } elseif ($config == 'asoc') {
                $config_d = new stdClass();
                $config_d->sitio_logo_url = '';
                $config_d->favicon_url = '';
                $config_d->verificar_cuenta = 'NO';
                $config_d->publicar_sin_revision = 'SI';
                $config_d->nombre_sitio = '';
                $config_d->copyright_descripcion = '';
                $config_d->email_sitio = '';
                $config_d->dominio = '';
                $config_d->estilos_json = null;
                return $config_d;
            }
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Compatible con PHP 7.2 y PHP 8+
        if (PHP_VERSION_ID >= 80000) {
            $data = $result->fetch_object();
        } else {
            $data = mysqli_fetch_object($result);
        }
        
        $stmt->close();
    
        // Verificar si la consulta no devolvió ningún resultado
        if ($data === null) {
            // Manejar el caso cuando no hay resultados
            if ($config == 'json') {
                echo json_encode(["error" => "No data found"]);
            } elseif ($config == 'asoc') {
                
                $config_d= new stdClass();
                $config_d->sitio_logo_url ='';
                $config_d->favicon_url  ='';
                $config_d->verificar_cuenta = 'NO'; // Valor por defecto
                $config_d->publicar_sin_revision = 'SI'; // Valor por defecto
                $config_d->nombre_sitio = '';
                $config_d->copyright_descripcion = '';
                $config_d->email_sitio = '';
                $config_d->dominio = '';
                
                return $config_d;
            }
        } else {
            // Procesar el resultado como se espera
            if ($config == 'json') {
                echo json_encode($data);
            } elseif ($config == 'asoc') {

                $this->servidor_smtp =$data->servidor_smtp;
                $this->email_remitente =$data->email_remitente;
                $this->clave_smtp =$data->clave_smtp;
                $this->puerto_smtp = $data->puerto_smtp;
                $this->nombre_sitio =$data->nombre_sitio;
                $this->descripcion_slogan = $data->descripcion_slogan;
                $this->descripcion_sitio = $data->descripcion_sitio;
                $this->favicon_url = $data->favicon_url;
                $this->sitio_logo_url = $data->sitio_logo_url;
                $this->copyright_descripcion = $data->copyright_descripcion;
                $this->email_sitio = $data->email_sitio;
                $this->autenticacion_ssl = $data->autenticacion_ssl;
                $this->dominio = $data->dominio;
                $this->publicar_sin_revision = $data->publicar_sin_revision;
                $this->verificar_cuenta = $data->verificar_cuenta;
                $this->rabbit_mq = $data->rabbit_mq;
                $this->ffmpeg = $data->ffmpeg;
                $this->redis_cache = $data->redis_cache;
                return $data;
            }
        }
    }

    public function DetectarMultimedias($logo_sitio, $favicon_sitio)
    {
        $fecha_actual = date('ymdis');
        
        // Asegurar que el directorio assets existe y tiene permisos
        $assets_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets';
        if (!is_dir($assets_dir)) {
            @mkdir($assets_dir, 0777, true);
        }
        @chmod($assets_dir, 0777);

        if (isset($logo_sitio['tmp_name']) && is_uploaded_file($logo_sitio['tmp_name'])) {

            $targetFile = $assets_dir . DIRECTORY_SEPARATOR . $fecha_actual . $logo_sitio['name'];

            if (@move_uploaded_file($logo_sitio['tmp_name'], $targetFile)) {
                @chmod($targetFile, 0666);
                $this->sitio_logo_url = '/assets/' . $fecha_actual . $logo_sitio['name'];
            } else {
                $error = error_get_last();
                $errorMsg = $error ? $error['message'] : 'Error desconocido al mover el archivo';
                $this->TrackingLog("Error moviendo logo: " . $errorMsg, 'errores');
            }
        }

        if (isset($favicon_sitio['tmp_name']) && is_uploaded_file($favicon_sitio['tmp_name'])) {

            $targetFile = $assets_dir . DIRECTORY_SEPARATOR . $fecha_actual . $favicon_sitio['name'];

            if (@move_uploaded_file($favicon_sitio['tmp_name'], $targetFile)) {
                @chmod($targetFile, 0666);
                $this->favicon_url = '/assets/' . $fecha_actual . $favicon_sitio['name'];
            } else {
                $error = error_get_last();
                $errorMsg = $error ? $error['message'] : 'Error desconocido al mover el archivo';
                $this->TrackingLog("Error moviendo favicon: " . $errorMsg, 'errores');
            }

        } 
    }
    public function Guardar_configuracion()
    {
        // Asegurar que todas las propiedades tengan valores por defecto
        $this->dominio = $this->dominio ?? '';
        $this->nombre_sitio = $this->nombre_sitio ?? '';
        $this->descripcion_slogan = $this->descripcion_slogan ?? '';
        $this->descripcion_sitio = $this->descripcion_sitio ?? '';
        $this->copyright_descripcion = $this->copyright_descripcion ?? '';
        $this->email_sitio = $this->email_sitio ?? '';
        $this->busqueda_descripcion = $this->busqueda_descripcion ?? '';
        $this->pagina_descripcion = $this->pagina_descripcion ?? '';
        $this->titulo_descripcion = $this->titulo_descripcion ?? '';
        $this->busqueda_hastag = $this->busqueda_hastag ?? '';
        $this->favicon_url = $this->favicon_url ?? '';
        $this->sitio_logo_url = $this->sitio_logo_url ?? '';
        $this->email_remitente = $this->email_remitente ?? '';
        $this->nombre_remitente = $this->nombre_remitente ?? '';
        $this->servidor_smtp = $this->servidor_smtp ?? '';
        $this->puerto_smtp = $this->puerto_smtp ?? '';
        $this->usuario_smtp = $this->usuario_smtp ?? '';
        $this->clave_smtp = $this->clave_smtp ?? '';
        // Convertir autenticacion_ssl a entero (1 para 'si', 0 para 'no' o vacío)
        if (empty($this->autenticacion_ssl) || strtolower($this->autenticacion_ssl) === 'no') {
            $this->autenticacion_ssl = 0;
        } elseif (strtolower($this->autenticacion_ssl) === 'si' || $this->autenticacion_ssl === '1' || $this->autenticacion_ssl === 1) {
            $this->autenticacion_ssl = 1;
        } else {
            $this->autenticacion_ssl = (int)$this->autenticacion_ssl;
        }
        // Convertir publicar_sin_revision a entero
        if (empty($this->publicar_sin_revision) || strtolower($this->publicar_sin_revision) === 'no') {
            $this->publicar_sin_revision = 0;
        } elseif (strtolower($this->publicar_sin_revision) === 'si' || $this->publicar_sin_revision === '1' || $this->publicar_sin_revision === 1) {
            $this->publicar_sin_revision = 1;
        } else {
            $this->publicar_sin_revision = (int)$this->publicar_sin_revision;
        }
        // Convertir verificar_cuenta a entero
        if (empty($this->verificar_cuenta) || strtolower($this->verificar_cuenta) === 'no') {
            $this->verificar_cuenta = 0;
        } elseif (strtolower($this->verificar_cuenta) === 'si' || $this->verificar_cuenta === '1' || $this->verificar_cuenta === 1) {
            $this->verificar_cuenta = 1;
        } else {
            $this->verificar_cuenta = (int)$this->verificar_cuenta;
        }
        // Convertir rabbit_mq a entero
        if (empty($this->rabbit_mq) || strtolower($this->rabbit_mq) === 'no') {
            $this->rabbit_mq = 0;
        } elseif (strtolower($this->rabbit_mq) === 'si' || $this->rabbit_mq === '1' || $this->rabbit_mq === 1) {
            $this->rabbit_mq = 1;
        } else {
            $this->rabbit_mq = (int)$this->rabbit_mq;
        }
        // Convertir ffmpeg a entero
        if (empty($this->ffmpeg) || strtolower($this->ffmpeg) === 'no') {
            $this->ffmpeg = 0;
        } elseif (strtolower($this->ffmpeg) === 'si' || $this->ffmpeg === '1' || $this->ffmpeg === 1) {
            $this->ffmpeg = 1;
        } else {
            $this->ffmpeg = (int)$this->ffmpeg;
        }
        // Convertir redis_cache a entero
        if (empty($this->redis_cache) || strtolower($this->redis_cache) === 'no') {
            $this->redis_cache = 0;
        } elseif (strtolower($this->redis_cache) === 'si' || $this->redis_cache === '1' || $this->redis_cache === 1) {
            $this->redis_cache = 1;
        } else {
            $this->redis_cache = (int)$this->redis_cache;
        }
        $this->estilos_json = $this->estilos_json ?? null;
        
        $sql = 'insert into configuracion(
                dominio,
                nombre_sitio,
                descripcion_slogan,
                descripcion_sitio,
                copyright_descripcion,
                email_sitio,
                busqueda_descripcion,
                pagina_descripcion,
                titulo_descripcion,
                busqueda_hastag,
                favicon_url,
                sitio_logo_url,
                email_remitente,
                nombre_remitente,
                servidor_smtp,
                puerto_smtp,
                usuario_smtp,
                clave_smtp,
                autenticacion_ssl,
                publicar_sin_revision,
                verificar_cuenta,
                rabbit_mq,
                ffmpeg,
                redis_cache,
                estilos_json
            )values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        $guardar = $this->conection->prepare($sql);
        
        if ($guardar === false) {
            $this->TrackingLog('Error al preparar consulta Guardar_configuracion: ' . $this->conection->error, 'errores');
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $this->conection->error]);
            return;
        }

        try {
            // 18 strings + 6 integers + 1 string = 25 parámetros
            $guardar->bind_param('ssssssssssssssssssiiiiiis',
                $this->dominio,           // 1 s
                $this->nombre_sitio,      // 2 s
                $this->descripcion_slogan,// 3 s
                $this->descripcion_sitio, // 4 s
                $this->copyright_descripcion, // 5 s
                $this->email_sitio,       // 6 s
                $this->busqueda_descripcion, // 7 s
                $this->pagina_descripcion, // 8 s
                $this->titulo_descripcion, // 9 s
                $this->busqueda_hastag,   // 10 s
                $this->favicon_url,       // 11 s
                $this->sitio_logo_url,    // 12 s
                $this->email_remitente,   // 13 s
                $this->nombre_remitente,  // 14 s
                $this->servidor_smtp,     // 15 s
                $this->puerto_smtp,       // 16 s
                $this->usuario_smtp,      // 17 s
                $this->clave_smtp,        // 18 s
                $this->autenticacion_ssl, // 19 i
                $this->publicar_sin_revision, // 20 i
                $this->verificar_cuenta,  // 21 i
                $this->rabbit_mq,         // 22 i
                $this->ffmpeg,            // 23 i
                $this->redis_cache,       // 24 i
                $this->estilos_json       // 25 s
            );
            
            if (!$guardar->execute()) {
                throw new Exception('Error al ejecutar: ' . $guardar->error);
            }
            
            $guardar->close();
            echo json_encode(['success' => true, 'message' => 'Configuración guardada con éxito!']);
        } catch (Exception $e) {
            $this->TrackingLog('Error en Guardar_configuracion: ' . $e->getMessage(), 'errores');
            if (isset($guardar)) {
                $guardar->close();
            }
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    public function Actualizar_configuracion()
    {
        // Asegurar que todas las propiedades tengan valores por defecto
        $this->dominio = $this->dominio ?? '';
        $this->nombre_sitio = $this->nombre_sitio ?? '';
        $this->descripcion_slogan = $this->descripcion_slogan ?? '';
        $this->descripcion_sitio = $this->descripcion_sitio ?? '';
        $this->copyright_descripcion = $this->copyright_descripcion ?? '';
        $this->email_sitio = $this->email_sitio ?? '';
        $this->busqueda_descripcion = $this->busqueda_descripcion ?? '';
        $this->pagina_descripcion = $this->pagina_descripcion ?? '';
        $this->titulo_descripcion = $this->titulo_descripcion ?? '';
        $this->busqueda_hastag = $this->busqueda_hastag ?? '';
        $this->favicon_url = $this->favicon_url ?? '';
        $this->sitio_logo_url = $this->sitio_logo_url ?? '';
        $this->email_remitente = $this->email_remitente ?? '';
        $this->nombre_remitente = $this->nombre_remitente ?? '';
        $this->servidor_smtp = $this->servidor_smtp ?? '';
        $this->puerto_smtp = $this->puerto_smtp ?? '';
        $this->usuario_smtp = $this->usuario_smtp ?? '';
        $this->clave_smtp = $this->clave_smtp ?? '';
        // Convertir autenticacion_ssl a entero (1 para 'si', 0 para 'no' o vacío)
        if (empty($this->autenticacion_ssl) || strtolower($this->autenticacion_ssl) === 'no') {
            $this->autenticacion_ssl = 0;
        } elseif (strtolower($this->autenticacion_ssl) === 'si' || $this->autenticacion_ssl === '1' || $this->autenticacion_ssl === 1) {
            $this->autenticacion_ssl = 1;
        } else {
            $this->autenticacion_ssl = (int)$this->autenticacion_ssl;
        }
        // Convertir publicar_sin_revision a entero
        if (empty($this->publicar_sin_revision) || strtolower($this->publicar_sin_revision) === 'no') {
            $this->publicar_sin_revision = 0;
        } elseif (strtolower($this->publicar_sin_revision) === 'si' || $this->publicar_sin_revision === '1' || $this->publicar_sin_revision === 1) {
            $this->publicar_sin_revision = 1;
        } else {
            $this->publicar_sin_revision = (int)$this->publicar_sin_revision;
        }
        // Convertir verificar_cuenta a entero
        if (empty($this->verificar_cuenta) || strtolower($this->verificar_cuenta) === 'no') {
            $this->verificar_cuenta = 0;
        } elseif (strtolower($this->verificar_cuenta) === 'si' || $this->verificar_cuenta === '1' || $this->verificar_cuenta === 1) {
            $this->verificar_cuenta = 1;
        } else {
            $this->verificar_cuenta = (int)$this->verificar_cuenta;
        }
        // Convertir rabbit_mq a entero
        if (empty($this->rabbit_mq) || strtolower($this->rabbit_mq) === 'no') {
            $this->rabbit_mq = 0;
        } elseif (strtolower($this->rabbit_mq) === 'si' || $this->rabbit_mq === '1' || $this->rabbit_mq === 1) {
            $this->rabbit_mq = 1;
        } else {
            $this->rabbit_mq = (int)$this->rabbit_mq;
        }
        // Convertir ffmpeg a entero
        if (empty($this->ffmpeg) || strtolower($this->ffmpeg) === 'no') {
            $this->ffmpeg = 0;
        } elseif (strtolower($this->ffmpeg) === 'si' || $this->ffmpeg === '1' || $this->ffmpeg === 1) {
            $this->ffmpeg = 1;
        } else {
            $this->ffmpeg = (int)$this->ffmpeg;
        }
        // Convertir redis_cache a entero
        if (empty($this->redis_cache) || strtolower($this->redis_cache) === 'no') {
            $this->redis_cache = 0;
        } elseif (strtolower($this->redis_cache) === 'si' || $this->redis_cache === '1' || $this->redis_cache === 1) {
            $this->redis_cache = 1;
        } else {
            $this->redis_cache = (int)$this->redis_cache;
        }
        // estilos_json se mantiene si no se envía (no se actualiza)
        
        $sql = "update configuracion set 
                    dominio = ?,
                    nombre_sitio = ?,
                    descripcion_slogan = ?,
                    descripcion_sitio = ?,
                    copyright_descripcion = ?,
                    email_sitio = ?,
                    busqueda_descripcion = ?,
                    pagina_descripcion = ?,
                    titulo_descripcion = ?,
                    busqueda_hastag = ?,
                    favicon_url = ?,
                    sitio_logo_url = ?,
                    email_remitente = ?,
                    nombre_remitente = ?,
                    servidor_smtp = ?, 
                    puerto_smtp = ?,
                    usuario_smtp = ?,
                    clave_smtp = ?,
                    autenticacion_ssl = ?,
                    publicar_sin_revision = ?,
                    verificar_cuenta = ?,
                    rabbit_mq = ?,
                    ffmpeg = ?,
                    redis_cache = ?
                    limit 1";

        $actualizar = $this->conection->prepare($sql);
        
        if ($actualizar === false) {
            $this->TrackingLog('Error al preparar consulta Actualizar_configuracion: ' . $this->conection->error, 'errores');
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $this->conection->error]);
            return;
        }

        try {
            // 18 strings + 6 integers = 24 parámetros (sin estilos_json en UPDATE)
            $actualizar->bind_param(
                'ssssssssssssssssssiiiiii',
                $this->dominio,           // 1 s
                $this->nombre_sitio,      // 2 s
                $this->descripcion_slogan,// 3 s
                $this->descripcion_sitio, // 4 s
                $this->copyright_descripcion, // 5 s
                $this->email_sitio,       // 6 s
                $this->busqueda_descripcion, // 7 s
                $this->pagina_descripcion, // 8 s
                $this->titulo_descripcion, // 9 s
                $this->busqueda_hastag,   // 10 s
                $this->favicon_url,       // 11 s
                $this->sitio_logo_url,    // 12 s
                $this->email_remitente,   // 13 s
                $this->nombre_remitente,  // 14 s
                $this->servidor_smtp,     // 15 s
                $this->puerto_smtp,       // 16 s
                $this->usuario_smtp,      // 17 s
                $this->clave_smtp,        // 18 s
                $this->autenticacion_ssl, // 19 i
                $this->publicar_sin_revision, // 20 i
                $this->verificar_cuenta,  // 21 i
                $this->rabbit_mq,         // 22 i
                $this->ffmpeg,            // 23 i
                $this->redis_cache        // 24 i
            );

            if (!$actualizar->execute()) {
                throw new Exception('Error al ejecutar: ' . $actualizar->error);
            }
            
            $actualizar->close();
            echo json_encode(['success' => true, 'message' => 'Configuración actualizada con éxito!']);
        } catch (Exception $e) {
            $this->TrackingLog('Error en Actualizar_configuracion: ' . $e->getMessage(), 'errores');
            if (isset($actualizar)) {
                $actualizar->close();
            }
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }   


}

?>
