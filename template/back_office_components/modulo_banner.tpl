<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="banners"/>
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-rectangle-ad"></i> Administrador de Banners</h2>
    </div>
    
    <!-- Tabs -->
    <div class="bo-tabs">
        <button class="bo-tab active" onclick="mostrarTabBanner('crear')">
            <i class="fa-solid fa-plus"></i> Crear Banner
        </button>
        <button class="bo-tab" onclick="mostrarTabBanner('administrar')">
            <i class="fa-solid fa-list"></i> Administrar
        </button>
    </div>
    
    <!-- Tab: Crear Banner -->
    <div id="tab-crear" class="tab-content-banner">
        <div class="bo-card">
            <div class="bo-card-header">
                <h5><i class="fa-solid fa-plus-circle"></i> Nuevo Banner</h5>
            </div>
            <div class="bo-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Título</label>
                        <input type="text" class="form-control" id="titulo_nuevo" placeholder="Título del banner">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Tipo de Banner</label>
                        <select class="form-control" id="tipo_nuevo">
                            <option value="">Seleccionar tipo...</option>
                            <option value="banner">Banner</option>
                            <option value="imagen">Imagen</option>
                            <option value="video">Video</option>
                            <option value="texto">Texto</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="bo-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_nuevo" rows="2" placeholder="Descripción del banner"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Imagen/Video</label>
                        <input type="file" class="form-control" id="imagen_banner_nuevo" accept="image/*,video/*">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Posición</label>
                        <input type="number" class="form-control" id="posicion_nuevo" min="1" placeholder="1">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="bo-label">Link (URL destino)</label>
                        <input type="url" class="form-control" id="link_nuevo" placeholder="https://ejemplo.com">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="bo-label">Script del Banner (opcional)</label>
                        <textarea class="form-control" id="script_nuevo" rows="3" placeholder="<script>...</script>"></textarea>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="bo-btn bo-btn-primary" id="guardar_ads">
                        <i class="fa-solid fa-save"></i> Guardar Banner
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab: Administrar Banners -->
    <div id="tab-administrar" class="tab-content-banner" style="display: none;">
        <div class="bo-card">
            <div class="bo-card-header">
                <h5><i class="fa-solid fa-list"></i> Listado de Banners</h5>
            </div>
            <div class="bo-card-body" style="padding: 0; overflow-x: auto;">
                <table class="bo-table" id="tablaAds">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Posición</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_banners">
                        <tr>
                            <td colspan="6" class="bo-empty">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                <p>Cargando banners...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Banner -->
<div class="modal fade" id="modalBanner" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: #2d3436; color: white; border: 1px solid rgba(255,255,255,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title"><i class="fa-solid fa-edit"></i> Editar Banner</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_banner">
                <input type="hidden" id="imagen_original">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Título</label>
                        <input type="text" class="form-control" id="titulo" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Tipo</label>
                        <select class="form-control" id="tipo" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                            <option value="banner">Banner</option>
                            <option value="imagen">Imagen</option>
                            <option value="video">Video</option>
                            <option value="texto">Texto</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="bo-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" rows="2" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen_banner" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                        <img id="previewImagen" src="" class="mt-2" style="max-width: 100px; display: none; border-radius: 5px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Posición</label>
                        <input type="number" class="form-control" id="posicion" min="1" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="bo-label">Link</label>
                        <input type="url" class="form-control" id="link_banner" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="bo-label">Script</label>
                        <textarea class="form-control" id="script_banner" rows="2" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div class="bo-switch">
                            <input type="checkbox" id="estado_banner">
                            <label class="bo-label" style="margin: 0;">Banner Activo</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <button type="button" class="bo-btn bo-btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="bo-btn bo-btn-primary" onclick="actualizar_ads()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarTabBanner(tab) {
    document.querySelectorAll('.tab-content-banner').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.bo-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + tab).style.display = 'block';
    event.target.closest('.bo-tab').classList.add('active');
}
</script>

<script src="{$dominio}/js/bk_modulo_banner.js"></script>
