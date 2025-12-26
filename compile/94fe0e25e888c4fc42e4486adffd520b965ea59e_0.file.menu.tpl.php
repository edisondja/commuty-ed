<?php
/* Smarty version 3.1.48, created on 2025-12-25 18:53:16
  from '/opt/lampp/htdocs/commuty-ed/template/menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_694d7a0c333e63_15828403',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '94fe0e25e888c4fc42e4486adffd520b965ea59e' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/menu.tpl',
      1 => 1766684899,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_694d7a0c333e63_15828403 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-md-3">
  <div class="menu-fijo" style="color: balck;">
    </br>
    <ul class="list-group" style="color:black; background: transparent;">

        <?php if ($_smarty_tpl->tpl_vars['foto_perfil']->value !== '') {?>
            <li class="list-group-item" style="color:white; background: transparent;">
              <img src="<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
" class="rounded" style="background:white; margin:2px; width:50px; height:50px;">
              <?php echo $_smarty_tpl->tpl_vars['nombre']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['apellido']->value;?>

            </li>
            <li class="list-group-item" style="color:white; background: transparent;">
              <i class="fa-solid fa-book"></i> Posts <?php echo $_smarty_tpl->tpl_vars['cantidad_tableros_usuario']->value->tableros;?>

            </li>
            <li class="list-group-item" style="color:white; background: transparent;">
              <i class="fa-solid fa-heart"></i> Likes <?php echo $_smarty_tpl->tpl_vars['cantidad_tableros_likes']->value;?>

            </li>
        <?php } else { ?>
            <li class="list-group-item" style="color:white; background: transparent;">
              <i class="fa-solid fa-book"></i> 
              <a href="registrer.php" style="text-decoration: none; color:white;">Registrate</a>
            </li>
            <li class="list-group-item" style="color:white; background: transparent;">
              <i class="fa-solid fa-book"></i> 
              <a href="contactar.php" style="text-decoration: none; color:white;">Contactanos</a>
            </li>
        <?php }?>
    </ul>
  </div>
</div>
<?php }
}
