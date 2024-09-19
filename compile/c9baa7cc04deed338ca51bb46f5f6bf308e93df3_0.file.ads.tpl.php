<?php
/* Smarty version 4.5.3, created on 2024-09-07 19:19:53
  from 'C:\xampp\htdocs\ventasrd\template\ads.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66dc8b39c44c34_68097288',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c9baa7cc04deed338ca51bb46f5f6bf308e93df3' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\ads.tpl',
      1 => 1725727930,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66dc8b39c44c34_68097288 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="col-sm-3" style="position: fixed; top: 20px; right: 20px; z-index: 10; padding-top:70px;">
    <!-- Tarjeta estilo Carbon Ads -->
    <div class="card text-center" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
        <div class="card-body d-flex align-items-center">
            <img src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/assets/camisa.png" class="rounded me-2" style="width: 100px;" alt="Publicidad">
            <div>
                <h6 class="card-title mb-2">Título de la Publicidad <?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
</h6>
                <p class="card-text" style="font-size: 14px;">Descripción breve y concisa sobre tu producto o servicio. Resalta las características clave en una línea.</p>
            </div>
        </div>
    </div>

    <hr/>

    <!-- Segunda Tarjeta estilo Carbon Ads -->
    <div class="card text-center" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
        <div class="card-body d-flex align-items-center">
            <img src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/assets/contenedor.png" class="rounded me-2" style="width: 100px;" alt="Publicidad">
            <div>
                <h6 class="card-title mb-2">Título de la Publicidad <?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
</h6>
                <p class="card-text" style="font-size: 14px;">Descripción breve y concisa sobre tu producto o servicio. Resalta las características clave en una línea.</p>
            </div>
        </div>
    </div>
</div>
<?php }
}
