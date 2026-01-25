/**
 * Sistema de Comentarios con Hilos
 * Maneja la carga, visualización y creación de comentarios y sus respuestas
 */

// Variables globales - usar las del archivo principal si existen
if (typeof currentCommentId === 'undefined') {
    var currentCommentId = null;
}
if (typeof action_comment === 'undefined') {
    var action_comment = 'normal';
}
// Base URL para subdirectorios (XAMPP)
var baseUrl = window.BASE_URL || '';

/**
 * Carga todos los comentarios de un tablero
 */
function cargarComentarios(id_tablero) {
    // Obtener dominio - intentar desde variable global o elemento DOM
    let dominio;
    if (typeof window.dominio !== 'undefined' && window.dominio) {
        dominio = window.dominio;
    } else {
        const dominioEl = document.getElementById('dominio');
        dominio = dominioEl ? dominioEl.value : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    }
    
    const FormDatas = new FormData();
    FormDatas.append('action', 'load_comments');
    FormDatas.append('id_board', id_tablero);

    axios.post(baseUrl + '/controllers/actions_board.php', FormDatas)
        .then(response => {
            let comentarios = [];
            let respuestasIndex = {}; // Índice de respuestas por comentario padre
            
            // Parsear respuesta
            let data = response.data;
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    data = {};
                }
            }
            
            // Verificar si la respuesta tiene la nueva estructura con índice
            if (data && data.comentarios && data.respuestas) {
                // Nueva estructura: { comentarios: [], respuestas: { coment_id: [] } }
                comentarios = Array.isArray(data.comentarios) ? data.comentarios : [];
                respuestasIndex = data.respuestas || {};
            } else if (Array.isArray(data)) {
                // Estructura antigua: array directo de comentarios
                comentarios = data;
                // Construir índice de respuestas desde comentarios_hijos
                comentarios.forEach(comentario => {
                    const id_coment = parseInt(comentario.id_comentario) || 0;
                    if (comentario.comentarios_hijos) {
                        respuestasIndex[id_coment] = Array.isArray(comentario.comentarios_hijos) 
                            ? comentario.comentarios_hijos 
                            : Object.values(comentario.comentarios_hijos);
                    } else {
                        respuestasIndex[id_coment] = [];
                    }
                });
            } else if (data && Array.isArray(data.data)) {
                // Otra posible estructura
                comentarios = data.data;
            }

            if (comentarios.length === 0) {
                document.getElementById('data_coments').innerHTML = '<li class="list-group-item">No hay comentarios aún</li>';
                return;
            }

            console.log('Comentarios cargados:', comentarios.length);
            console.log('Índice de respuestas:', respuestasIndex);

            // Renderizar comentarios con el índice de respuestas
            renderizarComentarios(comentarios, respuestasIndex);
            
        })
        .catch(error => {
            console.error('Error al cargar comentarios:', error);
            if (typeof alertify !== 'undefined') {
                alertify.error('Error al cargar los comentarios');
            }
        });
}

/**
 * Renderiza los comentarios en el DOM
 * @param {Array} comentarios - Array de comentarios principales
 * @param {Object} respuestasIndex - Índice de respuestas: { coment_id: [respuestas] }
 */
function renderizarComentarios(comentarios, respuestasIndex = {}) {
    // Guardar el índice globalmente para uso posterior (al agregar nuevas respuestas)
    window.respuestasIndex = respuestasIndex || {};
    
    // Obtener dominio e id_usuario
    let dominio;
    if (typeof window.dominio !== 'undefined' && window.dominio) {
        dominio = window.dominio;
    } else {
        const dominioEl = document.getElementById('dominio');
        dominio = dominioEl ? dominioEl.value : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    }
    
    let id_usuario = 0;
    if (typeof window.id_usuario !== 'undefined') {
        id_usuario = parseInt(window.id_usuario) || 0;
    } else {
        const idUsuarioEl = document.getElementById('id_usuario');
        id_usuario = idUsuarioEl ? parseInt(idUsuarioEl.value) || 0 : 0;
    }
    let html = '';

    // Recorrer la jerarquía: comentarios principales
    comentarios.forEach(comentario => {
        // Asegurar que id_comentario sea válido
        const id_comentario = parseInt(comentario.id_comentario) || 0;
        
        // Obtener respuestas desde el índice usando el id_comentario como clave
        let respuestas = [];
        if (respuestasIndex && respuestasIndex[id_comentario] !== undefined) {
            respuestas = Array.isArray(respuestasIndex[id_comentario]) 
                ? respuestasIndex[id_comentario] 
                : Object.values(respuestasIndex[id_comentario] || {});
        }
        
        console.log(`Comentario ${id_comentario} - Respuestas desde índice:`, respuestas.length);
        
        // Renderizar respuestas (hilos) para este comentario
        const htmlRespuestas = renderizarRespuestas(respuestas, id_comentario);

        // Botones de acción
        const botonesAccion = generarBotonesAccion(comentario, id_usuario);
        
        // OG Card si existe
        const ogCard = generarOGCard(comentario);

        // HTML del comentario principal
        html += `
            <li id="comment_${id_comentario}" class="list-group-item comments box_comment" data-comment-id="${id_comentario}">
                <div class="comment-header">
                    <img src="/${comentario.foto_url || 'assets/user_profile.png'}" class="rounded comment-avatar" alt="${comentario.usuario || 'Usuario'}">
                    <div class="comment-info">
                        <strong class="comment-username">${comentario.usuario || 'Usuario'}</strong>
                        <span class="comment-date">${formatearFecha(comentario.fecha_publicacion || '')}</span>
                    </div>
                    ${botonesAccion.eliminar}
                </div>
                ${ogCard}
                <div class="comment-text">${escapeHtml(comentario.texto || '')}</div>
                <div class="comment-actions">
                    <i class="fa-regular fa-thumbs-up" style="cursor:pointer"></i>
                    <i class="fa-regular fa-thumbs-down" style="cursor:pointer"></i>
                    <i class="fa-solid fa-heart-crack" style="cursor:pointer"></i>
                    <i class="fa-solid fa-reply reply-btn" 
                       data-comment-id="${id_comentario}" 
                       data-username="${comentario.usuario || 'Usuario'}"
                       style="cursor:pointer" 
                       title="Responder"></i>
                </div>
                ${htmlRespuestas}
            </li>
        `;
    });

    document.getElementById('data_coments').innerHTML = html;
    
    // Asignar eventos después de renderizar
    asignarEventosComentarios();
}

/**
 * Renderiza las respuestas (hilos) de un comentario
 */
function renderizarRespuestas(respuestas, id_comentario) {
    // Asegurar que respuestas sea un array
    if (!respuestas) {
        respuestas = [];
    }
    if (!Array.isArray(respuestas)) {
        // Si es un objeto, convertirlo a array
        if (typeof respuestas === 'object' && respuestas !== null) {
            respuestas = Object.values(respuestas);
        } else {
            respuestas = [];
        }
    }
    
    // SIEMPRE crear el contenedor, incluso si está vacío (para poder agregar respuestas después)
    if (respuestas.length === 0) {
        return `<ul id="replies_${id_comentario}" class="comment-replies" style="display:none;"></ul>`;
    }

    // Obtener dominio e id_usuario
    let dominio;
    if (typeof window.dominio !== 'undefined' && window.dominio) {
        dominio = window.dominio;
    } else {
        const dominioEl = document.getElementById('dominio');
        dominio = dominioEl ? dominioEl.value : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    }
    
    let id_usuario = 0;
    if (typeof window.id_usuario !== 'undefined') {
        id_usuario = parseInt(window.id_usuario) || 0;
    } else {
        const idUsuarioEl = document.getElementById('id_usuario');
        id_usuario = idUsuarioEl ? parseInt(idUsuarioEl.value) || 0 : 0;
    }
    let html = `<ul id="replies_${id_comentario}" class="comment-replies">`;

    respuestas.forEach(respuesta => {
        // Validar que la respuesta tenga los campos necesarios
        if (!respuesta || !respuesta.id_reply) {
            console.warn('Respuesta inválida:', respuesta);
            return;
        }
        
        const id_reply = parseInt(respuesta.id_reply) || 0;
        const user_id_reply = parseInt(respuesta.user_id) || 0;
        const botonEliminar = (id_usuario == user_id_reply) 
            ? `<i class="fa-solid fa-delete-left delete-reply" data-reply-id="${id_reply}" style="cursor:pointer;float:right"></i>`
            : '';

        html += `
            <li class="reply-item" id="reply_${id_reply}">
                <img src="/${respuesta.foto_url || 'assets/user_profile.png'}" class="rounded reply-avatar" alt="${respuesta.usuario || 'Usuario'}">
                <div class="reply-content">
                    <div class="reply-header" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                        <div class="reply-info">
                            <strong class="reply-username">${respuesta.usuario || 'Usuario'}</strong>
                            <span class="reply-date" style="margin-left:8px; color:#888; font-size:0.85em;">${formatearFecha(respuesta.fecha_creacion || '')}</span>
                        </div>
                        ${botonEliminar ? '<div class="reply-actions" style="margin-left:auto;">' + botonEliminar + '</div>' : ''}
                    </div>
                    <div class="reply-text">${escapeHtml(respuesta.text_coment || '')}</div>
                </div>
            </li>
        `;
    });

    html += '</ul>';
    return html;
}

/**
 * Genera los botones de acción para un comentario
 */
function generarBotonesAccion(comentario, id_usuario) {
    const botones = {
        eliminar: ''
    };

    const id_comentario = parseInt(comentario.id_comentario) || 0;
    const usuario_id = parseInt(comentario.usuario_id || comentario.id_user) || 0;
    
    if (usuario_id == id_usuario) {
        botones.eliminar = `
            <svg class="delete-comment" 
                 data-comment-id="${id_comentario}"
                 style="cursor:pointer;float:right" 
                 width="16" height="16" 
                 fill="currentColor" 
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z"/>
            </svg>
        `;
    }

    return botones;
}

/**
 * Genera el card OG si existe
 */
function generarOGCard(comentario) {
    if (!comentario.data_og || comentario.data_og === "[]") {
        return '<figure class="card_og" style="display:none"></figure>';
    }

    try {
        const og = JSON.parse(comentario.data_og);
        const description = og.description ? og.description.substr(0, 80) : '';
        
        return `
            <a class="og-card" href="${og.url}">
                <figure class="card_og" style="background:#f8f8f8;">
                    <img src="${og.image}" class="img_card_og" alt="${og.title}"/>
                    <hr/>
                    <p style="color:black">${og.title}</p>
                    <p>${description}</p>
                </figure>
            </a>
        `;
    } catch (e) {
        return '<figure class="card_og" style="display:none"></figure>';
    }
}

/**
 * Asigna eventos a los comentarios
 */
function asignarEventosComentarios() {
    // Obtener id_usuario
    let id_usuario = 0;
    if (typeof window.id_usuario !== 'undefined') {
        id_usuario = parseInt(window.id_usuario) || 0;
    } else {
        const idUsuarioEl = document.getElementById('id_usuario');
        id_usuario = idUsuarioEl ? parseInt(idUsuarioEl.value) || 0 : 0;
    }

    // Eventos para botones de respuesta
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const username = this.getAttribute('data-username');
            
            currentCommentId = commentId;
            action_comment = 'reply';
            
            const textField = document.querySelector('.textComent');
            if (textField) {
                textField.value = `@${username} `;
                textField.focus();
            }
        });
    });

    // Eventos para eliminar comentarios
    document.querySelectorAll('.delete-comment').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            eliminarComentario(commentId);
        });
    });

    // Eventos para eliminar respuestas
    document.querySelectorAll('.delete-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            const replyId = this.getAttribute('data-reply-id');
            eliminarRespuesta(replyId);
        });
    });
}

/**
 * Envía un comentario nuevo o una respuesta
 */
function enviarComentario(id_tablero) {
    const textField = document.querySelector('.textComent');
    const texto = textField.value.trim();
    
    if (!texto) {
        if (typeof alertify !== 'undefined') {
            alertify.warning('El comentario no puede estar vacío');
        }
        return;
    }

    const id_usuario = parseInt(document.getElementById('id_usuario').value) || 0;
    const dominio = document.getElementById('dominio').value;

    if (action_comment === 'reply' && currentCommentId) {
        // Enviar respuesta
        enviarRespuesta(currentCommentId, texto, id_usuario);
    } else {
        // Enviar comentario nuevo
        guardarComentario(id_usuario, id_tablero, texto, 'board');
    }

    textField.value = '';
    action_comment = 'normal';
    currentCommentId = null;
}

/**
 * Envía una respuesta a un comentario
 */
function enviarRespuesta(id_coment, texto, id_user) {
    // Limpiar el @usuario del texto
    const textoLimpio = texto.replace(/^@\w+\s+/, '').trim();
    
    if (!textoLimpio) {
        if (typeof alertify !== 'undefined') {
            alertify.warning('La respuesta no puede estar vacía');
        }
        return;
    }

    // Obtener dominio
    let dominio;
    if (typeof window.dominio !== 'undefined' && window.dominio) {
        dominio = window.dominio;
    } else {
        const dominioEl = document.getElementById('dominio');
        dominio = dominioEl ? dominioEl.value : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    }
    const FormDatas = new FormData();
    FormDatas.append('id_coment', id_coment);
    FormDatas.append('text_coment', textoLimpio);
    FormDatas.append('id_user', id_user);
    FormDatas.append('action', 'reply_coment');

    axios.post(baseUrl + '/controllers/actions_board.php', FormDatas, {
        headers: {
            'Content-Type': 'multipart/form-data'
        },
        transformResponse: [(data) => {
            // Intentar parsear la respuesta si es string
            if (typeof data === 'string') {
                try {
                    return JSON.parse(data);
                } catch (e) {
                    return data;
                }
            }
            return data;
        }]
    })
        .then(response => {
            console.log('Respuesta completa del servidor:', response);
            console.log('response.data:', response.data);
            console.log('Tipo de response.data:', typeof response.data);
            
            let respuestaData = null;
            
            // Procesar la respuesta del servidor
            if (response.data) {
                // Si es string, parsearlo
                if (typeof response.data === 'string') {
                    try {
                        respuestaData = JSON.parse(response.data);
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        console.error('String recibido:', response.data);
                        // Si no es JSON válido, intentar extraer JSON del string
                        const jsonMatch = response.data.match(/\{.*\}/);
                        if (jsonMatch) {
                            try {
                                respuestaData = JSON.parse(jsonMatch[0]);
                            } catch (e2) {
                                throw new Error('No se pudo parsear la respuesta del servidor');
                            }
                        } else {
                            throw new Error('Respuesta del servidor no es JSON válido');
                        }
                    }
                } else {
                    respuestaData = response.data;
                }
            }
            
            console.log('Datos procesados de la respuesta:', respuestaData);
            
            // Verificar que tenemos datos válidos
            if (respuestaData && typeof respuestaData === 'object') {
                // Verificar que no sea un array vacío
                if (Array.isArray(respuestaData) && respuestaData.length === 0) {
                    throw new Error('El servidor retornó un array vacío');
                }
                
                // Verificar que no sea un objeto de error
                if (respuestaData.error) {
                    throw new Error(respuestaData.error);
                }
                
                // Verificar que tenga los campos necesarios
                if (!respuestaData.id_reply && !respuestaData.text_coment) {
                    throw new Error('La respuesta del servidor no contiene los datos esperados');
                }
                
                // Insertar la respuesta en el DOM
                insertarRespuesta(respuestaData, id_coment);
                
                // Limpiar el campo de texto
                const textField = document.querySelector('.textComent');
                if (textField) {
                    textField.value = '';
                }
                
                // Resetear variables
                action_comment = 'normal';
                currentCommentId = null;
            } else {
                console.error('Respuesta inválida del servidor:', respuestaData);
                throw new Error('El servidor no retornó datos válidos');
            }
        })
        .catch(error => {
            console.error('Error al enviar respuesta:', error);
            console.error('Error completo:', error);
            console.error('Error response:', error.response);
            console.error('Error message:', error.message);
            
            let mensajeError = 'Error al enviar la respuesta';
            
            if (error.response) {
                // Error de respuesta del servidor
                if (error.response.data) {
                    let errorData = error.response.data;
                    if (typeof errorData === 'string') {
                        try {
                            errorData = JSON.parse(errorData);
                        } catch (e) {
                            // No es JSON, usar el string directamente
                        }
                    }
                    mensajeError += ': ' + (errorData.error || errorData.message || errorData);
                } else {
                    mensajeError += ': ' + error.response.statusText;
                }
            } else if (error.message) {
                mensajeError += ': ' + error.message;
            }
            
            if (typeof alertify !== 'undefined') {
                alertify.error(mensajeError);
            } else {
                alert(mensajeError);
            }
        });
}

/**
 * Inserta una nueva respuesta en el DOM
 */
function insertarRespuesta(respuestaData, id_coment) {
    // Obtener dominio e id_usuario
    let dominio;
    if (typeof window.dominio !== 'undefined' && window.dominio) {
        dominio = window.dominio;
    } else {
        const dominioEl = document.getElementById('dominio');
        dominio = dominioEl ? dominioEl.value : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    }
    
    let id_usuario = 0;
    if (typeof window.id_usuario !== 'undefined') {
        id_usuario = parseInt(window.id_usuario) || 0;
    } else {
        const idUsuarioEl = document.getElementById('id_usuario');
        id_usuario = idUsuarioEl ? parseInt(idUsuarioEl.value) || 0 : 0;
    }
    
    // Parsear datos si vienen como string
    let respuesta = respuestaData;
    if (typeof respuestaData === 'string') {
        try {
            respuesta = JSON.parse(respuestaData);
        } catch (e) {
            console.error('Error parsing respuesta:', e);
            return;
        }
    }
    
    // Validar que respuesta tenga los campos necesarios
    if (!respuesta || !respuesta.id_reply) {
        console.error('Respuesta inválida o sin id_reply:', respuesta);
        return;
    }
    
    // Actualizar el índice global de respuestas (si existe)
    if (typeof window.respuestasIndex === 'undefined') {
        window.respuestasIndex = {};
    }
    if (!window.respuestasIndex[id_coment]) {
        window.respuestasIndex[id_coment] = [];
    }
    window.respuestasIndex[id_coment].push(respuesta);

    // Buscar o crear contenedor de respuestas
    let container = document.querySelector(`#replies_${id_coment}`);
    
    if (!container) {
        // Buscar el comentario padre
        const parentComment = document.querySelector(`[data-comment-id="${id_coment}"]`);
        if (parentComment) {
            container = document.createElement('ul');
            container.id = `replies_${id_coment}`;
            container.className = 'comment-replies';
            parentComment.appendChild(container);
        } else {
            console.error('No se encontró el comentario padre');
            return;
        }
    }

    // Mostrar el contenedor si estaba oculto
    container.style.display = 'block';

    // Generar HTML de la respuesta
    const id_reply = parseInt(respuesta.id_reply) || 0;
    const user_id_reply = parseInt(respuesta.user_id) || 0;
    const botonEliminar = (id_usuario == user_id_reply) 
        ? `<i class="fa-solid fa-delete-left delete-reply" data-reply-id="${id_reply}" style="cursor:pointer;float:right"></i>`
        : '';

    const html = `
        <li class="reply-item" id="reply_${id_reply}">
            <img src="/${respuesta.foto_url || 'assets/user_profile.png'}" class="rounded reply-avatar" alt="${respuesta.usuario || 'Usuario'}">
            <div class="reply-content">
                <strong class="reply-username">${respuesta.usuario || 'Usuario'}</strong>
                <span class="reply-date">${formatearFecha(respuesta.fecha_creacion || '')}</span>
                ${botonEliminar}
                <div class="reply-text">${escapeHtml(respuesta.text_coment || '')}</div>
            </div>
        </li>
    `;

    container.insertAdjacentHTML('beforeend', html);

    // Asignar evento de eliminar
    const deleteBtn = container.querySelector(`[data-reply-id="${id_reply}"]`);
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            eliminarRespuesta(id_reply);
        });
    }
}

/**
 * Guarda un comentario nuevo
 */
function guardarComentario(id_usuario, id_tablero, texto, tipo_post) {
    // Obtener dominio y token
    let dominio;
    if (typeof window.dominio !== 'undefined' && window.dominio) {
        dominio = window.dominio;
    } else {
        const dominioEl = document.getElementById('dominio');
        dominio = dominioEl ? dominioEl.value : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    }
    
    const token = localStorage.getItem('token') || '';
    const FormDatas = new FormData();
    
    FormDatas.append('action', 'save_post');
    FormDatas.append('id_user', id_usuario);
    FormDatas.append('id_board', id_tablero);
    FormDatas.append('text', texto);
    FormDatas.append('data_og', '[]');
    FormDatas.append('type_post', tipo_post);

    axios.post(baseUrl + '/controllers/actions_board.php', FormDatas, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => {
        // Recargar comentarios
        cargarComentarios(id_tablero);
    })
    .catch(error => {
        console.error('Error al guardar comentario:', error);
        if (typeof alertify !== 'undefined') {
            alertify.error('Error al publicar el comentario');
        }
    });
}

/**
 * Elimina un comentario
 */
function eliminarComentario(id_comentario) {
    if (typeof alertify !== 'undefined') {
        alertify.confirm('Confirmar', '¿Estás seguro de eliminar este comentario?', 
            function() {
                // Obtener dominio
                let dominio;
                if (typeof window.dominio !== 'undefined' && window.dominio) {
                    dominio = window.dominio;
                } else {
                    const dominioEl = document.getElementById('dominio');
                    dominio = dominioEl ? dominioEl.value : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
                }
                const FormDatas = new FormData();
                FormDatas.append('action', 'delete_comment');
                FormDatas.append('id_comentario', id_comentario);

                axios.post(baseUrl + '/controllers/actions_board.php', FormDatas)
                    .then(() => {
                        document.querySelector(`#comment_${id_comentario}`).remove();
                        alertify.success('Comentario eliminado');
                    })
                    .catch(error => {
                        console.error('Error al eliminar:', error);
                        alertify.error('Error al eliminar el comentario');
                    });
            },
            function() {}
        );
    }
}

/**
 * Elimina una respuesta
 */
function eliminarRespuesta(id_reply) {
    if (typeof alertify !== 'undefined') {
        alertify.confirm('Confirmar', '¿Estás seguro de eliminar esta respuesta?', 
            function() {
                // Obtener dominio
                let dominio;
                if (typeof window.dominio !== 'undefined' && window.dominio) {
                    dominio = window.dominio;
                } else {
                    const dominioEl = document.getElementById('dominio');
                    dominio = dominioEl ? dominioEl.value : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
                }
                const FormDatas = new FormData();
                FormDatas.append('action', 'delete_reply_coment');
                FormDatas.append('id_coment', id_reply);

                axios.post(baseUrl + '/controllers/actions_board.php', FormDatas)
                    .then(() => {
                        document.querySelector(`#reply_${id_reply}`).remove();
                        alertify.success('Respuesta eliminada');
                    })
                    .catch(error => {
                        console.error('Error al eliminar:', error);
                        alertify.error('Error al eliminar la respuesta');
                    });
            },
            function() {}
        );
    }
}

/**
 * Utilidades
 */
function formatearFecha(fecha) {
    if (!fecha) return '';
    const date = new Date(fecha);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
