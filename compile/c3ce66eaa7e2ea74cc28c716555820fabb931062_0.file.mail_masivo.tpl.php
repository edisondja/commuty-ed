<?php
/* Smarty version 4.5.3, created on 2024-09-22 03:56:53
  from 'C:\xampp\htdocs\ventasrd\template\mail_masivo.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66ef7965ada861_07700153',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c3ce66eaa7e2ea74cc28c716555820fabb931062' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\mail_masivo.tpl',
      1 => 1726970208,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66ef7965ada861_07700153 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Template</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
     <body style="background-color: #f8f9fa; padding: 20px;">
        <div class="container">
            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title mb-0"></h2>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo $_smarty_tpl->tpl_vars['mensaje']->value;?>
</p>
                    <p class="card-text mt-4">
                        Si tienes alguna pregunta, no dudes en contactarnos a través de este correo.
                    </p>
                    <p class="card-text">Saludos cordiales,<br><?php echo $_smarty_tpl->tpl_vars['nombre_sitio']->value;?>
</p>
                </div>
                <div class="card-footer text-muted text-center">
                    © 2024 <?php echo $_smarty_tpl->tpl_vars['nombre_sitio']->value;?>
 Todos los derechos reservados.
                </div>
            </div>
        </div>
    </body>
</html><?php }
}
