<?php
/* Smarty version 3.1.48, created on 2025-12-26 05:40:11
  from '/opt/lampp/htdocs/commuty-ed/template/back_office_components/modulo_banner.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_694e11abb40099_26954024',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0a34b60b2e98b1563713c174aa7e8617f6f84eca' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/back_office_components/modulo_banner.tpl',
      1 => 1766724009,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_694e11abb40099_26954024 (Smarty_Internal_Template $_smarty_tpl) {
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
        <!-- Modal Banner -->
            <div class="modal fade" id="modalBanner" tabindex="-1" aria-labelledby="modalBannerLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBannerLabel">Editar Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formBanner">
                    <input type="hidden" id="id_banner"> <!-- Para editar -->
                    <input type="hidden" id="id_usuario" value="1"> <!-- Cambia según tu sesión -->
                    <input type="hidden" id="imagen_original" value="1"> <!-- Cambia según tu sesión -->
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" placeholder="Título del banner">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" placeholder="Descripción"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="imagen_banner" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen_banner">
                        <img id="previewImagen" src="" alt="Preview" class="img-fluid mt-2" style="max-width: 150px; display: none;">
                    </div>

                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-select" id="tipo">
                        <option>Selecciona el tipo de banner</option>
                        <option value="banner">banner</option>
                        <option value="imagen">Imagen</option>
                        <option value="texto">texto</option>
                        <option value="video">Video</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="script_banner" class="form-label">Script</label>
                        <textarea class="form-control" id="script_banner"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="posicion" class="form-label">Posición</label>
                        <input type="number" class="form-control" id="posicion" min="1">
                    </div>

                    <div class="mb-3">
                        <label for="link_banner" class="form-label">Link</label>
                        <input type="text" class="form-control" id="link_banner" placeholder="https://">
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="estado_banner">
                        <label class="form-check-label" for="estado_banner">Activo</label>
                    </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="actualizar_ads()">Guardar</button>
                </div>
                </div>
            </div>
            </div>
            <!-- Formulario de creación de banner -->
            <div class="tab-pane fade show active" id="publicar_banner_tab" role="tabpanel" aria-labelledby="publicar-banner-tab">
        
                <div class="card-body">
                    <div>
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
                            <input type="file" class="form-control" id="imagen_banner">
                        </div>

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Anuncio</label>
                            <select class="form-select" id="tipo">
                                <option value="">Selecciona el tipo de anuncio</option>
                                <option value="banner">Banner</option>
                                <option value="imagen">Imagen</option>
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

                        <button type="submit" class="btn btn-primary w-100" id="guardar_ads">Guardar Anuncio</button>
                    </div>
                </div>
            </div>

            <!-- Pestaña Administrar Banners -->
            <div class="tab-pane fade" id="administrar_banner_tab" role="tabpanel" aria-labelledby="administrar-banner-tab">
                <div class="container mt-4">
                        <h3 class="mb-3">Listado de Anuncios (ADS)</h3>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle" id="tablaAds">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Imagen</th>
                                        <th>Posición</th>
                                        <th>Fecha</th>
                                        <th>Script Banner</th>
                                        <th>Tipo</th>
                                        <th>Link</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí van los registros dinámicos -->
                                    <tr>
                                        <td>1</td>
                                        <td>Banner Promo</td>
                                        <td>Descripción de ejemplo</td>
                                        <td><img src="ruta/imagen.jpg" alt="Banner" class="img-fluid" width="80"></td>
                                        <td>1</td>
                                        <td>2025-09-24 10:30:00</td>
                                        <td><code>&lt;script&gt;...&lt;/script&gt;</code></td>
                                        <td>HTML</td>
                                        <td><a href="https://ejemplo.com" target="_blank">Ver Link</a></td>
                                        <td><span class="badge bg-success">Activo</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Editar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

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
