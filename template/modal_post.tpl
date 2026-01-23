<!-- Modal Moderno de Publicación -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modern-post-modal">
      
      <!-- Header Moderno -->
      <div class="modal-header modern-post-header">
        <div class="d-flex align-items-center w-100">
          <div class="modal-title-container">
            <h5 class="modal-title modern-title" id="exampleModalLabel">
              <i class="fas fa-edit me-2"></i>Crear Publicación
            </h5>
            <p class="modern-subtitle mb-0">Comparte tus pensamientos con la comunidad</p>
          </div>
          <button type="button" class="btn-close btn-close-white modern-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      
      <!-- Body Moderno -->
      <div class="modal-body modern-post-body">
        
        <!-- Perfil del Usuario -->
        <div class="user-profile-section">
          <div class="d-flex align-items-center">
            <div class="user-avatar-wrapper">
              <img src="{$foto_perfil}" class="user-avatar" alt="{$user_session}">
              <div class="avatar-ring"></div>
            </div>
            <div class="user-info">
              <h6 class="user-name mb-0">{$user_session}</h6>
              <span class="user-badge">
                <i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>
                En línea
              </span>
            </div>
          </div>
        </div>
        
        <!-- Textarea Moderno -->
        <div class="post-content-section">
          <div class="textarea-wrapper">
            <textarea 
              class="form-control modern-textarea" 
              id="board_title" 
              rows="6" 
              placeholder="¿Qué estás pensando? Comparte tus ideas, experiencias o momentos especiales..."
              maxlength="5000"></textarea>
            <div class="textarea-footer">
              <span class="char-counter">
                <span id="char_count">0</span>/5000
              </span>
              <div class="textarea-actions">
                <button class="btn-text-action" type="button" title="Formato de texto">
                  <i class="fas fa-bold"></i>
                </button>
                <button class="btn-text-action" type="button" title="Emojis">
                  <i class="far fa-smile"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Barra de Progreso Moderna -->
        <div class="upload-progress-section" id="progress_section" style="display: none;">
          <div class="progress-wrapper">
            <div class="progress-info">
              <span class="progress-label">Subiendo archivos...</span>
              <span class="progress-percentage" id="porcentaje">0%</span>
            </div>
            <div class="progress modern-progress">
              <div class="progress-bar modern-progress-bar" id="file" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
        
        <!-- Vista Previa de Multimedia -->
        <div id="multimedia_view" class="multimedia-preview-section"></div>
        
      </div>
      
      <!-- Footer Moderno -->
      <div class="modal-footer modern-post-footer">
        <div class="footer-actions">
          
          <!-- Botones de Acción -->
          <div class="action-buttons">
            <input accept="image/png,image/jpeg,video/*,audio/*" type="file" id="upload_images" style="display:none" multiple name="imagenes[]" />
            
            <button type="button" class="action-btn media-btn" id="upload_image" title="Subir imágenes o videos">
              <i class="fas fa-image"></i>
              <span class="action-label">Media</span>
            </button>
            
            <button type="button" class="action-btn emoji-btn" title="Agregar emoji">
              <i class="far fa-smile"></i>
              <span class="action-label">Emoji</span>
            </button>
            
            <button type="button" class="action-btn location-btn" title="Agregar ubicación">
              <i class="fas fa-map-marker-alt"></i>
              <span class="action-label">Ubicación</span>
            </button>
          </div>
          
          <!-- Botón de Publicar -->
          <button class="btn modern-post-btn" id="post" type="button">
            <i class="fas fa-paper-plane me-2"></i>
            <span>Publicar</span>
            <div class="btn-shine"></div>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Modal Moderno */
.modern-post-modal {
  border: none;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  background: linear-gradient(135deg, #20c997 0%, #17a085 100%);
}

.modern-post-header {
  background: linear-gradient(135deg, #20c997 0%, #17a085 100%);
  color: white;
  border: none;
  padding: 24px 28px;
  position: relative;
  overflow: hidden;
}

.modern-post-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
  opacity: 0.3;
}

.modern-post-header .d-flex {
  position: relative;
  z-index: 1;
}

.modern-title {
  font-size: 24px;
  font-weight: 700;
  margin: 0;
  display: flex;
  align-items: center;
}

.modern-subtitle {
  font-size: 13px;
  opacity: 0.9;
  margin-top: 4px;
  font-weight: 400;
}

.modern-close-btn {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  width: 36px;
  height: 36px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.modern-close-btn:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: rotate(90deg);
}

.modern-post-body {
  background: #ffffff;
  padding: 28px;
  min-height: 300px;
}

/* Sección de Perfil */
.user-profile-section {
  margin-bottom: 24px;
  padding-bottom: 20px;
  border-bottom: 1px solid #e9ecef;
}

.user-avatar-wrapper {
  position: relative;
  margin-right: 12px;
}

.user-avatar {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.avatar-ring {
  position: absolute;
  top: -3px;
  left: -3px;
  width: 62px;
  height: 62px;
  border-radius: 50%;
  border: 2px solid #20c997;
  animation: pulse-ring 2s infinite;
}

@keyframes pulse-ring {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.7;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.user-info {
  flex: 1;
}

.user-name {
  font-size: 16px;
  font-weight: 600;
  color: #2d3748;
}

.user-badge {
  font-size: 12px;
  color: #718096;
  display: flex;
  align-items: center;
}

/* Textarea Moderno */
.post-content-section {
  margin-bottom: 20px;
}

.textarea-wrapper {
  position: relative;
  background: #f7fafc;
  border-radius: 16px;
  padding: 4px;
  border: 2px solid #e2e8f0;
  transition: all 0.3s ease;
}

.textarea-wrapper:focus-within {
  border-color: #20c997;
  box-shadow: 0 0 0 4px rgba(32, 201, 151, 0.1);
  background: #fff;
}

.modern-textarea {
  border: none;
  background: transparent;
  resize: none;
  padding: 16px;
  font-size: 15px;
  line-height: 1.6;
  color: #2d3748;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.modern-textarea:focus {
  outline: none;
  box-shadow: none;
}

.modern-textarea::placeholder {
  color: #a0aec0;
  font-weight: 400;
}

.textarea-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 16px;
  border-top: 1px solid #e2e8f0;
  margin-top: 4px;
}

.char-counter {
  font-size: 12px;
  color: #718096;
  font-weight: 500;
}

.textarea-actions {
  display: flex;
  gap: 8px;
}

.btn-text-action {
  background: none;
  border: none;
  color: #718096;
  padding: 6px 10px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 14px;
}

.btn-text-action:hover {
  background: #e2e8f0;
  color: #20c997;
}

/* Progreso Moderno */
.upload-progress-section {
  margin: 20px 0;
  padding: 16px;
  background: #f7fafc;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}

.progress-wrapper {
  width: 100%;
}

.progress-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.progress-label {
  font-size: 13px;
  font-weight: 600;
  color: #2d3748;
}

.progress-percentage {
  font-size: 13px;
  font-weight: 700;
  color: #20c997;
}

.modern-progress {
  height: 8px;
  border-radius: 10px;
  background: #e2e8f0;
  overflow: hidden;
}

.modern-progress-bar {
  background: linear-gradient(90deg, #20c997 0%, #17a085 100%);
  border-radius: 10px;
  transition: width 0.3s ease;
  position: relative;
  overflow: hidden;
}

.modern-progress-bar::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

/* Vista Previa Multimedia */
.multimedia-preview-section {
  margin-top: 16px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  gap: 12px;
}

.multimedia-item {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  border: 2px solid #e2e8f0;
  background: #f7fafc;
  transition: all 0.3s ease;
}

.multimedia-item:hover {
  border-color: #20c997;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(32, 201, 151, 0.2);
}

.multimedia-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: 1;
}

.multimedia-item:hover .multimedia-overlay {
  opacity: 1;
}

.multimedia-delete-btn {
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #fc8181;
  font-size: 14px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.multimedia-delete-btn:hover {
  background: #fc8181;
  color: white;
  transform: scale(1.1);
}

.multimedia-preview {
  width: 100%;
  height: 120px;
  object-fit: cover;
  display: block;
}

/* Footer Moderno */
.modern-post-footer {
  background: #f7fafc;
  border-top: 1px solid #e2e8f0;
  padding: 20px 28px;
  border-radius: 0 0 20px 20px;
}

.footer-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.action-buttons {
  display: flex;
  gap: 12px;
}

.action-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 4px;
  background: #fff;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 10px 16px;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #718096;
  font-size: 12px;
  min-width: 70px;
}

.action-btn:hover {
  border-color: #20c997;
  color: #20c997;
  background: #f0fff4;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(32, 201, 151, 0.2);
}

.action-btn i {
  font-size: 18px;
  margin-bottom: 2px;
}

.action-label {
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.media-btn:hover {
  border-color: #48bb78;
  color: #48bb78;
  background: #f0fff4;
}

.emoji-btn:hover {
  border-color: #f6ad55;
  color: #f6ad55;
  background: #fffaf0;
}

.location-btn:hover {
  border-color: #fc8181;
  color: #fc8181;
  background: #fff5f5;
}

/* Botón de Publicar Moderno */
.modern-post-btn {
  background: linear-gradient(135deg, #20c997 0%, #17a085 100%);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 12px 32px;
  font-weight: 600;
  font-size: 15px;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(32, 201, 151, 0.4);
}

.modern-post-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(32, 201, 151, 0.5);
  background: linear-gradient(135deg, #17a085 0%, #20c997 100%);
}

.modern-post-btn:active {
  transform: translateY(0);
}

.modern-post-btn .btn-shine {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  transition: left 0.5s;
}

.modern-post-btn:hover .btn-shine {
  left: 100%;
}

.modern-post-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

/* Animaciones */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modern-post-modal {
  animation: fadeIn 0.3s ease;
}

/* Responsive */
@media (max-width: 768px) {
  .modal-dialog {
    margin: 10px;
  }
  
  .modern-post-header,
  .modern-post-body,
  .modern-post-footer {
    padding: 20px;
  }
  
  .action-label {
    display: none;
  }
  
  .action-btn {
    min-width: 50px;
    padding: 12px;
  }
  
  .footer-actions {
    flex-direction: column;
    gap: 16px;
  }
  
  .action-buttons {
    width: 100%;
    justify-content: space-around;
  }
  
  .modern-post-btn {
    width: 100%;
    justify-content: center;
  }
}

/* Dark mode support (opcional) */
@media (prefers-color-scheme: dark) {
  .modern-post-body {
    background: #1a202c;
    color: #e2e8f0;
  }
  
  .modern-textarea {
    color: #e2e8f0;
  }
  
  .textarea-wrapper {
    background: #2d3748;
    border-color: #4a5568;
  }
  
  .user-name {
    color: #e2e8f0;
  }
}
</style>

<script>
// Contador de caracteres
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('board_title');
    const charCount = document.getElementById('char_count');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            if (length > 4500) {
                charCount.style.color = '#fc8181';
            } else if (length > 4000) {
                charCount.style.color = '#f6ad55';
            } else {
                charCount.style.color = '#718096';
            }
        });
    }
    
    // Actualizar barra de progreso
    const progressBar = document.getElementById('file');
    const progressSection = document.getElementById('progress_section');
    const porcentaje = document.getElementById('porcentaje');
    
    if (progressBar && progressSection && porcentaje) {
        // Esta función será llamada desde BoardOperation.js
        window.updateUploadProgress = function(percent) {
            if (percent > 0) {
                progressSection.style.display = 'block';
                progressBar.style.width = percent + '%';
                progressBar.setAttribute('aria-valuenow', percent);
                porcentaje.textContent = Math.round(percent) + '%';
            } else {
                progressSection.style.display = 'none';
            }
        };
    }
});
</script>
