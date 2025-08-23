<style>
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
        <img class="imagenPerfil" src="{$foto_perfil}"/> Herramientas
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
