<?php
/* Smarty version 4.5.3, created on 2024-11-05 23:55:42
  from 'C:\xampp\htdocs\ventasrd\template\menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_672aa26e2324a3_87292637',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '20d8decd1f3ba88f967f1517ceeefd8276e5745e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\menu.tpl',
      1 => 1728496038,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_672aa26e2324a3_87292637 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '924156372672aa26e1db475_86655013';
?>

<div class="col-md-3">
<div class="menu-fijo"></br>
    <ul class="list-group"> 

        <?php if ($_smarty_tpl->tpl_vars['foto_perfil']->value !== '') {?>
            <li class="list-group-item"><img src="<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
" class="rounded" style="margin: 2px;width:50px;height:50px;">
                <?php echo $_smarty_tpl->tpl_vars['nombre']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['apellido']->value;?>

            </li>
            <li class="list-group-item"><i class="fa-solid fa-book"></i> Posts 850</li>
            <li class="list-group-item"><i class="fa-solid fa-heart"></i> Likes 500</li>
        <?php } else { ?>
            <li class="list-group-item"><i class="fa-solid fa-book"></i> <a href="registrer.php" style="text-decoration: none; color:antiquewhite;">Registrate</a></li>
            <li class="list-group-item"><i class="fa-solid fa-book"></i> <a href="contactar.php" style="text-decoration: none; color:antiquewhite;">Contactanos</a></li>
        <?php }?>
    </ul>
</div>
</div>

<?php }
}
