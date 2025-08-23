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
                    <p class="card-text">{$mensaje}</p>
                    <p class="card-text mt-4">
                        Si tienes alguna pregunta, no dudes en contactarnos a través de este correo.
                    </p>
                    <p class="card-text">Saludos cordiales,<br>{$nombre_sitio}</p>
                </div>
                <div class="card-footer text-muted text-center">
                    © 2024 {$nombre_sitio} Todos los derechos reservados.
                </div>
            </div>
        </div>
    </body>
</html>