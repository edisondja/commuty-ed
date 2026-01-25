<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="rabbitmq"/>
    <input type="hidden" id="dominio" value="{$dominio}">
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-server"></i> Monitoreo RabbitMQ</h2>
        <button class="bo-btn bo-btn-secondary" onclick="actualizarEstado()">
            <i class="fa-solid fa-refresh"></i> Actualizar
        </button>
    </div>
    
    <!-- Estado de Conexión -->
    <div class="bo-card">
        <div class="bo-card-header">
            <h5><i class="fa-solid fa-plug"></i> Estado de Conexión</h5>
        </div>
        <div class="bo-card-body">
            <div id="rabbitmq_status" class="bo-alert bo-alert-info">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <span>Verificando conexión...</span>
            </div>
            <div id="rabbitmq_details" style="display: none;">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="bo-stat-card">
                            <h3 id="rmq_host">-</h3>
                            <p>Host</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="bo-stat-card">
                            <h3 id="rmq_port">-</h3>
                            <p>Puerto</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="bo-stat-card">
                            <h3 id="rmq_user">-</h3>
                            <p>Usuario</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="bo-stat-card">
                            <h3 id="rmq_vhost">-</h3>
                            <p>VHost</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estado de Colas -->
    <div class="bo-card">
        <div class="bo-card-header">
            <h5><i class="fa-solid fa-list"></i> Estado de Colas</h5>
        </div>
        <div class="bo-card-body">
            <div id="queues_status">
                <div class="bo-alert bo-alert-info">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Cargando información de colas...</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Procesamiento Multimedia -->
    <div class="bo-card">
        <div class="bo-card-header">
            <h5><i class="fa-solid fa-video"></i> Procesamiento de Multimedia</h5>
        </div>
        <div class="bo-card-body">
            <div id="multimedia_status">
                <div class="bo-alert bo-alert-info">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Verificando procesos...</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="bo-card">
                <div class="bo-card-body text-center">
                    <i class="fa-solid fa-envelope fa-2x mb-3" style="color: var(--bo-secondary);"></i>
                    <h3 id="stats_messages" style="color: var(--bo-secondary);">-</h3>
                    <p style="color: rgba(255,255,255,0.6); margin: 0;">Mensajes en Cola</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="bo-card">
                <div class="bo-card-body text-center">
                    <i class="fa-solid fa-microchip fa-2x mb-3" style="color: var(--bo-secondary);"></i>
                    <h3 id="stats_processes" style="color: var(--bo-secondary);">-</h3>
                    <p style="color: rgba(255,255,255,0.6); margin: 0;">Procesos Activos</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{$dominio}/js/bk_modulo_rabbitmq.js"></script>
