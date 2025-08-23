<?php
/* Smarty version 3.1.48, created on 2025-08-23 23:20:08
  from '/opt/lampp/htdocs/commuty-ed/template/back_office_components/menu_backoffice.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68aa308876d5e8_19623090',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7233b51840ab589457b9d85dd942a27087994dc7' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/back_office_components/menu_backoffice.tpl',
      1 => 1755984006,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68aa308876d5e8_19623090 (Smarty_Internal_Template $_smarty_tpl) {
?><style>
    /* Contenedor del menú */
    .menu-container {
        background-color: #343a40; /* Fondo oscuro */
        color: white;
        border-radius: 10px;
        padding: 15px;
    }

    /* Título del menú */
    .menu-title {
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
    }

    .menu-title .imagenPerfil {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background: white;
    }

    /* Lista de navegación */
    .menu-container .nav-link {
        color: white;
        margin-bottom: 5px;
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Hover en verde azulado */
    .menu-container .nav-link:hover {
        background-color: #20c997;
        color: white;
        border-radius: 5px;
    }

    /* Íconos al lado derecho */
    .menu-container .nav-link i {
        margin-left: 10px;
    }
</style>

<nav class="col-md-2 col-12 mb-4 mb-md-0 menu-container" style="height: 500px; margin-top:3.5%">
    <div class="menu-title">
        <img class="imagenPerfil" src="<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
"/> Herramientas
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="backcoffe.php?option=mails">
                Correo Masivo <i class="fas fa-paper-plane"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="backcoffe.php?option=reports">
                Reportes <i class="fa-solid fa-flag"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="backcoffe.php?option=users">
                Usuarios <i class="fa-solid fa-user fa-lg"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="backcoffe.php?option=boards">
                Publicaciones <i class="fa-solid fa-photo-film"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="backcoffe.php?option=settings">
                Configuraciones <i class="fa-solid fa-gear"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="backcoffe.php?option=banners">
                Adm Banners <i class="fa-solid fa-rectangle-ad"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                Logout <i class="fas fa-bell"></i>
            </a>
        </li>
    </ul>
</nav>
<?php }
}
