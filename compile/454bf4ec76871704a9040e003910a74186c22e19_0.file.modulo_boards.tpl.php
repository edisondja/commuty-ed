<?php
/* Smarty version 3.1.48, created on 2025-09-10 03:45:16
  from '/opt/lampp/htdocs/commuty-ed/template/back_office_components/modulo_boards.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68c0d82c335883_47047506',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '454bf4ec76871704a9040e003910a74186c22e19' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/back_office_components/modulo_boards.tpl',
      1 => 1757468714,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68c0d82c335883_47047506 (Smarty_Internal_Template $_smarty_tpl) {
?><style>
.content-container_s {
    margin-top: 50px;
    padding: 30px;
    background-color: #ffffff; /* Fondo blanco */
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Título */
h3 {
    margin-bottom: 30px;
    color: #009688; /* Verde azulado */
    font-weight: bold;
}

/* Flex container */
.flex-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.checkbox-p {
    margin-right: 10px;
}

/* Input de búsqueda */
#search {
    border: 2px solid #009688; /* Verde azulado */
    border-radius: 8px;
    padding: 10px;
    color: #333;
    transition: 0.3s;
}

#search::placeholder {
    color: #707070; /* Placeholder gris suave */
}

#search:focus {
    border-color: #FF6F61; /* Coral al enfocar */
    box-shadow: 0 0 5px rgba(255,111,97,0.3);
}


/* Tabla personalizada */
.table-custom th {
    background-color: #009688; /* Verde azulado */
    color: #ffffff;            /* Texto blanco */
    border: none;

}

.table-custom td {
    background-color: #ffffff; /* Fondo blanco */
    color: #333333;            /* Texto gris oscuro */
    border-top: 1px solid #e0e0e0;
}

.table-custom tr:hover td {
    background-color: #f1fdfb; /* Hover verde azulado suave */
}




</style>


<div class="col-md-8 col-12 content-container_s mx-auto tabla_buscar">
    <h3>Buscar publicaciones <i class="fa-solid fa-photo-film"></i></h3>

    <input type="hidden" id="modulo_select" value="boards">
    <input type="search" class="form-control mb-4" id="search" placeholder="Busca lo que deseas">

    <table class="table table-custom table-scroll">
        <thead>
            <tr>
                <th>Titulo</th>
                <th>Portada</th>
                <th>Fecha publicacion</th>
                <th>Estado</th>
                <th>Foto</th>
                <th>Usuario</th>
                <th>Block</th>
                <th>Editar<th>
            </tr>
        </thead>
        <tbody id="data_boards" class="tabla_buscar">
        </tbody>
    </table>
</div>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/js/back_office.js"><?php echo '</script'; ?>
>
<?php }
}
