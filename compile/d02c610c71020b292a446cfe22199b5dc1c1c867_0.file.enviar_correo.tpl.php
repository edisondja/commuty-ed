<?php
/* Smarty version 3.1.48, created on 2025-08-23 23:23:39
  from '/opt/lampp/htdocs/commuty-ed/template/back_office_components/enviar_correo.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68aa315b7048f2_71291690',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd02c610c71020b292a446cfe22199b5dc1c1c867' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/back_office_components/enviar_correo.tpl',
      1 => 1755984217,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68aa315b7048f2_71291690 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-md-8 mx-auto"><hr>
    <div class="mail-container p-4">
        <div style="text-align: center;">
            <h3>Enviar correos masivos</h3>
        </div>

        <p style="text-align:center;">
            El mensaje que pongas en este campo será enviado a todos los usuarios de la plataforma por correo electrónico.
        </p>

        <input type="text" id="asunto" class="form-control custom-input mb-3" placeholder="Asunto">

        <textarea class="form-control custom-input mb-3" id="texto_correo" rows="12" placeholder="Escribe el mensaje aquí..."></textarea>

        <div style="text-align: center;">
            <button class="btn btn-send" id="send_mail">Enviar</button>
        </div>
    </div>
</div>

<style>
    /* Contenedor oscuro del módulo */
    .mail-container {
        background-color: #343a40; /* fondo oscuro */
        border-radius: 10px;
        color: white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .mail-container h3 {
        color: white;
        margin-bottom: 20px;
    }

    .mail-container p {
        color:#343a40;
        margin-bottom: 20px;
    }

    /* Inputs y textarea */
    .custom-input {
        background-color: #2b2929; /* fondo oscuro */
        color: white; /* texto blanco */
        border: 1px solid #495057;
        border-radius: 5px;
        transition: border 0.3s, box-shadow 0.3s;
    }

    .custom-input:focus {
        border-color: #20c997; /* verde azulado al enfocar */
        box-shadow: 0 0 5px #20c997;
        outline: none;
    }

    /* Botón enviar */
    .btn-send {
        background-color: #343a40; /* fondo oscuro */
        color: white;
        border: 1px solid #20c997;
        border-radius: 5px;
        width: 30%;
        transition: all 0.3s;
    }

    .btn-send:hover {
        background-color: #20c997; /* verde azulado al pasar mouse */
        color: white;
        border-color: #20c997;
        cursor: pointer;
    }
</style>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/js/bk_modulo_mail.js"><?php echo '</script'; ?>
>
<?php }
}
