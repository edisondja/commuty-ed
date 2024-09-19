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


    /*  
        @Edejesusa  21-07-2024
        Pasar el objecto de configuracion al constructor
        para que este pueda enviar el correo sin problemas,
    */
    public function __construct()
    {
        $this->SetConection();

           // Configuración del servidor SMTP
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

    public function EnviarCorreo($username,$subject='Activar cuenta',$config='registrer')
    {
        try {
            // Destinatarios    
      



           switch($config){
            
            case'registrer':


                $this->mail->setFrom($this->configuracion->email_remitente,'Admin');
                $this->mail->addAddress('edisondja@gmail.com', $username); // Añadir destinatario


                $template = $this->plantilla_registrer($this->configuracion->dominio,
                $this->configuracion->sitio_logo_url,
                $this->configuracion->nombre_sitio,
                $username,
                $this->configuracion->email_sitio);

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

            
            case 'send_mail_user':


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

        
        $plantilla = <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Email Template</title>
                <!-- Bootstrap CSS -->
                <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body style="background-color: #f8f9fa; padding: 20px;">

                <div class="container">
                    <div class="card mx-auto" style="max-width: 600px;">
                        <div class="card-header bg-primary text-white">
                            <h2 class="card-title mb-0"></h2>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{$this->mensaje}</p>
                            <p class="card-text mt-4">
                                Si tienes alguna pregunta, no dudes en contactarnos a través de este correo.
                            </p>
                            <p class="card-text">Saludos cordiales,<br>{$this->configuracion->nombre_sitio}</p>
                        </div>
                        <div class="card-footer text-muted text-center">
                            © 2024 {$this->configuracion->nombre_sitio} Todos los derechos reservados.
                        </div>
                    </div>
                </div>

            </body>
            </html>
            HTML;
            return $plantilla;

    }



    public function plantilla_registrer($dominio, $logo_url, $empresa, $usuario, $contacto) {
        $plantilla = '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Activación de Cuenta</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    text-align: center;
                    padding-bottom: 20px;
                }
                .header img {
                    max-width: 150px;
                }
                .content {
                    font-size: 16px;
                    line-height: 1.5;
                }
                .content h2 {
                    color: #333333;
                }
                .button {
                    display: block;
                    width: 100%;
                    text-align: center;
                    margin: 20px 0;
                }
                .button a {
                    background-color: #007BFF;
                    color: #ffffff;
                    padding: 15px 25px;
                    text-decoration: none;
                    border-radius: 5px;
                    font-size: 16px;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #666666;
                    margin-top: 20px;
                }
                .footer a {
                    color: #007BFF;
                    text-decoration: none;
                }
            </style>
        </head>
        <body> 
            <div class="container">
                <div class="header">
                    <img src="' . $dominio . '' . $logo_url . '" alt="' . $empresa . '">
                </div>
                <div class="content">
                    <h2>¡Bienvenido a ' . $empresa . ', ' . $usuario . '!</h2>
                    <p>Gracias por registrarte en ' . $empresa . '. Estamos emocionados de que te unas a nuestra comunidad. Para completar el proceso de registro y activar tu cuenta, por favor haz clic en el siguiente enlace:</p>
                    <div class="button">
                        <a href="ACTIVATION_URL">Activar mi cuenta</a>
                    </div>
                    <p>Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:</p>
                    <p><a href="' . $dominio . '/activate_user_account.php?username=' . $usuario . '">ACTIVATION_URL</a></p>
                    <p>Una vez que actives tu cuenta, podrás:</p>
                    <ul>
                        <li>Crear publicaciones</li>
                        <li>Comentar</li>
                        <li>Vender productos</li>
                    </ul>
                    <p>¡Esperamos verte pronto!</p>
                    <p>Saludos,</p>
                    <p>El equipo de ' . $empresa . '</p>
                </div>
                <div class="footer">
                    <p>' . $empresa . '</p>
                    <p>[Dirección de la Empresa]</p>
                    <p>' . $contacto . '</p>
                </div>
            </div>
        </body>
        </html>';
    
        return $plantilla;
    }
    


  
}
?>
