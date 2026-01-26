

<div class="row"> 

        <!-- Modal -->
        <div class="modal fade" id="modalActualizarTablero" tabindex="-1" aria-labelledby="modalActualizarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarLabel">Actualizar Tablero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formActualizarTablero">
                <input type="hidden" id="idTablero" name="id_tablero">
                <input type="hidden" id="idUsuario" name="id_tablero">

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcionTablero" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcionTablero" name="descripcion" rows="3" placeholder="Escribe la nueva descripción"></textarea>
                </div>

              <!-- Foto de portada -->
                <label for="fotoPortada" class="form-label fw-bold">Cambia a gusto la foto de portada que deseas</label>
                <div class="mb-3 text-center p-3 border rounded shadow-sm" style="background-color:#f8f9fa;">
                    <!-- Imagen de vista previa -->
                    <img 
                        src="https://via.placeholder.com/300x300?text=Vista+Previa" 
                        alt="Vista previa" 
                        id="vistaPreviaImagen" 
                        class="img-fluid rounded mb-3 border" 
                        style="max-height:300px; max-width:300px; object-fit:cover;" 
                    />

                    <!-- Label e input --><br/>
                    <label for="fotoPortada" class="form-label fw-bold">📷 Foto de portada</label>
                    <input class="form-control" type="file" id="fotoPortada" name="foto" accept="image/*">
                </div>

                <!-- Reproductor VAST -->
                <div class="mb-3">
                    <label for="selectReproductor" class="form-label fw-bold">
                        <i class="fa-solid fa-play-circle"></i> Reproductor de Video (VAST)
                    </label>
                    <select class="form-select" id="selectReproductor" name="id_reproductor">
                        <option value="">Sin reproductor asignado</option>
                        <!-- Se llena dinámicamente -->
                    </select>
                    <small class="text-muted">Selecciona un reproductor para mostrar anuncios en los videos de este tablero</small>
                </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCambiosTablero()">Guardar cambios</button>
            </div>

            </div>
        </div>
        </div>


        {include file='back_office_components/menu_backoffice.tpl'}
        
        {if $option == 'usuarios'}  

            {include file='back_office_components/modulo_usuario.tpl'}

        {else if $option=='publicaciones'}
            <!-- Aqui se coloca el modulo de ver los posts de los usuarios-->
            {include file='back_office_components/modulo_boards.tpl'}


        {else if $option=='configuraciones'}
            
            <!-- Aqui se coloca el modulo de ver los posts de los usuarios-->
            {include file='back_office_components/modulo_configuracion.tpl'}

        {else if $option=='envar_correos'}

            {include file='back_office_components/enviar_correo.tpl'}
            
        {else if $option=='adm_banners'}   
            
            {include file='back_office_components/modulo_banner.tpl'}

        {else if $option=='reportes'}    
            {include file='back_office_components/modulo_reportes.tpl'}
        
        {else if $option=='monitoreo_rabbitmq'}    
            {include file='back_office_components/modulo_rabbitmq.tpl'}
        
        {else if $option=='estilos'}    
            {include file='back_office_components/modulo_estilos.tpl'}
        
        {else if $option=='reproductores'}    
            {include file='back_office_components/modulo_reproductores.tpl'}
        
        {else if $option=='monitor'}    
            {include file='back_office_components/modulo_monitor.tpl'}
        {/if}

</div>


<style>
    .content-container {
        margin-top: 50px;
        padding: 30px;
        background-color:#1a1c1d;
        border-radius: 10px;
    }
    h3 {
        margin-bottom: 30px;
    }
    .flex-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .checkbox-p {
        margin-right: 10px;
    }
    .table-dark th, .table-dark td {
        color: #f8f9fa;
    }
    .table-dark th {
        background-color: #6c757d;
    }
    .table-dark td {
        background-color: #495057;
    }
</style>