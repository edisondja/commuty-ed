//En este script se cargan los reportes por lo usuario para que lo pueda visualizar el admin

function loadReports_admin() {
    let formData = new FormData();
    formData.append('action', 'load_report_admin');


    axios.post('/controllers/actions_board.php', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${token_get}`
        }
    }).then(response => {
        
        console.log(response.data);
     
            const reports = response.data;
            const reportList = document.getElementById('data_reports');
            reportList.innerHTML = ''; // Limpiar lista antes de cargar

            reports.forEach(report => {

                console.log(report.razon);
                reportList.innerHTML += tabla_report(report);

            });



    }).catch(error => {
        console.error('Error al cargar los reportes:', error);
    });
}


    function tabla_report(data) {

        let descripcion = "";
        if (data.descripcion && data.descripcion.length > 0) {
            descripcion = data.descripcion.length > 80
                ? data.descripcion.substring(0, 80) + ".."
                : data.descripcion;
        } else {
            descripcion = "Sin descripción";
        }

        let razon = "";
        if (data.razon && data.razon.length > 0) {
            razon = data.razon.length > 80
                ? data.razon.substring(0, 80) + ".."
                : data.razon;
        } else {
            razon = "Sin razón";
        }


        let Row = `
            <tr>  
                <td>${descripcion}</td>
                <td>${razon}</td>
                <td>${data.fecha_creacion}</td>
                <td>${data.usuario}</td>
                <td>
                    <img class="imagenPerfil" 
                        src="/${data.imagen_tablero}" 
                        alt="media" 
                        width="50" />
                </td>
                <td>${data.estado_tablero}</td>
            </tr>`;

        return Row;
    }

    function searchReports_admin(texto) {
        let formData = new FormData();
        formData.append('action', 'buscar_reporte');
        formData.append('texto', texto);

        axios.post('/controllers/actions_board.php', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${token_get}`
            }
        }).then(response => {
            console.log(response.data);
            const reports = response.data;
            const reportList = document.getElementById('data_reports');
            reportList.innerHTML = ''; // Limpiar lista antes de cargar

            reports.forEach(report => {
                console.log(report.razon);
                reportList.innerHTML += tabla_report(report);
            });
            reportList.insertAdjacentHTML('beforeend', `<tbody>`);


        }).catch(error => {
            console.error('Error al buscar los reportes:', error);
            alertify.error('Error al buscar los reportes');
        });
    }


loadReports_admin();


let searchInput = document.getElementById('search_report');

searchInput.addEventListener('input', function() {
    let query = searchInput.value.trim();
    if (query.length > 0) {
        searchReports_admin(query);
    } else {
        loadReports_admin(); // Cargar todos los reportes si el campo de búsqueda está vacío
    }
});