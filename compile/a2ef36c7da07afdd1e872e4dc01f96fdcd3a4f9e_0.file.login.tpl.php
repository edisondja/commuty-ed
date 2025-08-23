<?php
/* Smarty version 4.5.3, created on 2024-09-07 19:19:53
  from 'C:\xampp\htdocs\ventasrd\template\login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66dc8b399b40c0_54956955',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a2ef36c7da07afdd1e872e4dc01f96fdcd3a4f9e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\login.tpl',
      1 => 1716680146,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66dc8b399b40c0_54956955 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="clave" name="clave" required>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100" id="login">Iniciar Sesión</button>
            </div>
        </div>
    </div>
</div><?php }
}
