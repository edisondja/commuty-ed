//En este script se cargan los reportes por lo usuario para que lo pueda visualizar el admin

var baseUrl = '';

// Función para obtener baseUrl
function getBaseUrl() {
    if (window.BASE_URL) return window.BASE_URL;
    var dominioEl = document.getElementById('dominio');
    if (dominioEl && dominioEl.value) {
        var domVal = dominioEl.value;
        if (domVal.indexOf('http') === 0) {
            var match = domVal.match(/^https?:\/\/[^\/]+(\/.*)?$/);
            if (match && match[1]) return match[1].replace(/\/$/, '');
        } else if (domVal.indexOf('/') === 0) {
            return domVal.replace(/\/$/, '');
        }
    }
    return '';
}

function loadReports_admin() {
    let formData = new FormData();
    formData.append('action', 'load_report_admin');


    axios.post((baseUrl || window.BASE_URL || '') + '/controllers/actions_board.php', formData, {
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

        axios.post((baseUrl || window.BASE_URL || '') + '/controllers/actions_board.php', formData, {
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


// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    baseUrl = getBaseUrl();
    console.log('bk_reportes baseUrl:', baseUrl);
    
    loadReports_admin();

    let searchInput = document.getElementById('search_report');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            let query = searchInput.value.trim();
            if (query.length > 0) {
                searchReports_admin(query);
            } else {
                loadReports_admin();
            }
        });
    }
});