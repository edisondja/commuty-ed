<!-- Modal para Editar Publicación -->
<div class="modal fade" id="modal_update" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modern-edit-modal">
            <div class="modal-header modern-edit-header">
                <h5 class="modal-title" id="modalUpdateLabel">
                    <i class="fas fa-edit me-2"></i>Editar Publicación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <div class="modal-body modern-edit-body">
                <form id="formEditBoard">
                    <input type="hidden" id="edit_id_tablero" name="id_tablero">
                    <input type="hidden" id="edit_id_usuario" name="id_usuario">
                    
                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control modern-textarea" id="edit_descripcion" name="descripcion" rows="5" placeholder="Escribe la descripción de tu publicación..."></textarea>
                    </div>
                    
                    <!-- Imagen actual -->
                    <div class="mb-3">
                        <label class="form-label">Imagen Actual</label>
                        <div class="current-image-container">
                            <img id="edit_imagen_actual" src="" alt="Imagen actual" class="current-image-preview">
                            <input type="hidden" id="edit_imagen_actual_path" name="imagen_actual">
                        </div>
                    </div>
                    
                    <!-- Nueva imagen -->
                    <div class="mb-3">
                        <label for="edit_foto_portada" class="form-label">Cambiar Imagen (Opcional)</label>
                        <input type="file" class="form-control" id="edit_foto_portada" name="foto" accept="image/*">
                        <small class="form-text text-muted">Si no seleccionas una nueva imagen, se mantendrá la actual</small>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer modern-edit-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn modern-save-btn" id="btn_guardar_edicion" onclick="guardarEdicionBoard()">
                    <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.modern-edit-modal {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modern-edit-header {
    background: linear-gradient(135deg, #20c997 0%, #17a085 100%);
    color: white;
    border: none;
    padding: 20px 28px;
}

.modern-edit-body {
    background: #ffffff;
    padding: 28px;
}

.modern-edit-footer {
    background: #f7fafc;
    border-top: 1px solid #e2e8f0;
    padding: 20px 28px;
}

.current-image-container {
    text-align: center;
    padding: 15px;
    background: #f7fafc;
    border-radius: 12px;
    border: 2px dashed #e2e8f0;
}

.current-image-preview {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    object-fit: cover;
}

.modern-save-btn {
    background: linear-gradient(135deg, #20c997 0%, #17a085 100%);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 10px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(32, 201, 151, 0.4);
}

.modern-save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(32, 201, 151, 0.5);
    background: linear-gradient(135deg, #17a085 0%, #20c997 100%);
}
</style>
