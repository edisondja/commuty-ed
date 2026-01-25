<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="reproductores"/>
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-play-circle"></i> Reproductores VAST</h2>
        <button class="bo-btn bo-btn-primary" data-bs-toggle="modal" data-bs-target="#modalReproductor" onclick="limpiarFormulario()">
            <i class="fa-solid fa-plus"></i> Nuevo Reproductor
        </button>
    </div>
    
    <div class="bo-card">
        <div class="bo-card-header">
            <h5><i class="fa-solid fa-list"></i> Lista de Reproductores</h5>
        </div>
        <div class="bo-card-body" style="padding: 0; overflow-x: auto;">
            <table class="bo-table" id="tablaReproductores">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Pre-roll VAST</th>
                        <th>Skip</th>
                        <th>Default</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaReproductores">
                    <tr>
                        <td colspan="7" class="bo-empty">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            <p>Cargando reproductores...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Reproductor -->
<div class="modal fade" id="modalReproductor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: #2d3436; color: white; border: 1px solid rgba(255,255,255,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title" id="modalReproductorLabel">
                    <i class="fa-solid fa-play-circle"></i> Configurar Reproductor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="reproductor_id">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Nombre del Reproductor *</label>
                        <input type="text" class="form-control" id="reproductor_nombre" placeholder="Ej: Reproductor Principal" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bo-label">Segundos para Saltar</label>
                        <input type="number" class="form-control" id="skip_delay" value="5" min="0" max="30" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="bo-label">Descripción</label>
                    <textarea class="form-control" id="reproductor_descripcion" rows="2" placeholder="Descripción del reproductor..." style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);"></textarea>
                </div>
                
                <hr style="border-color: rgba(255,255,255,0.1);">
                <h6 style="color: var(--bo-secondary);"><i class="fa-solid fa-ad"></i> Configuración VAST</h6>
                
                <div class="mb-3">
                    <label class="bo-label">
                        <i class="fa-solid fa-play" style="color: #51cf66;"></i> VAST Pre-Roll (Antes del video)
                    </label>
                    <input type="url" class="form-control" id="vast_url" placeholder="https://ejemplo.com/vast/preroll.xml" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                </div>
                
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="bo-label">
                            <i class="fa-solid fa-pause" style="color: #ffd43b;"></i> VAST Mid-Roll (Durante el video)
                        </label>
                        <input type="url" class="form-control" id="vast_url_mid" placeholder="https://ejemplo.com/vast/midroll.xml" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="bo-label">Segundo Mid-Roll</label>
                        <input type="number" class="form-control" id="mid_roll_time" value="30" min="10" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="bo-label">
                        <i class="fa-solid fa-stop" style="color: #ff6b6b;"></i> VAST Post-Roll (Después del video)
                    </label>
                    <input type="url" class="form-control" id="vast_url_post" placeholder="https://ejemplo.com/vast/postroll.xml" style="background: rgba(0,0,0,0.3); color: white; border-color: rgba(255,255,255,0.2);">
                </div>
                
                <hr style="border-color: rgba(255,255,255,0.1);">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="bo-switch">
                            <input type="checkbox" id="reproductor_activo" checked>
                            <label class="bo-label" style="margin: 0;">Reproductor Activo</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bo-switch">
                            <input type="checkbox" id="reproductor_default">
                            <label class="bo-label" style="margin: 0;">Usar como predeterminado</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <button type="button" class="bo-btn bo-btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="bo-btn bo-btn-primary" onclick="guardarReproductor()">
                    <i class="fa-solid fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{$dominio}/js/bk_modulo_reproductores.js"></script>
