{* archivo: search_component.tpl *}
<style>
    .content-container {
        margin-top: 50px;
        padding: 30px;
        background-color: #495057;
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

<div class="col-md-8 col-12 content-container mx-auto tabla_buscar">
<h3 style="color: aliceblue;">Buscar publicaciones  <i class="fa-solid fa-photo-film"></i>   </h3>
    <!-- Configuracion de modulo para saber por que criterio se esta buscando
        desde el archivo back_office.js pueda detectar la configuracion
        para saber que datas filtrar -->
    <input type="hidden" id="modulo_select" value="boards">
    <input type="search" class="form-control mb-4" id="search" placeholder="Busca lo que deseas">
    

    <table class="table table-dark table-float-header ">
    <thead >
        <tr>
        
            <th>Descripcion</th>
            <th>Portada</th>
            <th>Fecha publicacion</th>
            <th>Estado</th>
            <th>Foto</th>
            <th>Usuario</th>
            <th>Block</th>
            
        </tr>
    </thead>
            <tbody id="data_boards" class="tabla_buscar"> 
                
            </tbody>
    </table>

</div>

<script src="{$dominio}/js/back_office.js"></script>