<?php
/* Smarty version 4.5.3, created on 2024-09-11 22:55:10
  from 'C:\xampp\htdocs\ventasrd\template\back_office_components\enviar_correo.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66e203ae401090_58783914',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c580832349ed906526854f4bd572ff221158aa34' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\back_office_components\\enviar_correo.tpl',
      1 => 1724386282,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66e203ae401090_58783914 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-md-8">

<div style="text-align: center;">
    <h3 style="color: antiquewhite;">Enviar correos masivos</h3>
</div>
   
<p style="color: antiquewhite; text-align:center">
    El mensaje que ponga en este campo sera enviado  a todos lo usuarios de la plataforma por correo
    electronico.

</p>
    <input type="text" id="asunto" class="form-control" style="background-color: rgb(43, 41, 41);color: antiquewhite;"><br/>

    <textarea class="form-control" id="texto_correo" rows="12" style="background-color: rgb(43, 41, 41); color: antiquewhite;">
    
    
    
    </textarea><br/>

    <div style="text-align: center;">
        <button class="btn btn-dark" style="width: 30%;" id="send_mail">Enviar</button>
    </div>




</div>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/js/bk_modulo_mail.js"><?php echo '</script'; ?>
><?php }
}
