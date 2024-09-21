<?php
/* Smarty version 4.5.3, created on 2024-09-20 03:59:21
  from 'C:\xampp\htdocs\ventasrd\template\active_account.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66ecd6f91eeb29_42305844',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '16f0b3e26d5fa6a8cd125eed16ec59a85cf31816' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\active_account.tpl',
      1 => 1723517676,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66ecd6f91eeb29_42305844 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="row">

    <div class="col-md-3">

    </div>

    <div class="col-md-8">              
            <h2 style="color: aliceblue"> Bienvenido <?php echo $_smarty_tpl->tpl_vars['usuario']->value;?>
 gracias por activar tu cuenta  a <?php echo $_smarty_tpl->tpl_vars['name_site']->value;?>
</h2>
    
            <div class="card">
            <div class="card-header bg-dark text-white">
                <h3>Normas de la Comunidad</h3>
            </div>
            <div class="card-body">
                <p class="card-text">
                    Queridos miembros de la comunidad,
                </p>
                <p class="card-text">
                    Les recordamos la importancia de seguir nuestras normas para mantener un entorno respetuoso y agradable para todos. Aquí algunos puntos clave a recordar:
                </p>
                <ul>
                    <li>Respetar a los demás miembros, independientemente de sus opiniones o creencias.</li>
                    <li>No se permite el uso de lenguaje ofensivo o discriminatorio.</li>
                    <li>Mantener las discusiones centradas en el tema y evitar desviarse con contenido irrelevante.</li>
                    <li>Informar a los moderadores sobre cualquier comportamiento inapropiado que pueda observar.</li>
                </ul>
                <p class="card-text">
                    Apreciamos su colaboración para hacer de nuestra comunidad un espacio seguro y acogedor para todos. ¡Gracias!
                </p>
                <p class="card-text text-end">
                    Atentamente,<br>
                    El equipo de <?php echo $_smarty_tpl->tpl_vars['name_site']->value;?>

                </p>
            </div>
        </div>
    
    </div>

</div><?php }
}
