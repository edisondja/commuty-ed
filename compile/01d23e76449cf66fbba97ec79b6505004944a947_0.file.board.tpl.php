<?php
/* Smarty version 3.1.48, created on 2026-01-04 04:37:30
  from '/opt/lampp/htdocs/commuty-ed/template/board.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_6959e07a665c24_77404801',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '01d23e76449cf66fbba97ec79b6505004944a947' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/board.tpl',
      1 => 1767497845,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:ads.tpl' => 1,
  ),
),false)) {
function content_6959e07a665c24_77404801 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/opt/lampp/htdocs/commuty-ed/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>
<br/><br/>
<div class="row">
    <div class="col-md-3"></div>

    <div class="col-sm-5" style="margin-bottom:15px;">
        <div class="card card-board mb-3" id="board<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
">
            <div class="body" style="padding:5px">
                <div class="title">
                <div class="board-header">
                        <!-- Perfil -->
                        <a href="<?php echo $_smarty_tpl->tpl_vars['url_board']->value;?>
/profile_user.php?user=<?php echo $_smarty_tpl->tpl_vars['tablero']->value['usuario'];?>
" class="profile-link">
                            <img class="imagenPerfil" src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['tablero']->value['foto_url'];?>
" />
                            <strong><?php echo $_smarty_tpl->tpl_vars['tablero']->value['nombre'];?>
 <?php echo $_smarty_tpl->tpl_vars['tablero']->value['apellido'];?>
</strong>
                           vb
                        </a>

                        <!-- Acciones -->
                     
                        <div class="actions" style="float:right;">
                            <?php if ($_smarty_tpl->tpl_vars['user_session']->value != '') {?>
                                <?php if ($_smarty_tpl->tpl_vars['id_user']->value == $_smarty_tpl->tpl_vars['tablero']->value['id_user']) {?>
                                    <i class="fa-solid fa-pen-to-square edit-icon"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal_update"
                                    data-value="<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
"
                                    style="cursor:pointer;">
                                    </i>
                                <?php }?>
                            <?php }?>

                            <a href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/single_board.php?id=<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['tablero']->value['titulo'],' ','_');?>
">
                                <i class="fa-solid fa-eye view-icon"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <p class="description-text" id="text<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
"><?php echo $_smarty_tpl->tpl_vars['tablero']->value['descripcion'];?>
</p>
            
                <?php if ($_smarty_tpl->tpl_vars['tablero']->value['imagen_tablero'] !== '') {?>
                    <div class='content_image'>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/single_board.php?id=<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['tablero']->value['titulo']," ","_");?>
">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['tablero']->value['imagen_tablero'];?>
" class="card-img-top board-image" alt="...">
                     </a>
                     </div>
                <?php }?>
            </div>

            <div class="card-footer footer-icons" style="float:right">
                <div style="float:right">
                    <i class="fa-solid fa-thumbs-up" style="display:none"></i>
                    <i class="fa-solid fa-bookmark" style="display:none"></i>
                    <i class="fa-regular fa-share-from-square share-icon" style="cursor:pointer"></i>
                    <i class="fa-regular fa-thumbs-up like-icon" style="cursor:pointer"></i>
                    <i class="fa-regular fa-comment-dots comment-icon" style="cursor:pointer"></i>
                    <i class="fa-regular fa-bookmark bookmark-icon" style="cursor:pointer"></i>
                    <?php if ($_smarty_tpl->tpl_vars['user_session']->value != '') {?>
                        <?php if ($_smarty_tpl->tpl_vars['id_user']->value == $_smarty_tpl->tpl_vars['tablero']->value['id_user']) {?>
                            <i class="fa fa-trash delete-icon" data-value="<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
" style="cursor: pointer;" aria-hidden="true"></i>
                        <?php }?>
                    <?php }?>
                </div>
            </div>
        </div>

    </div>

    <?php if ($_smarty_tpl->tpl_vars['counter']->value == true) {?>
        <!-- Visualizar publicidad de banners -->
        <?php $_smarty_tpl->_subTemplateRender("file:ads.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>    
    <?php }?>
</div>

<style>
.card-board {
    background-color: #1f2a2f;
    color: white;
    border-radius: 10px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card-board:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.3);
}

.imagenPerfil {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    margin-right: 5px;
}

.title strong a {
    color: #20c997;
    text-decoration: none;
}

.title .edit-icon, .title .view-icon {
    color: #ffffff;
    margin-left: 5px;
}

.description-text {
    color: #cfd8dc;
    padding-left: 10px;
    margin-top: 5px;
}

.board-image {
    object-fit: cover;
    width: 100%;
    max-height: 300px;
    border-radius: 5px;
    margin-top: 5px;
}

.footer-icons i {
    color: #20c997;
    margin-left: 8px;
    transition: transform 0.2s ease;
}

.footer-icons i:hover {
    transform: scale(1.2);
    color: #17a589;
}

.delete-icon {
    color: #ff6b6b;
}

.share-icon {
    color: #f8f9fa;
}

.like-icon, .comment-icon, .bookmark-icon {
    color: #20c997;
}
</style>
<?php }
}
