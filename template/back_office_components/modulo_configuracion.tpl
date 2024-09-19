
<div class="col-md-8 col-12 content-container mx-auto tabla_buscar">
    <div class="container mt-5">
    <input type="hidden" id="modulo_select" value="config"/>
    <h2>Configuración del Sitio <i class="fa-solid fa-gears"></i></h2>
    <select class="" id="search" style="float: right;">


    </select><hr/>
    <div class="container mt-5">
    <!-- Pestañas de Navegación -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="configuracion-sitio-tab" data-toggle="tab" href="#configuracion-sitio" role="tab" aria-controls="configuracion-sitio" aria-selected="true">Configuración del Sitio <i class="fa-regular fa-file"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="configuracion-correo-tab" data-toggle="tab" href="#configuracion-correo" role="tab" aria-controls="configuracion-correo" aria-selected="false">Configuración de Correo <i class="fa-solid fa-envelope"></i></a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- Contenido de la pestaña de Configuración del Sitio -->
        <div class="tab-pane fade show active" id="configuracion-sitio" role="tabpanel" aria-labelledby="configuracion-sitio-tab">
          
        
        <div class="form-group mt-3">
            <label for="nombre_sitio">Dominio</label>
            <input type="text" class="form-control" id="dominio_c" name="dominio_c" maxlength="150" placeholder="Ingrese el nombre del sitio">
        </div>

            <div class="form-group mt-3">
            <label for="nombre_sitio">Nombre del Sitio</label>
            <input type="text" class="form-control" id="nombre_sitio" name="nombre_sitio" maxlength="150" placeholder="Ingrese el nombre del sitio">
        </div>
            <div class="form-group">
                <label for="descripcion_slogan">Descripción del Slogan</label>
                <textarea class="form-control" id="descripcion_slogan" name="descripcion_slogar" rows="3" placeholder="Ingrese la descripción del slogan"></textarea>
            </div>
            <div class="form-group">
                <label for="descripcion_sitio">Descripción del Sitio</label>
                <textarea class="form-control" id="descripcion_sitio" name="descripcion_sitio" rows="3" placeholder="Ingrese la descripción del sitio"></textarea>
            </div>
            <div class="form-group">
                <hr/>
                <img class="imagenPerfil" id="favicon_img" src=""/>
                <pre>Puede subir su favicon formato JPG o PNG para ser visualizado en su web.</pre>
                <label for="favicon">Subir Favicon</label>
                <input type="file" class="form-control" id="favicon" name="favicon" maxlength="200" placeholder="Ingrese el URL del favicon">
            </div>
            <div class="form-group">
                <hr/>
                <img class="imagenPerfil" id="logo_img" src="" style="width: 230px; height:50px;" /> 
                <pre>
                    La dimensión para un logo de la plataforma debe de ser de 230px de anchura y 50px de altura,
                    para que se pueda visualizar de una manera correcta.
                </pre>
                <label for="sitio_logo">Subir Logo</label>
                <input type="file" class="form-control" id="sitio_logo" name="sitio_logo" maxlength="200" placeholder="Ingrese el URL del logo del sitio">
            </div>
            <div class="form-group">
                <label for="copyright_descripcion">Descripción de Copyright</label>
                <textarea class="form-control" id="copyright_descripcion" name="copyright_descripcion" rows="3" placeholder="Ingrese la descripción del copyright"></textarea>
            </div>
            <div class="form-group">
                <label for="email_sitio">Email del Sitio</label>
                <input type="email" class="form-control" id="email_sitio" name="email_sitio" maxlength="180" placeholder="Ingrese el email del sitio">
            </div>
            <div class="form-group">
                <label for="busqueda_descripcion">Descripción de Búsqueda</label>
                <textarea class="form-control" id="busqueda_descripcion" name="busqueda_descripcion" rows="3" placeholder="Ingrese la descripción de búsqueda"></textarea>
            </div>
            <div class="form-group">
                <label for="pagina_descripcion">Descripción de la Página</label>
                <textarea class="form-control" id="pagina_descripcion" name="pagina_descripcion" rows="3" placeholder="Ingrese la descripción de la página"></textarea>
            </div>
            <div class="form-group">
                <label for="titulo_descripcion">Descripción del Título</label>
                <textarea class="form-control" id="titulo_descripcion" name="titulo_descripcion" rows="3" placeholder="Ingrese la descripción del título"></textarea>
            </div>
            <div class="form-group">
                <label for="busqueda_hastag">Hashtags de Búsqueda</label>
                <input type="text" class="form-control" id="busqueda_hastag" name="busqueda_hastag" placeholder="Ingrese los hashtags de búsqueda">
            </div>
            <hr/>
            <div style="text-align: center;">
                <button type="submit" id="guardar_config" class="btn btn-dark">Guardar Configuración</button>
            </div>
        </div>

        <!-- Contenido de la pestaña de Configuración de Correo -->
        <div class="tab-pane fade" id="configuracion-correo" role="tabpanel" aria-labelledby="configuracion-correo-tab">
            <div class="form-group mt-3">
                <label for="email_remitente">Email Remitente</label>
                <input type="email" class="form-control" id="email_remitente" name="email_remitente" maxlength="180" placeholder="Ingrese el email remitente">
            </div>
            <div class="form-group">
                <label for="nombre_remitente">Nombre del Remitente</label>
                <input type="text" class="form-control" id="nombre_remitente" name="nombre_remitente" maxlength="150" placeholder="Ingrese el nombre del remitente">
            </div>
            <div class="form-group">
                <label for="servidor_smtp">Servidor SMTP</label>
                <input type="text" class="form-control" id="servidor_smtp" name="servidor_smtp" maxlength="200" placeholder="Ingrese el servidor SMTP">
            </div>
            <div class="form-group">
                <label for="puerto_smtp">Puerto SMTP</label>
                <input type="number" class="form-control" id="puerto_smtp" name="puerto_smtp" placeholder="Ingrese el puerto SMTP">
            </div>
            <div class="form-group">
                <label for="usuario_smtp">Usuario SMTP</label>
                <input type="text" class="form-control" id="usuario_smtp" name="usuario_smtp" maxlength="200" placeholder="Ingrese el usuario SMTP">
            </div>
            <div class="form-group">
                <label for="contrasena_smtp">Contraseña SMTP</label>
                <input type="password" class="form-control" id="contrasena_smtp" name="contrasena_smtp" maxlength="200" placeholder="Ingrese la contraseña SMTP">
            </div>
            <div class="form-group">
                <label for="autenticacion_ssl">Autenticación SSL</label>
                <select class="form-control" id="autenticacion_ssl" name="autenticacion_ssl">
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>
            <hr/>
            <div style="text-align: center;">
                <button type="submit" id="guardar_config_correo" class="btn btn-dark">Guardar Configuración de Correo</button>
            </div>
        </div>
            </div>
        </div>
    </div>
    
</div>


<style>
body {
    background-color: #e6e6e6; /* Dark background */
    color: white; /* White text */
}
.form-control {
    background-color: #e7ecf0;
    color: rgb(88, 87, 87);
}
.form-control::placeholder {
    color: #707070;
}
</style>

<script src="{$dominio}/js/bk_modulo_configuracion.js"></script>

<!-- Incluye jQuery y Bootstrap JS -->
<!-- 
    Usa la libreria de jquery para desplegar los tab del menu
    y la de bootstrap 4.5.2
-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>