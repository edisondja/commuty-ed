<?php
/* Smarty version 3.1.48, created on 2025-08-23 23:39:36
  from '/opt/lampp/htdocs/commuty-ed/template/back_office_components/modulo_banner.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68aa3518b8f1b9_97823054',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0a34b60b2e98b1563713c174aa7e8617f6f84eca' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/back_office_components/modulo_banner.tpl',
      1 => 1755985175,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68aa3518b8f1b9_97823054 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-md-8 col-12 content-container_s mx-auto tabla_buscar">
    <div class="container mt-5">
        <input type="hidden" id="modulo_select" value="config"/>
        <h2>Administrador de Banners <i class="fa-solid fa-gears"></i></h2>

        <!-- Pestañas de Navegación -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="publicar-banner-tab" data-toggle="tab" href="#publicar_banner_tab" role="tab" aria-controls="publicar_banner_tab" aria-selected="true">
                    Crear Banners <i class="fa-regular fa-file"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="administrar-banner-tab" data-toggle="tab" href="#administrar_banner_tab" role="tab" aria-controls="administrar_banner_tab" aria-selected="false">
                    Administrar Banners <i class="fa-solid fa-envelope"></i>
                </a>
            </li>
        </ul>
        <hr/>

        <div class="tab-content" id="myTabContent">
            <!-- Formulario de creación de banner -->
            <div class="tab-pane fade show active" id="publicar_banner_tab" role="tabpanel" aria-labelledby="publicar-banner-tab">
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" placeholder="Ingresa el título del anuncio">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" rows="3" placeholder="Escribe la descripción del anuncio"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="imagen_ruta" class="form-label">Subir imagen</label>
                            <input type="file" class="form-control" id="imagen_ruta">
                        </div>

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Anuncio</label>
                            <select class="form-select" id="tipo">
                                <option value="">Selecciona el tipo de anuncio</option>
                                <option value="banner">Banner</option>
                                <option value="video">Video</option>
                                <option value="texto">Texto</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="script_banner" class="form-label">Script Banner</label>
                            <textarea class="form-control" id="script_banner" rows="3" placeholder="Ingresa el script del banner"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="posicion" class="form-label">Posición del Anuncio</label>
                            <input type="number" class="form-control" id="posicion" placeholder="Ingresa la posición del anuncio">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Guardar Anuncio</button>
                    </form>
                </div>
            </div>

            <!-- Pestaña Administrar Banners -->
            <div class="tab-pane fade" id="administrar_banner_tab" role="tabpanel" aria-labelledby="administrar-banner-tab">
                <h3>Administrar Banners</h3>
            </div>
        </div>
    </div>
</div>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/js/bk_modulo_banner.js"><?php echo '</script'; ?>
>

<!-- jQuery y Bootstrap JS -->
<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.5.1.slim.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"><?php echo '</script'; ?>
>



<style>
/* Contenedor principal */
.content-container_s {
    margin-top: 50px;
    padding: 30px;
    background-color: #ffffff; /* Fondo blanco */
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Body general */
body {
    background-color: #e6e6e6; /* Gris muy claro */
    color: #333333;
}

/* Títulos */
h2, h3 {
    color: #009688; /* Verde azulado */
    font-weight: bold;
    margin-bottom: 20px;
}

/* Form Controls */
.form-control, .form-select {
    background-color: #ffffff;
    color: #333333;
    border: 2px solid #009688;
    border-radius: 8px;
    padding: 10px;
    transition: 0.3s;
}

.form-control::placeholder {
    color: #707070;
}

.form-control:focus, .form-select:focus {
    border-color: #FF6F61; /* Coral al enfocar */
    box-shadow: 0 0 5px rgba(255,111,97,0.3);
}

/* Botón */
.btn-primary {
    background-color: #009688; /* Verde azulado */
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-primary:hover {
    background-color: #00796b; /* Verde azulado oscuro */
    transform: translateY(-2px);
}

/* Tabs */
.nav-tabs .nav-link {
    color: #ffffff;
    background-color: #495057; /* Gris oscuro */
    border: 1px solid #6c757d;
    border-radius: 8px 8px 0 0;
    margin-right: 5px;
}

.nav-tabs .nav-link.active {
    background-color: #009688; /* Verde azulado */
    color: #ffffff;
    font-weight: bold;
}

.nav-tabs .nav-link:hover {
    background-color: #00796b;
    color: #ffffff;
}
</style>


<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/js/bk_modulo_banner.js"><?php echo '</script'; ?>
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
