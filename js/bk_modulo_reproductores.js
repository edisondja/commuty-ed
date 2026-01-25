/**
 * Módulo de gestión de Reproductores VAST
 */

const dominioVal = document.getElementById('dominio')?.value || '';
const apiUrl = '/controllers/actions_board.php';
const configHeaders = {
    headers: {
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
    }
};

// Cargar reproductores al iniciar
document.addEventListener('DOMContentLoaded', function() {
    cargarReproductores();
});

/**
 * Cargar lista de reproductores
 */
function cargarReproductores() {
    const formData = new FormData();
    formData.append('action', 'listar_reproductores');
    
    axios.post(apiUrl, formData, configHeaders)
        .then(response => {
            const data = response.data;
            renderizarReproductores(data.reproductores || []);
        })
        .catch(error => {
            console.error('Error cargando reproductores:', error);
            alertify.error('Error al cargar reproductores');
        });
}

/**
 * Renderizar tabla de reproductores
 */
function renderizarReproductores(reproductores) {
    const tbody = document.getElementById('listaReproductores');
    
    if (!reproductores || reproductores.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="bo-empty">
                    <i class="fa-solid fa-inbox"></i>
                    <p>No hay reproductores configurados</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = reproductores.map(rep => `
        <tr>
            <td>${rep.id_reproductor}</td>
            <td>
                <strong>${rep.nombre}</strong>
                ${rep.descripcion ? `<br><small style="color: rgba(255,255,255,0.5);">${rep.descripcion}</small>` : ''}
            </td>
            <td>
                ${rep.vast_url ? 
                    `<span class="bo-badge bo-badge-success" title="${rep.vast_url}">
                        <i class="fa-solid fa-check"></i> Configurado
                    </span>` : 
                    `<span class="bo-badge bo-badge-warning">No configurado</span>`
                }
            </td>
            <td>${rep.skip_delay}s</td>
            <td>
                ${rep.es_default == 1 ? 
                    `<span class="bo-badge bo-badge-info"><i class="fa-solid fa-star"></i> Default</span>` : 
                    `<button class="bo-btn bo-btn-secondary bo-btn-sm" onclick="hacerDefault(${rep.id_reproductor})" title="Hacer predeterminado">
                        <i class="fa-regular fa-star"></i>
                    </button>`
                }
            </td>
            <td>
                ${rep.activo == 1 ? 
                    `<span class="bo-badge bo-badge-success">Activo</span>` : 
                    `<span class="bo-badge bo-badge-danger">Inactivo</span>`
                }
            </td>
            <td>
                <div style="display: flex; gap: 5px;">
                    <button class="bo-btn bo-btn-primary bo-btn-sm" onclick="editarReproductor(${rep.id_reproductor})" title="Editar">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="bo-btn bo-btn-secondary bo-btn-sm" onclick="probarReproductor(${rep.id_reproductor})" title="Probar">
                        <i class="fa-solid fa-play"></i>
                    </button>
                    ${rep.es_default != 1 ? `
                        <button class="bo-btn bo-btn-danger bo-btn-sm" onclick="eliminarReproductor(${rep.id_reproductor})" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

/**
 * Limpiar formulario para nuevo reproductor
 */
function limpiarFormulario() {
    document.getElementById('reproductor_id').value = '';
    document.getElementById('reproductor_nombre').value = '';
    document.getElementById('reproductor_descripcion').value = '';
    document.getElementById('vast_url').value = '';
    document.getElementById('vast_url_mid').value = '';
    document.getElementById('vast_url_post').value = '';
    document.getElementById('skip_delay').value = '5';
    document.getElementById('mid_roll_time').value = '30';
    document.getElementById('reproductor_activo').checked = true;
    document.getElementById('reproductor_default').checked = false;
    
    document.getElementById('modalReproductorLabel').innerHTML = 
        '<i class="fa-solid fa-plus-circle"></i> Nuevo Reproductor';
}

/**
 * Editar reproductor existente
 */
function editarReproductor(id) {
    const formData = new FormData();
    formData.append('action', 'obtener_reproductor');
    formData.append('id_reproductor', id);
    
    axios.post(apiUrl, formData, configHeaders)
        .then(response => {
            const rep = response.data.reproductor;
            if (rep) {
                document.getElementById('reproductor_id').value = rep.id_reproductor;
                document.getElementById('reproductor_nombre').value = rep.nombre || '';
                document.getElementById('reproductor_descripcion').value = rep.descripcion || '';
                document.getElementById('vast_url').value = rep.vast_url || '';
                document.getElementById('vast_url_mid').value = rep.vast_url_mid || '';
                document.getElementById('vast_url_post').value = rep.vast_url_post || '';
                document.getElementById('skip_delay').value = rep.skip_delay || 5;
                document.getElementById('mid_roll_time').value = rep.mid_roll_time || 30;
                document.getElementById('reproductor_activo').checked = rep.activo == 1;
                document.getElementById('reproductor_default').checked = rep.es_default == 1;
                
                document.getElementById('modalReproductorLabel').innerHTML = 
                    '<i class="fa-solid fa-edit"></i> Editar Reproductor';
                
                const modal = new bootstrap.Modal(document.getElementById('modalReproductor'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertify.error('Error al cargar reproductor');
        });
}

/**
 * Guardar reproductor (crear o actualizar)
 */
function guardarReproductor() {
    const nombre = document.getElementById('reproductor_nombre').value.trim();
    
    if (!nombre) {
        alertify.error('El nombre del reproductor es obligatorio');
        return;
    }
    
    const formData = new FormData();
    const id = document.getElementById('reproductor_id').value;
    
    formData.append('action', id ? 'actualizar_reproductor' : 'crear_reproductor');
    if (id) formData.append('id_reproductor', id);
    formData.append('nombre', nombre);
    formData.append('descripcion', document.getElementById('reproductor_descripcion').value);
    formData.append('vast_url', document.getElementById('vast_url').value);
    formData.append('vast_url_mid', document.getElementById('vast_url_mid').value);
    formData.append('vast_url_post', document.getElementById('vast_url_post').value);
    formData.append('skip_delay', document.getElementById('skip_delay').value || 5);
    formData.append('mid_roll_time', document.getElementById('mid_roll_time').value || 30);
    formData.append('activo', document.getElementById('reproductor_activo').checked ? 1 : 0);
    formData.append('es_default', document.getElementById('reproductor_default').checked ? 1 : 0);
    
    axios.post(apiUrl, formData, configHeaders)
        .then(response => {
            if (response.data.success) {
                alertify.success(response.data.message || 'Reproductor guardado');
                bootstrap.Modal.getInstance(document.getElementById('modalReproductor')).hide();
                cargarReproductores();
            } else {
                alertify.error(response.data.message || 'Error al guardar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertify.error('Error al guardar reproductor');
        });
}

/**
 * Hacer reproductor predeterminado
 */
function hacerDefault(id) {
    const formData = new FormData();
    formData.append('action', 'set_reproductor_default');
    formData.append('id_reproductor', id);
    
    axios.post(apiUrl, formData, configHeaders)
        .then(response => {
            if (response.data.success) {
                alertify.success('Reproductor establecido como predeterminado');
                cargarReproductores();
            } else {
                alertify.error(response.data.message || 'Error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertify.error('Error al actualizar');
        });
}

/**
 * Eliminar reproductor
 */
function eliminarReproductor(id) {
    alertify.confirm('Eliminar Reproductor', 
        '¿Estás seguro de que deseas eliminar este reproductor?',
        function() {
            const formData = new FormData();
            formData.append('action', 'eliminar_reproductor');
            formData.append('id_reproductor', id);
            
            axios.post(apiUrl, formData, configHeaders)
                .then(response => {
                    if (response.data.success) {
                        alertify.success('Reproductor eliminado');
                        cargarReproductores();
                    } else {
                        alertify.error(response.data.message || 'Error al eliminar');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alertify.error('Error al eliminar');
                });
        },
        function() {
            // Cancelado
        }
    );
}

/**
 * Probar reproductor con video de ejemplo
 */
function probarReproductor(id) {
    const formData = new FormData();
    formData.append('action', 'obtener_reproductor');
    formData.append('id_reproductor', id);
    
    axios.post(apiUrl, formData, configHeaders)
        .then(response => {
            const rep = response.data.reproductor;
            if (rep) {
                // Mostrar modal de prueba
                const modal = new bootstrap.Modal(document.getElementById('modalProbarVast'));
                modal.show();
                
                const logDiv = document.getElementById('logVast');
                logDiv.innerHTML = '<small class="text-info">Iniciando reproductor...</small><br>';
                
                // Inicializar reproductor con VAST
                if (rep.vast_url) {
                    logDiv.innerHTML += `<small class="text-success">✓ Pre-roll VAST: ${rep.vast_url}</small><br>`;
                    logDiv.innerHTML += `<small class="text-muted">Skip después de ${rep.skip_delay} segundos</small><br>`;
                }
                
                if (rep.vast_url_mid) {
                    logDiv.innerHTML += `<small class="text-warning">✓ Mid-roll VAST en segundo ${rep.mid_roll_time}</small><br>`;
                }
                
                if (rep.vast_url_post) {
                    logDiv.innerHTML += `<small class="text-danger">✓ Post-roll VAST configurado</small><br>`;
                }
                
                if (!rep.vast_url && !rep.vast_url_mid && !rep.vast_url_post) {
                    logDiv.innerHTML += `<small class="text-secondary">Sin VAST configurado - reproducción directa</small><br>`;
                }
                
                // Aquí se integraría con una librería VAST como Google IMA SDK
                logDiv.innerHTML += `<small class="text-info">Para integración completa, configure Google IMA SDK</small>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertify.error('Error al cargar reproductor');
        });
}
