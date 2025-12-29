<?php
/* Smarty version 3.1.48, created on 2025-12-29 04:01:53
  from '/opt/lampp/htdocs/commuty-ed/template/ads.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_6951ef2149a598_09485196',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a56adc00e4896a4600261f36c5983465e9a8ccee' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/ads.tpl',
      1 => 1755969815,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6951ef2149a598_09485196 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="col-sm-3 ads_banners" style=" position: fixed; top: 20px; right: 20px; z-index: 10; padding-top:70px;">
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
