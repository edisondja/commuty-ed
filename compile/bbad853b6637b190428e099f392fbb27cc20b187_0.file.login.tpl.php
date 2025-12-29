<?php
/* Smarty version 3.1.48, created on 2025-12-29 04:01:34
  from '/opt/lampp/htdocs/commuty-ed/template/login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_6951ef0ebd49c1_90238569',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bbad853b6637b190428e099f392fbb27cc20b187' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/login.tpl',
      1 => 1758982106,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6951ef0ebd49c1_90238569 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content community-box">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold w-100 text-center" id="loginModalLabel">Iniciar Sesión</h5>
        <button type="button" class="btn-close custom-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="loginForm">
          <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" class="community-input" id="usuario" name="usuario" placeholder="Escribe tu usuario" required>
          </div>
          <div class="mb-3">
            <label for="clave" class="form-label">Contraseña</label>
            <input type="password" class="community-input" id="clave" name="clave" placeholder="••••••••" required>
          </div>
          <button type="submit" class="community-btn w-100 mt-3" id="login">
            Iniciar Sesión
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Caja principal */
.community-box {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(15px);
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  padding: 20px;
  border: 1px solid rgba(255,255,255,0.3);
}

/* Inputs */
.community-input {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #dcdcdc;
  border-radius: 12px;
  background: #fafafa;
  font-size: 14px;
  transition: all 0.2s ease-in-out;
}
.community-input:focus {
  border-color: #1f8f8c; /* Verde azulado */
  background: #fff;
  outline: none;
  box-shadow: 0 0 0 3px rgba(31,143,140,0.2);
}

/* Botón */
.community-btn {
  background: linear-gradient(135deg, #1f8f8c, #166c69); /* Verde azulado degradado */
  border: none;
  color: white;
  padding: 10px;
  font-size: 15px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
}
.community-btn:hover {
  background: linear-gradient(135deg, #166c69, #0f504b);
  transform: translateY(-1px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Cerrar custom */
.custom-close {
  background: transparent;
  border: none;
  font-size: 18px;
  opacity: 0.7;
}
.custom-close:hover {
  opacity: 1;
}
</style><?php }
}
