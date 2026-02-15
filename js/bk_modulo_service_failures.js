/*
    Módulo de Registro de Fallos de Servicios
*/

(function() {
    'use strict';

    var baseUrl = '';
    var apiUrl = '';
    var currentPage = 1;
    var currentFilters = {};

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
        apiUrl = (baseUrl || window.BASE_URL || '') + '/controllers/get_service_failures.php';
        
        console.log('Service Failures Module inicializado - API:', apiUrl);
        
        // Cargar datos iniciales
        loadFailures();
        
        // Event listeners
        document.getElementById('refresh-failures').addEventListener('click', function() {
            loadFailures();
        });
        
        document.getElementById('apply-filters').addEventListener('click', function() {
            applyFilters();
        });
        
        document.getElementById('clear-filters').addEventListener('click', function() {
            clearFilters();
        });
        
        document.getElementById('clear-resolved').addEventListener('click', function() {
            if (confirm('¿Estás seguro de eliminar todos los fallos resueltos?')) {
                clearResolvedFailures();
            }
        });
    });

    // Cargar fallos
    function loadFailures(page = 1) {
        currentPage = page;
        
        var url = apiUrl + '?page=' + page + '&limit=20';
        
        // Agregar filtros
        for (var key in currentFilters) {
            if (currentFilters[key]) {
                url += '&' + key + '=' + encodeURIComponent(currentFilters[key]);
            }
        }
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderFailures(data.data);
                    renderPagination(data.pagination);
                    updateStats(data.data);
                } else {
                    showError(data.message || 'Error al cargar fallos');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Error al cargar fallos de servicios');
            });
    }

    // Renderizar fallos
    function renderFailures(failures) {
        var container = document.getElementById('failures-table-container');
        if (!container) return;

        if (failures.length === 0) {
            container.innerHTML = '<div class="text-center text-muted p-4">No hay fallos registrados</div>';
            return;
        }

        var html = '';
        
        failures.forEach(function(failure) {
            var resolvedClass = failure.resolved ? 'resolved' : 'unresolved';
            var resolvedBadge = failure.resolved ? 
                '<span class="badge bg-success">Resuelto</span>' : 
                '<span class="badge bg-danger">No Resuelto</span>';
            
            var errorTypeClass = 'badge-error-type ' + (failure.error_type || 'runtime_error');
            
            html += '<div class="failure-item ' + resolvedClass + '">';
            html += '<div class="failure-header">';
            html += '<div>';
            html += '<span class="failure-service">' + escapeHtml(failure.service_name) + '</span>';
            html += ' <span class="' + errorTypeClass + '">' + escapeHtml(failure.error_type || 'unknown') + '</span>';
            html += '</div>';
            html += '<div class="d-flex align-items-center gap-2">';
            html += resolvedBadge;
            html += '<span class="failure-timestamp">' + failure.timestamp + '</span>';
            html += '</div>';
            html += '</div>';
            
            html += '<div class="failure-message">' + escapeHtml(failure.error_message) + '</div>';
            
            if (failure.stack_trace) {
                html += '<details class="mt-2">';
                html += '<summary style="cursor: pointer; color: rgba(255,255,255,0.7);">Stack Trace</summary>';
                html += '<pre style="background: rgba(0,0,0,0.3); padding: 10px; border-radius: 4px; font-size: 11px; overflow-x: auto;">' + escapeHtml(failure.stack_trace) + '</pre>';
                html += '</details>';
            }
            
            if (failure.additional_data && Object.keys(failure.additional_data).length > 0) {
                html += '<details class="mt-2">';
                html += '<summary style="cursor: pointer; color: rgba(255,255,255,0.7);">Información Adicional</summary>';
                html += '<pre style="background: rgba(0,0,0,0.3); padding: 10px; border-radius: 4px; font-size: 11px; overflow-x: auto;">' + JSON.stringify(failure.additional_data, null, 2) + '</pre>';
                html += '</details>';
            }
            
            if (failure.resolved && failure.resolved_by) {
                html += '<div class="mt-2" style="color: rgba(255,255,255,0.6); font-size: 12px;">';
                html += 'Resuelto por: ' + escapeHtml(failure.resolved_by) + ' el ' + (failure.resolved_at || '');
                html += '</div>';
            }
            
            html += '<div class="failure-actions">';
            if (!failure.resolved) {
                html += '<button class="btn btn-sm btn-success" onclick="markAsResolved(' + failure.id + ')">';
                html += '<i class="fa-solid fa-check"></i> Marcar como Resuelto';
                html += '</button>';
            } else {
                html += '<button class="btn btn-sm btn-warning" onclick="markAsUnresolved(' + failure.id + ')">';
                html += '<i class="fa-solid fa-undo"></i> Marcar como No Resuelto';
                html += '</button>';
            }
            html += '</div>';
            
            html += '</div>';
        });
        
        container.innerHTML = html;
    }

    // Renderizar paginación
    function renderPagination(pagination) {
        var container = document.getElementById('pagination-container');
        if (!container || !pagination || pagination.pages <= 1) {
            if (container) container.innerHTML = '';
            return;
        }

        var html = '<nav><ul class="pagination justify-content-center">';
        
        // Botón anterior
        html += '<li class="page-item' + (pagination.page <= 1 ? ' disabled' : '') + '">';
        html += '<a class="page-link" href="#" onclick="loadFailuresPage(' + (pagination.page - 1) + '); return false;">Anterior</a>';
        html += '</li>';
        
        // Páginas
        for (var i = 1; i <= pagination.pages; i++) {
            if (i === 1 || i === pagination.pages || (i >= pagination.page - 2 && i <= pagination.page + 2)) {
                html += '<li class="page-item' + (i === pagination.page ? ' active' : '') + '">';
                html += '<a class="page-link" href="#" onclick="loadFailuresPage(' + i + '); return false;">' + i + '</a>';
                html += '</li>';
            } else if (i === pagination.page - 3 || i === pagination.page + 3) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Botón siguiente
        html += '<li class="page-item' + (pagination.page >= pagination.pages ? ' disabled' : '') + '">';
        html += '<a class="page-link" href="#" onclick="loadFailuresPage(' + (pagination.page + 1) + '); return false;">Siguiente</a>';
        html += '</li>';
        
        html += '</ul></nav>';
        html += '<div class="text-center text-muted mt-2">Página ' + pagination.page + ' de ' + pagination.pages + ' (' + pagination.total + ' total)</div>';
        
        container.innerHTML = html;
    }

    // Actualizar estadísticas
    function updateStats(failures) {
        var total = failures.length;
        var unresolved = failures.filter(f => !f.resolved).length;
        var resolved = failures.filter(f => f.resolved).length;
        
        document.getElementById('total-failures').textContent = total;
        document.getElementById('unresolved-failures').textContent = unresolved;
        document.getElementById('resolved-failures').textContent = resolved;
    }

    // Aplicar filtros
    function applyFilters() {
        currentFilters = {
            service: document.getElementById('filter-service').value,
            resolved: document.getElementById('filter-resolved').value,
            date_from: document.getElementById('filter-date-from').value,
            date_to: document.getElementById('filter-date-to').value
        };
        
        loadFailures(1);
    }

    // Limpiar filtros
    function clearFilters() {
        document.getElementById('filter-service').value = '';
        document.getElementById('filter-resolved').value = '';
        document.getElementById('filter-date-from').value = '';
        document.getElementById('filter-date-to').value = '';
        currentFilters = {};
        loadFailures(1);
    }

    // Marcar como resuelto
    window.markAsResolved = function(id) {
        var apiResolve = (baseUrl || window.BASE_URL || '') + '/controllers/resolve_service_failure.php';
        
        fetch(apiResolve, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id, resolved: true, resolved_by: 'admin'})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertify.success('Fallo marcado como resuelto');
                loadFailures(currentPage);
            } else {
                alertify.error(data.message || 'Error al marcar como resuelto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertify.error('Error al marcar como resuelto');
        });
    };

    // Marcar como no resuelto
    window.markAsUnresolved = function(id) {
        var apiResolve = (baseUrl || window.BASE_URL || '') + '/controllers/resolve_service_failure.php';
        
        fetch(apiResolve, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id, resolved: false})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertify.success('Fallo marcado como no resuelto');
                loadFailures(currentPage);
            } else {
                alertify.error(data.message || 'Error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertify.error('Error');
        });
    };

    // Cargar página específica
    window.loadFailuresPage = function(page) {
        loadFailures(page);
    };

    // Limpiar resueltos
    function clearResolvedFailures() {
        // Implementar lógica para eliminar resueltos
        alertify.message('Funcionalidad en desarrollo');
    }

    // Mostrar error
    function showError(message) {
        var container = document.getElementById('failures-table-container');
        if (container) {
            container.innerHTML = '<div class="alert alert-danger">' + escapeHtml(message) + '</div>';
        }
    }

    // Escapar HTML
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Exponer función globalmente
    window.serviceFailuresControl = {
        load: loadFailures,
        refresh: function() { loadFailures(currentPage); }
    };

})();
