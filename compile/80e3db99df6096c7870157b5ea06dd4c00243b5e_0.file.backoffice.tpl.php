<?php
/* Smarty version 4.5.3, created on 2024-09-12 20:28:34
  from 'C:\xampp\htdocs\ventasrd\template\backoffice.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66e332d2243525_86329763',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '80e3db99df6096c7870157b5ea06dd4c00243b5e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\backoffice.tpl',
      1 => 1726165703,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:back_office_components/menu_backoffice.tpl' => 1,
    'file:back_office_components/modulo_usuario.tpl' => 1,
    'file:back_office_components/modulo_boards.tpl' => 1,
    'file:back_office_components/modulo_configuracion.tpl' => 1,
    'file:back_office_components/enviar_correo.tpl' => 1,
    'file:back_office_components/modulo_banner.tpl' => 1,
  ),
),false)) {
function content_66e332d2243525_86329763 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="row"> 

        <?php $_smarty_tpl->_subTemplateRender('file:back_office_components/menu_backoffice.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        
        <?php if ($_smarty_tpl->tpl_vars['option']->value == 'usuarios') {?>  

            <?php $_smarty_tpl->_subTemplateRender('file:back_office_components/modulo_usuario.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <?php } elseif ($_smarty_tpl->tpl_vars['option']->value == 'publicaciones') {?>
            <!-- Aqui se coloca el modulo de ver los posts de los usuarios-->
            <?php $_smarty_tpl->_subTemplateRender('file:back_office_components/modulo_boards.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


        <?php } elseif ($_smarty_tpl->tpl_vars['option']->value == 'configuraciones') {?>
            
            <!-- Aqui se coloca el modulo de ver los posts de los usuarios-->
            <?php $_smarty_tpl->_subTemplateRender('file:back_office_components/modulo_configuracion.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <?php } elseif ($_smarty_tpl->tpl_vars['option']->value == 'envar_correos') {?>

            <?php $_smarty_tpl->_subTemplateRender('file:back_office_components/enviar_correo.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            
        <?php } elseif ($_smarty_tpl->tpl_vars['option']->value == 'adm_banners') {?>   
            
            <?php $_smarty_tpl->_subTemplateRender('file:back_office_components/modulo_banner.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <?php }?>

</div>


<style>
    .content-container {
        margin-top: 50px;
        padding: 30px;
        background-color:#1a1c1d;
        border-radius: 10px;
    }
    h3 {
        margin-bottom: 30px;
    }
    .flex-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .checkbox-p {
        margin-right: 10px;
    }
    .table-dark th, .table-dark td {
        color: #f8f9fa;
    }
    .table-dark th {
        background-color: #6c757d;
    }
    .table-dark td {
        background-color: #495057;
    }
</style><?php }
}
