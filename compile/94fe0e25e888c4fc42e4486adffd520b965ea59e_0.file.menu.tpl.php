<?php
/* Smarty version 3.1.48, created on 2025-08-23 22:56:14
  from '/opt/lampp/htdocs/commuty-ed/template/menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68aa2aee8fdad7_14818807',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '94fe0e25e888c4fc42e4486adffd520b965ea59e' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/menu.tpl',
      1 => 1755982573,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68aa2aee8fdad7_14818807 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-md-3">
  <div class="menu-fijo" style="color: balck;">
    </br>
    <ul class="list-group" style="color:black; background: transparent;">

        <?php if ($_smarty_tpl->tpl_vars['foto_perfil']->value !== '') {?>
            <li class="list-group-item" style="color:black; background: transparent;">
              <img src="<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
" class="rounded" style="background:white; margin:2px; width:50px; height:50px;">
              <?php echo $_smarty_tpl->tpl_vars['nombre']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['apellido']->value;?>

            </li>
            <li class="list-group-item" style="color:black; background: transparent;">
              <i class="fa-solid fa-book"></i> Posts 850
            </li>
            <li class="list-group-item" style="color:black; background: transparent;">
              <i class="fa-solid fa-heart"></i> Likes 500
            </li>
        <?php } else { ?>
            <li class="list-group-item" style="color:black; background: transparent;">
              <i class="fa-solid fa-book"></i> 
              <a href="registrer.php" style="text-decoration: none; color:black;">Registrate</a>
            </li>
            <li class="list-group-item" style="color:black; background: transparent;">
              <i class="fa-solid fa-book"></i> 
              <a href="contactar.php" style="text-decoration: none; color:black;">Contactanos</a>
            </li>
        <?php }?>
    </ul>
  </div>
</div>
<?php }
}
