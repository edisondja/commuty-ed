<?php
/* Smarty version 3.1.48, created on 2025-12-29 04:01:34
  from '/opt/lampp/htdocs/commuty-ed/template/modal_notificacion.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_6951ef0ebee716_29310784',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dba2763000328c5eb246d20576bab76dc36f22e9' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/modal_notificacion.tpl',
      1 => 1755969815,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6951ef0ebee716_29310784 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal fade" id="notificacionModal" tabindex="-1" aria-labelledby="notificacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="notificacionModalLabel">Notificaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <!-- Lista de notificaciones -->
                <div id="lista_notificaciones">
                    <!-- Ejemplo de notificación -->
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notificaciones']->value, 'key');
$_smarty_tpl->tpl_vars['key']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value) {
$_smarty_tpl->tpl_vars['key']->do_else = false;
?>
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['key']->value['foto_url'];?>
" style="height:50px;width:50px;border-radius:100px;" alt="Foto de perfil">
                        <div class="ms-3">      
                                <p><strong><?php echo $_smarty_tpl->tpl_vars['key']->value['nombre'];?>
</strong> te ha mencionado en una publicación.</p>
                                <small class="text-muted"><?php echo $_smarty_tpl->tpl_vars['key']->value['fecha'];?>
</small>
                            <a href="single_board.php?id=<?php echo $_smarty_tpl->tpl_vars['key']->value['id_tablero'];?>
">
                                <button type="button" class="btn btn-sm btn-dark" 
                                data-bs-toggle="modal" data-bs-target="#notificacionModal" 
                               style="padding: 0.25rem 0.5rem; font-size: 0.8rem; float:right">🔔 Ver</button>
                            </a>
                        </div>
               
                    </div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <hr />
                    <!-- Más notificaciones aquí -->
                </div>
            </div>
            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div><?php }
}
