/**
 * Script para activar vista previa de video en hover y touch
 * Cuando una publicación tiene video, reproduce el preview_tablero al pasar el mouse o tocar
 */

document.addEventListener('DOMContentLoaded', function() {
    initBoardPreview();
});

/**
 * Inicializa la funcionalidad de vista previa para todas las imágenes de tableros
 */
function initBoardPreview() {
    const boardContainers = document.querySelectorAll('.board-image-container[data-has-preview="true"]');
    
    boardContainers.forEach(container => {
        // Evitar inicializar múltiples veces
        if (container.dataset.previewInit === 'true') {
            return;
        }
        container.dataset.previewInit = 'true';
        
        const video = container.querySelector('.board-preview-video');
        
        if (!video) {
            return;
        }
        
        // Evento hover para desktop - reproducir video
        container.addEventListener('mouseenter', function() {
            if (video) {
                video.currentTime = 0;
                video.play().catch(e => {
                    console.log('Error reproduciendo video preview:', e);
                });
            }
        });
        
        container.addEventListener('mouseleave', function() {
            if (video) {
                video.pause();
                video.currentTime = 0;
            }
        });
        
        // Eventos touch para móviles
        let touchStartTime = 0;
        let touchTimer = null;
        
        container.addEventListener('touchstart', function(e) {
            touchStartTime = Date.now();
            
            // Activar preview después de un breve delay
            touchTimer = setTimeout(() => {
                this.classList.add('touch-active');
                if (video) {
                    video.currentTime = 0;
                    video.play().catch(e => {
                        console.log('Error reproduciendo video preview en touch:', e);
                    });
                }
            }, 100);
        }, { passive: true });
        
        container.addEventListener('touchend', function(e) {
            const touchDuration = Date.now() - touchStartTime;
            
            // Si fue un toque rápido, mantener preview un poco más
            if (touchDuration < 300) {
                this.classList.add('touch-active');
                if (video) {
                    video.currentTime = 0;
                    video.play().catch(e => {});
                }
                
                // Mantener activo por 3 segundos
                setTimeout(() => {
                    this.classList.remove('touch-active');
                    if (video) {
                        video.pause();
                        video.currentTime = 0;
                    }
                }, 3000);
            } else {
                // Si fue un toque largo, desactivar al soltar
                this.classList.remove('touch-active');
                if (video) {
                    video.pause();
                    video.currentTime = 0;
                }
            }
            
            if (touchTimer) {
                clearTimeout(touchTimer);
            }
        }, { passive: true });
        
        container.addEventListener('touchcancel', function(e) {
            this.classList.remove('touch-active');
            if (video) {
                video.pause();
                video.currentTime = 0;
            }
            if (touchTimer) {
                clearTimeout(touchTimer);
            }
        }, { passive: true });
        
        // Desactivar preview cuando se hace scroll en móvil
        container.addEventListener('touchmove', function(e) {
            this.classList.remove('touch-active');
            if (video) {
                video.pause();
                video.currentTime = 0;
            }
            if (touchTimer) {
                clearTimeout(touchTimer);
            }
        }, { passive: true });
    });
}

/**
 * Reinicializa la vista previa para elementos agregados dinámicamente
 * Útil cuando se cargan nuevos tableros mediante AJAX
 */
function reinitBoardPreview() {
    initBoardPreview();
}

// Exportar función para uso global
if (typeof window !== 'undefined') {
    window.reinitBoardPreview = reinitBoardPreview;
}
