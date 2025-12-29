<?php
/* Smarty version 3.1.48, created on 2025-12-29 04:01:34
  from '/opt/lampp/htdocs/commuty-ed/template/update_user.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_6951ef0ebde1e7_83565215',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a59074260162860d38d95f4a7a09bd6a256e237e' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/update_user.tpl',
      1 => 1756073357,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6951ef0ebde1e7_83565215 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModalLabel">Actualizar Datos de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateUserForm">
                    <!-- Otros Datos -->
                    <input value="<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
"  type="hidden" name="imagen_actual" id="imagen_actual">
                    <fieldset class="mb-3">
                        <legend>Foto y Biografia</legend>
                        <div class="mb-3">
                            <label for="foto_url" class="form-label">Imagen de perfil</label>
                            <input type="file" class="form-control" id="foto_url" name="foto_url">
                        </div>
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                        </div>
                    </fieldset>

                    <!-- Información Personal -->
                    <fieldset class="mb-3">
                        <legend>Información Personal</legend>
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario_form" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="sexo" class="form-label">Sexo</label>
                            <select class="form-control" id="sexo" name="sexo" required>
                                <option value="F">Masculino</option>
                                <option value="M">Femenino</option>
                            </select>
                        </div>
                    </fieldset>

                    <!-- Submit Button -->
                    <button type="submit" id="update_changes" class="btn btn-primary w-100">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php }
}
