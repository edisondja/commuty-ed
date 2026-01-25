<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="reports">
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-flag"></i> Reportes de Usuarios</h2>
        <span class="bo-badge bo-badge-warning" id="total_reportes">Cargando...</span>
    </div>
    
    <div class="bo-search-container">
        <i class="fa-solid fa-search"></i>
        <input type="search" class="form-control bo-search-input" id="search_report" placeholder="Buscar reporte...">
    </div>
    
    <div class="bo-card">
        <div class="bo-card-body" style="padding: 0; overflow-x: auto;">
            <table class="bo-table">
                <thead>
                    <tr>
                        <th>Publicación</th>
                        <th>Razón</th>
                        <th>Fecha</th>
                        <th>Reportado por</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="data_reports">
                    <tr>
                        <td colspan="6" class="bo-empty">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            <p>Cargando reportes...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{$dominio}/js/back_office.js"></script>
<script src="{$dominio}/js/bk_reportes.js"></script>
