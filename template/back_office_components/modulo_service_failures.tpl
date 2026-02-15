<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="service_failures"/>
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-triangle-exclamation"></i> Registro de Fallos de Servicios</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-light" id="refresh-failures">
                <i class="fa-solid fa-sync-alt"></i> Actualizar
            </button>
            <button class="btn btn-sm btn-outline-danger" id="clear-resolved">
                <i class="fa-solid fa-trash"></i> Limpiar Resueltos
            </button>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="bo-card mb-3">
        <div class="bo-card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="bo-label">Servicio</label>
                    <select class="form-control" id="filter-service">
                        <option value="">Todos</option>
                        <option value="commuty-consumer">Consumer Videos</option>
                        <option value="commuty-resultado">Consumer Resultados</option>
                        <option value="rabbitmq-server">RabbitMQ</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="bo-label">Estado</label>
                    <select class="form-control" id="filter-resolved">
                        <option value="">Todos</option>
                        <option value="0">No Resueltos</option>
                        <option value="1">Resueltos</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="bo-label">Desde</label>
                    <input type="date" class="form-control" id="filter-date-from">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="bo-label">Hasta</label>
                    <input type="date" class="form-control" id="filter-date-to">
                </div>
            </div>
            <div class="mt-2">
                <button class="btn btn-sm btn-primary" id="apply-filters">
                    <i class="fa-solid fa-filter"></i> Aplicar Filtros
                </button>
                <button class="btn btn-sm btn-outline-secondary" id="clear-filters">
                    <i class="fa-solid fa-times"></i> Limpiar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row mb-3" id="stats-container">
        <div class="col-md-4">
            <div class="bo-card">
                <div class="bo-card-body text-center">
                    <h3 id="total-failures" class="text-warning">0</h3>
                    <p class="mb-0">Total de Fallos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bo-card">
                <div class="bo-card-body text-center">
                    <h3 id="unresolved-failures" class="text-danger">0</h3>
                    <p class="mb-0">No Resueltos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bo-card">
                <div class="bo-card-body text-center">
                    <h3 id="resolved-failures" class="text-success">0</h3>
                    <p class="mb-0">Resueltos</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de fallos -->
    <div class="bo-card">
        <div class="bo-card-body">
            <div id="failures-table-container">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
            
            <!-- Paginación -->
            <div class="mt-3" id="pagination-container"></div>
        </div>
    </div>
</div>

<style>
.failure-item {
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    border-left: 4px solid;
    background: rgba(255,255,255,0.05);
}

.failure-item.unresolved {
    border-left-color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.failure-item.resolved {
    border-left-color: #20c997;
    background: rgba(32, 201, 151, 0.1);
    opacity: 0.7;
}

.failure-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.failure-service {
    font-weight: 600;
    font-size: 16px;
    color: #20c997;
}

.failure-timestamp {
    color: rgba(255,255,255,0.6);
    font-size: 13px;
}

.failure-message {
    color: #ff6b6b;
    margin: 10px 0;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    background: rgba(0,0,0,0.3);
    padding: 10px;
    border-radius: 4px;
    word-break: break-word;
}

.failure-actions {
    margin-top: 10px;
    display: flex;
    gap: 10px;
}

.badge-error-type {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.badge-error-type.fatal_error {
    background: #dc3545;
    color: #fff;
}

.badge-error-type.runtime_error {
    background: #ffc107;
    color: #000;
}

.badge-error-type.exit_error {
    background: #fd7e14;
    color: #fff;
}
</style>

<script src="{$dominio}/js/bk_modulo_service_failures.js"></script>
