
/*
    Módulo de Monitoreo RabbitMQ
*/

var baseUrl = '';
var api_rabbitmq = '';

// Función para obtener baseUrl
function getBaseUrl() {
    if (window.BASE_URL) return window.BASE_URL;
    var dominioEl = document.getElementById('dominio');
    if (dominioEl && dominioEl.value) {
        var domVal = dominioEl.value;
        if (domVal.indexOf('http') === 0) {
            var match = domVal.match(/^https?:\/\/[^\/]+(\/.*)?$/);
            if (match && match[1]) return match[1].replace(/\/$/, '');
        } else if (domVal.indexOf('/') === 0) {
            return domVal.replace(/\/$/, '');
        }
    }
    return '';
}

// Actualizar estado al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    baseUrl = getBaseUrl();
    api_rabbitmq = (baseUrl || window.BASE_URL || '') + '/controllers/rabbitmq_monitor.php';
    console.log('bk_modulo_rabbitmq baseUrl:', baseUrl);
    
    actualizarEstado();
    // Actualizar cada 10 segundos
    setInterval(actualizarEstado, 10000);
});

function actualizarEstado() {
    console.log('Actualizando estado de RabbitMQ...');
    
    // Obtener estado de RabbitMQ
    obtenerEstadoRabbitMQ();
    
    // Obtener estado de colas
    obtenerEstadoColas();
    
    // Obtener estado de procesos
    obtenerEstadoProcesos();
}

function obtenerEstadoRabbitMQ() {
    axios.get(`${api_rabbitmq}?action=status`)
        .then(response => {
            const data = response.data;
            const statusDiv = document.getElementById('rabbitmq_status');
            const detailsDiv = document.getElementById('rabbitmq_details');
            
            if (data.success && data.connected) {
                statusDiv.className = 'alert alert-success';
                statusDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i> <strong>RabbitMQ está conectado y funcionando</strong>';
                detailsDiv.style.display = 'block';
                
                document.getElementById('rmq_host').textContent = data.host || '-';
                document.getElementById('rmq_port').textContent = data.port || '-';
                document.getElementById('rmq_user').textContent = data.user || '-';
                document.getElementById('rmq_vhost').textContent = data.vhost || '-';
            } else {
                statusDiv.className = 'alert alert-danger';
                statusDiv.innerHTML = '<i class="fa-solid fa-times-circle"></i> <strong>RabbitMQ no está disponible</strong><br>' + (data.message || 'No se pudo conectar');
                detailsDiv.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error al obtener estado de RabbitMQ:', error);
            const statusDiv = document.getElementById('rabbitmq_status');
            statusDiv.className = 'alert alert-danger';
            statusDiv.innerHTML = '<i class="fa-solid fa-times-circle"></i> <strong>Error al conectar con RabbitMQ</strong><br>' + (error.message || 'Error desconocido');
        });
}

function obtenerEstadoColas() {
    axios.get(`${api_rabbitmq}?action=queues`)
        .then(response => {
            const data = response.data;
            const queuesDiv = document.getElementById('queues_status');
            
            if (data.success) {
                let html = '<table class="table table-dark table-sm">';
                html += '<thead><tr><th>Cola</th><th>Mensajes</th><th>Consumidores</th><th>Estado</th></tr></thead><tbody>';
                
                if (data.queues && data.queues.length > 0) {
                    data.queues.forEach(queue => {
                        const statusClass = queue.messages > 0 ? 'warning' : 'success';
                        const statusIcon = queue.messages > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle';
                        html += `<tr>
                            <td><strong>${queue.name}</strong></td>
                            <td>${queue.messages}</td>
                            <td>${queue.consumers}</td>
                            <td><span class="badge bg-${statusClass}"><i class="fa-solid ${statusIcon}"></i> ${queue.messages > 0 ? 'Pendientes' : 'Vacía'}</span></td>
                        </tr>`;
                    });
                } else {
                    html += '<tr><td colspan="4" class="text-center">No se encontraron colas</td></tr>';
                }
                
                html += '</tbody></table>';
                queuesDiv.innerHTML = html;
                
                // Actualizar estadísticas
                const totalMessages = data.queues.reduce((sum, q) => sum + q.messages, 0);
                document.getElementById('stats_messages').textContent = totalMessages;
            } else {
                queuesDiv.innerHTML = `<div class="alert alert-danger">${data.message || 'Error al obtener información de colas'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error al obtener estado de colas:', error);
            document.getElementById('queues_status').innerHTML = 
                `<div class="alert alert-danger">Error al obtener información de colas: ${error.message}</div>`;
        });
}

function obtenerEstadoProcesos() {
    axios.get(`${api_rabbitmq}?action=processes`)
        .then(response => {
            const data = response.data;
            const processesDiv = document.getElementById('multimedia_status');
            
            if (data.success) {
                let html = '<div class="row">';
                
                // Consumer Service
                const consumerRunning = data.processes.consumer_service || false;
                html += `<div class="col-md-6 mb-3">
                    <div class="card ${consumerRunning ? 'bg-success' : 'bg-danger'}">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-video"></i> Consumer Service</h5>
                            <p class="card-text">
                                ${consumerRunning ? 
                                    '<i class="fa-solid fa-check-circle"></i> <strong>En ejecución</strong>' : 
                                    '<i class="fa-solid fa-times-circle"></i> <strong>Detenido</strong>'}
                            </p>
                            ${data.processes.consumer_pid ? `<small>PID: ${data.processes.consumer_pid}</small>` : ''}
                            <div class="mt-2">
                                ${consumerRunning ? 
                                    '<button class="btn btn-sm btn-danger" onclick="detenerServicio(\'consumer_service\')"><i class="fa-solid fa-stop"></i> Detener</button>' :
                                    '<button class="btn btn-sm btn-success" onclick="iniciarServicio(\'consumer_service\')"><i class="fa-solid fa-play"></i> Iniciar</button>'}
                            </div>
                        </div>
                    </div>
                </div>`;
                
                // Consumer Resultado
                const resultadoRunning = data.processes.consumer_resultado || false;
                html += `<div class="col-md-6 mb-3">
                    <div class="card ${resultadoRunning ? 'bg-success' : 'bg-danger'}">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-check-double"></i> Consumer Resultado</h5>
                            <p class="card-text">
                                ${resultadoRunning ? 
                                    '<i class="fa-solid fa-check-circle"></i> <strong>En ejecución</strong>' : 
                                    '<i class="fa-solid fa-times-circle"></i> <strong>Detenido</strong>'}
                            </p>
                            ${data.processes.resultado_pid ? `<small>PID: ${data.processes.resultado_pid}</small>` : ''}
                            <div class="mt-2">
                                ${resultadoRunning ? 
                                    '<button class="btn btn-sm btn-danger" onclick="detenerServicio(\'consumer_resultado\')"><i class="fa-solid fa-stop"></i> Detener</button>' :
                                    '<button class="btn btn-sm btn-success" onclick="iniciarServicio(\'consumer_resultado\')"><i class="fa-solid fa-play"></i> Iniciar</button>'}
                            </div>
                        </div>
                    </div>
                </div>`;
                
                html += '</div>';
                
                // Tabla de procesos activos
                if (data.processes.active_processes && data.processes.active_processes.length > 0) {
                    html += '<h5 class="mt-3">Procesos Activos de Multimedia</h5>';
                    html += '<table class="table table-dark table-sm"><thead><tr><th>Board ID</th><th>Estado</th><th>Tiempo</th></tr></thead><tbody>';
                    data.processes.active_processes.forEach(proc => {
                        html += `<tr>
                            <td>${proc.board_id || '-'}</td>
                            <td><span class="badge bg-info">${proc.status || 'Procesando'}</span></td>
                            <td>${proc.time || '-'}</td>
                        </tr>`;
                    });
                    html += '</tbody></table>';
                }
                
                processesDiv.innerHTML = html;
                
                // Actualizar estadísticas
                const activeCount = (consumerRunning ? 1 : 0) + (resultadoRunning ? 1 : 0);
                document.getElementById('stats_processes').textContent = activeCount + ' / 2';
            } else {
                processesDiv.innerHTML = `<div class="alert alert-danger">${data.message || 'Error al obtener información de procesos'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error al obtener estado de procesos:', error);
            document.getElementById('multimedia_status').innerHTML = 
                `<div class="alert alert-danger">Error al obtener información de procesos: ${error.message}</div>`;
        });
}

/**
 * Inicia un servicio de consumer
 */
function iniciarServicio(service) {
    if (!confirm(`¿Está seguro de que desea iniciar el servicio ${service}?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'start_service');
    formData.append('service', service);
    
    axios.post(api_rabbitmq, formData)
        .then(response => {
            const data = response.data;
            if (data.success) {
                if (typeof alertify !== 'undefined') {
                    alertify.success(data.message || 'Servicio iniciado correctamente');
                } else {
                    alert(data.message || 'Servicio iniciado correctamente');
                }
                // Actualizar estado después de 2 segundos
                setTimeout(actualizarEstado, 2000);
            } else {
                if (typeof alertify !== 'undefined') {
                    alertify.error(data.message || 'Error al iniciar el servicio');
                } else {
                    alert(data.message || 'Error al iniciar el servicio');
                }
            }
        })
        .catch(error => {
            console.error('Error al iniciar servicio:', error);
            const mensaje = error.response?.data?.message || error.message || 'Error al iniciar el servicio';
            if (typeof alertify !== 'undefined') {
                alertify.error(mensaje);
            } else {
                alert(mensaje);
            }
        });
}

/**
 * Detiene un servicio de consumer
 */
function detenerServicio(service) {
    if (!confirm(`¿Está seguro de que desea detener el servicio ${service}?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'stop_service');
    formData.append('service', service);
    
    axios.post(api_rabbitmq, formData)
        .then(response => {
            const data = response.data;
            if (data.success) {
                if (typeof alertify !== 'undefined') {
                    alertify.success(data.message || 'Servicio detenido correctamente');
                } else {
                    alert(data.message || 'Servicio detenido correctamente');
                }
                // Actualizar estado después de 2 segundos
                setTimeout(actualizarEstado, 2000);
            } else {
                if (typeof alertify !== 'undefined') {
                    alertify.error(data.message || 'Error al detener el servicio');
                } else {
                    alert(data.message || 'Error al detener el servicio');
                }
            }
        })
        .catch(error => {
            console.error('Error al detener servicio:', error);
            const mensaje = error.response?.data?.message || error.message || 'Error al detener el servicio';
            if (typeof alertify !== 'undefined') {
                alertify.error(mensaje);
            } else {
                alert(mensaje);
            }
        });
}
