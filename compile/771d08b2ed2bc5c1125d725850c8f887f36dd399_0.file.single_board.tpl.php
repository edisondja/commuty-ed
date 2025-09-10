<?php
/* Smarty version 3.1.48, created on 2025-09-10 05:28:57
  from '/opt/lampp/htdocs/commuty-ed/template/single_board.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68c0f079afff49_64214475',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '771d08b2ed2bc5c1125d725850c8f887f36dd399' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/single_board.tpl',
      1 => 1757474936,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:ads.tpl' => 1,
  ),
),false)) {
function content_68c0f079afff49_64214475 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/opt/lampp/htdocs/commuty-ed/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>
<div class="row">

    <div class="col-md-3">
        <br/>
        <svg class="bd-placeholder-img rounded float-start" style="width:100%;display:none;"
            xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 200x200"
            preserveAspectRatio="xMidYMid slice"
            focusable="false"><title>Placeholder</title><rect width="100%"
            height="100%" fill="#868e96"></rect><text x="3%" y="50%"
            fill="#dee2e6" dy=".3em">La publicidad sera colocada aca 200x200</text></svg>
    </div>

    <div class="col-md-6">
      <!-- Modal Reportar Publicación -->
        <div class="modal fade" id="report_modal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Reportar Publicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="mb-3">
                <label for="razon_reporte" class="form-label">Razón del reporte</label>
                <textarea class="form-control" id="razon_reporte" rows="3" placeholder="Escribe la razón del reporte..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="enviar_rpt">Enviar Reporte</button>
            </div>
            </div>
        </div>
        </div>
        <br/><br/><br/>
        <?php if ($_smarty_tpl->tpl_vars['estado']->value !== 'baneado') {?>
        <div class="card mb-3 card-custom">
        <div style="position: absolute; right: 10px; top: 10px;">
            <div class="dropdown custom-dropdown">
                <button class="btn btn-custom dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-align-justify"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li id="reportar_publicacion"><a class="dropdown-item" href="#">Reportar</a></li>
                    <li id="agregar_a_favorito"><a class="dropdown-item" href="#">Agregar a favorito</a></li>
                    <li id="agregar_calificacion"><a class="dropdown-item" href="#">Puntear</a></li>
                </ul>
            </div>
        </div>

            <div class="card-body">
                <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['id_tablero']->value;?>
' id='id_tablero'/>
                <?php if ($_smarty_tpl->tpl_vars['user_session']->value != '') {?>
                    <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['user_session']->value;?>
' id='usuario'/>
                    <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
' id='foto_url'/>
                <?php } else { ?>
                    <input type='hidden' value='0' id='id_usuario'/>
                    <input type='hidden' value='0' id='usuario'/>
                    <input type='hidden' value='0' id='foto_url'/>
                <?php }?>
                <img src="<?php echo $_smarty_tpl->tpl_vars['foto_usuario']->value;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['usuario']->value;?>
" class="profile-img">
                <strong class="username-text"><?php echo $_smarty_tpl->tpl_vars['usuario']->value;?>
</strong>
                <h5 class="card-title title-text"><?php echo $_smarty_tpl->tpl_vars['titulo']->value;?>
</h5>
                <p class="card-text description-text" id='descripcion'><?php echo $_smarty_tpl->tpl_vars['descripcion']->value;?>
</p>

           <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner fixed-size-carousel">
                    
                                        <?php if (count($_smarty_tpl->tpl_vars['multimedias_t']->value) > 0) {?>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['multimedias_t']->value, 'multimedia', false, NULL, 'mediaLoop', array (
  'first' => true,
  'index' => true,
));
$_smarty_tpl->tpl_vars['multimedia']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['multimedia']->value) {
$_smarty_tpl->tpl_vars['multimedia']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_mediaLoop']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_mediaLoop']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_mediaLoop']->value['index'];
?>
                            <?php if ($_smarty_tpl->tpl_vars['multimedia']->value['tipo_multimedia'] == 'imagen') {?>
                                <div class="carousel-item <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_mediaLoop']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_mediaLoop']->value['first'] : null)) {?>active<?php }?>">
                                    <img src="<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['multimedia']->value['ruta_multimedia'],"../",'');?>
" 
                                        class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                                </div>
                            <?php } else { ?>
                                <div class="carousel-item <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_mediaLoop']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_mediaLoop']->value['first'] : null)) {?>active<?php }?>">
                                    <video src="<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['multimedia']->value['ruta_multimedia'],"../",'');?>
" 
                                        class="d-block w-100 img-fluid card-img-top fixed-size-video" 
                                        controls></video>
                                </div>
                            <?php }?>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                                        <?php } elseif ($_smarty_tpl->tpl_vars['og_imagen']->value != '') {?>
                        <div class="carousel-item active">
                            <img src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['og_imagen']->value;?>
" 
                                class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                        </div>
                    <?php }?>
                </div>

                                <?php if (count($_smarty_tpl->tpl_vars['multimedias_t']->value) > 1) {?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                <?php }?>
            </div>

                <div class="card card-comments">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style='margin-left:85%; display:none;' id='cerrar_comentarios'>
                            <svg style='color:#515151;' xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </li>
                    </ul>
                </div>

                <?php if ($_smarty_tpl->tpl_vars['like_login_user']->value == 'tiene_like') {?> 
                    <i class="fa-solid fa-heart heart-liked" style="cursor:pointer" id="like"></i>
                    <span id="likes_c">
                        <?php if ($_smarty_tpl->tpl_vars['likes']->value->likes > 1) {?>
                            <?php echo $_smarty_tpl->tpl_vars['likes']->value->likes;?>
 personas y tu le gusta esto
                        <?php } else { ?>
                            <?php echo $_smarty_tpl->tpl_vars['likes']->value->likes;?>
 Te gusta esto
                        <?php }?>
                    </span>
                <?php } else { ?>
                    <i class="fa-regular fa-heart heart-default" style="cursor:pointer" id="like"></i>
                    <span id="likes_c"><?php echo $_smarty_tpl->tpl_vars['likes']->value->likes;?>
</span>
                <?php }?>
                    <i class="fa fa-eye"></i>
                    <span><?php echo $_smarty_tpl->tpl_vars['total_views']->value;?>
</span>

                <div class="card card-comments" id="coments">
                    <ul class="list-group list-group-flush" id='data_coments'>
                    </ul>
                </div>

                <div class="card card-comment-input">
                    <ul class="list-group list-group-flush">
                        <?php if ($_smarty_tpl->tpl_vars['id_user']->value != '') {?>
                            <div id="interface_og"></div>
                            <div class="list-group-item flex-container barContentComent fixed-bottom">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
" class="rounded comment-profile-img">
                                <textarea id="text_coment" class='textComent' rows='1' cols='25' placeholder='write a comment'></textarea>
                                <svg style='height: 35px;margin: 2px;' id='send_coment' xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor" class="bi bi-arrow-down-square-fill" viewBox="0 0 16 16">
                                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </div>
                        <?php } else { ?>
                            <li class="list-group-item">
                                <a href=''>i want comment need a account now</a>
                                <div id='send_coment' style='display:none'></div>
                            <?php }?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <br/>
    <?php } else { ?>
        <p class="h3 title_block">Contenido bloqueado por los administradores</p>
        <div class="card">
            <div class="card-body">
                <img class="card-img-top fixed-size-image" src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/assets/block_content.png"/>
            </div>
        </div>
    <?php }?>
   </div>
   <!--Componente de publicidad -->
   <?php $_smarty_tpl->_subTemplateRender('file:ads.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</div>


<?php echo '<script'; ?>
 type="text/javascript" src='js/single_board.js'><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src='js/action_coments.js'><?php echo '</script'; ?>
>


<style>
.card-custom {
    background-color: #1f2a2f;
    color: white;
    border-radius: 10px;
}

.btn-custom {
    background-color: #20c997;
    color: white;
    border: none;
}

.btn-custom:hover {
    background-color: #17a589;
}

.profile-img {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    margin: 5px;
}

.username-text {
    color: #20c997;
    margin-left: 5px;
}

.title-text {
    color: #ffffff;
}

.description-text {
    color: #cfd8dc;
}

.card-comments {
    background-color: #243537;
    color: white;
    border-radius: 5px;
    margin-top: 15px;
}

.card-comment-input {
    background-color: #243537;
    border-radius: 5px;
    margin-top: 10px;
    padding: 5px;
}

.comment-profile-img {
    width: 34px;
    height: 38px;
    margin: 2px;
}

.textComent {
    width: 65%;
    border-radius: 5px;
    padding: 5px;
    border: 1px solid #20c997;
    background-color: #1f2a2f;
    color: white;
}

.fixed-size-carousel {
    height: 300px;
}

.fixed-size-image {
    object-fit: cover;
    height: 300px;
    width: 100%;
}

.fixed-size-video {
    height: 300px;
    width: 100%;
}

.heart-liked {
    color: #20c997;
}

.heart-default {
    color: white;
}


/* Fondo del modal (semi-transparente) */
#report_modal .modal-content {
    background-color: #008080; /* Verde azulado */
    color: #fff; /* Texto blanco para contraste */
    border-radius: 12px;
    border: 2px solid #4d6857ff; /* Amarillo dorado */
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Header */
#report_modal .modal-header {
    border-bottom: 2px solid #546e51ff;
}

/* Botón cerrar */
#report_modal .btn-close {
    filter: invert(1); /* Hace que la "x" blanca se vea en fondo oscuro */
}

/* Body */
#report_modal .modal-body {
    background-color: #006666; /* Verde más oscuro */
    border-radius: 8px;
    padding: 15px;
}

/* Footer */
#report_modal .modal-footer {
    border-top: 2px solid #487255ff;
}

/* Botones */
#report_modal .btn-primary {
    background-color: #20c997; /* Verde azulado brillante */
    border-color: #20c997;
}

#report_modal .btn-primary:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
}

#report_modal .btn-secondary {
    background-color: #ffd700; /* Amarillo */
    color: #000;
    border-color: #064935ff;
}

#report_modal .btn-secondary:hover {
    background-color: #e6c200;
    border-color: #2c4d40ff;
}

/* Textarea */
#report_modal textarea.form-control {
    background-color: #0c2e2eff; /* Verde clarito */
    color: white;
    border: 1px solid #063d3fff;
    border-radius: 6px;
}




</style>


<?php }
}
