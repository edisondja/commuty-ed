<?php
/* Smarty version 4.5.3, created on 2024-09-11 22:50:29
  from 'C:\xampp\htdocs\ventasrd\template\back_office_components\modulo_usuario.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66e20295c97857_51190568',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3438e0498100aceb7a9e75b83696ee7f02440d22' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\back_office_components\\modulo_usuario.tpl',
      1 => 1720487783,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66e20295c97857_51190568 (Smarty_Internal_Template $_smarty_tpl) {
?><style>
    .content-container {
        margin-top: 50px;
        padding: 30px;
        background-color: #495057;
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
</style>

<div class="col-md-8 col-12 content-container mx-auto tabla_buscar">
<h3 style="color: aliceblue;">Buscar usuarios <i class="fa-solid fa-users"></i></h3>
        <input type="hidden" id="modulo_select" value="users">
  <!-- Configuracion de modulo para saber por que criterio se esta buscando
        desde el archivo back_office.js pueda detectar la configuracion
        para saber que datas filtrar -->
    <input type="hidden" id="modulo_select" value="users">
    <input type="search" class="form-control mb-4" id="search" placeholder="Busca lo que deseas">


    <table class="table table-dark table-float-header ">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Estado de usuario</th>
            <th>Foto</th>
            <th>Block</th>

            
        </tr>
    </thead>
            <tbody id="data_usuario" class="tabla_buscar"> 
                
            </tbody>
    </table>

</div>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/js/back_office.js"><?php echo '</script'; ?>
><?php }
}
