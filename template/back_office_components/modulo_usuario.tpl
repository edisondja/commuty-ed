<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="users">
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-users"></i> Gestión de Usuarios</h2>
        <span class="bo-badge bo-badge-info" id="total_usuarios">Cargando...</span>
    </div>
    
    <div class="bo-search-container">
        <i class="fa-solid fa-search"></i>
        <input type="search" class="form-control bo-search-input" id="search" placeholder="Buscar usuario por nombre, email...">
    </div>
    
    <div class="bo-card">
        <div class="bo-card-body" style="padding: 0; overflow-x: auto;">
            <table class="bo-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="data_usuario">
                    <tr>
                        <td colspan="5" class="bo-empty">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            <p>Cargando usuarios...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{$dominio}/js/back_office.js"></script>
