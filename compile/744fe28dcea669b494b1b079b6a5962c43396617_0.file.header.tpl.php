<?php
/* Smarty version 4.5.3, created on 2024-09-07 19:19:53
  from 'C:\xampp\htdocs\ventasrd\template\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66dc8b3959c4e0_03551608',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '744fe28dcea669b494b1b079b6a5962c43396617' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\header.tpl',
      1 => 1725728264,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:login.tpl' => 1,
    'file:update_user.tpl' => 1,
    'file:menu.tpl' => 1,
    'file:board.tpl' => 1,
    'file:single_board.tpl' => 1,
    'file:profile.tpl' => 1,
    'file:registrer.tpl' => 1,
    'file:backoffice.tpl' => 1,
    'file:active_account.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_66dc8b3959c4e0_03551608 (Smarty_Internal_Template $_smarty_tpl) {
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
  <body style='background:#1e1e23;'>
    <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
' id='dominio'/> 
    <input type='hidden' id='paginador_scroll'  value='<?php echo $_smarty_tpl->tpl_vars['paginador_scroll']->value;?>
'/>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#18171f; position: fixed; top: 0; width: 100%; z-index: 999;">
      <div class="container-fluid">
          <a class="navbar-brand" style="color: #09b9e1;" href="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
">
              <img src='<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['logosite']->value;?>
' />
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

              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Post this content</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                                  <table>
                                  
                                      <tr>
                                          <td><img src='<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
' style='height:50px;width:50px;border-radius:100px;'>&nbsp;</td>
                                          <td><?php echo $_smarty_tpl->tpl_vars['user_session']->value;?>
</td>
                                      </tr>
                                  </table><hr/>
                                  <div class='card-body'>
                                      <textarea class='form-control' id="board_title" rows='5'></textarea>
                                  </div><hr/>
                                  <div class="flex-container">
                                  <progress id="file" style="display:none" class="progress"  max="100" value="0"></progress>
                                      <strong style="margin-top: -3.4px; display:none" id="porcentaje">&nbsp;0%</strong>
                                  </div>
                                  <div id='multimedia_view' class='flex-container'>
                                    
                                  </div>  
                        </div>
                        <div class="modal-footer">
                              <input   accept="image/png,image/jpeg,video/*,audio/*" type='file' id='upload_images' style='display:none' multiple name="imagenes[]" />

                             <table class='table'>
                                      <tr>
                                        <td style='width:5%;cursor:pointer;'>
                                        <svg id="upload_image" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                                          <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                          <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"/>
                                          </svg>
                                          <svg style='margin:5px;' xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-gif" viewBox="0 0 16 16">
                                              <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2H9v-1h3a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.278 13.124a1.403 1.403 0 0 0-.14-.492 1.317 1.317 0 0 0-.314-.407 1.447 1.447 0 0 0-.48-.275 1.88 1.88 0 0 0-.636-.1c-.361 0-.67.076-.926.229a1.48 1.48 0 0 0-.583.632 2.136 2.136 0 0 0-.199.95v.506c0 .272.035.52.105.745.07.224.177.417.32.58.142.162.32.288.533.377.215.088.466.132.753.132.268 0 .5-.037.697-.111a1.29 1.29 0 0 0 .788-.77c.065-.174.097-.358.097-.551v-.797H1.717v.589h.823v.255c0 .132-.03.254-.09.363a.67.67 0 0 1-.273.264.967.967 0 0 1-.457.096.87.87 0 0 1-.519-.146.881.881 0 0 1-.305-.413 1.785 1.785 0 0 1-.096-.615v-.499c0-.365.078-.648.234-.85.158-.2.38-.301.665-.301a.96.96 0 0 1 .3.044c.09.03.17.071.236.126a.689.689 0 0 1 .17.19.797.797 0 0 1 .097.25h.776Zm1.353 2.801v-3.999H3.84v4h.79Zm1.493-1.59v1.59h-.791v-3.999H7.88v.653H6.124v1.117h1.605v.638H6.124Z"/>
                                          </svg>
                                        </td>
                                      
                                      </td>
                                    </tr>
                              </table>
                              <button class="btn btn-dark" id='post'>POST</button>
                        </div>
                      </div>
                    </div>
                  </div>
                   <!-- Incluir menu -->
                   <?php $_smarty_tpl->_assignInScope('counter', true);?>


                   <?php if ($_smarty_tpl->tpl_vars['content_config']->value !== "backoffice") {?>

                    <?php $_smarty_tpl->_subTemplateRender('file:menu.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                   <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['content_config']->value == 'boards') {?>
                

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

                    <?php } else { ?>
                  <?php }?>

                  
            

                  
                  <?php if ($_smarty_tpl->tpl_vars['content_config']->value != 'profile') {?>
                    <?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                  <?php }?>


    

             

            </div>
      
<?php }
}
