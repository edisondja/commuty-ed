
<div class="col-md-8 col-12 content-container mx-auto tabla_buscar">
    <div class="container mt-5">
    <input type="hidden" id="modulo_select" value="config"/>
    <h2>Administrador de Banners <i class="fa-solid fa-gears"></i></h2>
    <div class="container mt-5">
    <!-- Pestañas de Navegación -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="configuracion-sitio-tab" data-toggle="tab" href="#publicar_banner_tab" role="tab" aria-controls="configuracion-sitio" aria-selected="true">Crear Banners <i class="fa-regular fa-file"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="configuracion-correo-tab" data-toggle="tab" href="#configuracion-correo" role="tab" aria-controls="configuracion-correo" aria-selected="false">Administrar Banners <i class="fa-solid fa-envelope"></i></a>
        </li>
    </ul><hr/>

    <div class="tab-content" id="myTabContent">
        <!-- Formalario de creacion de banner -->
        <div class="tab-pane fade show active" id="publicar_banner_tab" role="tabpanel" aria-labelledby="configuracion-sitio-tab">

            <div class="card-body">
            <form>
            <!-- Campo titulo -->
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" placeholder="Ingresa el título del anuncio">
            </div>

            <!-- Campo descripcion -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" rows="3" placeholder="Escribe la descripción del anuncio"></textarea>
            </div>

            <!-- Campo imagen_ruta -->
            <div class="mb-3">
                <label for="imagen_ruta" class="form-label">Subir iamgen</label>
                <input type="file" class="form-control" id="imagen_ruta" placeholder="Ingresa la ruta de la imagen">
            </div>

            <!-- Campo tipo -->
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Anuncio</label>
                <select class="form-select" id="tipo">
                <option value="">Selecciona el tipo de anuncio</option>
                <option value="banner">Banner</option>
                <option value="video">Video</option>
                <option value="texto">Texto</option>
                </select>
            </div>

            <!-- Campo script_banner -->
            <div class="mb-3">
                <label for="script_banner" class="form-label">Script Banner</label>
                <textarea class="form-control" id="script_banner" rows="3" placeholder="Ingresa el script del banner"></textarea>
            </div>

            <!-- Campo posicion -->
            <div class="mb-3">
                <label for="posicion" class="form-label">Posición del Anuncio</label>
                <input type="number" class="form-control" id="posicion" placeholder="Ingresa la posición del anuncio">
            </div>

            <!-- Botón de envío -->
            <button type="submit" class="btn btn-primary w-100">Guardar Anuncio</button>
            </form>
        </div>
            
        </div>

        <!-- Contenido de la pestaña de Configuración de Correo -->
        <div class="tab-pane fade" id="configuracion-correo" role="tabpanel" aria-labelledby="configuracion-correo-tab">
            
                <h3>Administrar Banners</h3>

        </div>
            </div>
        </div>
    </div>
    
</div>


<style>
body {
    background-color: #e6e6e6; /* Dark background */
    color: white; /* White text */
}
.form-control {
    background-color: #e7ecf0;
    color: rgb(88, 87, 87);
}
.form-control::placeholder {
    color: #707070;
}
</style>

<script src="{$dominio}/js/bk_modulo_banner.js"></script>

<!-- Incluye jQuery y Bootstrap JS -->
<!-- 
    Usa la libreria de jquery para desplegar los tab del menu
    y la de bootstrap 4.5.2
-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>