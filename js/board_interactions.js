/**
 * Sistema de Interacciones para Publicaciones
 * Maneja: Editar, Me Gusta, Comentar y Compartir desde la lista de publicaciones
 */

// Base URL para subdirectorios (XAMPP)
var baseUrl = window.BASE_URL || '';

document.addEventListener('DOMContentLoaded', function() {
    initBoardInteractions();
});

/**
 * Inicializa todas las interacciones
 */
function initBoardInteractions() {
    initEditBoard();
    initLikeBoard();
    initCommentBoard();
    initShareBoard();
    loadLikesForBoards();
}

/**
 * Inicializa la funcionalidad de editar publicaciones
 */
function initEditBoard() {
    const editIcons = document.querySelectorAll('.edit-icon[data-value]');
    
    editIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const idTablero = this.getAttribute('data-value');
            loadBoardDataForEdit(idTablero);
        });
    });
}

/**
 * Carga los datos del tablero para editar
 */
function loadBoardDataForEdit(idTablero) {
    const dominio = document.getElementById('dominio')?.value || '';
    
    axios.get(baseUrl + '/controllers/actions_board.php', {
        params: {
            action: 'cargar_un_tablero',
            id_tablero: idTablero
        }
    })
    .then(response => {
        let data = response.data;
        
        // Si es string, parsearlo
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data);
            } catch (e) {
                console.error('Error parsing board data:', e);
                // Intentar obtener datos del DOM directamente
                const boardCard = document.querySelector(`#board${idTablero}`);
                if (boardCard) {
                    const descripcion = boardCard.querySelector('.description-text')?.textContent || '';
                    const imagen = boardCard.querySelector('.board-image')?.src || '';
                    
                    document.getElementById('edit_id_tablero').value = idTablero;
                    document.getElementById('edit_id_usuario').value = document.getElementById('id_usuario')?.value || '';
                    document.getElementById('edit_descripcion').value = descripcion;
                    
                    if (imagen) {
                        document.getElementById('edit_imagen_actual').src = imagen;
                        // Extraer path relativo
                        let path = imagen;
                        try {
                            const url = new URL(imagen, window.location.origin);
                            path = url.pathname.replace(/^\//, '');
                        } catch(e) {
                            path = imagen.replace(/^\//, '');
                        }
                        document.getElementById('edit_imagen_actual_path').value = path;
                    }
                    
                    const modal = new bootstrap.Modal(document.getElementById('modal_update'));
                    modal.show();
                }
                return;
            }
        }
        
        // Llenar el modal con los datos
        document.getElementById('edit_id_tablero').value = data.id_tablero || idTablero;
        document.getElementById('edit_id_usuario').value = document.getElementById('id_usuario')?.value || '';
        document.getElementById('edit_descripcion').value = data.descripcion || '';
        
        // Imagen actual
        if (data.imagen_tablero) {
            const dominio = document.getElementById('dominio')?.value || '';
            let imagenPath = data.imagen_tablero.startsWith('/') ? data.imagen_tablero.substring(1) : data.imagen_tablero;
            imagenPath = imagenPath.replace(/^\/+/, '');
            
            document.getElementById('edit_imagen_actual').src = `/${imagenPath}`;
            document.getElementById('edit_imagen_actual_path').value = imagenPath;
        } else {
            // Si no hay imagen, ocultar el contenedor
            document.querySelector('.current-image-container').style.display = 'none';
        }
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('modal_update'));
        modal.show();
    })
    .catch(error => {
        console.error('Error cargando datos del tablero:', error);
        // Intentar obtener datos del DOM directamente como fallback
        const boardCard = document.querySelector(`#board${idTablero}`);
        if (boardCard) {
            const descripcion = boardCard.querySelector('.description-text')?.textContent || '';
            const imagen = boardCard.querySelector('.board-image')?.src || '';
            
            document.getElementById('edit_id_tablero').value = idTablero;
            document.getElementById('edit_id_usuario').value = document.getElementById('id_usuario')?.value || '';
            document.getElementById('edit_descripcion').value = descripcion;
            
            if (imagen) {
                document.getElementById('edit_imagen_actual').src = imagen;
                let path = imagen;
                try {
                    const url = new URL(imagen, window.location.origin);
                    path = url.pathname.replace(/^\//, '');
                } catch(e) {
                    path = imagen.replace(/^\//, '');
                }
                document.getElementById('edit_imagen_actual_path').value = path;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('modal_update'));
            modal.show();
        } else {
            if (typeof alertify !== 'undefined') {
                alertify.error('Error al cargar los datos de la publicación');
            }
        }
    });
}

/**
 * Guarda los cambios de la edición
 */
function guardarEdicionBoard() {
    const idTablero = document.getElementById('edit_id_tablero').value;
    const idUsuario = document.getElementById('edit_id_usuario').value;
    const descripcion = document.getElementById('edit_descripcion').value;
    const foto = document.getElementById('edit_foto_portada').files[0];
    const imagenActual = document.getElementById('edit_imagen_actual_path').value;
    
    if (!descripcion || descripcion.trim() === '') {
        if (typeof alertify !== 'undefined') {
            alertify.warning('La descripción no puede estar vacía');
        } else {
            alert('La descripción no puede estar vacía');
        }
        return;
    }
    
    const dominio = document.getElementById('dominio')?.value || '';
    const FormData = new FormData();
    FormData.append('action', 'update_board');
    FormData.append('id_tablero', idTablero);
    FormData.append('id_usuario', idUsuario);
    FormData.append('descripcion', descripcion);
    
    if (foto) {
        FormData.append('foto', foto);
    } else {
        FormData.append('imagen_actual', imagenActual);
    }
    
    axios.post(baseUrl + '/controllers/actions_board.php', FormData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {
        // Actualizar la descripción en la página
        const descElement = document.querySelector(`#text${idTablero}`);
        if (descElement) {
            descElement.textContent = descripcion;
        }
        
        // Actualizar la imagen si se cambió
        if (foto) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.querySelector(`#board${idTablero} .board-image`);
                if (imgElement) {
                    imgElement.src = e.target.result;
                }
            };
            reader.readAsDataURL(foto);
        }
        
        // Cerrar modal
        const modalElement = document.getElementById('modal_update');
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
        
        if (typeof alertify !== 'undefined') {
            alertify.success('Publicación actualizada correctamente');
        }
        
        // Recargar la página después de un breve delay
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    })
    .catch(error => {
        console.error('Error guardando cambios:', error);
        if (typeof alertify !== 'undefined') {
            alertify.error('Error al guardar los cambios');
        }
    });
}

/**
 * Inicializa la funcionalidad de Me Gusta
 */
function initLikeBoard() {
    // Usar delegación de eventos para los likes
    document.addEventListener('click', function(e) {
        if (e.target.closest('.like-icon')) {
            const likeIcon = e.target.closest('.like-icon');
            // En vista de post individual el like lo maneja single_board.js (#like + #likes_c)
            if (likeIcon.id === 'like' && document.getElementById('likes_c')) return;
            e.preventDefault();
            e.stopPropagation();
            const idTablero = likeIcon.getAttribute('data-tablero') || (() => {
                const boardCard = likeIcon.closest('.card-board');
                return boardCard ? boardCard.id.replace('board', '') : null;
            })();
            if (idTablero) {
                toggleLike(idTablero, likeIcon);
            }
        }
    });
}

/**
 * Alterna el estado de Me Gusta
 */
function toggleLike(idTablero, likeIcon) {
    const dominio = document.getElementById('dominio')?.value || '';
    const idUsuario = document.getElementById('id_usuario')?.value;
    
    if (!idUsuario || idUsuario == '0') {
        if (typeof alertify !== 'undefined') {
            alertify.warning('Debes iniciar sesión para dar me gusta');
        } else {
            alert('Debes iniciar sesión para dar me gusta');
        }
        return;
    }
    
    const FormData = new FormData();
    FormData.append('action', 'save_like');
    FormData.append('id_tablero', idTablero);
    FormData.append('id_usuario', idUsuario);
    
    axios.post(baseUrl + '/controllers/actions_board.php', FormData)
        .then(response => {
            const data = response.data;
            var delta = 0;
            
            if (data && typeof data === 'string') {
                const responseText = data.trim();
                if (responseText === '_success' || responseText === 'activo_success') {
                    delta = 1;
                    likeIcon.classList.remove('fa-regular');
                    likeIcon.classList.add('fa-solid');
                    likeIcon.style.color = '#20c997';
                } else if (responseText === 'inactivo_success') {
                    delta = -1;
                    likeIcon.classList.remove('fa-solid');
                    likeIcon.classList.add('fa-regular');
                    likeIcon.style.color = '#20c997';
                }
            } else if (data && typeof data === 'object') {
                if (data.success || data.status === 'activo') {
                    delta = 1;
                    likeIcon.classList.remove('fa-regular');
                    likeIcon.classList.add('fa-solid');
                    likeIcon.style.color = '#20c997';
                } else {
                    delta = -1;
                    likeIcon.classList.remove('fa-solid');
                    likeIcon.classList.add('fa-regular');
                    likeIcon.style.color = '#20c997';
                }
            }
            var likesC = document.getElementById('likes_c');
            if (likesC && delta !== 0) {
                var txt = likesC.textContent || '';
                var num = parseInt(txt.replace(/\D/g, ''), 10) || 0;
                num = Math.max(0, num + delta);
                likesC.textContent = num;
            }
        })
        .catch(error => {
            console.error('Error dando like:', error);
            if (typeof alertify !== 'undefined') {
                alertify.error('Error al dar me gusta');
            }
        });
}

/**
 * Carga los likes para todas las publicaciones
 */
function loadLikesForBoards() {
    const boardCards = document.querySelectorAll('.card-board[id^="board"]');
    const dominio = document.getElementById('dominio')?.value || '';
    const idUsuario = document.getElementById('id_usuario')?.value || 0;
    
    boardCards.forEach(card => {
        const idTablero = card.id.replace('board', '');
        const likeIcon = card.querySelector('.like-icon');
        
        if (!likeIcon || !idTablero) return;
        
        // Verificar si el usuario ya dio like
        if (idUsuario && idUsuario != '0') {
            axios.get(baseUrl + '/controllers/actions_board.php', {
                params: {
                    action: 'verificar_mi_like',
                    id_tablero: idTablero,
                    id_usuario: idUsuario
                }
            })
            .then(response => {
                let data = response.data;
                
                // Si es string, verificar directamente
                if (typeof data === 'string') {
                    if (data.includes('tiene_like')) {
                        likeIcon.classList.remove('fa-regular');
                        likeIcon.classList.add('fa-solid');
                        likeIcon.style.color = '#20c997';
                    }
                } 
                // Si es objeto, verificar la propiedad status
                else if (data && typeof data === 'object') {
                    if (data.status === 'tiene_like' || (typeof data.status === 'string' && data.status.includes('tiene_like'))) {
                        likeIcon.classList.remove('fa-regular');
                        likeIcon.classList.add('fa-solid');
                        likeIcon.style.color = '#20c997';
                    }
                }
            })
            .catch(error => {
                console.error('Error verificando like:', error);
            });
        }
        
        // Cargar cantidad de likes
        axios.get(baseUrl + '/controllers/actions_board.php', {
            params: {
                action: 'contar_likes_board',
                id_tablero: idTablero
            }
        })
        .then(response => {
            if (response.data && response.data.likes !== undefined) {
                // Opcional: mostrar contador de likes
                // Puedes agregar un span para mostrar el número
            }
        })
        .catch(error => {
            console.error('Error contando likes:', error);
        });
    });
}

/**
 * Inicializa la funcionalidad de comentar
 */
function initCommentBoard() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.comment-icon')) {
            e.preventDefault();
            e.stopPropagation();
            
            const commentIcon = e.target.closest('.comment-icon');
            let idTablero = commentIcon.getAttribute('data-tablero');
            
            // Si no tiene data-tablero, intentar obtenerlo del card
            if (!idTablero) {
                const boardCard = commentIcon.closest('.card-board');
                idTablero = boardCard ? boardCard.id.replace('board', '') : null;
            }
            
            if (idTablero) {
                // Redirigir a la vista individual con scroll a comentarios
                const baseUrl = window.BASE_URL || '';
                window.location.href = `${baseUrl}/post/${idTablero}#coments`;
            }
        }
    });
}

/**
 * Inicializa la funcionalidad de compartir
 */
function initShareBoard() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.share-icon')) {
            e.preventDefault();
            e.stopPropagation();
            
            const shareIcon = e.target.closest('.share-icon');
            let idTablero = shareIcon.getAttribute('data-tablero');
            
            // Si no tiene data-tablero, intentar obtenerlo del card
            if (!idTablero) {
                const boardCard = shareIcon.closest('.card-board');
                idTablero = boardCard ? boardCard.id.replace('board', '') : null;
            }
            
            if (idTablero) {
                const boardCard = shareIcon.closest('.card-board');
                showShareModal(idTablero, boardCard);
            }
        }
    });
}

/**
 * Muestra el modal de compartir (diseño moderno)
 */
function showShareModal(idTablero, boardCard) {
    const baseUrl = window.BASE_URL || '';
    const dominio = document.getElementById('dominio')?.value || '';
    const origin = (dominio && dominio.indexOf('http') === 0) ? dominio.replace(/\/$/, '') : (window.location.origin + (baseUrl ? baseUrl : ''));
    const titulo = (boardCard && boardCard.querySelector && boardCard.querySelector('.description-text')) ? (boardCard.querySelector('.description-text').textContent || '').trim() : 'Publicación';
    const url = origin + '/post/' + idTablero;
    
    let shareModal = document.getElementById('shareModal');
    if (!shareModal) {
        shareModal = document.createElement('div');
        shareModal.id = 'shareModal';
        shareModal.className = 'modal fade';
        shareModal.setAttribute('tabindex', '-1');
        shareModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered share-modal-dialog">
                <div class="modal-content share-modal-content">
                    <div class="share-modal-header">
                        <span class="share-modal-icon"><i class="fa-solid fa-share-nodes"></i></span>
                        <h5 class="share-modal-title">Compartir publicación</h5>
                        <p class="share-modal-subtitle">Elige cómo quieres compartir el enlace</p>
                        <button type="button" class="btn-close btn-close-white share-modal-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body share-modal-body">
                        <div class="share-link-wrap">
                            <input type="text" class="share-link-input" id="share_url" readonly>
                            <button type="button" class="share-copy-btn" id="copy_url_btn" title="Copiar enlace">
                                <i class="fa-regular fa-copy share-copy-icon"></i>
                                <i class="fa-solid fa-check share-copy-done" style="display:none;"></i>
                                <span class="share-copy-text">Copiar</span>
                            </button>
                        </div>
                        <div class="share-native-wrap" id="share_native_wrap" style="display: none;">
                            <button type="button" class="share-native-btn" id="share_native_btn">
                                <i class="fa-solid fa-mobile-screen"></i>
                                <span>Compartir (app del dispositivo)</span>
                            </button>
                        </div>
                        <p class="share-divider"><span>O comparte en</span></p>
                        <div class="share-grid">
                            <button type="button" class="share-option share-btn" data-platform="facebook" title="Facebook">
                                <span class="share-option-icon"><i class="fa-brands fa-facebook-f"></i></span>
                                <span class="share-option-label">Facebook</span>
                            </button>
                            <button type="button" class="share-option share-btn" data-platform="twitter" title="X / Twitter">
                                <span class="share-option-icon"><i class="fa-brands fa-x-twitter"></i></span>
                                <span class="share-option-label">X</span>
                            </button>
                            <button type="button" class="share-option share-btn" data-platform="whatsapp" title="WhatsApp">
                                <span class="share-option-icon"><i class="fa-brands fa-whatsapp"></i></span>
                                <span class="share-option-label">WhatsApp</span>
                            </button>
                            <button type="button" class="share-option share-btn" data-platform="telegram" title="Telegram">
                                <span class="share-option-icon"><i class="fa-brands fa-telegram"></i></span>
                                <span class="share-option-label">Telegram</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        if (!document.getElementById('share-modal-styles')) {
            var style = document.createElement('style');
            style.id = 'share-modal-styles';
            style.textContent = `
                .share-modal-dialog { max-width: 420px; }
                .share-modal-content { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
                .share-modal-header {
                    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                    color: #fff;
                    padding: 1.5rem 1.25rem;
                    position: relative;
                    text-align: center;
                }
                .share-modal-icon {
                    width: 48px; height: 48px;
                    background: rgba(255,255,255,0.12);
                    border-radius: 14px;
                    display: inline-flex; align-items: center; justify-content: center;
                    font-size: 1.25rem;
                    margin-bottom: 0.75rem;
                }
                .share-modal-title { font-size: 1.125rem; font-weight: 700; margin: 0; }
                .share-modal-subtitle { font-size: 0.8125rem; opacity: 0.85; margin: 0.25rem 0 0; }
                .share-modal-close { position: absolute; top: 1rem; right: 1rem; opacity: 0.8; filter: brightness(0) invert(1); }
                .share-modal-body { padding: 1.25rem 1.25rem 1.5rem; }
                .share-link-wrap {
                    display: flex;
                    gap: 0.5rem;
                    margin-bottom: 1rem;
                }
                .share-link-input {
                    flex: 1;
                    padding: 0.625rem 0.875rem;
                    border: 1px solid #e2e8f0;
                    border-radius: 12px;
                    font-size: 0.875rem;
                    background: #f8fafc;
                }
                .share-copy-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.35rem;
                    padding: 0.625rem 0.875rem;
                    background: #0f172a;
                    color: #fff;
                    border: none;
                    border-radius: 12px;
                    font-size: 0.875rem;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background 0.2s, transform 0.15s;
                }
                .share-copy-btn:hover { background: #1e293b; }
                .share-copy-btn:active { transform: scale(0.98); }
                .share-native-wrap { margin-bottom: 1rem; }
                .share-native-btn {
                    width: 100%;
                    padding: 0.75rem 1rem;
                    background: #f1f5f9;
                    border: 1px dashed #cbd5e1;
                    border-radius: 12px;
                    font-size: 0.9375rem;
                    color: #475569;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                }
                .share-native-btn:hover { background: #e2e8f0; }
                .share-divider { text-align: center; margin: 1rem 0 0.75rem; font-size: 0.8125rem; color: #94a3b8; }
                .share-divider span { background: #fff; padding: 0 0.5rem; }
                .share-grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 0.75rem;
                }
                .share-option {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 0.35rem;
                    padding: 0.75rem 0.5rem;
                    border: 1px solid #e2e8f0;
                    border-radius: 14px;
                    background: #fff;
                    font-size: 0.75rem;
                    color: #475569;
                    cursor: pointer;
                    transition: transform 0.15s, box-shadow 0.15s;
                }
                .share-option:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
                .share-option-icon {
                    width: 40px; height: 40px;
                    border-radius: 12px;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 1.125rem;
                }
                .share-option[data-platform="facebook"] .share-option-icon { background: #1877f2; color: #fff; }
                .share-option[data-platform="twitter"] .share-option-icon { background: #0f172a; color: #fff; }
                .share-option[data-platform="whatsapp"] .share-option-icon { background: #25d366; color: #fff; }
                .share-option[data-platform="telegram"] .share-option-icon { background: #0088cc; color: #fff; }
            `;
            document.head.appendChild(style);
        }
        document.body.appendChild(shareModal);
        
        var copyBtn = document.getElementById('copy_url_btn');
        var copyText = copyBtn.querySelector('.share-copy-text');
        var copyIcon = copyBtn.querySelector('.share-copy-icon');
        var copyDone = copyBtn.querySelector('.share-copy-done');
        copyBtn.addEventListener('click', function() {
            var urlInput = document.getElementById('share_url');
            var u = urlInput.value;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(u).then(function() {
                    copyIcon.style.display = 'none';
                    copyDone.style.display = '';
                    copyText.textContent = '¡Copiado!';
                    if (typeof alertify !== 'undefined') alertify.success('Enlace copiado');
                    setTimeout(function() {
                        copyIcon.style.display = '';
                        copyDone.style.display = 'none';
                        copyText.textContent = 'Copiar';
                    }, 2000);
                });
            } else {
                urlInput.select();
                document.execCommand('copy');
                copyText.textContent = '¡Copiado!';
                if (typeof alertify !== 'undefined') alertify.success('Enlace copiado');
                setTimeout(function() { copyText.textContent = 'Copiar'; }, 2000);
            }
        });
        
        document.querySelectorAll('#shareModal .share-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var platform = this.getAttribute('data-platform');
                var currentUrl = document.getElementById('share_url').value;
                var currentTitle = shareModal.getAttribute('data-share-title') || 'Publicación';
                shareToPlatform(platform, currentUrl, currentTitle);
            });
        });
        
        var nativeWrap = document.getElementById('share_native_wrap');
        var nativeBtn = document.getElementById('share_native_btn');
        if (navigator.share) {
            nativeWrap.style.display = 'block';
            nativeBtn.addEventListener('click', function() {
                var currentUrl = document.getElementById('share_url').value;
                var currentTitle = shareModal.getAttribute('data-share-title') || 'Publicación';
                navigator.share({ title: currentTitle, url: currentUrl }).then(function() {
                    var m = bootstrap.Modal.getInstance(shareModal);
                    if (m) m.hide();
                }).catch(function() {});
            });
        }
    }
    
    document.getElementById('share_url').value = url;
    shareModal.setAttribute('data-share-title', titulo);
    
    var modal = new bootstrap.Modal(shareModal);
    modal.show();
}

/**
 * Comparte a una plataforma específica
 */
function shareToPlatform(platform, url, text) {
    const encodedUrl = encodeURIComponent(url);
    const encodedText = encodeURIComponent(text);
    
    let shareUrl = '';
    
    switch(platform) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`;
            break;
        case 'whatsapp':
            shareUrl = `https://wa.me/?text=${encodedText} ${encodedUrl}`;
            break;
        case 'telegram':
            shareUrl = `https://t.me/share/url?url=${encodedUrl}&text=${encodedText}`;
            break;
        default:
            return;
    }
    
    window.open(shareUrl, '_blank', 'width=600,height=400');
    
    // Cerrar modal
    const modalElement = document.getElementById('shareModal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
}

// Exportar funciones globales
if (typeof window !== 'undefined') {
    window.guardarEdicionBoard = guardarEdicionBoard;
    window.reinitBoardInteractions = initBoardInteractions;
}
