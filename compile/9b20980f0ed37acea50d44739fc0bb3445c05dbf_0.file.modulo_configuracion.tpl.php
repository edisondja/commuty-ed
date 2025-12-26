<?php
/* Smarty version 3.1.48, created on 2025-09-27 19:52:09
  from '/opt/lampp/htdocs/commuty-ed/template/back_office_components/modulo_configuracion.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68d8244947f596_06548649',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b20980f0ed37acea50d44739fc0bb3445c05dbf' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/back_office_components/modulo_configuracion.tpl',
      1 => 1758995528,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68d8244947f596_06548649 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="col-md-8 col-12 content-container_s mx-auto tabla_buscar">
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
           <li class="nav-item">
            <a class="nav-link" id="configuracion-reglas-tab" data-toggle="tab" href="#configuracion-reglas" role="tab" aria-controls="configuracion-reglas" aria-selected="false">Reglas <i class="fa-solid fa-photo-film""></i></a>
        </li>

         </li>
           <li class="nav-item">
            <a class="nav-link" id="configuracion-correso-tab" data-toggle="tab" href="#configuracion-reglas" role="tab" aria-controls="configuracion-reglas" aria-selected="false">Cambiar colores <i class="fa-solid fa-photo-film""></i></a>
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
            <hr/>
            <div class="form-group form-check form-switch">
            <input class="form-check-input" type="checkbox" id="publicar_sin_revision" name="publicar_sin_revision">
            <label class="form-check-label" for="publicar_sin_revision">Publicar sin revisión ( Al activar esta opcion las publicaciones deben ser 
            verificadas por el administrador, antes de ser publicas. )</label>
            </div>
            <hr/>
            <hr/>
                <div class="form-group form-check form-switch">
                <input class="form-check-input" type="checkbox" id="verificar_cuenta" name="publicar_sin_revision">
                <label class="form-check-label" for="publicar_sin_revision">Verificar cuenta ( Si se activa esta opcion las cuentas creadas deben de ser verificadas por email. )</label>
                </div>
            <hr/>
                <hr/>
                <div class="form-group form-check form-switch">
                <input class="form-check-input" type="checkbox" id="rabbit_mq" name="publicar_sin_revision">
                <label class="form-check-label" for="rabbit_mq">Broker Rabbit MQ (Colas de mensajes asincronas para evitar cuellos de botellas en procesos de alto de rendimiento.)</label>
                </div>
            <hr/>
                <hr/>
                <div class="form-group form-check form-switch">
                <input class="form-check-input" type="checkbox" id="ffmpeg" name="ffmpeg">
                <label class="form-check-label" for="rabbit_mq">Motor multimedia FFMPEG (Renderizaje de multimedias y generacion de vistas previas automaticas.)</label>
                </div>
            <hr/>

            <hr/>
                <hr/>
                <div class="form-group form-check form-switch">
                <input class="form-check-input" type="checkbox" id="redis_cache" name="redis_cache">
                <label class="form-check-label" for="rabbit_mq">Redis Cache</label>
                </div>
            <hr/>

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

.content-container_s {
    background-color: #495057; /* Gris del menú */
    color: #ffffff; /* Texto blanco */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Body general */
body {
    background-color: #e6e6e6; /* Gris muy claro */
    color: #333333;
}

/* Inputs y textarea */
.form-control {
    background-color: #ffffff; /* Fondo blanco limpio */
    color: #333333;            /* Texto oscuro */
    border: 2px solid #009688; /* Verde azulado */
    border-radius: 8px;
    padding: 10px;
    transition: 0.3s;
}

.form-control::placeholder {
    color: #707070; /* Placeholder gris suave */
}

.form-control:focus {
    border-color: #FF6F61; /* Coral al enfocar */
    box-shadow: 0 0 5px rgba(255,111,97,0.3);
}

/* Botones */
.btn-dark {
    background-color: #009688; /* Verde azulado */
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-dark:hover {
    background-color: #00796b; /* Verde azulado oscuro al hover */
    transform: translateY(-2px);
}

/* Títulos y encabezados */
h2, h3 {
    color: #009688; /* Verde azulado */
}

/* Tabs de Bootstrap personalizados */
.nav-tabs .nav-link {
    color: #ffffff; /* Texto blanco en tabs */
    background-color: #495057;
    border: 1px solid #6c757d;
    border-radius: 8px 8px 0 0;
    margin-right: 5px;
}

.nav-tabs .nav-link.active {
    background-color: #009688; /* Verde azulado en tab activo */
    color: #ffffff;
    font-weight: bold;
}

.nav-tabs .nav-link:hover {
    background-color: #00796b; /* Verde azulado oscuro al hover */
    color: #ffffff;
}

</style>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/js/bk_modulo_configuracion.js"><?php echo '</script'; ?>
>

<!-- Incluye jQuery y Bootstrap JS -->
<!-- 
    Usa la libreria de jquery para desplegar los tab del menu
    y la de bootstrap 4.5.2
-->
<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.5.1.slim.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"><?php echo '</script'; ?>
><?php }
}
