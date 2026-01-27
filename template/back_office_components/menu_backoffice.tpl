<style>
    /* Contenedor del menú */
    .menu-container {
        background: linear-gradient(180deg, #2d3436 0%, #1e272e 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 15px;
        min-height: auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        position: sticky;
        top: 20px;
    }

    /* Título del menú */
    .menu-title {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .menu-title .imagenPerfil {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        background: white;
        border: 2px solid #009688;
    }

    /* Lista de navegación */
    .menu-container .nav-link {
        color: rgba(255,255,255,0.85);
        margin-bottom: 4px;
        padding: 10px 12px;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    /* Hover */
    .menu-container .nav-link:hover {
        background-color: rgba(0, 150, 136, 0.2);
        color: #00d9be;
        transform: translateX(5px);
    }

    /* Item activo */
    .menu-container .nav-link.active {
        background-color: #009688;
        color: white;
        font-weight: 500;
    }

    /* Íconos */
    .menu-container .nav-link i {
        margin-left: 10px;
        width: 20px;
        text-align: center;
        opacity: 0.8;
    }

    .menu-container .nav-link:hover i,
    .menu-container .nav-link.active i {
        opacity: 1;
    }

    /* Separador */
    .menu-separator {
        height: 1px;
        background: rgba(255,255,255,0.1);
        margin: 15px 0;
    }

    /* Logout especial */
    .menu-container .nav-link.logout-link {
        color: #ff6b6b;
        margin-top: 10px;
    }

    .menu-container .nav-link.logout-link:hover {
        background-color: rgba(255, 107, 107, 0.2);
        color: #ff4757;
    }

    /* Badge para notificaciones */
    .menu-badge {
        background: #ff6b6b;
        color: white;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .menu-container {
            position: relative;
            top: 0;
            margin-bottom: 20px;
        }
        
        .menu-container .nav {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .menu-container .nav-item {
            flex: 1 1 calc(50% - 5px);
        }
        
        .menu-container .nav-link {
            font-size: 0.8rem;
            padding: 8px 10px;
            justify-content: center;
            text-align: center;
        }
        
        .menu-container .nav-link i {
            display: none;
        }
    }
</style>

{* Detectar opción actual para marcar activo *}
{assign var="current_option" value=$smarty.get.option|default:'users'}

<nav class="col-md-2 col-12 mb-4 mb-md-0 menu-container">
    <div class="menu-title">
        <img class="imagenPerfil" src="{$foto_perfil}" onerror="this.onerror=null; this.src='{$dominio}/assets/user_profile.png'"/> 
        <span>Panel Admin</span>
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'users'}active{/if}" href="{$dominio}/admin/users">
                Usuarios <i class="fa-solid fa-users"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'boards'}active{/if}" href="{$dominio}/admin/boards">
                Publicaciones <i class="fa-solid fa-photo-film"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'reports'}active{/if}" href="{$dominio}/admin/reports">
                Reportes <i class="fa-solid fa-flag"></i>
            </a>
        </li>
        
        <div class="menu-separator"></div>
        
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'settings'}active{/if}" href="{$dominio}/admin/settings">
                Configuraciones <i class="fa-solid fa-gear"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'estilos'}active{/if}" href="{$dominio}/admin/styles">
                Estilos y Colores <i class="fa-solid fa-palette"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'banners'}active{/if}" href="{$dominio}/admin/banners">
                Banners <i class="fa-solid fa-rectangle-ad"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'reproductores'}active{/if}" href="{$dominio}/admin/players">
                Reproductores VAST <i class="fa-solid fa-play-circle"></i>
            </a>
        </li>
        
        <div class="menu-separator"></div>
        
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'mails'}active{/if}" href="{$dominio}/admin/mail">
                Correo Masivo <i class="fa-solid fa-envelope"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'rabbitmq'}active{/if}" href="{$dominio}/admin/rabbitmq">
                RabbitMQ <i class="fa-solid fa-server"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'monitor'}active{/if}" href="{$dominio}/admin/monitor">
                Monitor en Tiempo Real <i class="fa-solid fa-chart-line"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {if $current_option == 'failures'}active{/if}" href="{$dominio}/admin/failures">
                Fallos de Servicios <i class="fa-solid fa-triangle-exclamation"></i>
            </a>
        </li>
        
        <div class="menu-separator"></div>
        
        <li class="nav-item">
            <a class="nav-link" href="{$dominio}/">
                <i class="fa-solid fa-home" style="margin-left:0; margin-right:8px;"></i> Volver al Sitio
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link logout-link" href="{$dominio}/logout.php">
                Cerrar Sesión <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </li>
    </ul>
</nav>
