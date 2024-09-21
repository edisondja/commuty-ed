<?php

require('EncryptToken.php');

Class User extends EncryptToken{

    public int $id_user;
    public string $usuario;
    public string $sexo;
    public string $nombre;
    public string $apellido;
    public string $clave;
    public string $email;
    public string $bio;
    public string $foto_url;
    public Mail $correo;
    public $conection;

        function __construct(){

        
           $this->SetConection();

        }

        function RegistrerUser(){


        $validar_correo = $this->ExistEmailUser($config='asoc');       
            
            if($validar_correo!=='disponible'){

                echo "Email_no_avaible";
                return;
            }

            $estado = $this->disable();
            $tipo_usuario = 'normal';
            //$this->SendMailActivedAccount($this->usuario);
            //return;
            if($this->ExistUser()=="disponible"){

                $clave = md5($this->clave);
                $fecha = date('y-m-d h:i:s');
                $sql="INSERT INTO
                    USER (usuario,clave,
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

            $sql = "select * from user where usuario=?";
			$cargado = $this->conection->prepare($sql);
            $cargado->bind_param('s',$user);
            $cargado->execute();
            $user = $cargado->get_result();
            if($config=='asoc'){

                $user = mysqli_fetch_object($user);
                return $user;

            }else{
                $user = mysqli_fetch_object($user);
                $user  = json_decode($user);
                echo $user;
            }   
        
            
        }



        function get_info_user($config='json'){


           // $this->DecodeToken($jwt)
            $sql = "select * from user where id_user=?";
			$cargado = $this->conection->prepare($sql);
            $cargado->bind_param('i',$this->id_user);
            $cargado->execute();
            $user = $cargado->get_result();
            $user = mysqli_fetch_object($user);
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
           

            $fecha = date('y-m-d h:i:s');
            $sql = "update user
            set
                usuario = ?,
                sexo = ?,
                foto_url = ?,
                fecha_creacion = ?,
                nombre = ?,
                apellido = ?,
                bio = ?
            where
                id_user = ?;";

            $ready = $this->conection->prepare($sql);
            $ready->bind_param('sssssssi',
            $this->usuario,
            $this->sexo,
            $this->foto_url,
            $fecha,
            $this->nombre,
            $this->apellido,
            $this->bio,
            $this->id_user
            );
            $ready->execute();



        }
        function LoadConfigPayUser(){


        }

       public function uploadImage($file, $targetDir = "../images/", $maxFileSize = 5 * 1024 * 1024, $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif']) {
            // Verificar si el archivo existe en la solicitud
          
            file_put_contents('logs/logs.txt','entro informacion realmente');

            if (!isset($file) || $file['error'] == UPLOAD_ERR_NO_FILE) {
                return $file["tmp_name"];
            }
        
            // Verificar si hay algún error en la subida
            if ($file['error'] != UPLOAD_ERR_OK) {
                return $file["tmp_name"];
            }
        
            // Verificar el tamaño del archivo
            if ($file["size"] > $maxFileSize) {
                return $file["tmp_name"];
            }
        
            // Verificar el tipo MIME del archivo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file["tmp_name"]);
            finfo_close($finfo);
            if (!in_array($mimeType, $allowedMimeTypes)) {
                return $file["tmp_name"];
            }
        
            // Crear el directorio de subida si no existe
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
        
            // Generar un nombre único para el archivo
            $targetFile = $targetDir . uniqid() . '.' . pathinfo($file["name"], PATHINFO_EXTENSION);
        
            // Mover el archivo a la ubicación deseada
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
               // return "El archivo " . htmlspecialchars(basename($file["name"])) . " ha sido subido exitosamente.";
               
               file_put_contents('../logs/logs.txt','entro informacion realmente');
               return str_replace('..','',$targetFile); 

            } else {
                return $file["tmp_name"];
            }
        }
        
        public function Login($user,$clave)
        {       
                session_start();    
                $clave = md5($clave);
                $sql = "select * from user where email=? or usuario=? and clave=?";
                $guardar = $this->conection->prepare($sql);
                $guardar->bind_param('sss',$user,$user,$clave);
                $guardar->execute();
                $data = $guardar->get_result();
                    
                if($data->num_rows>0){

                    $data = mysqli_fetch_object($data);
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

                    echo "user incorrect";

                }

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

          $SQL =  "select * from user where usuario=?";
          $DataUser =  $this->conection->prepare($SQL);
          
          try{
            
                $DataUser->bind_param('s',$usuario);
                $DataUser->execute();
                $user = $DataUser->get_result();
                $user = mysqli_fetch_object($user);     
                return $user;

          }catch(Exception $e){

            $this->TrackingLog(date('y-m-d h:i:s').'Error cargando el perfil '.$usuario,'eventos');

          }

        }
    
        public function ExistUser($config='asoc'){
            
          $SQL =  "select * from user where usuario=?";
          $DataUser =  $this->conection->prepare($SQL);
          $DataUser->bind_param('s',$this->usuario);
          $DataUser->execute();
          $exist = $DataUser->get_result();
          
          if($config=='asoc'){

            if($exist->num_rows>0){

                $exist = mysqli_fetch_object($exist);
                return $exist->usuario;

            }else{

                return "disponible";

            }

          }else{

                if($exist->num_rows>0){

                    $exist = mysqli_fetch_object($exist);
                    echo $exist->usuario;

                }else{

                   echo "disponible";

                }

          }
          
        }

         
        public function ExistEmailUser($config='asoc'){
     
            $SQL =  "select * from user where email=?";
            $DataUser =  $this->conection->prepare($SQL);
            $DataUser->bind_param('s',$this->email);
            $DataUser->execute();
            $exist = $DataUser->get_result();
            

            if($config=='asoc'){
                if($exist->num_rows>0){

                    $exist = mysqli_fetch_object($exist);
                    return $exist->email;
        
                }else{

                    return "disponible";
                
                }
            }else{

                if($exist->num_rows>0){

                    $exist = mysqli_fetch_object($exist);
                    echo $exist->email;
        
                }else{

                    echo "disponible";
                }

            }
          }


          public function CargarTodosLosCorreos($config='json') {

            /*00
                Cargar todos los correos electronicos para enviarles mensajes
            */
            $SQL = "SELECT DISTINCT email,usuario,nombre FROM USER WHERE estado=?";
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
            
            $sql = "SELECT * FROM user WHERE nombre LIKE ? OR usuario LIKE ? limit 50";
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
            $sql = "update user set estado=? where id_user=?";
			
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
            $sql = "update user set estado=? where id_user=?";
			
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

