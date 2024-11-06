<div class="container mt-5" ><hr/>
    <h1 class="text-center" style="color: aliceblue;">Generador de PDF con Imágenes

    </h1>

    <div class="card mt-4">
        <div class="card-body">
        <div style="text-align: center;">
            <img src="assets/gem_pdf.png" style="height: 150px; width: 150px; border-radius: 5px;"><hr/>
            <strong>Gema de conversión PDF</strong>
        </div>
            <div class="row">
                <!-- Columna izquierda para el formulario -->
                <div class="col-md-6">
                    <form id="pdfForm" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-dark btn-block mt-3">Generar PDF</button><hr/>
                        <div class="form-group">
                            <label for="images">Selecciona Imágenes</label>
                            <input type="file" id="images" name="images[]" class="form-control-file" accept="image/*" multiple required>
                            <small class="form-text text-muted">Puedes seleccionar múltiples imágenes para generar el PDF.</small>
                        </div>
                        <div id="imagePreview" class="d-flex flex-wrap mt-3"></div>
                      

                    </form>
                </div>

                <!-- Columna derecha para el visor del PDF -->
                <div class="col-md-6">
                    <iframe id="pdfViewer" src="" width="100%" height="600px" frameborder="0"></iframe>
                </div>
            </div>
        </div>
</div>




    <div id="pdfResult" class="mt-4 text-center">
        <!-- Aquí se mostrará el enlace para descargar el PDF -->
    </div>
</div>


<script src="{$dominio}/js/convert_pdf.js"></script>