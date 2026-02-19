<!-- Modal Login - Estilo moderno -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content login-modal-modern">
      <div class="login-modal-header">
        <div class="login-modal-icon">
          <i class="fa-solid fa-right-to-bracket"></i>
        </div>
        <h5 class="modal-title" id="loginModalLabel">Iniciar sesión</h5>
        <p class="login-modal-subtitle">Ingresa a tu cuenta</p>
        <button type="button" class="btn-close btn-close-white login-modal-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body login-modal-body">
        <form id="loginForm" class="login-form-modern">
          <div class="login-field">
            <label for="usuario" class="login-label">Usuario</label>
            <div class="login-input-wrap">
              <i class="fa-solid fa-user login-input-icon"></i>
              <input type="text" class="login-input" id="usuario" name="usuario" placeholder="Tu usuario" autocomplete="username" required>
            </div>
          </div>
          <div class="login-field">
            <label for="clave" class="login-label">Contraseña</label>
            <div class="login-input-wrap">
              <i class="fa-solid fa-lock login-input-icon"></i>
              <input type="password" class="login-input" id="clave" name="clave" placeholder="••••••••" autocomplete="current-password" required>
            </div>
          </div>
          <button type="submit" class="login-btn" id="login">
            <span class="login-btn-text">Entrar</span>
            <i class="fa-solid fa-arrow-right login-btn-icon"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
/* Contenedor del modal */
.login-modal-modern {
  border: none;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

/* Cabecera con gradiente */
.login-modal-header {
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
  color: #fff;
  padding: 1.75rem 1.5rem 1.5rem;
  position: relative;
  text-align: center;
}

.login-modal-icon {
  width: 48px;
  height: 48px;
  margin: 0 auto 0.75rem;
  background: rgba(255, 255, 255, 0.12);
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}

.login-modal-header .modal-title {
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0;
  letter-spacing: -0.02em;
}

.login-modal-subtitle {
  font-size: 0.8125rem;
  opacity: 0.85;
  margin: 0.25rem 0 0;
  font-weight: 400;
}

.login-modal-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  opacity: 0.8;
  filter: brightness(0) invert(1);
}
.login-modal-close:hover {
  opacity: 1;
}

/* Cuerpo */
.login-modal-body {
  padding: 1.5rem 1.5rem 1.75rem;
  background: #fff;
}

.login-form-modern {
  display: flex;
  flex-direction: column;
  gap: 1.125rem;
}

.login-field {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.login-label {
  font-size: 0.8125rem;
  font-weight: 600;
  color: #475569;
}

.login-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.login-input-icon {
  position: absolute;
  left: 1rem;
  color: #94a3b8;
  font-size: 0.9375rem;
  pointer-events: none;
  transition: color 0.2s ease;
}

.login-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.75rem;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  font-size: 0.9375rem;
  background: #f8fafc;
  color: #0f172a;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.login-input::placeholder {
  color: #94a3b8;
}

.login-input:hover {
  border-color: #cbd5e1;
  background: #fff;
}

.login-input:focus {
  outline: none;
  border-color: #0f172a;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
}

.login-input:focus + .login-input-icon,
.login-input-wrap:focus-within .login-input-icon {
  color: #0f172a;
}

/* Botón */
.login-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  width: 100%;
  margin-top: 0.25rem;
  padding: 0.875rem 1.25rem;
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  color: #fff;
  border: none;
  border-radius: 12px;
  font-size: 0.9375rem;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
}

.login-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 20px rgba(15, 23, 42, 0.25);
}

.login-btn:active {
  transform: translateY(0);
}

.login-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.login-btn-icon {
  font-size: 0.8125rem;
  transition: transform 0.2s ease;
}

.login-btn:hover .login-btn-icon {
  transform: translateX(3px);
}

</style>
