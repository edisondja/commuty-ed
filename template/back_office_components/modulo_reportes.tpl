{* archivo: search_component.tpl *}
<style>
.content-container_s {
    margin-top: 50px;
    padding: 30px;
    background-color: #ffffff; /* Fondo blanco */
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Título */
h3 {
    margin-bottom: 30px;
    color: #009688; /* Verde azulado */
    font-weight: bold;
}

/* Flex container */
.flex-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.checkbox-p {
    margin-right: 10px;
}

/* Input de búsqueda */
#search {
    border: 2px solid #009688; /* Verde azulado */
    border-radius: 8px;
    padding: 10px;
    color: #333;
    transition: 0.3s;
}

#search::placeholder {
    color: #707070; /* Placeholder gris suave */
}

#search:focus {
    border-color: #FF6F61; /* Coral al enfocar */
    box-shadow: 0 0 5px rgba(255,111,97,0.3);
}

/* Tabla personalizada */
.table-custom th {
    background-color: #009688; /* Verde azulado */
    color: #ffffff;            /* Texto blanco */
    border: none;
}

.table-custom td {
    background-color: #ffffff; /* Fondo blanco */
    color: #333333;            /* Texto gris oscuro */
    border-top: 1px solid #e0e0e0;
}

.table-custom tr:hover td {
    background-color: #f1fdfb; /* Hover verde azulado suave */
}
</style>


<div class="col-md-8 col-12 content-container_s mx-auto tabla_buscar">
    <h3>Lista de reportes de usuarios <i class="fa-solid fa-photo-film"></i></h3>

    <input type="hidden" id="modulo_select" value="boards">
    <input type="search" class="form-control mb-4" id="search" placeholder="Busca lo que deseas">

    <table class="table table-custom table-float-header">
        <thead>
            <tr>
                <th>Titulo</th>
                <th>Razon</th>
                <th>Fecha publicacion</th>
                <th>Usuario</th>
                <th>Foto</th>

            </tr>
        </thead>
        <tbody id="data_boards" class="tabla_buscar">
        </tbody>
    </table>
</div>

<script src="{$dominio}/js/back_office.js"></script>
<script src="{$dominio}/js/bk_reportes.js"></script>