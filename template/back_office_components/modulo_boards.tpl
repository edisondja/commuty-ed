<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="boards">
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-photo-film"></i> Gestión de Publicaciones</h2>
        <span class="bo-badge bo-badge-info" id="total_boards">Cargando...</span>
    </div>
    
    <div class="bo-search-container">
        <i class="fa-solid fa-search"></i>
        <input type="search" class="form-control bo-search-input" id="search" placeholder="Buscar publicación...">
    </div>
    
    <div class="bo-card">
        <div class="bo-card-body" style="padding: 0; overflow-x: auto;">
            <table class="bo-table">
                <thead>
                    <tr>
                        <th>Contenido</th>
                        <th>Portada</th>
                        <th>Fecha</th>
                        <th>Autor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="data_boards">
                    <tr>
                        <td colspan="6" class="bo-empty">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            <p>Cargando publicaciones...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{$dominio}/js/back_office.js"></script>
