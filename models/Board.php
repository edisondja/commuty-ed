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

        public function detectar_imagen_portada($portada, $id_tablero, $archivo_temp)
        {
         //   echo ' PORTADA LEIDA ' || $portada;

            if ($portada == 'jpeg' || $portada == 'png') {
                if ($this->portada_board == false) {
                    /*la primera imagen que encuentre
                        se le pondra de portada al
                    */
                    $fecha = new DateTime();
                    $estado = $this->enable();
                    $fecha_a = $fecha->getTimestamp();
                    $guardar_c = "../imagenes_tablero/$fecha_a.jpg";
                    $this->asignar_portada_tablero(
                        $id_tablero,
                        $archivo_temp,
                        $guardar_c
                    );
                    $this->portada_board = true;
                }
            }
        }

        public function asignar_portada_tablero($id_tablero, $ruta_temp, $guardar_como)
        {
            $sql = 'update tableros set imagen_tablero=? where id_tablero=?';
            $guardar = $this->conection->prepare($sql);
           
            try {
                move_uploaded_file($ruta_temp, $guardar_como);
                $ruta_portada = str_replace('..', '', $guardar_como);
                $guardar->bind_param('si', $ruta_portada, $id_tablero);
                $guardar->execute();

            } catch (Exception $e) {

                $fecha = date('ymdis');

                $this->TrackingLog($fecha.' No se puedo asignar la portada '.$e,'errores');
            }
        }

    public function guardar_tablero()
    {
        try {
            // Fecha en formato MySQL
            $fecha = date('Y-m-d H:i:s');

            $configuracion = $this->config->cargar_configuracion('asoc');

            if ($configuracion->publicar_sin_revision == 'SI') {
                $estado = $this->enable();
            } else {
                $estado = $this->disable();
            }

            // Insertar tablero en la base de datos
            $sql = "insert into tableros(descripcion, fecha_creacion, imagen_tablero, id_usuario, estado)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conection->prepare($sql);
            $stmt->bind_param('sssis', $this->description, $fecha, $this->imagen_tablero, $this->id_usuario, $estado);
            $stmt->execute();
            $last_id = $this->conection->insert_id;
            $stmt->close();

            // Procesar archivos subidos si existen
            if (isset($_FILES['media']['tmp_name'])) {
                $archivos = is_array($_FILES['media']['tmp_name']) 
                            ? count($_FILES['media']['tmp_name']) 
                            : 1;

                for ($i = 0; $i < $archivos; ++$i) {
                    // Obtener info del archivo
                    $tmp_name = is_array($_FILES['media']['tmp_name']) ? $_FILES['media']['tmp_name'][$i] : $_FILES['media']['tmp_name'];
                    $type     = is_array($_FILES['media']['type']) ? $_FILES['media']['type'][$i] : $_FILES['media']['type'];

                    // Validar tipo de archivo permitido
                    $allowed_types = ['image/jpeg', 'image/png', 'video/mp4'];
                    if (!in_array($type, $allowed_types)) continue;

                    $tipo_archivo = $this->detectar_archivo($type);

                    // Detectar portada
                    $this->detectar_imagen_portada($tipo_archivo, $last_id, $tmp_name);

                    // Asignar multimedia al tablero
                    $this->asignador_de_multimedia_tablero(
                        $last_id,
                        $tmp_name,
                        $type,
                        $this->limitarTexto($this->description)
                    );
                }
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
         
            $sql = "SELECT * FROM tableros INNER JOIN user
             ON tableros.id_usuario = user.id_user 
            WHERE id_tablero = ?";
            $cargado = $this->conection->prepare($sql);
            $cargado->bind_param('i', $id_tablero);
            $cargado->execute();
            $data = $cargado->get_result();
            $cargado->close();
        
            $data = mysqli_fetch_object($data);
         
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
            $sql = 'SELECT * FROM tableros INNER JOIN user
             ON tableros.id_usuario = user.id_user 
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
                    inner join user u on  t.id_usuario=u.id_user
                    where t.estado=? order by id_tablero desc limit ?,?';
                    $cargar = $this->conection->prepare($sql);
                    $cargar->bind_param('sii', $estado, $inicio, $fin);
                    $cargar->execute();
                    $data = $cargar->get_result();
                
                break;

                case 'paginar_usuario':

                    $sql = 'select * from tableros t
                    inner join user u on  t.id_usuario=u.id_user
                    where t.estado=? and t.id_usuario=? order by id_tablero desc limit ?,?';
                    $cargar = $this->conection->prepare($sql);
                    $cargar->bind_param('siii', $estado,$user_id,$inicio, $fin);
                    $cargar->execute();
                    $data = $cargar->get_result();


                break;

                case 'paginar_favoritos':

                    /*
                        En este apartado solo se paginan los tableros
                        favoritos, que el usuario guardo como preferencias
                        
                    */

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
                    INNER JOIN user as u ON t.id_usuario = u.id_user 
                    WHERE t.titulo LIKE ? OR t.descripcion LIKE ?
                    order by fecha_creacion desc
                    LIMIT 8
                ');
                $data->bind_param('ss', $texto, $texto);
            } else {
                $data = $this->conection->prepare('
                    SELECT * FROM tableros as t
                    INNER JOIN user as u ON t.id_usuario = u.id_user 
                    WHERE (t.titulo LIKE ? OR t.descripcion LIKE ?) 
                    AND t.estado = ? order by fecha_creacion desc
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
            $this->conection;

            $fecha = new DateTime();
            $estado = $this->enable();
            sleep(1);
            $fecha_a = $fecha->getTimestamp();
            $tipo_a = $this->detectar_archivo($tipo_archivo);
            $titulo_de_assets = $this->titleList($titulo);
            $guardar_como = '';
            //echo $tipo_a;
            if ($tipo_a == 'jpeg' || $tipo_a == 'png') {
                $tipo_asset = 'imagen';
                $guardar_como = "../imagenes_tablero/$fecha_a$titulo_de_assets.jpg";
                sleep(1);
                if (!move_uploaded_file($url_temp, $guardar_como)) {
                    //echo "Error al mover el archivo a $guardar_como";

                    return;
                }
            } elseif ($tipo_a == 'mp4' || $tipo_a == 'webm' || $tipo_a == 'avi') {
                $tipo_asset = 'video';
                $guardar_como = "../videos/$fecha_a$titulo_de_assets.mp4";
                sleep(1);
                if (!move_uploaded_file($url_temp, $guardar_como)) {
                    //echo "Error al mover el archivo a $guardar_como";

                    $this->TrackingLog(date('ymdis').' No se puedo asignar la portada '.$e,'errores');
                    return;
                }
            } else {

                echo 'Lo sentimos este tipo de archivo no esta permitido';
                $this->TrackingLog(date('ymdis').' No se puedo asignar la portada '.$e,'errores');

            }

            //$guardar_como = str_replace('../','',$guardar_como);

            $sql = 'insert into asignar_multimedia_t(id_tablero,
                ruta_multimedia,
                tipo_multimedia,
                estado)values(?,?,?,?)';
            //echo $id_tablero . ", " . $guardar_como . ", " . $tipo_asset . ", " . $estado;
            try{
                $guardar = $this->conection->prepare($sql);
                $guardar->bind_param('isss', $id_tablero, $guardar_como, $tipo_asset, $estado);
                $guardar->execute();
                $guardar->close();
                $this->TrackingLog(date('ymdis').'Multimedia agregada con exito','eventos');

            }catch(Exception $e){

                $this->TrackingLog(date('ymdis').'No se pudo asignar la multimedia '.$e,'errores');

            }

        }

        public function cargar_tablerosx($id_usuario = 'general', $opcion = 'json')
        {
            $this->conection;
            $estatus = $this->enable();

            if ($id_usuario == 'general') {
                $sql = 'select * from tableros inner join user on 
                tableros.id_usuario=user.id_user 
                where tableros.estado=? order by id_tablero desc limit 8';
                $cargado = $this->conection->prepare($sql);
                $cargado->bind_param('s', $estatus);
            } else {
                $sql = 'select * from tableros  inner join user on tableros.id_usuario=user.id_user where id_usuario=? and tableros.estado=? order by id_tablero desc limit 8';
                $cargado = $this->conection->prepare($sql);
                $cargado->bind_param('is', $id_usuario, $estatus);
            }

            try{
                $cargado->execute();
                $data = $cargado->get_result();
                $cargado->close();
                $json = [];
                foreach ($data as $key) {
                    $json[] = $key;
                }
                if ($opcion == 'json') {
                    echo json_encode($json);
                } else {
                    /* If the var opcion dont is equal to json return array associative */
                    return $json;
                }

            }catch(Exception $e){

                $this->TrackingLog(date('ymdis').'Error cargando tableros '.$e,'errores');

            }
        }

        public function eliminar_multimedia_de_tablero($id_multimediar)
        {
            $estado = $this->disable();
            $this->conection;
            $sql = 'update asignar_multimedia_t set estado=? where id_asignar=?';
         
            try{

                $eliminar = $this->conection->prepare($sql);
                $eliminar->bind_param('si', $estado, $id_multimedia);
                $eliminar->execute();
                $eliminar->close();

            }catch(Exception $e){

                $this->TrackingLog(date('ymdis').'No se pudo eliminar multimedia del tablero'.$e,'errores');

            }
        }

        public function cargar_multimedias_de_tablero($id_tablero, $config = 'json')
        {
            $this->conection;
            $estado = $this->enable();


            $sql = 'select * from tableros AS t 
                inner join asignar_multimedia_t as m
                on t.id_tablero=m.id_tablero where t.id_tablero=? 
                and t.estado=?';
            $cargar = $this->conection->prepare($sql);
            $cargar->bind_param('is', $id_tablero, $estado);
            $cargar->execute();
            $data = $cargar->get_result();
            $cargar->close();
            $datos = [];
            foreach ($data as $key) {
                $datos[] = $key;
            }
            if ($config == 'json') {
                echo json_encode($datos);
            } else {
                return $datos;
            }
        }


 

    }
