<!doctype html>
<html lang="en">
  <head>
  {literal}
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KBJQB4PRQ2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-KBJQB4PRQ2');
    </script>
    {/literal}
        <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
    <!-- Plyr: reproductor de video moderno (open source) - tema visible -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
    <style>
    .plyr-wrap, .js-plyr-video { --plyr-color-main: #20c997; --plyr-video-background: #0f172a; }
    .plyr-wrap .plyr--video, .multimedia-item .plyr--video {
        border-radius: 12px; overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.35);
        border: 1px solid rgba(32, 201, 151, 0.4);
    }
    .plyr__control--overlaid { background: #20c997 !important; color: #0f172a !important; }
    .plyr__control--overlaid:hover { background: #17a589 !important; }
    </style>
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    <title>{$titulo}</title>
        <meta name="description" content="{$descripcion}">
        <script>
            // Base URL - definir directamente desde PHP/Smarty
            window.SITE_DOMAIN = '{$dominio}';
            window.BASE_URL = (function() {
                var dominio = '{$dominio}';
                if (!dominio) return '';
                if (dominio.indexOf('http') === 0) {
                    try {
                        var url = new URL(dominio);
                        return url.pathname.replace(/\/$/, '') || '';
                    } catch(e) {
                        var match = dominio.match(/^https?:\/\/[^\/]+(\/.*)?$/);
                        return (match && match[1]) ? match[1].replace(/\/$/, '') : '';
                    }
                } else if (dominio.indexOf('/') === 0) {
                    return dominio.replace(/\/$/, '');
                }
                return '';
            })();
            console.log('BASE_URL:', window.BASE_URL);
        </script>
        <meta name="rating" content="RTA-5042-1996-1400-1577-RTA" />
        <meta http-equiv="Content-Language" content="en-US">
        <meta name="Robots" content="all"/>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        {if isset($favicon) && $favicon != '' && $favicon != 'assets/favicon.ico'}
            {if strpos($favicon, 'http') === 0}
                <link rel="icon" href="{$favicon}" type="image/png">
            {else}
                <link rel="icon" href="{$dominio}/{$favicon}" type="image/png">
            {/if}
        {/if}

        <!-- ETIQUETAS TWITTER -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@{$name}">
        <meta name="twitter:title" content="{$titulo|escape:'html'}">
        <meta name="twitter:description" content="{$descripcion|escape:'html'|truncate:200}">
        {if isset($og_imagen) && $og_imagen != ''}
            {if strpos($og_imagen, 'http') === 0}
                <meta name="twitter:image" content="{$og_imagen}">
            {else}
                <meta name="twitter:image" content="{$dominio}/{$og_imagen}">
            {/if}
        {else}
            <meta name="twitter:image" content="{$dominio}/assets/default_share.png">
        {/if}
        <meta name="twitter:url" content="{$url_board}">

        <!-- ETIQUETAS FACEBOOK / OPEN GRAPH --> 
        <meta property="og:type" content="article">
        <meta property="og:title" content="{$titulo|escape:'html'}">
        <meta property="og:description" content="{$descripcion|escape:'html'|truncate:300}">
        <meta property="og:url" content="{$url_board}">
        <meta property="og:site_name" content="{$name}">
        <meta property="og:locale" content="es_ES">
        {if isset($og_imagen) && $og_imagen != ''}
            {if strpos($og_imagen, 'http') === 0}
                <meta property="og:image" content="{$og_imagen}">
            {else}
                <meta property="og:image" content="{$dominio}/{$og_imagen}">
            {/if}
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
            <meta property="og:image:type" content="image/jpeg">
        {else}
            <meta property="og:image" content="{$dominio}/assets/default_share.png">
        {/if}
        {if isset($og_video) && $og_video != ''}
            <meta property="og:video" content="{$dominio}/{$og_video}">
            <meta property="og:video:type" content="video/mp4">
        {/if}
        <meta name="google" value="notranslate">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content=""/>
        {$libs_cdn}
        
        {if isset($estilos) && !empty($estilos)}
        <style>
            :root {
                --color-primario: {$estilos.color_primario|default:'#20c997'};
                --color-secundario: {$estilos.color_secundario|default:'#09b9e1'};
                --color-fondo: {$estilos.color_fondo|default:'#1a1c1d'};
                --color-texto: {$estilos.color_texto|default:'#cfd8dc'};
                --color-enlace: {$estilos.color_enlace|default:'#20c997'};
                --color-enlace-hover: {$estilos.color_enlace_hover|default:'#17a085'};
                --color-boton: {$estilos.color_boton|default:'#20c997'};
                --color-boton-hover: {$estilos.color_boton_hover|default:'#17a085'};
                --color-tarjeta: {$estilos.color_tarjeta|default:'#2d2d2d'};
                --color-borde: {$estilos.color_borde|default:'#444'};
                --color-header: {$estilos.color_header|default:'#1a1a1a'};
            }
            
            body {
                background-color: var(--color-fondo) !important;
                color: var(--color-texto) !important;
            }
            
            a {
                color: var(--color-enlace) !important;
            }
            
            a:hover {
                color: var(--color-enlace-hover) !important;
            }
            
            .btn-primary, .btn {
                background-color: var(--color-boton) !important;
                border-color: var(--color-boton) !important;
            }
            
            .btn-primary:hover, .btn:hover {
                background-color: var(--color-boton-hover) !important;
                border-color: var(--color-boton-hover) !important;
            }
            
            .card, .card-body, .card-header {
                background-color: var(--color-tarjeta) !important;
                border-color: var(--color-borde) !important;
                color: var(--color-texto) !important;
            }
            
            .navbar, .navbar-dark {
                background-color: var(--color-header) !important;
            }
            
            .bg-dark {
                background-color: var(--color-tarjeta) !important;
            }
            
            .text-primary {
                color: var(--color-primario) !important;
            }
            
            .text-secondary {
                color: var(--color-secundario) !important;
            }
            
            .border-primary {
                border-color: var(--color-primario) !important;
            }
        </style>
        {/if}

        <!-- Google Analytics -->
        {if isset($google_analytics_id) && $google_analytics_id != ''}
        {* Detectar si es GA4 (G-XXXXXXXXXX) o Universal Analytics (UA-XXXXXXXXX-X) *}
        {if $google_analytics_id|strpos:'G-' === 0}
        {* Google Analytics 4 (GA4) *}
        <script async src="https://www.googletagmanager.com/gtag/js?id={$google_analytics_id}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){ldelim}dataLayer.push(arguments);{rdelim}
            gtag('js', new Date());
            gtag('config', '{$google_analytics_id}');
        </script>
        {elseif $google_analytics_id|strpos:'UA-' === 0}
        {* Universal Analytics (Legacy) *}
        <script async src="https://www.googletagmanager.com/gtag/js?id={$google_analytics_id}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){ldelim}dataLayer.push(arguments);{rdelim}
            gtag('js', new Date());
            gtag('config', '{$google_analytics_id}');
        </script>
        {else}
        {* Formato no reconocido, intentar como GA4 *}
        <script async src="https://www.googletagmanager.com/gtag/js?id={$google_analytics_id}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){ldelim}dataLayer.push(arguments);{rdelim}
            gtag('js', new Date());
            gtag('config', '{$google_analytics_id}');
        </script>
        {/if}
        {/if}

  </head>
  <body style='background:#151c1b;'>
    
    <input type='hidden' id="api_transfer_video" value='{$api_transfer_video}'>
    <input type='hidden' value='{$dominio}' id='dominio'/> 
    <input type='hidden' id='paginador_scroll'  value='{$paginador_scroll}'/>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#034a4b; position: fixed; top: 0; width: 100%; z-index: 999;">
      
    <div class="container-fluid">

        <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Cabecera del modal -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationModalLabel">Notificaciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- Cuerpo del modal -->
                    <div class="modal-body">
                        <!-- Aquí puedes listar las notificaciones -->
                        <ul class="list-group">
                            <li class="list-group-item">Notificación 1: Nuevo comentario en tu publicación.</li>
                            <li class="list-group-item">Notificación 2: Tienes una nueva solicitud de amistad.</li>
                            <li class="list-group-item">Notificación 3: Actualización en las políticas de privacidad.</li>
                        </ul>
                    </div>
                    <!-- Pie del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Marcar como leídas</button>
                    </div>
                </div>
            </div>
        </div>

          <a class="navbar-brand" style="color: #09b9e1;" href="{$dominio}">
              {if isset($logosite) && $logosite != ''}
                  {if strpos($logosite, 'http') === 0}
                      <img src='{$logosite}' style="width:230px; height:50px;" />
                  {else}
                      <img src='{$dominio}/{$logosite}' style="width:230px; height:50px;" />
                  {/if}
              {else}
                  <img src='{$dominio}/assets/ventasRD.png' style="width:230px; height:50px;" />
              {/if}
              <strong style='color:#ebebeb; font-size:15px;'>{$user_session}</strong>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          
          {if $id_user!=''}
              <input type='hidden' value='{$foto_perfil}' id='foto_perfil'/>
              <input type='hidden' value='{$user_session}' id='nombre_usuario'/>
              <input type='hidden' value='{$id_user}' id='id_usuario'/>
                  
              <table style='margin:5px; display:none;'>
                  <tr>
                      <td><img src='{$foto_perfil}' style='border-radius:100px; width:35px; height:30px; margin:2px;'></td>
                      <td></td>
                  </tr>
              </table>
          {else}
              <input type='hidden' value='{$foto_perfil}' id='foto_perfil'/>
              <input type='hidden' value='0' class='user_update'/>
              <input type='hidden' value='{$user_session}' id='nombre_usuario'/>
              <input type='hidden' value='0' id='id_usuario'/>
          {/if} 

          <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
              <ul class="navbar-nav">
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Menu
                      </a>
                      <ul class="dropdown-menu dropdown-menu-dark">
                          {if $id_user!=''}
                              <li><a class="dropdown-item" data-bs-toggle="modal" style='cursor:pointer' data-bs-target="#exampleModal">Public Post</a></li>
                              <li><a class="dropdown-item" data-bs-toggle="modal" style='cursor:pointer' data-bs-target="#transferVideo">Transfer video</a></li>
                              {if $type_user=='admin'}
                                  <li><a class="dropdown-item user_update" href="{$dominio}/admin" style='cursor:pointer'>Admin</a></li>
                              {/if}
                              <li><a class="dropdown-item user_update" data-bs-toggle="modal" style='cursor:pointer' data-bs-target="#updateUserModal">User Update</a></li>
                              <li><a class="dropdown-item" style='cursor:pointer' id='singout'>Sing out</a></li>
                              <li><a class="dropdown-item" href="{$dominio}/profile/{$user_session}">My Profile</a></li>
                              <li class="dropdown-item" style='display:none' id='login' style='cursor:pointer'>Login</li>
                          {else}
                              <li class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal" style='cursor:pointer'>Login</li>
                              <a href="{$dominio}/registrer.php" style='text-decoration:none;'>
                                  <li class="dropdown-item" style='cursor:pointer'>Registrer</li>
                              </a>
                          {/if}
                      </ul>
                  </li>
                  {if $id_user!=''}
                    <li class="nav-item">
                        <button type="button" class="nav-notif-btn" data-bs-toggle="modal" data-bs-target="#notificacionModal" aria-label="Notificaciones">
                            <i class="fa-solid fa-bell"></i>
                            {if $cantidad_notificacion && $cantidad_notificacion > 0}
                                <span class="nav-notif-badge">{$cantidad_notificacion}</span>
                            {/if}
                        </button>
                    </li>
                  {else}
                    <li class="nav-item">
                        <span class="nav-notif-btn nav-notif-btn-disabled" id="notify" aria-label="Inicia sesión para ver notificaciones"><i class="fa-solid fa-bell"></i></span>
                    </li>
                  {/if}
              </ul>
          </div>

          <!-- Barra de búsqueda responsive -->
          <form method='get' action='index.php' class="search-form-nav">
              <div class="search-container">
                  <input type='search' name='search' class='form-control search-input' placeholder='Buscar...'/>
                  <button class='btn btn-search' type="submit">
                      <i class="fa-solid fa-search"></i>
                  </button>
              </div>
          </form>
          
          <style>
              .nav-notif-btn {
                  position: relative;
                  width: 42px;
                  height: 42px;
                  border: none;
                  background: rgba(255, 255, 255, 0.1);
                  color: #fff;
                  border-radius: 12px;
                  cursor: pointer;
                  display: inline-flex;
                  align-items: center;
                  justify-content: center;
                  font-size: 1.125rem;
                  transition: background 0.2s ease, color 0.2s ease;
              }
              .nav-notif-btn:hover {
                  background: rgba(255, 255, 255, 0.2);
                  color: #fff;
              }
              .nav-notif-btn-disabled {
                  opacity: 0.6;
                  cursor: default;
              }
              .nav-notif-badge {
                  position: absolute;
                  top: 4px;
                  right: 4px;
                  min-width: 18px;
                  height: 18px;
                  padding: 0 5px;
                  background: #ef4444;
                  color: #fff;
                  font-size: 0.6875rem;
                  font-weight: 700;
                  border-radius: 9px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
              }
              .search-form-nav {
                  flex: 1;
                  max-width: 400px;
                  margin: 0 15px;
              }
              .search-container {
                  display: flex;
                  align-items: center;
                  background: rgba(255,255,255,0.1);
                  border-radius: 25px;
                  overflow: hidden;
                  border: 1px solid rgba(255,255,255,0.2);
                  transition: all 0.3s ease;
              }
              .search-container:focus-within {
                  background: rgba(255,255,255,0.15);
                  border-color: #09b9e1;
                  box-shadow: 0 0 0 2px rgba(9, 185, 225, 0.2);
              }
              .search-input {
                  flex: 1;
                  border: none !important;
                  background: transparent !important;
                  color: #fff !important;
                  padding: 8px 15px !important;
                  font-size: 14px;
                  box-shadow: none !important;
              }
              .search-input::placeholder {
                  color: rgba(255,255,255,0.6);
              }
              .search-input:focus {
                  outline: none !important;
              }
              .btn-search {
                  background: transparent;
                  border: none;
                  color: #09b9e1;
                  padding: 8px 15px;
                  cursor: pointer;
                  transition: all 0.3s ease;
              }
              .btn-search:hover {
                  color: #fff;
                  background: rgba(9, 185, 225, 0.3);
              }
              
              /* Mobile styles */
              @media (max-width: 991px) {
                  .search-form-nav {
                      order: 3;
                      width: 100%;
                      max-width: 100%;
                      margin: 10px 0;
                      padding: 0 10px;
                  }
                  .search-container {
                      width: 100%;
                  }
                  .search-input {
                      padding: 12px 15px !important;
                      font-size: 16px; /* Evita zoom en iOS */
                  }
                  .btn-search {
                      padding: 12px 18px;
                  }
              }
              
              @media (max-width: 576px) {
                  .navbar-brand img {
                      width: 150px !important;
                      height: auto !important;
                  }
                  .search-form-nav {
                      padding: 0 5px;
                  }
              }
          </style>
      </div>
  </nav>

      <div class="container-fluid">

              <hr/>
              {include file="login.tpl"}
              {include file="update_user.tpl"}
         
            <!-- Modal Transfer VIdeo -->
            {include file="transfer_video.tpl"}


            <!-- Modal notificaciones-->

            {include file="modal_notificacion.tpl"}
            
            <!-- Modal POST-->
              {include file="modal_post.tpl"}
            <!-- Modal Editar Publicación -->
              {include file="modal_edit_board.tpl"}
                   <!-- Incluir menu -->
                   {assign var="counter" value=true}


                   {if $content_config!=="backoffice"}

                    {include file='menu.tpl'}

                   {/if}
               

                    {if $content_config=='boards'}
                        <br/><br/><br/><br/>
                    {foreach from=$tableros item=tablero}
            
                        {include file="board.tpl"}
                        {assign var="counter" value=false}
                        
                    {/foreach}
                    <script type="text/javascript" src="{$dominio}/js/board_preview.js"></script>
                    <script type="text/javascript" src="{$dominio}/js/board_interactions.js"></script>

                    {elseif $content_config=='single_board'}
                        <!--  include template for board-->
                      {assign var="counter" value=true}

                      {include file="single_board.tpl"}
                      <script type="text/javascript" src="{$dominio}/js/board_interactions.js"></script>
            

                    {elseif $content_config=='profile'}
                        {include file="profile.tpl"}
                        {assign var="counter" value=false}
                        <script type="text/javascript" src="{$dominio}/js/board_interactions.js"></script>
                        <!--  include template for user-->
                    {elseif $content_config=='registrer'}

                        {include file="registrer.tpl"}

                    {elseif $content_config=="backoffice"}

                        {include file="backoffice.tpl"}   

                    {elseif $content_config=="activate_user_account"}
  
                        {include file="active_account.tpl"}   

                    {elseif $content_config=='convert_pdf'}

                        {include file="pdf_convert.tpl"}

                    {else}
                  {/if}

                  
            

                  
                  {if $content_config!='profile'}
                    {include file="footer.tpl"}
                  {/if}


    

             

            </div>
      
