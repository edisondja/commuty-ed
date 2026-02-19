<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="config"/>
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-gear"></i> Configuración del Sitio</h2>
    </div>
    
    <!-- Tabs de navegación -->
    <div class="bo-tabs" id="configTabs">
        <button class="bo-tab active" onclick="mostrarTab('sitio')">
            <i class="fa-solid fa-globe"></i> Sitio
        </button>
        <button class="bo-tab" onclick="mostrarTab('correo')">
            <i class="fa-solid fa-envelope"></i> Correo SMTP
        </button>
        <button class="bo-tab" onclick="mostrarTab('opciones')">
            <i class="fa-solid fa-sliders"></i> Opciones
        </button>
    </div>
    
    <!-- Tab: Configuración del Sitio -->
    <div id="tab-sitio" class="tab-content-panel">
        <div class="row">
            <div class="col-md-6">
                <div class="bo-card">
                    <div class="bo-card-header">
                        <h5><i class="fa-solid fa-info-circle"></i> Información Básica</h5>
                    </div>
                    <div class="bo-card-body">
                        <div class="mb-3">
                            <label class="bo-label">Dominio</label>
                            <input type="text" class="form-control" id="dominio_c" placeholder="https://tudominio.com">
                        </div>
                        <div class="mb-3">
                            <label class="bo-label">Nombre del Sitio</label>
                            <input type="text" class="form-control" id="nombre_sitio" placeholder="Mi Sitio">
                        </div>
                        <div class="mb-3">
                            <label class="bo-label">Slogan</label>
                            <textarea class="form-control" id="descripcion_slogan" rows="2" placeholder="Tu slogan aquí..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="bo-label">Descripción del Sitio</label>
                            <textarea class="form-control" id="descripcion_sitio" rows="3" placeholder="Descripción para SEO..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="bo-label">Email del Sitio</label>
                            <input type="email" class="form-control" id="email_sitio" placeholder="contacto@tudominio.com">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="bo-card">
                    <div class="bo-card-header">
                        <h5><i class="fa-solid fa-image"></i> Imágenes del Sitio</h5>
                    </div>
                    <div class="bo-card-body">
                        <div class="mb-4">
                            <label class="bo-label">Logo del Sitio</label>
                            <p class="bo-label-small text-muted mb-1">Logo actual (el que ve el usuario)</p>
                            <div class="config-preview-wrap config-preview-logo mb-2">
                                <img id="logo_img" src="" alt="Logo actual" class="config-logo-img" onerror="this.style.display='none'; this.nextElementSibling && (this.nextElementSibling.style.display='flex');">
                                <span class="config-no-image" id="logo_placeholder" style="display: none;">Sin logo cargado</span>
                            </div>
                            <input type="file" class="form-control" id="sitio_logo" accept="image/*">
                            <small style="color: rgba(255,255,255,0.5);">Recomendado: 230x50 px</small>
                        </div>
                        <div class="mb-3">
                            <label class="bo-label">Favicon</label>
                            <p class="bo-label-small text-muted mb-1">Favicon actual (icono de la pestaña)</p>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="config-preview-wrap config-preview-favicon">
                                    <img id="favicon_img" src="" alt="Favicon actual" class="config-favicon-img" onerror="this.style.display='none'; this.nextElementSibling && (this.nextElementSibling.style.display='flex');">
                                    <span class="config-no-image config-no-image-sm" id="favicon_placeholder" style="display: none;">Sin favicon</span>
                                </div>
                                <span style="color: rgba(255,255,255,0.5);">Vista previa</span>
                            </div>
                            <input type="file" class="form-control" id="favicon" accept="image/*">
                        </div>
                    </div>
                </div>
                
                <div class="bo-card">
                    <div class="bo-card-header">
                        <h5><i class="fa-solid fa-copyright"></i> Copyright y SEO</h5>
                    </div>
                    <div class="bo-card-body">
                        <div class="mb-3">
                            <label class="bo-label">Texto de Copyright</label>
                            <textarea class="form-control" id="copyright_descripcion" rows="2" placeholder="© 2024 Tu Empresa"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="bo-label">Hashtags de Búsqueda</label>
                            <input type="text" class="form-control" id="busqueda_hastag" placeholder="#hashtag1, #hashtag2">
                        </div>
                        <div class="mb-3">
                            <label class="bo-label">
                                <i class="fa-brands fa-google"></i> Google Analytics ID
                            </label>
                            <input type="text" class="form-control" id="google_analytics_id" placeholder="G-XXXXXXXXXX o UA-XXXXXXXXX-X">
                            <small style="color: rgba(255,255,255,0.5); display: block; margin-top: 5px;">
                                Ingresa tu ID de medición de Google Analytics (GA4 o Universal Analytics)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bo-card">
            <div class="bo-card-header">
                <h5><i class="fa-solid fa-file-alt"></i> Descripciones Adicionales</h5>
            </div>
            <div class="bo-card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="bo-label">Descripción de Búsqueda</label>
                        <textarea class="form-control" id="busqueda_descripcion" rows="3"></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="bo-label">Descripción de Página</label>
                        <textarea class="form-control" id="pagina_descripcion" rows="3"></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="bo-label">Descripción del Título</label>
                        <textarea class="form-control" id="titulo_descripcion" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button class="bo-btn bo-btn-primary" id="guardar_config">
                <i class="fa-solid fa-save"></i> Guardar Configuración
            </button>
        </div>
    </div>
    
    <!-- Tab: Configuración de Correo -->
    <div id="tab-correo" class="tab-content-panel" style="display: none;">
        <div class="bo-card">
            <div class="bo-card-header">
                <h5><i class="fa-solid fa-server"></i> Servidor SMTP</h5>
            </div>
            <div class="bo-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Email Remitente</label>
                        <input type="email" class="form-control" id="email_remitente" placeholder="noreply@tudominio.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Nombre del Remitente</label>
                        <input type="text" class="form-control" id="nombre_remitente" placeholder="Mi Sitio">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Servidor SMTP</label>
                        <input type="text" class="form-control" id="servidor_smtp" placeholder="smtp.gmail.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Puerto SMTP</label>
                        <input type="number" class="form-control" id="puerto_smtp" placeholder="587">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Usuario SMTP</label>
                        <input type="text" class="form-control" id="usuario_smtp" placeholder="usuario@gmail.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Contraseña SMTP</label>
                        <input type="password" class="form-control" id="contrasena_smtp" placeholder="••••••••">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Autenticación SSL</label>
                        <select class="form-control" id="autenticacion_ssl">
                            <option value="si">Sí (TLS/SSL)</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button class="bo-btn bo-btn-primary" id="guardar_config_correo">
                <i class="fa-solid fa-save"></i> Guardar Configuración de Correo
            </button>
        </div>
    </div>
    
    <!-- Tab: Opciones -->
    <div id="tab-opciones" class="tab-content-panel" style="display: none;">
        <div class="bo-card">
            <div class="bo-card-header">
                <h5><i class="fa-solid fa-toggle-on"></i> Opciones del Sistema</h5>
            </div>
            <div class="bo-card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="bo-switch">
                            <input type="checkbox" id="publicar_sin_revision">
                            <div>
                                <label class="bo-label" style="margin-bottom: 0;">Publicar sin Revisión</label>
                                <small style="color: rgba(255,255,255,0.5); display: block;">Las publicaciones requieren aprobación del admin</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="bo-switch">
                            <input type="checkbox" id="verificar_cuenta">
                            <div>
                                <label class="bo-label" style="margin-bottom: 0;">Verificar Cuenta por Email</label>
                                <small style="color: rgba(255,255,255,0.5); display: block;">Los usuarios deben verificar su email</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="bo-switch">
                            <input type="checkbox" id="rabbit_mq">
                            <div>
                                <label class="bo-label" style="margin-bottom: 0;">RabbitMQ</label>
                                <small style="color: rgba(255,255,255,0.5); display: block;">Colas de mensajes para procesos asíncronos</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="bo-switch">
                            <input type="checkbox" id="ffmpeg">
                            <div>
                                <label class="bo-label" style="margin-bottom: 0;">FFmpeg</label>
                                <small style="color: rgba(255,255,255,0.5); display: block;">Procesamiento de video y generación de previews</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="bo-switch">
                            <input type="checkbox" id="redis_cache">
                            <div>
                                <label class="bo-label" style="margin-bottom: 0;">Redis Cache</label>
                                <small style="color: rgba(255,255,255,0.5); display: block;">Caché en memoria para mejor rendimiento</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button class="bo-btn bo-btn-primary" id="guardar_config">
                <i class="fa-solid fa-save"></i> Guardar Opciones
            </button>
        </div>
    </div>
</div>

<style>
.bo-label-small { font-size: 0.8125rem; margin-bottom: 0.25rem; }
.config-preview-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.06);
    border: 1px dashed rgba(255,255,255,0.2);
    border-radius: 10px;
    min-height: 60px;
}
.config-preview-logo { min-height: 70px; padding: 12px; }
.config-logo-img {
    max-width: 100%;
    max-height: 50px;
    width: auto;
    object-fit: contain;
}
.config-preview-favicon {
    width: 48px;
    height: 48px;
    min-height: 48px;
    padding: 6px;
}
.config-favicon-img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}
.config-no-image {
    color: rgba(255,255,255,0.45);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
}
.config-no-image-sm { font-size: 0.75rem; }
</style>
<script>
function mostrarTab(tab) {
    // Ocultar todos los paneles
    document.querySelectorAll('.tab-content-panel').forEach(p => p.style.display = 'none');
    // Desactivar todos los tabs
    document.querySelectorAll('.bo-tab').forEach(t => t.classList.remove('active'));
    
    // Mostrar panel seleccionado
    document.getElementById('tab-' + tab).style.display = 'block';
    // Activar tab clickeado
    event.target.closest('.bo-tab').classList.add('active');
}
</script>

<script src="{$dominio}/js/bk_modulo_configuracion.js"></script>
