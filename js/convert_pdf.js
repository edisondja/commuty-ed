  // Función para mostrar la vista previa de las imágenes seleccionadas
  document.getElementById('images').addEventListener('change', function(event) {
    const imagePreview = document.getElementById('imagePreview');
    imagePreview.innerHTML = ''; // Limpiar el contenedor de vista previa

    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Crear una miniatura para cada imagen
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('img-thumbnail', 'm-2');
            img.style.width = '150px';
            img.style.height = 'auto';
            imagePreview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});

// Función para enviar las imágenes seleccionadas al servidor y generar el PDF
document.getElementById('pdfForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData();
    const files = document.getElementById('images').files;
    formData.append('action','create_pdf');
    for (let i = 0; i < files.length; i++) {
        formData.append('images[]', files[i]);
    }

    axios.post('/controllers/actions_board.php', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {
        const data = response.data;
        if (data.success) {
            document.getElementById('pdfResult').innerHTML = `
                <a href="${data.pdfUrl}" class="btn btn-success mt-3" download="Generado.pdf">Descargar PDF</a>
            `;
            let embed = document.querySelector('#pdfViewer');

            embed.src = `${data.pdfUrl}`;


        } else {
            console.log(response.data);
            alert('Error al generar el PDF');
        }
    })
    .catch(error => console.error('Error:', error));
});