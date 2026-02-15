<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="estilos"/>
    <input type="hidden" id="dominio_js" value="{$dominio}"/>
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-palette"></i> Estilos y Colores</h2>
        <button class="bo-btn bo-btn-secondary" id="resetear_estilos">
            <i class="fa-solid fa-rotate-left"></i> Restablecer
        </button>
    </div>
    
    <div class="row">
        <!-- Colores Principales -->
        <div class="col-md-6 mb-4">
            <div class="bo-card">
                <div class="bo-card-header">
                    <h5><i class="fa-solid fa-paint-brush"></i> Colores Principales</h5>
                </div>
                <div class="bo-card-body">
                    <div class="mb-3">
                        <label class="bo-label">Color Primario</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_primario" value="#20c997" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_primario_text" value="#20c997">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bo-label">Color Secundario</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_secundario" value="#09b9e1" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_secundario_text" value="#09b9e1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bo-label">Color de Fondo</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_fondo" value="#1a1c1d" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_fondo_text" value="#1a1c1d">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bo-label">Color de Texto</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_texto" value="#cfd8dc" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_texto_text" value="#cfd8dc">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Enlaces y Botones -->
        <div class="col-md-6 mb-4">
            <div class="bo-card">
                <div class="bo-card-header">
                    <h5><i class="fa-solid fa-link"></i> Enlaces y Botones</h5>
                </div>
                <div class="bo-card-body">
                    <div class="mb-3">
                        <label class="bo-label">Color de Enlaces</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_enlace" value="#20c997" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_enlace_text" value="#20c997">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bo-label">Color Enlace Hover</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_enlace_hover" value="#17a085" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_enlace_hover_text" value="#17a085">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bo-label">Color de Botones</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_boton" value="#20c997" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_boton_text" value="#20c997">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bo-label">Color Botón Hover</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_boton_hover" value="#17a085" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_boton_hover_text" value="#17a085">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tarjetas y Contenedores -->
        <div class="col-md-6 mb-4">
            <div class="bo-card">
                <div class="bo-card-header">
                    <h5><i class="fa-solid fa-square"></i> Tarjetas y Contenedores</h5>
                </div>
                <div class="bo-card-body">
                    <div class="mb-3">
                        <label class="bo-label">Fondo de Tarjetas</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_tarjeta" value="#2d2d2d" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_tarjeta_text" value="#2d2d2d">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bo-label">Color de Bordes</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_borde" value="#444444" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_borde_text" value="#444444">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bo-label">Fondo de Headers</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control" id="color_header" value="#1a1a1a" style="width: 60px; height: 40px; padding: 2px;">
                            <input type="text" class="form-control" id="color_header_text" value="#1a1a1a">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vista Previa -->
        <div class="col-md-6 mb-4">
            <div class="bo-card">
                <div class="bo-card-header">
                    <h5><i class="fa-solid fa-eye"></i> Vista Previa</h5>
                </div>
                <div class="bo-card-body" id="preview_container">
                    <div class="mb-3">
                        <button class="bo-btn" id="preview_button" style="background-color: #20c997; color: white;">
                            Botón de Ejemplo
                        </button>
                    </div>
                    <div class="mb-3">
                        <a href="#" id="preview_link" style="color: #20c997;">Enlace de Ejemplo</a>
                    </div>
                    <div class="p-3" id="preview_card" style="background-color: #2d2d2d; border: 1px solid #444; border-radius: 8px;">
                        <h6 style="color: #cfd8dc;">Tarjeta de Ejemplo</h6>
                        <p style="color: #cfd8dc; margin: 0; font-size: 0.9rem;">Así se verán los elementos con los colores seleccionados.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <button class="bo-btn bo-btn-primary" id="guardar_estilos">
            <i class="fa-solid fa-save"></i> Guardar Estilos
        </button>
    </div>
    
    <div class="mt-3">
        <div class="bo-alert bo-alert-success" id="mensaje_estilos" style="display: none;"></div>
    </div>
</div>

<script type="text/javascript" src="{$dominio}/js/bk_modulo_estilos.js"></script>
