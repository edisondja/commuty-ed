<?php
/* Smarty version 3.1.48, created on 2025-09-05 04:53:59
  from '/opt/lampp/htdocs/commuty-ed/template/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68ba50c781d470_79034206',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f410541cc83663bf194cbd4ea38a86a9e4888eea' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/header.tpl',
      1 => 1757040837,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:login.tpl' => 1,
    'file:update_user.tpl' => 1,
    'file:modal_notificacion.tpl' => 1,
    'file:modal_post.tpl' => 1,
    'file:menu.tpl' => 1,
    'file:board.tpl' => 1,
    'file:single_board.tpl' => 1,
    'file:profile.tpl' => 1,
    'file:registrer.tpl' => 1,
    'file:backoffice.tpl' => 1,
    'file:active_account.tpl' => 1,
    'file:pdf_convert.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_68ba50c781d470_79034206 (Smarty_Internal_Template $_smarty_tpl) {
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
    <?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"><?php echo '</script'; ?>
>
    <title><?php echo $_smarty_tpl->tpl_vars['titulo']->value;?>
</title>
        <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['descripcion']->value;?>
">
        <meta name="rating" content="RTA-5042-1996-1400-1577-RTA" />
        <meta http-equiv="Content-Language" content="en-US">
        <meta name="Robots" content="all"/>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/png">

        <!-- ETIQUETAS TWITER -->
        <meta name="twitter:card" content="<?php echo $_smarty_tpl->tpl_vars['og_imagen']->value;?>
" >
        <meta name="twitter:site" content="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
">
        <meta name="twitter:title" content="<?php echo $_smarty_tpl->tpl_vars['titulo']->value;?>
" >
        <meta name="twitter:description" content="<?php echo $_smarty_tpl->tpl_vars['descripcion']->value;?>
" >
        <meta name="twitter:image" content="<?php echo $_smarty_tpl->tpl_vars['og_imagen']->value;?>
" >
        <meta name="twitter:url" content="<?php echo $_smarty_tpl->tpl_vars['url_board']->value;?>
" >

        <!-- ETIQUETAS FACEBOOK -->
        <meta property="og:image" content="<?php echo $_smarty_tpl->tpl_vars['og_imagen']->value;?>
">
        <meta property="og:video" content="">
        <meta property="og:title" content="<?php echo $_smarty_tpl->tpl_vars['titulo']->value;?>
">
        <meta property="og:url" content="<?php echo $_smarty_tpl->tpl_vars['url_board']->value;?>
">
        <meta property="og:description" content="<?php echo $_smarty_tpl->tpl_vars['descripcion']->value;?>
">
        <meta property="og:site_name" content="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
">
        <meta name="google" value="notranslate">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content=""/>
        <?php echo $_smarty_tpl->tpl_vars['libs_cdn']->value;?>


  </head>
  <body style='background:#151c1b;'>
  
    <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
' id='dominio'/> 
    <input type='hidden' id='paginador_scroll'  value='<?php echo $_smarty_tpl->tpl_vars['paginador_scroll']->value;?>
'/>
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

          <a class="navbar-brand" style="color: #09b9e1;" href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
">
              <img src='<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['logosite']->value;?>
' style="width:230px; height:50px;" />
              <strong style='color:#ebebeb; font-size:15px;'><?php echo $_smarty_tpl->tpl_vars['user_session']->value;?>
</strong>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          
          <?php if ($_smarty_tpl->tpl_vars['id_user']->value != '') {?>
              <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
' id='foto_perfil'/>
              <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['user_session']->value;?>
' id='nombre_usuario'/>
              <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['id_user']->value;?>
' id='id_usuario'/>
                  
              <table style='margin:5px; display:none;'>
                  <tr>
                      <td><img src='<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
' style='border-radius:100px; width:35px; height:30px; margin:2px;'></td>
                      <td></td>
                  </tr>
              </table>
          <?php } else { ?>
              <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
' id='foto_perfil'/>
              <input type='hidden' value='0' class='user_update'/>
              <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['user_session']->value;?>
' id='nombre_usuario'/>
          <?php }?> 

          <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
              <ul class="navbar-nav">
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Menu
                      </a>
                      <ul class="dropdown-menu dropdown-menu-dark">
                          <?php if ($_smarty_tpl->tpl_vars['id_user']->value != '') {?>
                              <li><a class="dropdown-item" data-bs-toggle="modal" style='cursor:pointer' data-bs-target="#exampleModal">Public Post</a></li>
                              <?php if ($_smarty_tpl->tpl_vars['type_user']->value == 'admin') {?>
                                  <li><a class="dropdown-item user_update" href="backcoffe.php" style='cursor:pointer'>Admin</a></li>
                              <?php }?>
                              <li><a class="dropdown-item user_update" data-bs-toggle="modal" style='cursor:pointer' data-bs-target="#updateUserModal">User Update</a></li>
                              <li><a class="dropdown-item" style='cursor:pointer' id='singout'>Sing out</a></li>
                              <li><a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/profile_user.php?user=<?php echo $_smarty_tpl->tpl_vars['user_session']->value;?>
">My Profile</a></li>
                              <li class="dropdown-item" style='display:none' id='login' style='cursor:pointer'>Login</li>
                          <?php } else { ?>
                              <li class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal" style='cursor:pointer'>Login</li>
                              <a href="registrer.php" style='text-decoration:none;'>
                                  <li class="dropdown-item" style='cursor:pointer'>Registrer</li>
                              </a>
                          <?php }?>
                      </ul>
                  </li>
                  <?php if ($_smarty_tpl->tpl_vars['id_user']->value != '') {?>
                 
                    
                    <!-- Si existe sesion de un usuario entonces carga sus notifiaciones si tiene disponibles  -->
                    <i class="fa-solid fa-envelope" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#notificacionModal" style="cursor:pointer; padding-top: 8%;font-size: 24px;color: #e9e7e7ff; display: inline-flex;">
                            <p style="font-size: 10px;">
                                <?php if ($_smarty_tpl->tpl_vars['cantidad_notificacion']->value != '') {?> 
                                    &nbsp;+<?php echo $_smarty_tpl->tpl_vars['cantidad_notificacion']->value;?>

                                <?php }?>
                            </p>
                    </i>
                  <?php } else { ?>
                    <i class="fa-solid fa-envelope" id="notify" style="cursor:pointer; padding-top: 8%;font-size: 24px;color: #ffffff; display: inline-flex;">
                    </i>
                  <?php }?>
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
              <?php $_smarty_tpl->_subTemplateRender("file:login.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
              <?php $_smarty_tpl->_subTemplateRender("file:update_user.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
         

            <!-- Modal notificaciones-->

            <?php $_smarty_tpl->_subTemplateRender("file:modal_notificacion.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            
            <!-- Modal POST-->
              <?php $_smarty_tpl->_subTemplateRender("file:modal_post.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                   <!-- Incluir menu -->
                   <?php $_smarty_tpl->_assignInScope('counter', true);?>


                   <?php if ($_smarty_tpl->tpl_vars['content_config']->value !== "backoffice") {?>

                    <?php $_smarty_tpl->_subTemplateRender('file:menu.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                   <?php }?>
               

                    <?php if ($_smarty_tpl->tpl_vars['content_config']->value == 'boards') {?>
                        <br/><br/><br/><br/>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tableros']->value, 'tablero');
$_smarty_tpl->tpl_vars['tablero']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['tablero']->value) {
$_smarty_tpl->tpl_vars['tablero']->do_else = false;
?>
            
                        <?php $_smarty_tpl->_subTemplateRender("file:board.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
                        <?php $_smarty_tpl->_assignInScope('counter', false);?>
                        
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                    <?php } elseif ($_smarty_tpl->tpl_vars['content_config']->value == 'single_board') {?>
                        <!--  include template for board-->
                      <?php $_smarty_tpl->_assignInScope('counter', true);?>

                      <?php $_smarty_tpl->_subTemplateRender("file:single_board.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            

                    <?php } elseif ($_smarty_tpl->tpl_vars['content_config']->value == 'profile') {?>
                        <?php $_smarty_tpl->_subTemplateRender("file:profile.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                        <?php $_smarty_tpl->_assignInScope('counter', false);?>

                        <!--  include template for user-->
                    <?php } elseif ($_smarty_tpl->tpl_vars['content_config']->value == 'registrer') {?>

                        <?php $_smarty_tpl->_subTemplateRender("file:registrer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <?php } elseif ($_smarty_tpl->tpl_vars['content_config']->value == "backoffice") {?>

                        <?php $_smarty_tpl->_subTemplateRender("file:backoffice.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>   

                    <?php } elseif ($_smarty_tpl->tpl_vars['content_config']->value == "activate_user_account") {?>
  
                        <?php $_smarty_tpl->_subTemplateRender("file:active_account.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>   

                    <?php } elseif ($_smarty_tpl->tpl_vars['content_config']->value == 'convert_pdf') {?>

                        <?php $_smarty_tpl->_subTemplateRender("file:pdf_convert.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <?php } else { ?>
                  <?php }?>

                  
            

                  
                  <?php if ($_smarty_tpl->tpl_vars['content_config']->value != 'profile') {?>
                    <?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                  <?php }?>


    

             

            </div>
      
<?php }
}
