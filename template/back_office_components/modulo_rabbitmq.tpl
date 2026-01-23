
<div class="col-md-8 col-12 content-container_s mx-auto tabla_buscar">
    <div class="container mt-5">
        <input type="hidden" id="modulo_select" value="rabbitmq"/>
        <h2>Monitoreo RabbitMQ <i class="fa-solid fa-server"></i></h2>
        <button class="btn btn-primary" style="float: right;" onclick="actualizarEstado()">
            <i class="fa-solid fa-refresh"></i> Actualizar
        </button>
        <hr/>

        <!-- Estado de RabbitMQ -->
        <div class="card mb-4" style="background-color: #2d2d2d; color: white;">
            <div class="card-header" style="background-color: #1a1a1a;">
                <h4><i class="fa-solid fa-plug"></i> Estado de Conexión RabbitMQ</h4>
            </div>
            <div class="card-body">
                <div id="rabbitmq_status" class="alert alert-info">
                    <i class="fa-solid fa-spinner fa-spin"></i> Verificando...
                </div>
                <div id="rabbitmq_details" style="display: none;">
                    <table class="table table-dark table-sm">
                        <tr>
                            <th>Host:</th>
                            <td id="rmq_host">-</td>
                        </tr>
                        <tr>
                            <th>Puerto:</th>
                            <td id="rmq_port">-</td>
                        </tr>
                        <tr>
                            <th>Usuario:</th>
                            <td id="rmq_user">-</td>
                        </tr>
                        <tr>
                            <th>VHost:</th>
                            <td id="rmq_vhost">-</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Estado de Colas -->
        <div class="card mb-4" style="background-color: #2d2d2d; color: white;">
            <div class="card-header" style="background-color: #1a1a1a;">
                <h4><i class="fa-solid fa-list"></i> Estado de Colas</h4>
            </div>
            <div class="card-body">
                <div id="queues_status">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-spinner fa-spin"></i> Cargando información de colas...
                    </div>
                </div>
            </div>
        </div>

        <!-- Procesos de Multimedia -->
        <div class="card mb-4" style="background-color: #2d2d2d; color: white;">
            <div class="card-header" style="background-color: #1a1a1a;">
                <h4><i class="fa-solid fa-video"></i> Procesamiento de Multimedia</h4>
            </div>
            <div class="card-body">
                <div id="multimedia_status">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-spinner fa-spin"></i> Verificando procesos...
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="card mb-4" style="background-color: #2d2d2d; color: white;">
            <div class="card-header" style="background-color: #1a1a1a;">
                <h4><i class="fa-solid fa-chart-bar"></i> Estadísticas</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Mensajes en Cola</h5>
                                <p class="card-text" id="stats_messages">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Procesos Activos</h5>
                                <p class="card-text" id="stats_processes">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="dominio" value="{$dominio}">
<script src="{$dominio}/js/bk_modulo_rabbitmq.js"></script>
