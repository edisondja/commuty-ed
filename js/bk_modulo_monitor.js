/*
    Módulo de Monitoreo en Tiempo Real
*/

(function() {
    'use strict';

    var baseUrl = '';
    var apiUrl = '';
    var refreshInterval = null;
    var autoRefreshEnabled = true;
    var refreshIntervalTime = 3000; // 3 segundos

    // Función para obtener baseUrl
    function getBaseUrl() {
        if (window.BASE_URL) return window.BASE_URL;
        var dominioEl = document.getElementById('dominio');
        if (dominioEl && dominioEl.value) {
            var domVal = dominioEl.value;
            if (domVal.indexOf('http') === 0) {
                try {
                    var url = new URL(domVal);
                    return url.pathname.replace(/\/$/, '') || '';
                } catch(e) {
                    var match = domVal.match(/^https?:\/\/[^\/]+(\/.*)?$/);
                    return (match && match[1]) ? match[1].replace(/\/$/, '') : '';
                }
            } else if (domVal.indexOf('/') === 0) {
                return domVal.replace(/\/$/, '');
            }
        }
        return '';
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        baseUrl = getBaseUrl();
        apiUrl = (baseUrl || window.BASE_URL || '') + '/controllers/monitor_services.php';
        
        console.log('Monitor inicializado - API:', apiUrl);
        
        // Cargar datos iniciales
        loadMonitorData();
        
        // Configurar auto-refresh
        if (autoRefreshEnabled) {
            startAutoRefresh();
        }
        
        // Botón de refresh manual
        var refreshBtn = document.getElementById('refresh-monitor');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                loadMonitorData();
            });
        }
    });

    // Cargar datos del monitor
    function loadMonitorData() {
        if (!apiUrl) {
            console.error('API URL no configurada');
            return;
        }

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                renderServices(data.services || {});
                renderRabbitMQ(data.rabbitmq || {});
                renderPHPProcesses(data.php_processes || []);
                renderLogs(data.logs || {});
                updateTimestamp(data.timestamp);
            })
            .catch(error => {
                console.error('Error cargando datos del monitor:', error);
                showError('Error al cargar datos del monitor');
            });
    }

    // Renderizar servicios systemd
    function renderServices(services) {
        var container = document.getElementById('services-status');
        if (!container) return;

        var html = '';
        
        for (var serviceName in services) {
            var service = services[serviceName];
            var isActive = service.active;
            var statusClass = isActive ? 'active' : 'inactive';
            var statusBadge = isActive ? 
                '<span class="status-badge active">ACTIVO</span>' : 
                '<span class="status-badge inactive">INACTIVO</span>';
            
            html += '<div class="service-item ' + statusClass + '">';
            html += '<div class="service-name">' + serviceName + '</div>';
            html += '<div class="service-status">' + statusBadge + '</div>';
            
            if (isActive) {
                if (service.memory) {
                    html += '<div class="service-info">💾 Memoria: ' + service.memory + '</div>';
                }
                if (service.cpu) {
                    html += '<div class="service-info">⚡ CPU: ' + service.cpu + '</div>';
                }
                if (service.uptime) {
                    html += '<div class="service-info">🕐 Iniciado: ' + service.uptime + '</div>';
                }
            }
            
            html += '</div>';
        }
        
        container.innerHTML = html || '<p class="text-muted">No hay servicios configurados</p>';
    }

    // Renderizar estado de RabbitMQ
    function renderRabbitMQ(rabbitmq) {
        var container = document.getElementById('rabbitmq-status');
        if (!container) return;

        var html = '';
        
        if (rabbitmq.connected) {
            html += '<div class="service-item active">';
            html += '<div class="service-name">Conexión</div>';
            html += '<div class="service-status"><span class="status-badge active">CONECTADO</span></div>';
            html += '</div>';
            
            if (rabbitmq.queues) {
                for (var queueName in rabbitmq.queues) {
                    var queue = rabbitmq.queues[queueName];
                    if (queue.error) {
                        html += '<div class="queue-item">';
                        html += '<div class="queue-name">' + queueName + '</div>';
                        html += '<div class="text-danger">❌ Error: ' + queue.error + '</div>';
                        html += '</div>';
                    } else {
                        html += '<div class="queue-item">';
                        html += '<div class="queue-name">' + queueName + '</div>';
                        html += '<div class="queue-stats">';
                        html += '<span>📨 Mensajes: <strong>' + (queue.messages || 0) + '</strong></span>';
                        html += '<span>👥 Consumers: <strong>' + (queue.consumers || 0) + '</strong></span>';
                        html += '</div>';
                        html += '</div>';
                    }
                }
            }
        } else {
            html += '<div class="service-item inactive">';
            html += '<div class="service-name">Conexión</div>';
            html += '<div class="service-status"><span class="status-badge inactive">DESCONECTADO</span></div>';
            if (rabbitmq.error) {
                html += '<div class="service-info text-danger">Error: ' + rabbitmq.error + '</div>';
            }
            html += '</div>';
        }
        
        container.innerHTML = html || '<p class="text-muted">No hay información disponible</p>';
    }

    // Renderizar procesos PHP
    function renderPHPProcesses(processes) {
        var container = document.getElementById('php-processes');
        if (!container) return;

        var html = '';
        
        if (processes.length > 0) {
            html += '<div class="service-item active">';
            html += '<div class="service-name">Procesos Activos: ' + processes.length + '</div>';
            
            processes.forEach(function(proc) {
                html += '<div class="service-info" style="margin-top: 10px; padding: 8px; background: rgba(0,0,0,0.2); border-radius: 4px;">';
                html += '<div><strong>' + proc.script + '</strong></div>';
                html += '<div>PID: ' + proc.pid + ' | CPU: ' + proc.cpu + ' | Memoria: ' + proc.memory + '</div>';
                html += '</div>';
            });
            
            html += '</div>';
        } else {
            html += '<div class="service-item inactive">';
            html += '<div class="service-name">No hay procesos PHP corriendo</div>';
            html += '</div>';
        }
        
        container.innerHTML = html;
    }

    // Renderizar logs
    function renderLogs(logs) {
        var container = document.getElementById('recent-logs');
        if (!container) return;

        var html = '';
        
        for (var logType in logs) {
            var logEntries = logs[logType];
            if (logEntries && logEntries.length > 0) {
                html += '<div style="margin-bottom: 15px;">';
                html += '<strong style="color: #20c997;">' + logType + ':</strong>';
                
                logEntries.forEach(function(entry) {
                    if (entry.trim()) {
                        var isError = entry.toLowerCase().includes('error') || 
                                     entry.toLowerCase().includes('warning') ||
                                     entry.toLowerCase().includes('failed');
                        var logClass = isError ? 'error' : '';
                        html += '<div class="log-entry ' + logClass + '">' + escapeHtml(entry) + '</div>';
                    }
                });
                
                html += '</div>';
            }
        }
        
        container.innerHTML = html || '<p class="text-muted">No hay logs recientes</p>';
        
        // Auto-scroll al final
        container.scrollTop = container.scrollHeight;
    }

    // Actualizar timestamp
    function updateTimestamp(timestamp) {
        // Puedes agregar un indicador visual de última actualización
        var indicator = document.querySelector('.auto-refresh-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'auto-refresh-indicator';
            document.body.appendChild(indicator);
        }
        indicator.textContent = '🔄 Actualizado: ' + timestamp;
    }

    // Iniciar auto-refresh
    function startAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        refreshInterval = setInterval(function() {
            loadMonitorData();
        }, refreshIntervalTime);
    }

    // Detener auto-refresh
    function stopAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }

    // Mostrar error
    function showError(message) {
        var containers = ['services-status', 'rabbitmq-status', 'php-processes', 'recent-logs'];
        containers.forEach(function(id) {
            var container = document.getElementById(id);
            if (container) {
                container.innerHTML = '<div class="alert alert-danger">' + message + '</div>';
            }
        });
    }

    // Escapar HTML
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Exponer funciones globalmente si es necesario
    window.monitorControl = {
        refresh: loadMonitorData,
        startAutoRefresh: startAutoRefresh,
        stopAutoRefresh: stopAutoRefresh
    };

})();
