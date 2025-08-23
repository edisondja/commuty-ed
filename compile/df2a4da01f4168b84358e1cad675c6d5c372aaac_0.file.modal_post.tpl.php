<?php
/* Smarty version 3.1.48, created on 2025-08-23 19:47:31
  from '/opt/lampp/htdocs/commuty-ed/template/modal_post.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68a9feb31e1506_37270171',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'df2a4da01f4168b84358e1cad675c6d5c372aaac' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/modal_post.tpl',
      1 => 1755969815,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68a9feb31e1506_37270171 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div><?php }
}
