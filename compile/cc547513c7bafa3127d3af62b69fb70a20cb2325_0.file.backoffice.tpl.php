<?php
/* Smarty version 3.1.48, created on 2025-09-11 05:21:46
  from '/opt/lampp/htdocs/commuty-ed/template/backoffice.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68c2404a46f292_53641536',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cc547513c7bafa3127d3af62b69fb70a20cb2325' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/backoffice.tpl',
      1 => 1757560457,
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
    'file:back_office_components/modulo_reportes.tpl' => 1,
  ),
),false)) {
function content_68c2404a46f292_53641536 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="row"> 

        <!-- Modal -->
        <div class="modal fade" id="modalActualizarTablero" tabindex="-1" aria-labelledby="modalActualizarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarLabel">Actualizar Tablero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formActualizarTablero">
                <input type="hidden" id="idTablero" name="id_tablero">
                <input type="hidden" id="idUsuario" name="id_tablero">

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcionTablero" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcionTablero" name="descripcion" rows="3" placeholder="Escribe la nueva descripción"></textarea>
                </div>

              <!-- Foto de portada -->
                <label for="fotoPortada" class="form-label fw-bold">Cambia a gusto la foto de portada que deseas</label>
                <div class="mb-3 text-center p-3 border rounded shadow-sm" style="background-color:#f8f9fa;">
                    <!-- Imagen de vista previa -->
                    <img 
                        src="https://via.placeholder.com/300x300?text=Vista+Previa" 
                        alt="Vista previa" 
                        id="vistaPreviaImagen" 
                        class="img-fluid rounded mb-3 border" 
                        style="max-height:300px; max-width:300px; object-fit:cover;" 
                    />

                    <!-- Label e input --><br/>
                    <label for="fotoPortada" class="form-label fw-bold">📷 Foto de portada</label>
                    <input class="form-control" type="file" id="fotoPortada" name="foto" accept="image/*">
                </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCambiosTablero()">Guardar cambios</button>
            </div>

            </div>
        </div>
        </div>


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

        <?php } elseif ($_smarty_tpl->tpl_vars['option']->value == 'reportes') {?>    
            <?php $_smarty_tpl->_subTemplateRender('file:back_office_components/modulo_reportes.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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
