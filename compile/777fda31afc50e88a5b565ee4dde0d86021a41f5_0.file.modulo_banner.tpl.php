<?php
/* Smarty version 4.5.3, created on 2024-09-12 20:54:02
  from 'C:\xampp\htdocs\ventasrd\template\back_office_components\modulo_banner.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66e338ca9c6568_47799077',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '777fda31afc50e88a5b565ee4dde0d86021a41f5' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\back_office_components\\modulo_banner.tpl',
      1 => 1726167241,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66e338ca9c6568_47799077 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="col-md-8 col-12 content-container mx-auto tabla_buscar">
    <div class="container mt-5">
    <input type="hidden" id="modulo_select" value="config"/>
    <h2>Administrador de Banners <i class="fa-solid fa-gears"></i></h2>
    <div class="container mt-5">
    <!-- Pestañas de Navegación -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="configuracion-sitio-tab" data-toggle="tab" href="#publicar_banner_tab" role="tab" aria-controls="configuracion-sitio" aria-selected="true">Crear Banners <i class="fa-regular fa-file"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="configuracion-correo-tab" data-toggle="tab" href="#configuracion-correo" role="tab" aria-controls="configuracion-correo" aria-selected="false">Administrar Banners <i class="fa-solid fa-envelope"></i></a>
        </li>
    </ul><hr/>

    <div class="tab-content" id="myTabContent">
        <!-- Formalario de creacion de banner -->
        <div class="tab-pane fade show active" id="publicar_banner_tab" role="tabpanel" aria-labelledby="configuracion-sitio-tab">

            <div class="card-body">
            <form>
            <!-- Campo titulo -->
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" placeholder="Ingresa el título del anuncio">
            </div>

            <!-- Campo descripcion -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" rows="3" placeholder="Escribe la descripción del anuncio"></textarea>
            </div>

            <!-- Campo imagen_ruta -->
            <div class="mb-3">
                <label for="imagen_ruta" class="form-label">Subir iamgen</label>
                <input type="file" class="form-control" id="imagen_ruta" placeholder="Ingresa la ruta de la imagen">
            </div>

            <!-- Campo tipo -->
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Anuncio</label>
                <select class="form-select" id="tipo">
                <option value="">Selecciona el tipo de anuncio</option>
                <option value="banner">Banner</option>
                <option value="video">Video</option>
                <option value="texto">Texto</option>
                </select>
            </div>

            <!-- Campo script_banner -->
            <div class="mb-3">
                <label for="script_banner" class="form-label">Script Banner</label>
                <textarea class="form-control" id="script_banner" rows="3" placeholder="Ingresa el script del banner"></textarea>
            </div>

            <!-- Campo posicion -->
            <div class="mb-3">
                <label for="posicion" class="form-label">Posición del Anuncio</label>
                <input type="number" class="form-control" id="posicion" placeholder="Ingresa la posición del anuncio">
            </div>

            <!-- Botón de envío -->
            <button type="submit" class="btn btn-primary w-100">Guardar Anuncio</button>
            </form>
        </div>
            
        </div>

        <!-- Contenido de la pestaña de Configuración de Correo -->
        <div class="tab-pane fade" id="configuracion-correo" role="tabpanel" aria-labelledby="configuracion-correo-tab">
            
                <h3>Administrar Banners</h3>

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
