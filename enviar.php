<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'edisondja@gmail.com';
    $mail->Password   = 'lanrquvflprxbdwu';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Destinatarios
    $mail->setFrom('edisondja@gmail.com', 'Edison De Jesus');
    $mail->addAddress('edisondja@gmail.com', 'Destinatario'); // Añadir destinatario
                
    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Activar cuenta';
    $mail->Body    = '
        <h3>Vertifique en el enlace para mayor informacion</h3>
            <p>Estamos probando el servidor de correo de la mejor tienda de rd.</p>
    ';
    $mail->AltBody = 'Este es el cuerpo del correo en texto plano';

    $mail->send();
    echo 'El mensaje ha sido enviado';
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
}
?>
