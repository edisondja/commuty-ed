/**
 * Sistema de Interacciones para Publicaciones
 * Maneja: Editar, Me Gusta, Comentar y Compartir desde la lista de publicaciones
 */

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
    
    axios.get('/controllers/actions_board.php', {
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
    
    axios.post('/controllers/actions_board.php', FormData, {
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
            e.preventDefault();
            e.stopPropagation();
            
            const likeIcon = e.target.closest('.like-icon');
            const boardCard = likeIcon.closest('.card-board');
            const idTablero = boardCard ? boardCard.id.replace('board', '') : null;
            
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
    
    axios.post('/controllers/actions_board.php', FormData)
        .then(response => {
            const data = response.data;
            
            // Actualizar icono
            if (data && typeof data === 'string') {
                const responseText = data.trim();
                if (responseText === '_success' || responseText === 'activo_success') {
                    // Like activado
                    likeIcon.classList.remove('fa-regular');
                    likeIcon.classList.add('fa-solid');
                    likeIcon.style.color = '#20c997';
                    
                    if (typeof alertify !== 'undefined') {
                        alertify.success('Me gusta agregado');
                    }
                } else if (responseText === 'inactivo_success') {
                    // Like desactivado
                    likeIcon.classList.remove('fa-solid');
                    likeIcon.classList.add('fa-regular');
                    likeIcon.style.color = '#20c997';
                    
                    if (typeof alertify !== 'undefined') {
                        alertify.message('Me gusta removido');
                    }
                }
            } else if (data && typeof data === 'object') {
                // Si la respuesta es un objeto JSON
                if (data.success || data.status === 'activo') {
                    likeIcon.classList.remove('fa-regular');
                    likeIcon.classList.add('fa-solid');
                    likeIcon.style.color = '#20c997';
                } else {
                    likeIcon.classList.remove('fa-solid');
                    likeIcon.classList.add('fa-regular');
                    likeIcon.style.color = '#20c997';
                }
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
            axios.get('/controllers/actions_board.php', {
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
        axios.get('/controllers/actions_board.php', {
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
                const dominio = document.getElementById('dominio')?.value || '';
                window.location.href = `/single_board.php?id=${idTablero}#coments`;
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
 * Muestra el modal de compartir
 */
function showShareModal(idTablero, boardCard) {
    const dominio = document.getElementById('dominio')?.value || '';
    const titulo = boardCard.querySelector('.description-text')?.textContent || 'Publicación';
    const url = `${window.location.origin}/single_board.php?id=${idTablero}`;
    const imagen = boardCard.querySelector('.board-image')?.src || '';
    
    // Crear modal de compartir si no existe
    let shareModal = document.getElementById('shareModal');
    if (!shareModal) {
        shareModal = document.createElement('div');
        shareModal.id = 'shareModal';
        shareModal.className = 'modal fade';
        shareModal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #20c997 0%, #17a085 100%); color: white;">
                        <h5 class="modal-title"><i class="fas fa-share-alt me-2"></i>Compartir Publicación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">URL de la publicación</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="share_url" readonly>
                                <button class="btn btn-outline-secondary" type="button" id="copy_url_btn">
                                    <i class="fas fa-copy"></i> Copiar
                                </button>
                            </div>
                        </div>
                        <div class="share-buttons">
                            <button class="btn btn-primary share-btn" data-platform="facebook" style="background: #1877f2; border: none; margin: 5px;">
                                <i class="fab fa-facebook-f me-2"></i> Facebook
                            </button>
                            <button class="btn btn-info share-btn" data-platform="twitter" style="background: #1da1f2; border: none; margin: 5px; color: white;">
                                <i class="fab fa-twitter me-2"></i> Twitter
                            </button>
                            <button class="btn btn-danger share-btn" data-platform="whatsapp" style="background: #25d366; border: none; margin: 5px;">
                                <i class="fab fa-whatsapp me-2"></i> WhatsApp
                            </button>
                            <button class="btn btn-secondary share-btn" data-platform="telegram" style="background: #0088cc; border: none; margin: 5px; color: white;">
                                <i class="fab fa-telegram me-2"></i> Telegram
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(shareModal);
        
        // Event listeners
        document.getElementById('copy_url_btn').addEventListener('click', function() {
            const urlInput = document.getElementById('share_url');
            urlInput.select();
            document.execCommand('copy');
            if (typeof alertify !== 'undefined') {
                alertify.success('URL copiada al portapapeles');
            } else {
                alert('URL copiada');
            }
        });
        
        // Botones de compartir
        document.querySelectorAll('.share-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const platform = this.getAttribute('data-platform');
                const url = document.getElementById('share_url').value;
                shareToPlatform(platform, url, titulo);
            });
        });
    }
    
    // Llenar URL
    document.getElementById('share_url').value = url;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(shareModal);
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
