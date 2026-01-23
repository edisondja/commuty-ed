/**
 * Script para activar vista previa de video en hover y touch
 * Cuando una publicación tiene video, muestra el preview_tablero al pasar el mouse o tocar
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
        const previewSrc = container.getAttribute('data-preview');
        const mainImage = container.querySelector('.board-main-image');
        
        if (!previewSrc || previewSrc === '' || !mainImage) {
            return;
        }
        
        // Construir la URL completa del preview
        const dominio = document.getElementById('dominio')?.value || '';
        const previewUrl = previewSrc.startsWith('http') ? previewSrc : `${dominio}/${previewSrc.replace(/^\//, '')}`;
        
        // Establecer el background-image del pseudo-elemento ::after
        container.style.setProperty('--preview-url', `url(${previewUrl})`);
        
        // Agregar estilos dinámicos si no existen
        if (!document.getElementById('board-preview-styles')) {
            const style = document.createElement('style');
            style.id = 'board-preview-styles';
            style.textContent = `
                .board-image-container[data-has-preview="true"]::after {
                    background-image: var(--preview-url);
                }
            `;
            document.head.appendChild(style);
        }
        
        // Evento hover para desktop
        container.addEventListener('mouseenter', function() {
            if (previewSrc && previewSrc !== '') {
                this.classList.add('hover-active');
                // Asegurar que el preview se muestre
                this.style.setProperty('--preview-url', `url(${previewUrl})`);
            }
        });
        
        container.addEventListener('mouseleave', function() {
            this.classList.remove('hover-active');
        });
        
        // Eventos touch para móviles
        let touchStartTime = 0;
        let touchTimer = null;
        
        container.addEventListener('touchstart', function(e) {
            touchStartTime = Date.now();
            
            // Activar preview después de un breve delay para evitar activación accidental
            touchTimer = setTimeout(() => {
                if (previewSrc && previewSrc !== '') {
                    this.classList.add('touch-active');
                    this.style.setProperty('--preview-url', `url(${previewUrl})`);
                }
            }, 100);
        }, { passive: true });
        
        container.addEventListener('touchend', function(e) {
            const touchDuration = Date.now() - touchStartTime;
            
            // Si fue un toque rápido, podría ser un click, mantener preview un poco más
            if (touchDuration < 300) {
                if (previewSrc && previewSrc !== '') {
                    this.classList.add('touch-active');
                    this.style.setProperty('--preview-url', `url(${previewUrl})`);
                    
                    // Mantener activo por 2 segundos
                    setTimeout(() => {
                        this.classList.remove('touch-active');
                    }, 2000);
                }
            } else {
                // Si fue un toque largo, desactivar al soltar
                this.classList.remove('touch-active');
            }
            
            if (touchTimer) {
                clearTimeout(touchTimer);
            }
        }, { passive: true });
        
        container.addEventListener('touchcancel', function(e) {
            this.classList.remove('touch-active');
            if (touchTimer) {
                clearTimeout(touchTimer);
            }
        }, { passive: true });
        
        // Desactivar preview cuando se hace scroll en móvil
        let scrollTimer = null;
        container.addEventListener('touchmove', function(e) {
            this.classList.remove('touch-active');
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
