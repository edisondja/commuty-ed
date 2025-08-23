<?php
/* Smarty version 4.5.3, created on 2024-09-12 20:19:49
  from 'C:\xampp\htdocs\ventasrd\template\back_office_components\menu_backoffice.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66e330c5e38f95_89762368',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f7a2f0d206f51fdcd07264de1a5453ad5c437453' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\back_office_components\\menu_backoffice.tpl',
      1 => 1726165188,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66e330c5e38f95_89762368 (Smarty_Internal_Template $_smarty_tpl) {
?>


<nav class="col-md-2 col-12 mb-4 mb-md-0 menu-container" style="height: 500px; margin-top:3.5%">
<div class="menu-title"><img class="imagenPerfil" src="<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
"/> Herramientas</div>
<ul class="nav flex-column">
<li class="nav-item">
        <a class="nav-link" style="cursor: pointer;"   href="backcoffe.php?option=mails"> Correo Masivo
        <i class="fas fa-paper-plane"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="backcoffe.php?option=reports">Reportes
        <i class="fa-solid fa-flag"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="backcoffe.php?option=users">Usuarios
            <i class="fa-solid fa-user fa-lg"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="backcoffe.php?option=boards">Publicaciones
        <i class="fa-solid fa-photo-film"></i>        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="backcoffe.php?option=settings">Configuraciones
        <i class="fa-solid fa-gear"></i>
        </a>
    </li>
        <li class="nav-item">
        <a class="nav-link" href="backcoffe.php?option=banners">
        Adm  Banners
        <i class="fa-solid fa-rectangle-ad"></i>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="#">Logout
        <i class="fas fa-bell"></i>

        </a>
    </li>
</ul>
</nav><?php }
}
