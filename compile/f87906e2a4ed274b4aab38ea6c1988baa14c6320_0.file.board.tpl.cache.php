<?php
/* Smarty version 4.5.3, created on 2024-11-05 23:55:42
  from 'C:\xampp\htdocs\ventasrd\template\board.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_672aa26e706759_51122003',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f87906e2a4ed274b4aab38ea6c1988baa14c6320' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\board.tpl',
      1 => 1728750707,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:ads.tpl' => 1,
  ),
),false)) {
function content_672aa26e706759_51122003 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\ventasrd\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.replace.php','function'=>'smarty_modifier_replace',),));
$_smarty_tpl->compiled->nocache_hash = '849233223672aa26e44bc03_85722169';
?>

<br/><br/>
<div class="row">
    <div class="col-md-3"></div>

      <div class="col-sm-5" style='margin-bottom:15px;'>

                <div class='card text-white bg-dark mb-3' id="board<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
">
                            <div class='body' style='padding:5px'>
                              <div class='title'><strong><a href='<?php echo $_smarty_tpl->tpl_vars['url_board']->value;?>
/profile_user.php?user=<?php echo $_smarty_tpl->tpl_vars['tablero']->value['usuario'];?>
'> <img class='imagenPerfil' src='<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['tablero']->value['foto_url'];?>
'/></a>
                                <?php echo $_smarty_tpl->tpl_vars['tablero']->value['nombre'];?>
 <?php echo $_smarty_tpl->tpl_vars['tablero']->value['apellido'];?>
 
                                <div style="float: right;">
                                <?php if ($_smarty_tpl->tpl_vars['user_session']->value != '') {?>
                                  <?php if ($_smarty_tpl->tpl_vars['id_user']->value == $_smarty_tpl->tpl_vars['tablero']->value['id_user']) {?>
                                      <i class="fa-solid fa-pen-to-square"  data-bs-toggle="modal" data-bs-target="#modal_update"   data-value='<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
' style="cursor:pointer;"></i>
                                  <?php }?>
                                <?php }?>
                                </div>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/single_board.php?id=<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['tablero']->value['titulo']," ","_");?>
">
                                <i class="fa-solid fa-eye"></i></strong></div>
                                </a>
                             
                              <p style='padding-left: 10px;' id="text<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
"><?php echo $_smarty_tpl->tpl_vars['tablero']->value['descripcion'];?>
​</p>
                              <a href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/single_board.php?id=<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['tablero']->value['titulo']," ","_");?>
">
                                <?php if ($_smarty_tpl->tpl_vars['tablero']->value['imagen_tablero'] !== '') {?>
                                  
                                  <a href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/single_board.php?id=<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['tablero']->value['titulo']," ","_");?>
">
                                  <img src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['tablero']->value['imagen_tablero'];?>
" style=""  class="card-img-top" alt="...">
                                  </a>

                                <?php } else { ?>

                                  <a href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/single_board.php?id=<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['tablero']->value['titulo']," ","_");?>
">
                                  </a>
                                <?php }?>
                              </a>

                         
                            </div>
                            <p class='p'  style='padding:5px;'>
                              
                            </p>

                              <div class="card-footer" style='float:right'>
                                    <div style='float:right'>
                                      <i class="fa-solid fa-thumbs-up"style='display:none'></i>
                                      <i class="fa-solid fa-bookmark" style='display:none'></i>
                                      <i class="fa-regular fa-share-from-square" style='cursor:pointer'></i>
                                      <i class="fa-regular fa-thumbs-up" style='cursor:pointer'></i>
                                      <i class="fa-regular fa-comment-dots" style='cursor:pointer'></i>
                                      <i class="fa-regular fa-bookmark" style='cursor:pointer'></i>
                                      <?php if ($_smarty_tpl->tpl_vars['user_session']->value != '') {?>
                                        <?php if ($_smarty_tpl->tpl_vars['id_user']->value == $_smarty_tpl->tpl_vars['tablero']->value['id_user']) {?>
                                            <i class="fa fa-trash" data-value='<?php echo $_smarty_tpl->tpl_vars['tablero']->value['id_tablero'];?>
' style="cursor: pointer;" aria-hidden="true"></i>
                                          <?php }?>
                                        <?php } else { ?>
                                    <?php }?>
                                    </div>
                              </div>
                      </div>
      </div>
    <?php if ($_smarty_tpl->tpl_vars['counter']->value == true) {?>
      <!-- Visualizar publicidad de banners -->
      <?php $_smarty_tpl->_subTemplateRender("file:ads.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>    
    <?php }?>
</div><?php }
}
