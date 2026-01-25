<?php

    class Board extends EncryptToken
    {
        public  $board_id;
        public  $title;
        public  $description;
        public  $imagen_tablero;
        public  $id_usuario;
        public  $portada_board = false;
        public  $CapturarUsuario;
        public  $config;
        public  $conection;

        public function __construct()
        {
            $this->CapturarUsuario = new User();
            $this->config = new Config();
            $this->SetConection();
        }

        /**
         * Procesa una imagen: la guarda, la asigna como portada (si es la primera) 
         * y la registra en asignar_multimedia_t
         * @return string|null Ruta donde se guardó la imagen, o null si falló
         */
        public function procesar_imagen($tipo_archivo, $id_tablero, $archivo_temp, $titulo)
        {
            // Solo procesar imágenes
            if (!in_array($tipo_archivo, ['jpeg', 'png', 'gif', 'webp'])) {
                return null;
            }
            
            // Asegurar conexión
            if (!isset($this->conection) || !($this->conection instanceof mysqli)) {
                $this->SetConection();
            }
            
            $fecha = new DateTime();
            $fecha_a = $fecha->getTimestamp();
            $estado = $this->enable();
            $titulo_limpio = $this->titleList($this->limitarTexto($titulo));
            
            // Directorio de imágenes (ruta absoluta)
            $img_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'imagenes_tablero';
            if (!is_dir($img_dir)) {
                @mkdir($img_dir, 0777, true);
            }
            @chmod($img_dir, 0777);
            
            // Nombre del archivo
            $nombre_archivo = $fecha_a . $titulo_limpio . '.jpg';
            $ruta_absoluta = $img_dir . DIRECTORY_SEPARATOR . $nombre_archivo;
            
            // Ruta relativa para guardar en BD (sin ../)
            $ruta_relativa = 'imagenes_tablero/' . $nombre_archivo;
            
            // Mover el archivo
            if (!@move_uploaded_file($archivo_temp, $ruta_absoluta)) {
                $this->TrackingLog(date('ymdis').' Error al mover imagen: ' . $ruta_absoluta, 'errores');
                return null;
            }
            
            @chmod($ruta_absoluta, 0666);
            
            // Si es la primera imagen, usarla como PORTADA del tablero
            if ($this->portada_board == false) {
                $sql = 'UPDATE tableros SET imagen_tablero = ? WHERE id_tablero = ?';
                $stmt = $this->conection->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('si', $ruta_relativa, $id_tablero);
                    $stmt->execute();
                    $stmt->close();
                    $this->TrackingLog(date('ymdis').'Portada asignada: ' . $ruta_relativa, 'eventos');
                }
                $this->portada_board = true;
            }
            
            // Guardar en asignar_multimedia_t
            $sql = 'INSERT INTO asignar_multimedia_t (id_tablero, ruta_multimedia, tipo_multimedia, estado) VALUES (?, ?, ?, ?)';
            $tipo_asset = 'imagen';
            try {
                $stmt = $this->conection->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('isss', $id_tablero, $ruta_relativa, $tipo_asset, $estado);
                    $stmt->execute();
                    $stmt->close();
                    $this->TrackingLog(date('ymdis').'Imagen guardada en multimedia: ' . $ruta_relativa, 'eventos');
                }
            } catch(Exception $e) {
                $this->TrackingLog(date('ymdis').'Error al guardar imagen en multimedia: '.$e, 'errores');
            }
            
            return $ruta_relativa;
        }
        
        // Funciones legacy (mantener por compatibilidad)
        public function detectar_imagen_portada($portada, $id_tablero, $archivo_temp)
        {
            // Redirigir a la nueva función
            return $this->procesar_imagen($portada, $id_tablero, $archivo_temp, $this->description ?? '');
        }

        public function asignar_portada_tablero($id_tablero, $ruta_temp, $guardar_como)
        {
            // Esta función ya no se usa, se mantiene por compatibilidad
        }

    public function guardar_tablero()
    {
        try {
            // Fecha en formato MySQL
            $fecha = date('Y-m-d H:i:s');

            $configuracion = $this->config->cargar_configuracion('asoc');

            // Si no hay configuración o publicar_sin_revision no está en 'SI', usar 'activo' por defecto
            if ($configuracion && isset($configuracion->publicar_sin_revision) && $configuracion->publicar_sin_revision == 'SI') {
                $estado = $this->enable();
            } else {
                // Por defecto, publicar como activo si no hay configuración
                $estado = $this->enable();
            }

            // Insertar tablero en la base de datos
            $sql = "insert into tableros(descripcion, fecha_creacion, imagen_tablero, id_usuario, estado)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conection->prepare($sql);
            $stmt->bind_param('sssis', $this->description, $fecha, $this->imagen_tablero, $this->id_usuario, $estado);
            $stmt->execute();
            $last_id = $this->conection->insert_id;
            $stmt->close();
            
            // Tipos de archivo permitidos
            $allowed_images = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $allowed_videos = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'video/x-matroska', 'video/avi'];
            $allowed_types = array_merge($allowed_images, $allowed_videos);

            // Procesar archivos subidos si existen
            if (isset($_FILES['media']['tmp_name'])) {
                $archivos = is_array($_FILES['media']['tmp_name']) 
                            ? count($_FILES['media']['tmp_name']) 
                            : 1;

                for ($i = 0; $i < $archivos; ++$i) {
                    // Obtener info del archivo
                    $tmp_name = is_array($_FILES['media']['tmp_name']) ? $_FILES['media']['tmp_name'][$i] : $_FILES['media']['tmp_name'];
                    $type     = is_array($_FILES['media']['type']) ? $_FILES['media']['type'][$i] : $_FILES['media']['type'];
                    
                    // Saltar archivos vacíos o no permitidos
                    if (empty($tmp_name) || !in_array($type, $allowed_types)) continue;

                    $tipo_archivo = $this->detectar_archivo($type);
                    
                    if (in_array($type, $allowed_images)) {
                        // IMÁGENES: guardar, asignar portada (primera) y registrar en asignar_multimedia_t
                        $this->procesar_imagen($tipo_archivo, $last_id, $tmp_name, $this->description);
                    } elseif (in_array($type, $allowed_videos)) {
                        // VIDEOS: enviar a RabbitMQ para procesar con FFmpeg
                        // El consumer_resultado.php guardará en asignar_multimedia_t
                        $this->enviar_a_procesar_multimedia($tmp_name, $tipo_archivo, $last_id);
                    }
                }
            } elseif (isset($_POST['media'])) {
                // Video por URL (transferencia)
                $this->enviar_a_procesar_multimedia($_POST['media'], 'transfer_video', $last_id);
            }


            // Retornar tablero creado en JSON
            if ($last_id !== null) {
                $this->cargar_solo_tablero($last_id, 'json');
            }

        } catch (Exception $e) {
            $this->TrackingLog(date('Y-m-d H:i:s') . ' No se pudo guardar el tablero: ' . $e->getMessage(), 'errores');
            // Manejo de errores
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


        private function actualizar_estado_board($estado)
        {
            /*Fragmento utilizado para cambiar los estados de los tableros */
            $sql = 'update tableros set estado=? where id_usuario=? and id_tablero=?';
            $guardar = $this->conection->prepare($sql);
 

            echo 'emtro a actualizar estado';

            try{
            $guardar->bind_param('sii', $estado, $this->id_usuario, $this->board_id);
            $guardar->execute();

            echo 'estado actualizado con exito';

            }catch(Exception $e){


              $this->TrackingLog(date('ymdis').' Errorr actualizando estado del tablero','errores');

            }
            $guardar->close();
        }

        public function desactivar_tablero()
        {
            $this->conection;
            $this->actualizar_estado_board($this->disable());
            echo 'tablero desactivado';
        }

        public function bloquear_tablero()
        {
            $this->CapturarUsuario->id_user = $this->id_usuario;

            $usaurio = $this->CapturarUsuario->get_info_user('asoc');

            if ($usaurio->type_user == 'admin') {
                /*
                    Si el usuario que esta intentando bloquear este tablero es admin se
                    esta accion de bloqueo sera iniciada
                */
                $this->conection;
                $this->actualizar_estado_board($this->banned());
                echo 'tablero bloqueado';

                $this->TrackingLog('Bloqueando tablero','eventos');
            }
        }

        public function activar_tablero()
        {
            $this->conection;
            $this->actualizar_estado_board($this->enable());
            echo 'tablero activado';
        }

        public function actualizar_tablero()
        {
            $fecha = date("Y-m-d H:i:s"); 
            /*
            FormDatas.append('id_user',document.getElementById('id_usuario').value);
            FormDatas.append('texto',document.getElementById('id_usuario').value);
            FormDatas.append('id_board',id_board);
            */

            try {
                $sql = 'UPDATE tableros 
                        SET descripcion = ?, 
                            fecha_creacion = ?, 
                            id_usuario = ? 
                        WHERE id_tablero = ?';
                $actualizar = $this->conection->prepare($sql);
                if ($actualizar === false) {
                    throw new Exception('Error al preparar la consulta: '.$this->conection->error);
                }

                // Bind the parameters to the SQL query
                $bind = $actualizar->bind_param('ssii', $this->description,$fecha,$this->id_usuario, $this->board_id);
                if ($bind === false) {
                    throw new Exception('Error al vincular los parámetros: '.$actualizar->error);
                }

                // Execute the prepared statement
                $exec = $actualizar->execute();
                if ($exec === false) {
                    throw new Exception('Error al ejecutar la consulta: '.$actualizar->error);
                }

                // Close the statement
                $actualizar->close();
            } catch (Exception $e) {
                exit('No se pudo actualizar el tablero: '.$e->getMessage());
            }
        }

        public function cargar_solo_tablero($id_tablero,$config='asoc')
        {
         
            $sql = "SELECT * FROM tableros INNER JOIN users
             ON tableros.id_usuario = users.id_user 
            WHERE id_tablero = ?";
            $cargado = $this->conection->prepare($sql);
            $cargado->bind_param('i', $id_tablero);
            $cargado->execute();
            $result = $cargado->get_result();
            $cargado->close();
        
            // Compatible con PHP 7.2 y PHP 8+
            if (PHP_VERSION_ID >= 80000) {
                $data = $result->fetch_object();
            } else {
                $data = mysqli_fetch_object($result);
            }
         
            if ($config == 'json') {
                echo json_encode($data);
            } else {
                return $data;
            }
        }

        public function cargar_tableros($id_tablero, $config = 'json')
        {
            // Asumimos que $this->conection es una instancia válida de mysqli
            $this->conection;
            $estado = $this->enable();
            $estado2 = $this->banned();
            $sql = 'SELECT * FROM tableros INNER JOIN users
             ON tableros.id_usuario = users.id_user 
            WHERE id_tablero = ? AND  tableros.estado = ?
            OR tableros.estado=? 
            ';
            $cargado = $this->conection->prepare($sql);

            if ($cargado === false) {
                // Manejo de errores de preparación
                exit('Error en la preparación de la consulta: '.$this->conection->error);
            }

            $cargado->bind_param('iss', $id_tablero, $estado, $estado2);

            if ($cargado->execute() === false) {
                // Manejo de errores de ejecución
                exit('Error en la ejecución de la consulta: '.$cargado->error);
            }

            $result = $cargado->get_result();
            if ($result === false) {
                // Manejo de errores de obtención de resultados
                exit('Error al obtener el resultado: '.$cargado->error);
            }

            $data = $result->fetch_object();
            $cargado->close();

            if ($config == 'json') {
                echo json_encode($data);
            } else {
                return $data;
            }
        }



        public function paginar_tableros($inicio,$config='paginar_general',$user_id=0)
        {
            $estado = $this->enable();

            $limite = 5;

            if ($inicio <= 1) {
                //si es igual menor a 1 el limite sera 8 y el inicio 1
                $fin = $limite;
                $inicio = 1;
            } else {
                $fin = ($inicio * $limite);
                $inicio = ($fin - $limite);
            }

            switch($config){

                case 'paginar_general':

                    $sql = 'select * from tableros t
                    inner join users u on  t.id_usuario=u.id_user
                    where t.estado=? order by id_tablero desc limit ?,?';
                    $cargar = $this->conection->prepare($sql);
                    $cargar->bind_param('sii', $estado, $inicio, $fin);
                    $cargar->execute();
                    $result = $cargar->get_result();
                    // Convertir a array para compatibilidad con PHP 7.2 y PHP 8+
                    $data = [];
                    if (PHP_VERSION_ID >= 80000) {
                        foreach ($result as $row) {
                            $data[] = $row;
                        }
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            $data[] = $row;
                        }
                    }
                
                break;

                case 'paginar_usuario':

                    $sql = 'select * from tableros t
                    inner join users u on  t.id_usuario=u.id_user
                    where t.estado=? and t.id_usuario=? order by id_tablero desc limit ?,?';
                    $cargar = $this->conection->prepare($sql);
                    $cargar->bind_param('siii', $estado,$user_id,$inicio, $fin);
                    $cargar->execute();
                    $result = $cargar->get_result();
                    // Convertir a array para compatibilidad con PHP 7.2 y PHP 8+
                    $data = [];
                    if (PHP_VERSION_ID >= 80000) {
                        foreach ($result as $row) {
                            $data[] = $row;
                        }
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            $data[] = $row;
                        }
                    }


                break;

                case 'paginar_favoritos':

                    /*
                        En este apartado solo se paginan los tableros
                        favoritos, que el usuario guardo como preferencias
                        
                    */
                    $data = [];

                break;
            }

     

            return $data;
        }

        public function search_tablero($texto, $config = 'asoc')
        {
            global $conexion;

            $estado = $this->enable();
            $texto = "%$texto%";

            // Prepara la consulta SQL

            if ($config == 'json') {
                $data = $this->conection->prepare('
                    SELECT t.descripcion,t.fecha_creacion,t.tipo_tablero,
                    t.imagen_tablero,u.foto_url,u.usuario,t.estado,t.id_tablero
                    FROM tableros as t
                    INNER JOIN users as u ON t.id_usuario = u.id_user 
                    WHERE t.titulo LIKE ? OR t.descripcion LIKE ?
                    order by t.fecha_creacion desc
                    LIMIT 8
                ');
                $data->bind_param('ss', $texto, $texto);
            } else {
                $data = $this->conection->prepare('
                    SELECT * FROM tableros as t
                    INNER JOIN users as u ON t.id_usuario = u.id_user 
                    WHERE (t.titulo LIKE ? OR t.descripcion LIKE ?) 
                    AND t.estado = ? order by t.fecha_creacion desc
                    LIMIT 8
                ');

                $data->bind_param('sss', $texto, $texto, $estado);
            }


            try{
                // Enlaza los parámetros a la consulta
                $data->execute();

                // Obtiene el resultado
                $resp = $data->get_result();
                $data->close();

                // Procesa el resultado
                $datos = [];
                while ($key = $resp->fetch_assoc()) {
                    $datos[] = $key;
                }

            }catch(Exception $e){

                $this->TrackingLog($e.' Error buscando tableros: criterio'.$texto,'errores');

            }
            // Devuelve el resultado en el formato especificado
            if ($config !== 'json') {
                return $datos;
            } else {
                echo json_encode($datos);
            }
        }

        public function asignar_metadatos_a_multimedia($id_asignar, $texto, $precio, $metodo_de_pago)
        {
            $this->conection;
            $sql = 'update asingar_multimedia_t set texto=?,precio=?,metodo_de_pago=? where id_asignar=?';
            $acoplar = $this->conection->prepare($sql);
            $acoplar->bind_param('sisi', $texto, $precio, $metodo_de_pago, $id_asignar);
            $acoplar->execute() or exit('error');
            $acoplar->close();
            echo 'update asset success';

            $this->TrackingLog(date('ymdis').' No se puedo asignar la portada '.$e,'errores');
        }

        public function asignador_de_multimedia_tablero($id_tablero, $url_temp, $tipo_archivo, $titulo)
        {
            // Asegurar conexión
            if (!isset($this->conection) || !($this->conection instanceof mysqli)) {
                $this->SetConection();
            }

            $fecha = new DateTime();
            $estado = $this->enable();
            $fecha_a = $fecha->getTimestamp();
            $tipo_a = $this->detectar_archivo($tipo_archivo);
            $titulo_de_assets = $this->titleList($titulo);
            
            // Solo procesar IMÁGENES aquí. Los videos se procesan en consumer_service/consumer_resultado
            if ($tipo_a == 'jpeg' || $tipo_a == 'png') {
                $tipo_asset = 'imagen';
                
                // Directorio de imágenes (ruta absoluta)
                $img_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'imagenes_tablero';
                if (!is_dir($img_dir)) {
                    @mkdir($img_dir, 0777, true);
                }
                @chmod($img_dir, 0777);
                
                // Nombre del archivo
                $nombre_archivo = $fecha_a . $titulo_de_assets . '.jpg';
                $ruta_absoluta = $img_dir . DIRECTORY_SEPARATOR . $nombre_archivo;
                
                // Ruta relativa para guardar en BD (sin ../)
                $guardar_como = 'imagenes_tablero/' . $nombre_archivo;
                
                // Mover el archivo
                if (!@move_uploaded_file($url_temp, $ruta_absoluta)) {
                    $this->TrackingLog(date('ymdis').' Error al mover imagen: ' . $ruta_absoluta, 'errores');
                    return;
                }
                
                @chmod($ruta_absoluta, 0666);
                
                // Insertar en asignar_multimedia_t
                $sql = 'INSERT INTO asignar_multimedia_t (id_tablero, ruta_multimedia, tipo_multimedia, estado) VALUES (?, ?, ?, ?)';
                try {
                    $guardar = $this->conection->prepare($sql);
                    if ($guardar) {
                        $guardar->bind_param('isss', $id_tablero, $guardar_como, $tipo_asset, $estado);
                        $guardar->execute();
                        $guardar->close();
                        $this->TrackingLog(date('ymdis').'Imagen agregada: ' . $guardar_como, 'eventos');
                    }
                } catch(Exception $e) {
                    $this->TrackingLog(date('ymdis').'No se pudo asignar la imagen: '.$e, 'errores');
                }
                
            } elseif ($tipo_a == 'mp4' || $tipo_a == 'webm' || $tipo_a == 'avi' || $tipo_a == 'mov' || $tipo_a == 'mkv') {
                // Los videos NO se procesan aquí - se envían a RabbitMQ y el consumer los maneja
                $this->TrackingLog(date('ymdis').'Video detectado, será procesado por consumer_service', 'eventos');
            } else {
                $this->TrackingLog(date('ymdis') . ' Tipo de archivo no permitido: ' . $tipo_a, 'errores');
            }
        }

        public function cargar_tablerosx($id_usuario = 'general', $opcion = 'json')
        {
            // Asegurar que la conexión esté disponible y sea válida
            if (!isset($this->conection) || !($this->conection instanceof mysqli)) {
                $this->SetConection();
            }
            
            // Si aún no hay conexión válida, crear una nueva
            if (!isset($this->conection) || !($this->conection instanceof mysqli)) {
                $this->conection = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                if ($this->conection->connect_error) {
                    throw new Exception("Error de conexión: " . $this->conection->connect_error);
                }
                $this->conection->set_charset("utf8mb4");
            }
            
            // Verificar que la conexión esté activa
            if (!$this->conection) {
                throw new Exception("No se pudo establecer la conexión a la base de datos");
            }
            
            $estatus = $this->enable();
            $estatus_baneado = $this->banned();

            if ($id_usuario == 'general') {
                // Mostrar tableros activos e inactivos (pero no baneados)
                $sql = 'SELECT * FROM tableros INNER JOIN users ON 
                tableros.id_usuario=users.id_user 
                WHERE (tableros.estado=? OR tableros.estado=?) AND tableros.estado!=? 
                ORDER BY id_tablero DESC LIMIT 8';
                $cargado = $this->conection->prepare($sql);
                if ($cargado === false) {
                    $error_msg = 'Error al preparar la consulta: ' . $this->conection->error . ' | SQL: ' . $sql;
                    throw new Exception($error_msg);
                }
                $estatus_inactivo = $this->disable();
                $cargado->bind_param('sss', $estatus, $estatus_inactivo, $estatus_baneado);
            } else {
                $sql = 'SELECT * FROM tableros INNER JOIN users ON tableros.id_usuario=users.id_user WHERE id_usuario=? AND tableros.estado=? ORDER BY id_tablero DESC LIMIT 8';
                $cargado = $this->conection->prepare($sql);
                if ($cargado === false) {
                    $error_msg = 'Error al preparar la consulta: ' . $this->conection->error . ' | SQL: ' . $sql;
                    throw new Exception($error_msg);
                }
                $cargado->bind_param('is', $id_usuario, $estatus);
            }

            try{
                $cargado->execute();
                $data = $cargado->get_result();
                $cargado->close();
                
                // Compatible con PHP 7.2 y PHP 8+
                $json = [];
                if ($data instanceof mysqli_result) {
                    // PHP 8+ soporta foreach directamente, PHP 7.2 necesita fetch_assoc
                    if (PHP_VERSION_ID >= 80000) {
                        foreach ($data as $key) {
                            $json[] = $key;
                        }
                    } else {
                        // PHP 7.2
                        while ($row = $data->fetch_assoc()) {
                            $json[] = $row;
                        }
                    }
                }
                
                if ($opcion == 'json') {
                    echo json_encode($json);
                } else {
                    /* If the var opcion dont is equal to json return array associative */
                    return $json;
                }

            }catch(Exception $e){

                $this->TrackingLog(date('ymdis').'Error cargando tableros '.$e->getMessage(),'errores');
                return [];

            }
        }
        
        /**
         * Método para verificar y activar tableros inactivos si es necesario
         * Útil para debugging
         */
        public function contar_tableros_por_estado() {
            if (!isset($this->conection) || !($this->conection instanceof mysqli)) {
                $this->SetConection();
            }
            
            $sql = "SELECT estado, COUNT(*) as total FROM tableros GROUP BY estado";
            $result = $this->conection->query($sql);
            $estados = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $estados[$row['estado']] = $row['total'];
                }
            }
            return $estados;
        }

        public function eliminar_multimedia_de_tablero($id_multimediar)
        {
            $estado = $this->disable();
            $this->conection;
            $sql = 'update asignar_multimedia_t set estado=? where id_asignar=?';
         
            try{

                $eliminar = $this->conection->prepare($sql);
                $eliminar->bind_param('si', $estado, $id_multimediar);
                $eliminar->execute();
                $eliminar->close();

            }catch(Exception $e){

                $this->TrackingLog(date('ymdis').'No se pudo eliminar multimedia del tablero'.$e,'errores');

            }
        }

        public function cargar_multimedias_de_tablero($id_tablero, $config = 'json')
        {
            // Asegurar conexión
            if (!isset($this->conection) || !($this->conection instanceof mysqli)) {
                $this->SetConection();
            }
            
            $estado = $this->enable();

            // Buscar multimedias del tablero (filtrando por estado de la multimedia, no del tablero)
            $sql = 'SELECT m.* FROM asignar_multimedia_t m
                    WHERE m.id_tablero = ? AND m.estado = ?';
            $cargar = $this->conection->prepare($sql);
            $cargar->bind_param('is', $id_tablero, $estado);
            $cargar->execute();
            $result = $cargar->get_result();
            $cargar->close();
            $datos = [];
            // Compatible con PHP 7.2 y PHP 8+
            if (PHP_VERSION_ID >= 80000) {
                foreach ($result as $key) {
                    $datos[] = $key;
                }
            } else {
                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }
            }
            if ($config == 'json') {
                echo json_encode($datos);
            } else {
                return $datos;
            }
        }


        
        public function contar_tableros_usuario($config = 'object') {
            $sql = "SELECT COUNT(*) AS tableros FROM tableros WHERE id_usuario = ?";

            try {
                $stmt = $this->conection->prepare($sql);
                $stmt->bind_param('i', $this->id_usuario);
                $stmt->execute();

                $result = $stmt->get_result();
                $row = $result->fetch_object(); // devuelve stdClass { tableros: X }

                if ($config === 'object') {
                    return $row; // stdClass con propiedad 'tableros'
                } else if ($config === 'json') {
                    return json_encode($row); // devuelve JSON
                } else {
                    return $row->tableros; // si quieres devolver solo el número
                }

            } catch (Exception $e) {
                $this->TrackingLog(date('ymdis') . ' No se pudo cargar la cantidad de tableros del usuario: ' . $e, 'errores');
                return null; // en caso de error
            }
        }


   public function enviar_a_procesar_multimedia($ruta_temporal, $tipo_archivo, $board_id)
        {
            // 1. LIMPIEZA DE URL (Elimina el punto conflictivo antes de enviarlo al producer)
            if ($tipo_archivo === 'transfer_video') {
                $ruta_temporal = rtrim(trim($ruta_temporal), '.');
            }

            // Log en lugar de echo para no interferir con JSON
            $this->TrackingLog("Enviando multimedia a procesar para Board: $board_id", 'eventos');

            $url =  DOMAIN."/producer_service.php";

            if ($tipo_archivo === 'transfer_video') {
                if (!filter_var($ruta_temporal, FILTER_VALIDATE_URL)) {
                    return ['ok' => false, 'error' => 'URL inválida'];
                }
                $data = [
                    'url_archivo'  => $ruta_temporal,
                    'tipo_archivo' => $tipo_archivo,
                    'board_id'     => $board_id,
                    'token_unico'  => uniqid('req_', true) // Token para identificar esta petición única
                ];
            } else {
                if (!file_exists($ruta_temporal)) {
                    return ['ok' => false, 'error' => 'Archivo no existe'];
                }
                $data = [
                    'archivo' => new CURLFile(
                        realpath($ruta_temporal),
                        mime_content_type($ruta_temporal),
                        basename($ruta_temporal)
                    ),
                    'tipo_archivo' => $tipo_archivo,
                    'board_id'     => $board_id,
                    'token_unico'  => uniqid('req_', true)
                ];
            }

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $data,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 10, // Tiempo máximo para conectar
                CURLOPT_TIMEOUT        => 60,  // Tiempo máximo de ejecución
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => ['Expect:']
            ]);

            $response = curl_exec($ch);
            
            // Si cURL falla, no reintentes automáticamente sin lógica de control
            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                return ['ok' => false, 'error' => "cURL Error: $error"];
            }

            curl_close($ch);
            return ['ok' => true, 'response' => json_decode($response, true)];
        }




    }
