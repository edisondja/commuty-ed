<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="monitor"/>
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-chart-line"></i> Monitor de Servicios en Tiempo Real</h2>
        <button class="btn btn-sm btn-outline-light" id="refresh-monitor">
            <i class="fa-solid fa-sync-alt"></i> Actualizar
        </button>
    </div>
    
    <div class="row mt-4">
        <!-- Servicios Systemd -->
        <div class="col-md-6 mb-4">
            <div class="bo-card">
                <div class="bo-card-header">
                    <h5><i class="fa-solid fa-server"></i> Servicios Systemd</h5>
                </div>
                <div class="bo-card-body" id="services-status">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- RabbitMQ -->
        <div class="col-md-6 mb-4">
            <div class="bo-card">
                <div class="bo-card-header">
                    <h5><i class="fa-solid fa-rabbit"></i> RabbitMQ</h5>
                </div>
                <div class="bo-card-body" id="rabbitmq-status">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Procesos PHP -->
        <div class="col-md-6 mb-4">
            <div class="bo-card">
                <div class="bo-card-header">
                    <h5><i class="fa-brands fa-php"></i> Procesos PHP</h5>
                </div>
                <div class="bo-card-body" id="php-processes">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Logs Recientes -->
        <div class="col-md-6 mb-4">
            <div class="bo-card">
                <div class="bo-card-header">
                    <h5><i class="fa-solid fa-file-lines"></i> Logs Recientes</h5>
                </div>
                <div class="bo-card-body" id="recent-logs" style="max-height: 400px; overflow-y: auto;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.service-item {
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 8px;
    border-left: 4px solid;
    background: rgba(255,255,255,0.05);
}

.service-item.active {
    border-left-color: #20c997;
    background: rgba(32, 201, 151, 0.1);
}

.service-item.inactive {
    border-left-color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.service-name {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 8px;
}

.service-status {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.active {
    background: #20c997;
    color: #000;
}

.status-badge.inactive {
    background: #dc3545;
    color: #fff;
}

.service-info {
    font-size: 13px;
    color: rgba(255,255,255,0.7);
    margin-top: 8px;
}

.queue-item {
    padding: 10px;
    margin-bottom: 8px;
    background: rgba(255,255,255,0.05);
    border-radius: 6px;
}

.queue-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.queue-stats {
    display: flex;
    gap: 15px;
    font-size: 13px;
}

.log-entry {
    padding: 8px;
    margin-bottom: 5px;
    background: rgba(0,0,0,0.3);
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    border-left: 3px solid #20c997;
}

.log-entry.error {
    border-left-color: #dc3545;
    color: #ff6b6b;
}

.auto-refresh-indicator {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(32, 201, 151, 0.9);
    color: #000;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    z-index: 1000;
}
</style>

<script src="{$dominio}/js/bk_modulo_monitor.js"></script>
