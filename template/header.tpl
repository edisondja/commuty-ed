<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    <title>{$titulo}</title>
        <meta name="description" content="{$descripcion}">
        <meta name="rating" content="RTA-5042-1996-1400-1577-RTA" />
        <meta http-equiv="Content-Language" content="en-US">
        <meta name="Robots" content="all"/>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" href="{$dominio}/{$favicon}" type="image/png">

        <!-- ETIQUETAS TWITER -->
        <meta name="twitter:card" content="{$og_imagen}" >
        <meta name="twitter:site" content="{$name}">
        <meta name="twitter:title" content="{$titulo}" >
        <meta name="twitter:description" content="{$descripcion}" >
        <meta name="twitter:image" content="{$og_imagen}" >
        <meta name="twitter:url" content="{$url_board}" >

        <!-- ETIQUETAS FACEBOOK -->
        <meta property="og:image" content="{$og_imagen}">
        <meta property="og:video" content="">
        <meta property="og:title" content="{$titulo}">
        <meta property="og:url" content="{$url_board}">
        <meta property="og:description" content="{$descripcion}">
        <meta property="og:site_name" content="{$name}">
        <meta name="google" value="notranslate">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content=""/>
        {$libs_cdn}

  </head>
  <body style='background:#F8FAFC;'>
  
    <input type='hidden' value='{$dominio}' id='dominio'/> 
    <input type='hidden' id='paginador_scroll'  value='{$paginador_scroll}'/>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#008080; position: fixed; top: 0; width: 100%; z-index: 999;">
      
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
              <img src='{$dominio}/{$logosite}' />
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
                              {if $type_user=='admin'}
                                  <li><a class="dropdown-item user_update" href="backcoffe.php" style='cursor:pointer'>Admin</a></li>
                              {/if}
                              <li><a class="dropdown-item user_update" data-bs-toggle="modal" style='cursor:pointer' data-bs-target="#updateUserModal">User Update</a></li>
                              <li><a class="dropdown-item" style='cursor:pointer' id='singout'>Sing out</a></li>
                              <li><a class="dropdown-item" href="{$dominio}/profile_user.php?user={$user_session}">My Profile</a></li>
                              <li class="dropdown-item" style='display:none' id='login' style='cursor:pointer'>Login</li>
                          {else}
                              <li class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal" style='cursor:pointer'>Login</li>
                              <a href="registrer.php" style='text-decoration:none;'>
                                  <li class="dropdown-item" style='cursor:pointer'>Registrer</li>
                              </a>
                          {/if}
                      </ul>
                  </li>
                  {if $id_user!=''}
                 
                    
                    <!-- Si existe sesion de un usuario entonces carga sus notifiaciones si tiene disponibles  -->
                    <i class="fa-solid fa-envelope" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#notificacionModal" style="cursor:pointer; padding-top: 8%;font-size: 24px;color: #e9e7e7ff; display: inline-flex;">
                            <p style="font-size: 10px;">
                                {if $cantidad_notificacion!=''} 
                                    &nbsp;+{$cantidad_notificacion}
                                {/if}
                            </p>
                    </i>
                  {else}
                    <i class="fa-solid fa-envelope" id="notify" style="cursor:pointer; padding-top: 8%;font-size: 24px;color: #ffffff; display: inline-flex;">
                    </i>
                  {/if}
              </ul>
          </div>

          <form method='get' action='index.php'>
              <table style='margin-left:30px;'>
                  <tr>
                      <td><input type='search' name='search' class='form-control' placeholder='write the name of table'/></td>
                      <td><button class='btn btn-dark'>Search</button></td>
                  </tr>
              </table>
          </form>
      </div>
  </nav>

      <div class="container-fluid">
     
              <hr/>
              {include file="login.tpl"}
              {include file="update_user.tpl"}
         

            <!-- Modal notificaciones-->

            {include file="modal_notificacion.tpl"}
            
            <!-- Modal POST-->
              {include file="modal_post.tpl"}
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

                    {elseif $content_config=='single_board'}
                        <!--  include template for board-->
                      {assign var="counter" value=true}

                      {include file="single_board.tpl"}
            

                    {elseif $content_config=='profile'}
                        {include file="profile.tpl"}
                        {assign var="counter" value=false}

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
      
