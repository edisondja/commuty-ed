<div class="modal fade" id="notificacionModal" tabindex="-1" aria-labelledby="notificacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="notificacionModalLabel">Notificaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <!-- Lista de notificaciones -->
                <div id="lista_notificaciones">
                    <!-- Ejemplo de notificación -->
                    {foreach $notificaciones as $key}
                    <div class="d-flex align-items-center mb-3">
                        <img src="{$dominio}/{$key['foto_url']}" style="height:50px;width:50px;border-radius:100px;" alt="Foto de perfil">
                        <div class="ms-3">      
                                <p><strong>{$key['nombre']}</strong> te ha mencionado en una publicación.</p>
                                <small class="text-muted">{$key['fecha']}</small>
                            <a href="single_board.php?id={$key['id_tablero']}">
                                <button type="button" class="btn btn-sm btn-dark" 
                                data-bs-toggle="modal" data-bs-target="#notificacionModal" 
                               style="padding: 0.25rem 0.5rem; font-size: 0.8rem; float:right">🔔 Ver</button>
                            </a>
                        </div>
               
                    </div>
                {/foreach}
                    <hr />
                    <!-- Más notificaciones aquí -->
                </div>
            </div>
            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>