<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail extends EncryptToken
{
    public int $id_mail;
    public string $host;
    public string $mensaje;
    public string $resector;
    public PHPMailer $mail;
    public Config $configuracion;
    public User $usuario;


    /*  
        @Edejesusa  21-07-2024
        Pasar el objecto de configuracion al constructor
        para que este pueda enviar el correo sin problemas,
    */
    public function __construct()
    {
        $this->SetConection();

           // Configuración del servidor SMTP
           $this->usuario = new User();
           $this->mail = new PHPMailer(true);
           $this->configuracion = new Config();
           $this->configuracion->Cargar_configuracion('asoc');
           $this->mail->isSMTP();
           //$this->mail->SMTPDebug = 2;                                    // Habilitar salida de depuración detallada
           $this->mail->Host       = $this->configuracion->servidor_smtp;
           $this->mail->SMTPAuth   = true;
           $this->mail->Username   = $this->configuracion->email_remitente;
           $this->mail->Password   =  $this->configuracion->clave_smtp;
           $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
           $this->mail->Port       =  $this->configuracion->puerto_smtp;
               // Contenido del correo
           if($this->configuracion->autenticacion_ssl=='si'){
                $this->mail->isHTML(true);
           }else{ 
               $this->mail->isHTML(false);
           }

    }

    public function EnviarCorreo($id_user,$subject='Activar cuenta',$config='registrer')
    {
        try {
            // Destinatarios    
            
            $this->usuario->id_user = $id_user;
            /*Capturando datos de usuario registrado
               para luego notificar por gmail
            */
            $data= $this->usuario->get_info_user('asoc');

           switch($config){
            
            case'registrer':


                $this->mail->setFrom($this->configuracion->email_remitente,'Admin');
                $this->mail->addAddress($data->email, $data->usuario); // Añadir destinatario
                $template = $this->plantilla_registrer($data->usuario,$this->configuracion->email_sitio);
                $this->mail->Subject = $subject;
                $this->mail->Body = $template;
                $this->mail->AltBody = 'Este es el cuerpo del correo en texto plano';
                $this->mail->send();

            break;

            case 'send_mail_all':

                $user = new User();
                $correos = $user->CargarTodosLosCorreos('asoc');
                    
                foreach($correos as $key){

                    /*
                        Enviando correo a todos los usuarios de la plataforma
                    */
                    $this->mail->addAddress($key['email'], $key['usuario']); // Añadir destinatario
                    $template = $this->plantilla_correo_masivo();
                    $this->mail->Subject = $subject;
                    $this->mail->Body = $template;
                   // $this->mail->AltBody = 'Este es el cuerpo del correo en texto plano';
                    $this->mail->send();
    
                }
                
                

            break;

            
            case 'send_mail_to_user':
                
                
                $this->mail->addAddress($key['email'], $key['usuario']); // Añadir destinatario
                $template = $this->plantilla_correo_masivo();
                $this->mail->Subject = $subject;
                $this->mail->Body = $template;
               // $this->mail->AltBody = 'Este es el cuerpo del correo en texto plano';
                $this->mail->send();


            break;
            
           } 
          

           

          //  echo 'El mensaje ha sido enviado';
        } catch (Exception $e) {

            $this->TrackingLog(date('y-m-d h:i:s').'Error Enviando mail tipo: '.$config,'errores');
            //Aqui se debe de colocar una Bitacora para nificar los errores
            //echo "El mensaje no pudo ser enviado. Error de Mailer: {$this->mail->ErrorInfo}";
        }
    }

    public function EnviarCorreoBloqueado()
    {
        // Implementación de EnviarCorreoBloqueado
    }

    public function EnviarCorreoMasivo()
    {
        // Implementación de EnviarCorreoMasivo
    }


    public function plantilla_correo_masivo(){

        
         // URL de destino (cambia según el nombre de tu archivo PHP en el servidor)
     
         $url = $this->configuracion->dominio."/generar_template_mail_masivo.php";
         // Los datos que quieres enviar
         $data = [
             'mensaje' => $this->mensaje
         ];
         // Inicializa cURL
         $ch = curl_init();
         // Configura la URL y otros parámetros para la petición POST
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
         // Ejecuta la solicitud y captura la respuesta
         $response = curl_exec($ch);
         // Cierra la sesión de cURL
         curl_close($ch);
         // Imprime la respuesta (esto generará y mostrará la plantilla HTML)
         return $response;
    }



    public function plantilla_registrer($usuario, $contacto) {
     

        //Enviar datos a la plantilla que sera enviada por correo
        
        $url = $this->configuracion->dominio."/generar_template_mail_registrer.php";
        // Los datos que quieres enviar
        $data = [
            'usuario' => $usuario,
            'contacto' =>$contacto
        ];
        // Inicializa cURL
        $ch = curl_init();
        // Configura la URL y otros parámetros para la petición POST
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // Ejecuta la solicitud y captura la respuesta
        $response = curl_exec($ch);
        // Cierra la sesión de cURL
        curl_close($ch);
        // Imprime la respuesta (esto generará y mostrará la plantilla HTML)
        return $response;
            

    }
    


  
}
?>
