'<!DOCTYPE html>
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
                    <img src="{$logo}" alt="' . $empresa . '">
                </div>
                <div class="content">
                    <h2>¡Bienvenido a {$empresa} {$usuario}</h2>
                    <p>Gracias por registrarte {$empresa} Estamos emocionados de que te unas a 
                    nuestra comunidad. Para completar el proceso de registro y activar tu cuenta, 
                    por favor haz clic en el siguiente enlace:</p>
                    <div class="button">
                        <a href="{$dominio}/activate_user_account.php?username={$usuario}">Activar mi cuenta</a>
                    </div>
                    <p>Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:</p>
                    <p><a href="{$dominio}/activate_user_account.php?username={$usuario}">ACTIVATION_URL</a></p>
                    <p>Una vez que actives tu cuenta, podrás:</p>
                    <ul>
                        <li>Crear publicaciones</li>
                        <li>Comentar</li>
                        <li>Vender productos</li>
                    </ul>
                    <p>¡Esperamos verte pronto!</p>
                    <p>Saludos,</p>
                    <p>El equipo de {$empresa}'</p>
                </div>
                <div class="footer">
                    <p>{$empresa}</p>
                    <p></p>
                    <p>{$contacto}</p>
                </div>
            </div>
        </body>
        </html>'