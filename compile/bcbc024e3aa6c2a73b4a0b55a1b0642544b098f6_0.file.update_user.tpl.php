<?php
/* Smarty version 4.5.3, created on 2024-09-07 19:19:53
  from 'C:\xampp\htdocs\ventasrd\template\update_user.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66dc8b39a2e732_02670282',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bcbc024e3aa6c2a73b4a0b55a1b0642544b098f6' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\update_user.tpl',
      1 => 1717085550,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66dc8b39a2e732_02670282 (Smarty_Internal_Template $_smarty_tpl) {
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
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
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
