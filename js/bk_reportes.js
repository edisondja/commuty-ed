//En este script se cargan los reportes por lo usuario para que lo pueda visualizar el admin






function loadReports_admin() {
    let formData = new FormData();
    formData.append('action', 'load_report_admin');

    axios.post(`${dominio}/controllers/actions_board.php`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${token_get}`
        }
    }).then(response => {
        
        console.log(response.data);
        if (response.data.status === 'success') {
            const reports = response.data.reports;
            const reportList = document.getElementById('reportList');
            reportList.innerHTML = ''; // Limpiar lista antes de cargar


            reports.forEach(report => {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item';
                listItem.innerHTML = `
                    <strong>Usuario ID:</strong> ${report.id_usuario} <br>
                    <strong>Tablero ID:</strong> ${report.id_tablero} <br>
                    <strong>Razón:</strong> ${report.descripcion} <br>
                    <strong>Fecha:</strong> ${report.fecha_creacion} <br>
                    <strong>Estado:</strong> ${report.estado}
                `;
                reportList.appendChild(listItem);
            });
        } else {
            alertify.error('Error al cargar los reportes');
        }
    }).catch(error => {
        console.error('Error al cargar los reportes:', error);
        alertify.error('Error al cargar los reportes');
    });
}


loadReports_admin();