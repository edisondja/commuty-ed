<?php

require('EncryptToken.php');

Class User extends EncryptToken{

    public $id_user;
    public $usuario;
    public $sexo;
    public $nombre;
    public $apellido;
    public $clave;
    public $email;
    public $bio;
    public $foto_url;
    public $correo; // Mail $correo → quitar tipo
    public $conection;
    public $config;


        function __construct(){

            $this->config = new Config();
           $this->SetConection();

        }

        function RegistrerUser(){


        $validar_correo = $this->ExistEmailUser($config='asoc');       
            
            if($validar_correo!=='disponible'){

                echo "Email_no_avaible";
                return;
            }

            $configuracion = $this->config->cargar_configuracion('asoc');
            // Verificar si la configuración existe y tiene la propiedad verificar_cuenta
            if ($configuracion && isset($configuracion->verificar_cuenta) && $configuracion->verificar_cuenta == 'SI') {
                $estado = $this->disable();
            } else {
                // Por defecto, activar la cuenta si no hay configuración
                $estado = $this->enable();
            }
            $tipo_usuario = 'normal';
            //$this->SendMailActivedAccount($this->usuario);
            //return;
            if($this->ExistUser()=="disponible"){

                $clave = md5($this->clave);
                $fecha = date('y-m-d h:i:s');
                $sql="insert into
                    users (usuario,clave,
                         email,sexo,
                         foto_url,
                         fecha_creacion,
                         nombre,
                         apellido,
                         bio,estado,
                         type_user)
                VALUES(?,?,?,?,?,?,?,?,?,?,?)";
                $ready = $this->conection->prepare($sql);
                $ready->bind_param('sssssssssss',
                $this->usuario,
                $clave,
                $this->email,
                $this->sexo,
                $this->foto_url,
                $fecha,
                $this->nombre,
                $this->apellido,
                $this->bio,
                $estado,
                $tipo_usuario
                );
                $ready->execute();
                $id_usuario  = $this->conection->insert_id;
                $this->SendMailActivedAccount($id_usuario);
            
                echo "success";


            }else{

                echo "User_no_avaible";

            }
        
        } 

        function get_id_from_user($user,$config="asoc"){

            $sql = "select * from users where usuario=?";
			$cargado = $this->conection->prepare($sql);
            $cargado->bind_param('s',$user);
            $cargado->execute();
            $result = $cargado->get_result();
            
            // Compatible con PHP 7.2 y PHP 8+
            if($config=='asoc'){
                if (PHP_VERSION_ID >= 80000) {
                    $user = $result->fetch_object();
                } else {
                    $user = mysqli_fetch_object($result);
                }
                return $user ? $user : null;

            }else{
                if (PHP_VERSION_ID >= 80000) {
                    $user = $result->fetch_object();
                } else {
                    $user = mysqli_fetch_object($result);
                }
                $user  = json_decode($user);
                echo $user;
            }   
        
            
        }



        function get_info_user($config='json'){


           // $this->DecodeToken($jwt)
            $sql = "select * from users where id_user=?";
			$cargado = $this->conection->prepare($sql);
            $cargado->bind_param('i',$this->id_user);
            $cargado->execute();
            $result = $cargado->get_result();
            // Compatible con PHP 7.2 y PHP 8+
            if (PHP_VERSION_ID >= 80000) {
                $user = $result->fetch_object();
            } else {
                $user = mysqli_fetch_object($result);
            }
            if($config=='json'){

                $user  = json_encode($user);
                echo $user;

            }else{

                /*Devuelve un objecto de con los
                 datos del usuario logueado*/
                 
                return $user;

            }
        
            
        }

       public function updateUser() {
            try {
                    $sql = "update users set
                                usuario = ?,
                                sexo = ?,
                                foto_url = ?,
                                nombre = ?,
                                apellido = ?,
                                bio = ?
                            where id_user = ?;";

                    $stmt = $this->conection->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Error al preparar la consulta: " . $this->conection->error);
                    }
                    
                    if (empty($this->foto_url)) {
                        $this->foto_url = $_POST['imagen_actual'] ?? null;
                    }

                    $stmt->bind_param(
                        'ssssssi',
                        $this->usuario,
                        $this->sexo,
                        $this->foto_url,
                        $this->nombre,
                        $this->apellido,
                        $this->bio,
                        $this->id_user
                    );

                    $stmt->execute();

                    // 🔹 Debug: imprimir lo que se envió
                    $debug = [
                        'usuario'  => $this->usuario,
                        'sexo'     => $this->sexo,
                        'foto_url' => $this->foto_url,
                        'nombre'   => $this->nombre,
                        'apellido' => $this->apellido,
                        'bio'      => $this->bio,
                        'id_user'  => $this->id_user
                    ];

                    if ($stmt->affected_rows > 0) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Usuario actualizado correctamente',
                            'id_user' => $this->id_user,
                            'debug'   => $debug
                        ];
                    } else {
                        // 🔹 Verificar si el usuario existe
                        $check = $this->conection->prepare("SELECT COUNT(*) as total FROM users WHERE id_user = ?");
                        $check->bind_param('i', $this->id_user);
                        $check->execute();
                        $result = $check->get_result()->fetch_assoc();
                        $check->close();

                        if ($result['total'] > 0) {
                            $response = [
                                'status' => 'warning',
                                'message' => 'No hubo cambios en los datos',
                                'id_user' => $this->id_user,
                                'debug'   => $debug
                            ];
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'Usuario no encontrado',
                                'id_user' => $this->id_user,
                                'debug'   => $debug
                            ];
                        }
                    }

                } catch (Exception $e) {
                    $this->TrackingLog("Error al actualizar el usuario: " . $e->getMessage(), 'errores');
                    $response = [
                        'status' => 'error',
                        'message' => 'Ocurrió un error al actualizar el usuario'
                    ];
                } finally {
                    if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();
                    if (isset($this->conection)) $this->conection->close();
                }

                header('Content-Type: application/json');
                echo json_encode($response);
        }
                
        
        function LoadConfigPayUser(){


        }

       public function uploadImage(
            $file = null, 
            $targetDir = "../images/", 
            $maxFileSize = 5242880, // 5 MB
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif']
        ) {
            // Verificar que $file sea un array válido
            if (!isset($file) || !is_array($file) || !isset($file['tmp_name'])) {
                return null;
            }

            // Si no se seleccionó archivo
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                return null;
            }

            // Si hubo un error en la subida
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return null;
            }

            // Verificar tamaño
            if ($file['size'] > $maxFileSize) {
                return null;
            }

            // Verificar tipo MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedMimeTypes)) {
                return null;
            }

            // Crear directorio si no existe
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Generar nombre único
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $targetFile = $targetDir . uniqid('', true) . '.' . $extension;

            // Mover el archivo
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                // Devolver ruta relativa para base de datos

                $this->foto_url =str_replace('..', '', $targetFile);
                return str_replace('..', '', $targetFile);
            }

            return null;
        }

        
        public function Login($user,$clave)
        {       
                session_start();    
                
                // Limpiar espacios en blanco
                $user = trim($user);
                $clave = trim($clave);
                
                // Cifrar la contraseña
                $clave_md5 = md5($clave);
                
                // Debug: Verificar qué se está buscando (solo para desarrollo, quitar en producción)
                error_log("Login attempt - User: $user, Clave MD5: $clave_md5");
                
                // Corregir la consulta: usar paréntesis para agrupar correctamente
                // La consulta anterior tenía problema de precedencia: email=? or usuario=? and clave=?
                // Se evaluaba como: (email=?) or (usuario=? and clave=?) lo cual es incorrecto
                $sql = "select * from users where (email=? or usuario=?) and clave=?";
                $guardar = $this->conection->prepare($sql);
                if ($guardar === false) {
                    echo json_encode(['error' => 'Error al preparar la consulta: ' . $this->conection->error]);
                    return;
                }
                $guardar->bind_param('sss',$user,$user,$clave_md5);
                $guardar->execute();
                $data = $guardar->get_result();
                
                // Debug: Verificar cuántos resultados se encontraron
                error_log("Login query results: " . $data->num_rows);
                    
                if($data->num_rows>0){
                    // Compatible con PHP 7.2 y PHP 8+
                    if (PHP_VERSION_ID >= 80000) {
                        $data = $data->fetch_object();
                    } else {
                        $data = mysqli_fetch_object($data);
                    }
                    
                    // Verificar que el objeto no sea null
                    if (!$data) {
                        echo json_encode(['error' => 'Error al obtener datos del usuario']);
                        return;
                    }
                    
                    // Verificar que el usuario esté activo
                    if ($data->estado == 'inactivo' || $data->estado == 'baneado') {
                        echo json_encode(['error' => 'Usuario inactivo o baneado']);
                        return;
                    }
                    
                    $_SESSION['nombre'] = $data->nombre;
                    $_SESSION['apellido'] = $data->apellido;
                    $_SESSION['sexo'] = $data->sexo;
                    $_SESSION['id_user'] = $data->id_user;
                    $_SESSION['usuario']= $data->usuario;
                    $_SESSION['foto_url'] = $data->foto_url;
                    $_SESSION['type_user']= $data->type_user;
                    $_SESSION['estado_user'] = $data->estado;
                    
                    
                   $token=$this->EncryptUser($data->id_user,$data->usuario);
                    
                    $user_user = [
                            'token'=>$token,
                            'usuario'=>$data->usuario,
                            'estado'=>$data->estado,
                            'nombre'=>$data->nombre,
                            'apellido'=>$data->apellido,
                            'foto_url'=>$data->foto_url,
                            'id_user'=>$data->id_user
                    ];

                    $this->TrackingLog(date('y-m-d h:i:s').'Inicio de sesion usaurio '.$data->usuario,'eventos');
                    echo json_encode($user_user);

                }else{
                    // Debug: Verificar si el usuario existe pero la clave no coincide
                    $sql_check_user = "SELECT usuario, email, clave FROM users WHERE usuario=? OR email=?";
                    $check = $this->conection->prepare($sql_check_user);
                    if ($check) {
                        $check->bind_param('ss', $user, $user);
                        $check->execute();
                        $user_result = $check->get_result();
                        if ($user_result->num_rows > 0) {
                            $user_data = $user_result->fetch_assoc();
                            error_log("Usuario existe pero clave no coincide. Clave BD: " . substr($user_data['clave'], 0, 10) . "... Clave ingresada MD5: " . substr($clave_md5, 0, 10) . "...");
                        } else {
                            error_log("Usuario no encontrado: $user");
                        }
                        $check->close();
                    }
                    
                    echo json_encode(['error' => 'Usuario o contraseña incorrectos']);

                }
                
                $guardar->close();

        }


        public function SigOut($username){

            try{
                session_start();

                $this->TrackingLog(date('y-m-d h:i:s').' Cerrando sesion '.$username,'eventos');
                session_destroy();
                echo 'SigOut';
             

            }catch(Exception $e){

                $this->TrackingLog(date('y-m-d h:i:s').' Cerrando sesion '.$username.' '.$e,'errores');
                echo 'SigOut';  


            }

        }


        public function SendMailActivedAccount($username){

            $acitvar_cuenta = new Mail(new Config);
            $acitvar_cuenta->EnviarCorreo($username);

                //echo "verificando correo electronico";
             //   header("location:../index.php");
        }


        function LoadProfileUser($usuario){

          $SQL =  "select * from users where usuario=?";
          $DataUser =  $this->conection->prepare($SQL);
          
          try{
            
                $DataUser->bind_param('s',$usuario);
                $DataUser->execute();
                $result = $DataUser->get_result();
                // Compatible con PHP 7.2 y PHP 8+
                if (PHP_VERSION_ID >= 80000) {
                    $user = $result->fetch_object();
                } else {
                    $user = mysqli_fetch_object($result);
                }
                return $user ? $user : null;

          }catch(Exception $e){

            $this->TrackingLog(date('y-m-d h:i:s').'Error cargando el perfil '.$usuario,'eventos');

          }

        }
    
        public function ExistUser($config='asoc'){
            
          $SQL =  "select * from users where usuario=?";
          $DataUser =  $this->conection->prepare($SQL);
          $DataUser->bind_param('s',$this->usuario);
          $DataUser->execute();
          $exist = $DataUser->get_result();
          
          if($config=='asoc'){

            if($exist->num_rows>0){

                // Compatible con PHP 7.2 y PHP 8+
                if (PHP_VERSION_ID >= 80000) {
                    $exist = $exist->fetch_object();
                } else {
                    $exist = mysqli_fetch_object($exist);
                }
                return $exist ? $exist->usuario : null;

            }else{

                return "disponible";

            }

          }else{

                if($exist->num_rows>0){

                    // Compatible con PHP 7.2 y PHP 8+
                    if (PHP_VERSION_ID >= 80000) {
                        $exist = $exist->fetch_object();
                    } else {
                        $exist = mysqli_fetch_object($exist);
                    }
                    echo $exist ? $exist->usuario : '';

                }else{

                   echo "disponible";

                }

          }
          
        }

         
        public function ExistEmailUser($config='asoc'){
     
            $SQL =  "select * from users where email=?";
            $DataUser =  $this->conection->prepare($SQL);
            $DataUser->bind_param('s',$this->email);
            $DataUser->execute();
            $exist = $DataUser->get_result();
            

            if($config=='asoc'){
                if($exist->num_rows>0){

                    // Compatible con PHP 7.2 y PHP 8+
                    if (PHP_VERSION_ID >= 80000) {
                        $exist = $exist->fetch_object();
                    } else {
                        $exist = mysqli_fetch_object($exist);
                    }
                    return $exist ? $exist->email : null;
        
                }else{

                    return "disponible";
                
                }
            }else{

                if($exist->num_rows>0){

                    // Compatible con PHP 7.2 y PHP 8+
                    if (PHP_VERSION_ID >= 80000) {
                        $exist = $exist->fetch_object();
                    } else {
                        $exist = mysqli_fetch_object($exist);
                    }
                    echo $exist ? $exist->email : '';
        
                }else{

                    echo "disponible";
                }

            }
          }


          public function CargarTodosLosCorreos($config='json') {

            /*00
                Cargar todos los correos electronicos para enviarles mensajes
            */
            $SQL = "select distinct email,usuario,nombre from users where estado=?";
            try {
                $estado = $this->enable();
                
                // Preparar y ejecutar la consulta
                $result = $this->conection->prepare($SQL);
                $result->bind_param('s', $estado);
                $result->execute();
        
                // Obtener el resultado
                $data = $result->get_result();
                
                // Verificar si hay datos
                if ($data->num_rows > 0 && $config=='json') {
                    // Convertir el resultado en un array
                    $emails = [];
                    while ($row = $data->fetch_assoc()) {
                        $emails[] = $row;
                    }
                    // Devolver el array como JSON
                    echo json_encode($emails);

                }else if($data->num_rows > 0 && $config=='asoc')

                    return $data;

                else {
                    echo json_encode(['message' => 'No se encontraron correos.']);
                }
        
                // Cerrar el statement
                $result->close();
        
            } catch (Exception $e) {
                // Registrar el error con el mensaje y el stack trace
                $this->TrackingLog(date('Y-m-d H:i:s') . ' Error cargando correos: ' . $e->getMessage(), 'errores');
                
                // Enviar un mensaje de error en formato JSON
                echo json_encode(['error' => 'Error al cargar los correos.']);
            }
        }
        
        



        public function BuscarUsuarios($context, $config) {
            
            $sql = "select * from users where nombre LIKE ? OR usuario LIKE ? limit 50";
            $cargar = $this->conection->prepare($sql);
            
            if (!$cargar) {
                // Manejo de error en la preparación de la consulta
                throw new Exception("Error preparando la consulta: " . $this->conection->error);
            }
        
            $search = "%" . $context . "%";
            $cargar->bind_param('ss', $search, $search);
            
            try{

                $cargar->execute();
                $result = $cargar->get_result();
            
                if ($result === false) {
                    // Manejo de error en la ejecución de la consulta
                    throw new Exception("Error ejecutando la consulta: " . $this->conection->error);
                }
            
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            
                switch ($config) {
                    case 'asoc':
                        return $data;
            
                    case 'json':
                        echo json_encode($data);
                        break;
            
                    default:
                        throw new Exception("Configuración no válida: " . $config);
                }

           }catch(Exception $e){

              #@Edejesua 10-09-2024
               $this->TrackingLog(date('y-m-d h:i:s').'Error buscando usuarios '.$e,'eventos');

           }
        }


        public function ActivarUsuario($msj=false){

            $estado ='activo';
            $sql = "update users set estado=? where id_user=?";
			
            try{
                $cargado = $this->conection->prepare($sql);
                $cargado->bind_param('si',$estado,$this->id_user);
                $cargado->execute();
                if($msj==true){
                    
                        echo "Usuario activado";
                }
                
             $this->TrackingLog(date('y-m-d h:i:s').' Activando usaurio ID: '.$this->id_user,'eventos');


           }catch(Exception $e){

             $this->TrackingLog(date('y-m-d h:i:s').' Error Activando usaurio ID: '.$this->id_user,'errores');

           }
        }

        public function DesactivarUsuario($msj=false){

            $estado ='inactivo';
            $sql = "update users set estado=? where id_user=?";
			
            try{

                $cargado = $this->conection->prepare($sql);
                $cargado->bind_param('si',$estado,$this->id_user);
                $cargado->execute();
                //echo "Usuario desactivado";

                if($msj==true){
                    echo "Usuario activado";
                }
                $this->TrackingLog(date('y-m-d h:i:s').' Desactivando usaurio ID: '.$this->id_user,'errores');

            }catch(Exception $e){

                $this->TrackingLog(date('y-m-d h:i:s').' Error Desactivando usaurio ID: '.$this->id_user,'errores');

            }

        }

}



/*
$usuario = new User();
$usuario->getBearerToken();
$usuario->Login('edisondja','Meteoro2412');
$usuario->usuario = 'edisonxxx';
$usuario->nombre= 'Edison De Jesus';
$usuario->foto_url= 'Edison De Jesus';
$usuario->apellido= 'Edison De Jesus';
$usuario->clave = "concentro";
$token = $usuario->EncryptUser(1);
$usuario->VerifiyTokenExpired('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiZWRpc29uZGphIiwiaWRfdXNlciI6MSwiZXhwIjoxNjY2MzI1MjUxfQ.xV3UfYNvt5CvWvHc9k7KnD8k1_0uxFMGpi9HsgC86to');
//echo $token;
//$usuario->DecodeToken($token);
//$usuario->RegistrerUser();


*/



?>

