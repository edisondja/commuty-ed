/**
 * Sistema de Calificación con Estrellas (1-5)
 * Permite a los usuarios calificar publicaciones con círculos/estrellas
 */

document.addEventListener('DOMContentLoaded', function() {
    initRatingSystem();
});

/**
 * Inicializa el sistema de calificación
 */
function initRatingSystem() {
    const ratingSections = document.querySelectorAll('.rating-section');
    
    ratingSections.forEach(section => {
        const tableroId = section.getAttribute('data-tablero-id');
        if (!tableroId) return;
        
        // Cargar calificación promedio y la del usuario
        loadRating(tableroId);
        
        // Configurar eventos de las estrellas
        const stars = section.querySelectorAll('.star');
        stars.forEach((star, index) => {
            // Obtener el valor del atributo data-value o usar index + 1
            const value = parseInt(star.getAttribute('data-value')) || (index + 1);
            
            // Asegurar que el atributo data-value esté presente
            if (!star.getAttribute('data-value')) {
                star.setAttribute('data-value', value);
            }
            
            // Click para calificar
            star.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Click en estrella:', value, 'para tablero:', tableroId);
                rateBoard(tableroId, value);
            });
            
            // Hover para preview
            star.addEventListener('mouseenter', function() {
                highlightStars(section, value, true);
            });
            
            star.addEventListener('mouseleave', function() {
                const currentRating = section.getAttribute('data-current-rating') || 0;
                highlightStars(section, parseInt(currentRating), false);
            });
        });
    });
}

/**
 * Carga la calificación promedio y la del usuario
 */
function loadRating(tableroId) {
    const dominio = document.getElementById('dominio')?.value || window.location.origin || '';
    let idUsuario = document.getElementById('id_usuario')?.value || 0;
    
    // Intentar obtener id_usuario de diferentes formas
    if (!idUsuario || idUsuario == '0') {
        if (typeof window.id_usuario !== 'undefined') {
            idUsuario = window.id_usuario;
        } else if (typeof window.id_user !== 'undefined') {
            idUsuario = window.id_user;
        }
    }
    
    // Cargar promedio
    axios.get(`${dominio}/controllers/actions_board.php`, {
        params: {
            action: 'get_rating_average',
            id_tablero: tableroId
        }
    })
    .then(response => {
        let data = response.data;
        
        // Si es string, parsearlo
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data);
            } catch (e) {
                console.error('Error parseando promedio:', e);
                return;
            }
        }
        
        if (data && data.promedio !== undefined) {
            updateRatingDisplay(tableroId, data);
        }
    })
    .catch(error => {
        console.error('Error cargando promedio:', error);
    });
    
    // Cargar calificación del usuario si está logueado
    if (idUsuario && idUsuario != '0' && idUsuario != '') {
        axios.get(`${dominio}/controllers/actions_board.php`, {
            params: {
                action: 'get_my_rating',
                id_tablero: tableroId,
                id_usuario: idUsuario
            }
        })
        .then(response => {
            let data = response.data;
            
            // Si es string, parsearlo
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    console.error('Error parseando calificación del usuario:', e);
                    return;
                }
            }
            
            if (data && data.puntuacion) {
                const section = document.querySelector(`.rating-section[data-tablero-id="${tableroId}"]`);
                if (section) {
                    section.setAttribute('data-current-rating', data.puntuacion);
                    highlightStars(section, data.puntuacion);
                }
            }
        })
        .catch(error => {
            console.error('Error cargando calificación del usuario:', error);
        });
    }
}

/**
 * Califica una publicación
 */
function rateBoard(tableroId, puntuacion) {
    const dominio = document.getElementById('dominio')?.value || window.location.origin || '';
    let idUsuario = document.getElementById('id_usuario')?.value;
    
    // Intentar obtener id_usuario de diferentes formas
    if (!idUsuario || idUsuario == '0') {
        // Buscar en variables globales
        if (typeof window.id_usuario !== 'undefined') {
            idUsuario = window.id_usuario;
        } else if (typeof window.id_user !== 'undefined') {
            idUsuario = window.id_user;
        }
    }
    
    if (!idUsuario || idUsuario == '0' || idUsuario == '') {
        if (typeof alertify !== 'undefined') {
            alertify.warning('Debes iniciar sesión para calificar');
        } else {
            alert('Debes iniciar sesión para calificar');
        }
        return;
    }
    
    console.log('Calificando:', { tableroId, puntuacion, idUsuario, dominio });
    
    const formData = new FormData();
    formData.append('action', 'save_rating');
    formData.append('id_tablero', tableroId);
    formData.append('id_usuario', idUsuario);
    formData.append('puntuacion', puntuacion);
    
    axios.post(`${dominio}/controllers/actions_board.php`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            console.log('Respuesta del servidor:', response.data);
            
            // El modelo Rating devuelve JSON directamente
            let data = response.data;
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    console.error('Error parseando respuesta:', e);
                    data = { success: false };
                }
            }
            
            if (data && data.success) {
                // Actualizar visualización
                const section = document.querySelector(`.rating-section[data-tablero-id="${tableroId}"]`);
                if (section) {
                    section.setAttribute('data-current-rating', puntuacion);
                    highlightStars(section, puntuacion);
                }
                
                // Recargar promedio después de un breve delay
                setTimeout(() => {
                    loadRating(tableroId);
                }, 300);
            } else {
                console.error('Error en respuesta:', data);
                if (typeof alertify !== 'undefined') {
                    alertify.error(data.error || 'Error al guardar la calificación');
                }
            }
        })
        .catch(error => {
            console.error('Error calificando:', error);
            console.error('Detalles:', error.response?.data || error.message);
            if (typeof alertify !== 'undefined') {
                alertify.error('Error al guardar la calificación. Ver consola para más detalles.');
            } else {
                alert('Error al guardar la calificación');
            }
        });
}

/**
 * Resalta las estrellas según el valor
 */
function highlightStars(section, value, isHover = false) {
    if (!value || value <= 0) {
        value = 0;
    }
    
    const stars = section.querySelectorAll('.star');
    stars.forEach((star, index) => {
        const starValue = parseInt(star.getAttribute('data-value')) || (index + 1);
        
        if (starValue <= value) {
            star.classList.add('active');
            if (isHover) {
                star.classList.add('hover-active');
            }
        } else {
            star.classList.remove('active');
            star.classList.remove('hover-active');
        }
    });
}

/**
 * Actualiza la visualización del promedio
 */
function updateRatingDisplay(tableroId, data) {
    const section = document.querySelector(`.rating-section[data-tablero-id="${tableroId}"]`);
    if (!section) return;
    
    const averageElement = section.querySelector('.rating-average');
    const countElement = section.querySelector('.rating-count');
    
    if (averageElement) {
        averageElement.textContent = data.promedio.toFixed(1);
    }
    
    if (countElement) {
        const count = data.total_calificaciones || 0;
        const text = count === 1 ? 'calificación' : 'calificaciones';
        countElement.textContent = `(${count} ${text})`;
    }
    
    // Actualizar visualización de estrellas según promedio redondeado
    if (data.promedio_redondeado > 0) {
        highlightStars(section, data.promedio_redondeado);
    }
}

/**
 * Reinicializa el sistema para elementos agregados dinámicamente
 */
function reinitRatingSystem() {
    initRatingSystem();
}

// Exportar para uso global
if (typeof window !== 'undefined') {
    window.reinitRatingSystem = reinitRatingSystem;
}
