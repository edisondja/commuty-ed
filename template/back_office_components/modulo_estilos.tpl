<div class="col-md-8 col-12 content-container_s mx-auto tabla_buscar">
    <div class="container mt-5">
        <input type="hidden" id="modulo_select" value="estilos"/>
        <input type="hidden" id="dominio_js" value="{$dominio}"/>
        <h2>Gestión de Estilos y Colores <i class="fa-solid fa-palette"></i></h2>
        <hr/>

        <div class="row">
            <!-- Colores Principales -->
            <div class="col-md-6 mb-4">
                <div class="card text-white bg-dark">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-paint-brush"></i> Colores Principales</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="color_primario" class="form-label">Color Primario</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_primario" value="#20c997" title="Color primario">
                                <input type="text" class="form-control" id="color_primario_text" value="#20c997" placeholder="#20c997">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_secundario" class="form-label">Color Secundario</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_secundario" value="#09b9e1" title="Color secundario">
                                <input type="text" class="form-control" id="color_secundario_text" value="#09b9e1" placeholder="#09b9e1">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_fondo" class="form-label">Color de Fondo</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_fondo" value="#1a1c1d" title="Color de fondo">
                                <input type="text" class="form-control" id="color_fondo_text" value="#1a1c1d" placeholder="#1a1c1d">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_texto" class="form-label">Color de Texto</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_texto" value="#cfd8dc" title="Color de texto">
                                <input type="text" class="form-control" id="color_texto_text" value="#cfd8dc" placeholder="#cfd8dc">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colores de Enlaces y Botones -->
            <div class="col-md-6 mb-4">
                <div class="card text-white bg-dark">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-link"></i> Enlaces y Botones</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="color_enlace" class="form-label">Color de Enlaces</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_enlace" value="#20c997" title="Color de enlaces">
                                <input type="text" class="form-control" id="color_enlace_text" value="#20c997" placeholder="#20c997">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_enlace_hover" class="form-label">Color Enlace Hover</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_enlace_hover" value="#17a085" title="Color hover">
                                <input type="text" class="form-control" id="color_enlace_hover_text" value="#17a085" placeholder="#17a085">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_boton" class="form-label">Color de Botones</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_boton" value="#20c997" title="Color de botones">
                                <input type="text" class="form-control" id="color_boton_text" value="#20c997" placeholder="#20c997">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_boton_hover" class="form-label">Color Botón Hover</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_boton_hover" value="#17a085" title="Color botón hover">
                                <input type="text" class="form-control" id="color_boton_hover_text" value="#17a085" placeholder="#17a085">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colores de Tarjetas y Contenedores -->
            <div class="col-md-6 mb-4">
                <div class="card text-white bg-dark">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-square"></i> Tarjetas y Contenedores</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="color_tarjeta" class="form-label">Fondo de Tarjetas</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_tarjeta" value="#2d2d2d" title="Fondo tarjetas">
                                <input type="text" class="form-control" id="color_tarjeta_text" value="#2d2d2d" placeholder="#2d2d2d">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_borde" class="form-label">Color de Bordes</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_borde" value="#444" title="Color bordes">
                                <input type="text" class="form-control" id="color_borde_text" value="#444" placeholder="#444">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_header" class="form-label">Fondo de Headers</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_header" value="#1a1a1a" title="Fondo headers">
                                <input type="text" class="form-control" id="color_header_text" value="#1a1a1a" placeholder="#1a1a1a">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vista Previa -->
            <div class="col-md-6 mb-4">
                <div class="card text-white bg-dark">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-eye"></i> Vista Previa</h5>
                    </div>
                    <div class="card-body" id="preview_container">
                        <div class="mb-3">
                            <button class="btn" id="preview_button" style="background-color: #20c997; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                                Botón de Ejemplo
                            </button>
                        </div>
                        <div class="mb-3">
                            <a href="#" id="preview_link" style="color: #20c997; text-decoration: none;">Enlace de Ejemplo</a>
                        </div>
                        <div class="mb-3 p-3" id="preview_card" style="background-color: #2d2d2d; border: 1px solid #444; border-radius: 5px;">
                            <h6 style="color: #cfd8dc;">Tarjeta de Ejemplo</h6>
                            <p style="color: #cfd8dc; margin: 0;">Este es un ejemplo de cómo se verán los elementos con los colores seleccionados.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="text-center mt-4">
            <button class="btn btn-primary" id="guardar_estilos" style="background-color: #20c997; border: none; padding: 10px 30px; margin-right: 10px;">
                <i class="fa-solid fa-save"></i> Guardar Estilos
            </button>
            <button class="btn btn-secondary" id="resetear_estilos" style="background-color: #6c757d; border: none; padding: 10px 30px;">
                <i class="fa-solid fa-rotate-left"></i> Restablecer Valores por Defecto
            </button>
        </div>

        <div class="mt-3">
            <div class="alert alert-info" id="mensaje_estilos" style="display: none;"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{$dominio}/js/bk_modulo_estilos.js"></script>

<style>
    .form-control-color {
        width: 60px;
        height: 38px;
        border: 1px solid #444;
        border-radius: 5px;
        cursor: pointer;
    }
    .input-group {
        display: flex;
        gap: 10px;
    }
    .input-group .form-control {
        flex: 1;
    }
</style>
