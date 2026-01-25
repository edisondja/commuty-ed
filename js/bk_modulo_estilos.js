/*
    Módulo de Gestión de Estilos y Colores
*/

document.addEventListener('DOMContentLoaded', function() {
    const dominio = document.getElementById('dominio_js').value;
    
    // Cargar estilos guardados al iniciar
    cargarEstilos();
    
    // Sincronizar inputs de color con inputs de texto
    sincronizarInputsColor();
    
    // Actualizar vista previa cuando cambien los colores
    actualizarVistaPrevia();
    
    // Evento para guardar estilos
    document.getElementById('guardar_estilos').addEventListener('click', guardarEstilos);
    
    // Evento para resetear estilos
    document.getElementById('resetear_estilos').addEventListener('click', resetearEstilos);
});

/**
 * Sincroniza los inputs de color con los inputs de texto
 */
function sincronizarInputsColor() {
    const colorInputs = [
        'color_primario', 'color_secundario', 'color_fondo', 'color_texto',
        'color_enlace', 'color_enlace_hover', 'color_boton', 'color_boton_hover',
        'color_tarjeta', 'color_borde', 'color_header'
    ];
    
    colorInputs.forEach(colorId => {
        const colorInput = document.getElementById(colorId);
        const textInput = document.getElementById(colorId + '_text');
        
        // Sincronizar color -> texto
        colorInput.addEventListener('input', function() {
            textInput.value = this.value;
            actualizarVistaPrevia();
        });
        
        // Sincronizar texto -> color
        textInput.addEventListener('input', function() {
            const colorValue = this.value;
            if (/^#[0-9A-F]{6}$/i.test(colorValue)) {
                colorInput.value = colorValue;
                actualizarVistaPrevia();
            }
        });
    });
}

/**
 * Actualiza la vista previa con los colores seleccionados
 */
function actualizarVistaPrevia() {
    const colorPrimario = document.getElementById('color_primario').value;
    const colorEnlace = document.getElementById('color_enlace').value;
    const colorTarjeta = document.getElementById('color_tarjeta').value;
    const colorTexto = document.getElementById('color_texto').value;
    const colorBorde = document.getElementById('color_borde').value;
    
    // Actualizar botón
    const previewButton = document.getElementById('preview_button');
    previewButton.style.backgroundColor = colorPrimario;
    previewButton.style.color = '#ffffff';
    
    // Actualizar enlace
    const previewLink = document.getElementById('preview_link');
    previewLink.style.color = colorEnlace;
    
    // Actualizar tarjeta
    const previewCard = document.getElementById('preview_card');
    previewCard.style.backgroundColor = colorTarjeta;
    previewCard.style.borderColor = colorBorde;
    previewCard.querySelector('h6').style.color = colorTexto;
    previewCard.querySelector('p').style.color = colorTexto;
}

/**
 * Carga los estilos guardados desde el servidor
 */
function cargarEstilos() {
    const dominio = document.getElementById('dominio_js').value;
    const FormDatas = new FormData();
    FormDatas.append('action', 'load_styles');
    
    axios.post('/controllers/actions_board.php', FormDatas)
        .then(response => {
            if (response.data && response.data.success) {
                const estilos = response.data.estilos || {};
                
                // Aplicar estilos a los inputs
                if (estilos.color_primario) {
                    document.getElementById('color_primario').value = estilos.color_primario;
                    document.getElementById('color_primario_text').value = estilos.color_primario;
                }
                if (estilos.color_secundario) {
                    document.getElementById('color_secundario').value = estilos.color_secundario;
                    document.getElementById('color_secundario_text').value = estilos.color_secundario;
                }
                if (estilos.color_fondo) {
                    document.getElementById('color_fondo').value = estilos.color_fondo;
                    document.getElementById('color_fondo_text').value = estilos.color_fondo;
                }
                if (estilos.color_texto) {
                    document.getElementById('color_texto').value = estilos.color_texto;
                    document.getElementById('color_texto_text').value = estilos.color_texto;
                }
                if (estilos.color_enlace) {
                    document.getElementById('color_enlace').value = estilos.color_enlace;
                    document.getElementById('color_enlace_text').value = estilos.color_enlace;
                }
                if (estilos.color_enlace_hover) {
                    document.getElementById('color_enlace_hover').value = estilos.color_enlace_hover;
                    document.getElementById('color_enlace_hover_text').value = estilos.color_enlace_hover;
                }
                if (estilos.color_boton) {
                    document.getElementById('color_boton').value = estilos.color_boton;
                    document.getElementById('color_boton_text').value = estilos.color_boton;
                }
                if (estilos.color_boton_hover) {
                    document.getElementById('color_boton_hover').value = estilos.color_boton_hover;
                    document.getElementById('color_boton_hover_text').value = estilos.color_boton_hover;
                }
                if (estilos.color_tarjeta) {
                    document.getElementById('color_tarjeta').value = estilos.color_tarjeta;
                    document.getElementById('color_tarjeta_text').value = estilos.color_tarjeta;
                }
                if (estilos.color_borde) {
                    document.getElementById('color_borde').value = estilos.color_borde;
                    document.getElementById('color_borde_text').value = estilos.color_borde;
                }
                if (estilos.color_header) {
                    document.getElementById('color_header').value = estilos.color_header;
                    document.getElementById('color_header_text').value = estilos.color_header;
                }
                
                actualizarVistaPrevia();
            }
        })
        .catch(error => {
            console.error('Error al cargar estilos:', error);
        });
}

/**
 * Guarda los estilos en el servidor
 */
function guardarEstilos() {
    const dominio = document.getElementById('dominio_js').value;
    const FormDatas = new FormData();
    FormDatas.append('action', 'save_styles');
    
    // Recopilar todos los valores de color
    FormDatas.append('color_primario', document.getElementById('color_primario').value);
    FormDatas.append('color_secundario', document.getElementById('color_secundario').value);
    FormDatas.append('color_fondo', document.getElementById('color_fondo').value);
    FormDatas.append('color_texto', document.getElementById('color_texto').value);
    FormDatas.append('color_enlace', document.getElementById('color_enlace').value);
    FormDatas.append('color_enlace_hover', document.getElementById('color_enlace_hover').value);
    FormDatas.append('color_boton', document.getElementById('color_boton').value);
    FormDatas.append('color_boton_hover', document.getElementById('color_boton_hover').value);
    FormDatas.append('color_tarjeta', document.getElementById('color_tarjeta').value);
    FormDatas.append('color_borde', document.getElementById('color_borde').value);
    FormDatas.append('color_header', document.getElementById('color_header').value);
    
    axios.post('/controllers/actions_board.php', FormDatas)
        .then(response => {
            const mensajeDiv = document.getElementById('mensaje_estilos');
            if (response.data && response.data.success) {
                mensajeDiv.className = 'alert alert-success';
                mensajeDiv.textContent = 'Estilos guardados correctamente';
                mensajeDiv.style.display = 'block';
                
                if (typeof alertify !== 'undefined') {
                    alertify.success('Estilos guardados correctamente');
                }
                
                // Ocultar mensaje después de 3 segundos
                setTimeout(() => {
                    mensajeDiv.style.display = 'none';
                }, 3000);
            } else {
                mensajeDiv.className = 'alert alert-danger';
                mensajeDiv.textContent = response.data.message || 'Error al guardar estilos';
                mensajeDiv.style.display = 'block';
                
                if (typeof alertify !== 'undefined') {
                    alertify.error('Error al guardar estilos');
                }
            }
        })
        .catch(error => {
            console.error('Error al guardar estilos:', error);
            const mensajeDiv = document.getElementById('mensaje_estilos');
            mensajeDiv.className = 'alert alert-danger';
            mensajeDiv.textContent = 'Error al guardar estilos: ' + (error.message || 'Error desconocido');
            mensajeDiv.style.display = 'block';
            
            if (typeof alertify !== 'undefined') {
                alertify.error('Error al guardar estilos');
            }
        });
}

/**
 * Restablece los valores por defecto
 */
function resetearEstilos() {
    if (!confirm('¿Está seguro de que desea restablecer los valores por defecto?')) {
        return;
    }
    
    // Valores por defecto
    document.getElementById('color_primario').value = '#20c997';
    document.getElementById('color_primario_text').value = '#20c997';
    document.getElementById('color_secundario').value = '#09b9e1';
    document.getElementById('color_secundario_text').value = '#09b9e1';
    document.getElementById('color_fondo').value = '#1a1c1d';
    document.getElementById('color_fondo_text').value = '#1a1c1d';
    document.getElementById('color_texto').value = '#cfd8dc';
    document.getElementById('color_texto_text').value = '#cfd8dc';
    document.getElementById('color_enlace').value = '#20c997';
    document.getElementById('color_enlace_text').value = '#20c997';
    document.getElementById('color_enlace_hover').value = '#17a085';
    document.getElementById('color_enlace_hover_text').value = '#17a085';
    document.getElementById('color_boton').value = '#20c997';
    document.getElementById('color_boton_text').value = '#20c997';
    document.getElementById('color_boton_hover').value = '#17a085';
    document.getElementById('color_boton_hover_text').value = '#17a085';
    document.getElementById('color_tarjeta').value = '#2d2d2d';
    document.getElementById('color_tarjeta_text').value = '#2d2d2d';
    document.getElementById('color_borde').value = '#444';
    document.getElementById('color_borde_text').value = '#444';
    document.getElementById('color_header').value = '#1a1a1a';
    document.getElementById('color_header_text').value = '#1a1a1a';
    
    actualizarVistaPrevia();
    
    if (typeof alertify !== 'undefined') {
        alertify.success('Valores restablecidos');
    }
});
