<div class="row">
    <div class="col-md-3"></div>

    <div class="col-md-6 form-container" id="form_reg">
        <h3 class="text-center mb-4">Registrate</h3>
        
        <div class="form-group mb-3">
            <label for="username"><strong>Username</strong></label>
            <input type="text" id="username" name="user" class="form-control" />
        </div>
        <div class="alert alert-danger" style="display: none;" id="alert_user_field" role="alert">
            Este usuario ya está en uso.
        </div>
        
        <div class="form-group mb-3">
            <label for="password"><strong>Password</strong></label>
            <input type="password" id="password" name="password" class="form-control" />
        </div>
    
        <div class="form-group mb-3">
            <label for="password2"><strong>Repeat password</strong></label>
            <input type="password" id="password2" name="password" class="form-control" />
        </div>
        <div class="alert alert-danger" style="display: none;" id="alert_passowrd_field" role="alert">
            Las contraseñas no coinciden.
        </div>

        <div class="form-group mb-3">
            <label for="email"><strong>Email</strong></label>
            <input type="text" placeholder="example@hotmail.com" id="email" name="email" class="form-control" />
        </div>
        <div class="alert alert-danger" style="display: none;" id="alert_email_field" role="alert">
            Esto no es un correo electrónico válido.
        </div>
        
        <div class="form-group mb-3">
            <label for="name"><strong>Name</strong></label>
            <input type="text" id="name" name="name" class="form-control" />
        </div>

        <div class="form-group mb-3">
            <label for="sex"><strong>Sex</strong></label>
            <select name="sex" id="sex" class="form-control">
                <option value='h'>Hombre</option>
                <option value='m'>Mujer</option>
            </select>
        </div>
        
        <div class="form-group mb-3">
            <label for="last_name"><strong>Last Name</strong></label>
            <input type="text" id="last_name" name="last_name" class="form-control" />
        </div>
        
        <div class="form-group mb-3">
            <label for="bio_u"><strong>Write a micro bio</strong></label>
            <textarea id="bio_u" name="bio" class="form-control"></textarea>
        </div>
        
        <input type="hidden" name="action" value="create_user" />
        <hr/>
        
        <div class="text-center">
            <button type="button" id="btn_join" class="btn btn-dark btn-lg">Join Now</button>
        </div>
    </div>
   
   
    <div class="col-md-6 card" id="welcome_fmb" style="background-color: #1f1f1f; color: #ffffff; display:none;">
        <div class="card-header text-center" style="background-color: #333333;">
            <h3>¡Bienvenido a {$nombre}!</h3>
        </div>
                <div class="card-body">
                    <p class="card-text" id="user_text">
                        Hola [Nombre del Usuario],
                    </p>
                    <p class="card-text">
                        Gracias por registrarte en nuestro sitio. Estamos emocionados de que te hayas unido a nuestra comunidad.
                    </p>
                    <p class="card-text">
                        Para completar tu registro, por favor revisa tu correo electrónico. Hemos enviado un enlace de activación a tu dirección de correo. Haz clic en ese enlace para activar tu cuenta y empezar a disfrutar de todos nuestros servicios.
                    </p>
                    <p class="card-text">
                        Si no ves el correo en tu bandeja de entrada, asegúrate de revisar también tu carpeta de spam o correo no deseado.
                    </p>
                    <p class="card-text">
                        ¡Gracias por unirte a nosotros!
                    </p>
                </div>
                <div class="card-footer text-center" style="background-color: #333333;">
                    <a href="{$dominio}" class="btn btn-dark">Ir a la página principal</a>
                </div>
        </div>
    

</div>
<!-- Add the following CSS to your main stylesheet or within a <style> tag -->
{literal}
    <style>

    body {
            background-color: #121212;
           
            font-family: 'Arial', sans-serif;
        }
    .form-container {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 10px;
        margin-left: 2%;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .form-container h3 {
        margin-bottom: 1.5rem;
        color: #dc3545; /* Bootstrap danger color */
    }
    .form-container label {
        color: #6c757d; /* Bootstrap secondary color */
    }
    .form-container .form-control:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    .btn-custom {
        background-color: #dc3545;
        color: #ffffff;
        border: none;
    }
    .btn-custom:hover {
        background-color: #c82333;
    }
</style>


    
{/literal}

<script src="{$dominio}/js/mod_registro_usuarios.js"></script>


<style>
 .form-container {
            background-color: #1f1f1f;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background-color: #333333;
            border: 1px solid #444444;
            color: #ffffff;
        }
        .form-control:focus {
            background-color: #444444;
            border-color: #666666;
            color: #ffffff;
        }
        .form-group label {
            color: #aaaaaa;
        }
        .btn-dark {
            background-color: #333333;
            border-color: #555555;
        }
        .btn-dark:hover {
            background-color: #555555;
            border-color: #777777;
        }
        .alert-danger {
            background-color: #d9534f;
            border-color: #d43f3a;
            color: #ffffff;
        }

</style>