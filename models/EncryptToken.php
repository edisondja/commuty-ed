<?php

    require('Prop.php');

    use Firebase\JWT\ExpiredException;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    global $conexion;
    $conexion = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);

    Class EncryptToken extends Prop{
     
        public string $key = 'meteorito62410';
        

        function __construct(){

            // $this->SetConection();
        }

        public function SetConection(){
            global $conexion; 
            $this->conection = $conexion;
        }

        public function EncryptUser($id_user,$usuario){

            $init = time();

            $payload = [
                'user'=>$usuario,
                'id_user' => $id_user,
                'exp' => (time() + 60*60)
            ];
            
            $jwt = JWT::encode($payload,$this->key,'HS256');

            return $jwt;
            //print_r($jwt);

           // $this->DecodeToken($jwt);
        }

        public function VerifiyTokenExpired($jwt){


            try{

            $token  = (array) $this->DecodeToken($jwt);

            //  echo "TOKEN CON EXITO CREADO ".$token['id_user']."  ".$token['user'];

                
               return (object)[     
                     
                     'token'=>'ok',
                     'id_user'=>$token['id_user'],
                     'user'=>$token['user']
               
                    ];

            
            }catch(ExpiredException $e){

                return json_encode(['token'=>'expired']);
            }
            
        }


        public function DecodeToken($jwt){


            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));

            return $decoded ;


        }


        function getAuthorizationHeader(){
            $headers = null;
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                //print_r($requestHeaders);
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }

        public function getBearerToken() {
            $headers = $this->getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            return null;
        }

        public function test(){


            echo  "runing";
        }



    }








?>