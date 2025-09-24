
cargar_banners();

document.querySelector("#guardar_ads").addEventListener('click',()=>{


        guardar_banner();

});




// Abrir modal para nuevo banner
  function abrirModalNuevo(id_banner){

    document.getElementById('modalBannerLabel').innerText = 'Nuevo Banner';
    document.getElementById('formBanner').reset();
    document.getElementById('id_banner').value = '';
    document.getElementById('previewImagen').style.display = 'none';
    const modal = new bootstrap.Modal(document.getElementById('modalBanner'));
    modal.show();

    abrirModalEditar(id_banner);
  }

  // Preview de imagen
  document.getElementById('imagen_banner').addEventListener('change', function(e){
    const preview = document.getElementById('previewImagen');
    const file = e.target.files[0];
    if(file){
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
    } else {
      preview.style.display = 'none';
    }
  });


  // Abrir modal para editar banner con datos del API
function abrirModalEditar(id_banner) {

  let FormDatas = new FormData();
  FormDatas.append('action','cargar_ads_s');
  FormDatas.append('ads_id',id_banner);

 axios.post(`${dominio.value}/controllers/actions_board.php`,FormDatas,{headers:{
                'Content-Type': 'multipart/form-data'
                //'Authorization': `Bearer ${token_get}`
                }  
}).then(response => {
      const data = response.data;
      console.log(response);

      document.getElementById('modalBannerLabel').innerText = 'Editar Banner';
      document.getElementById('id_banner').value = data.ads_id;
      document.getElementById('titulo').value = data.titulo;
      document.getElementById('descripcion').value = data.descripcion;
      document.getElementById('tipo').value = data.tipo;
      document.getElementById('script_banner').value = data.script_banner;
      document.getElementById('posicion').value = data.posicion;
      document.getElementById('link_banner').value = data.link_banner;
      document.getElementById('imagen_original').value = data.imagen_ruta; 
      document.getElementById('estado_banner').checked = data.estado === 'activo';


      const preview = document.getElementById('previewImagen');
      if (data.imagen_ruta) {
          preview.src = data.imagen_ruta;
          preview.style.display = 'block';
      } else {
          preview.style.display = 'none';
      }

      const modal = new bootstrap.Modal(document.getElementById('modalBanner'));
      modal.show();
  })
  .catch(error => {
      console.error("Error obteniendo banner:", error);
      alert("Ocurrió un error cargando los datos del banner.");
  });
}


// Guardar cambios de Banner (UPDATE o INSERT según tu backend)
function actualizar_ads() {

  let formData = new FormData();
  formData.append('action', 'guardar_ads'); // o 'update_ads' según tu backend
  formData.append('ads_id', document.getElementById('id_banner').value);
  formData.append('titulo', document.getElementById('titulo').value);
  formData.append('descripcion', document.getElementById('descripcion').value);
  formData.append('tipo', document.getElementById('tipo').value);
  formData.append('script_banner', document.getElementById('script_banner').value);
  formData.append('posicion', document.getElementById('posicion').value);
  formData.append('link_banner', document.getElementById('link_banner').value);
  formData.append('estado', document.getElementById('estado_banner').checked ? 'activo' : 'inactivo');
  
  // Si seleccionaron una nueva imagen
  let nuevaImagen = document.getElementById('imagen_banner').files[0];
  if (nuevaImagen) {
    formData.append('imagen_banner', nuevaImagen);
  } else {
    // Enviar la imagen original si no fue reemplazada
    formData.append('imagen_ruta', document.getElementById('imagen_original').value);
  }

  axios.post(`${dominio.value}/controllers/actions_board.php`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
      // 'Authorization': `Bearer ${token_get}`
    }
  })
  .then(response => {
    console.log("Respuesta:", response.data);

    if (response.data.status === "success") {
      alert("Banner guardado correctamente ✅");
      // cerrar modal
      bootstrap.Modal.getInstance(document.getElementById('modalBanner')).hide();
      // refrescar tabla/lista de banners
      cargarTablaBanners(); 
    } else {
      alert("Error al guardar: " + response.data.msg);
    }
  })
  .catch(error => {
    console.error("Error guardando banner:", error);
    alert("Ocurrió un error al guardar el banner.");
  });
}





function renderAdsTable(data) {
    const tbody = document.querySelector("#tablaAds tbody");
    tbody.innerHTML = ""; // limpia antes de renderizar

    data.forEach(ad => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${ad.ads_id}</td>
            <td>${ad.titulo}</td>
            <td>${ad.descripcion}</td>
            <td>
                ${ad.imagen_ruta 
                    ? `<img src="${ad.imagen_ruta}" alt="Banner" class="img-fluid" width="80">` 
                    : `<span class="text-muted">Sin imagen</span>`}
            </td>
            <td>${ad.posicion}</td>
            <td>${ad.fecha_ads}</td>
            <td><code>${ad.script_banner}</code></td>
            <td>${ad.tipo}</td>
            <td>${ad.link_banner 
                    ? `<a href="${ad.link_banner}" target="_blank">Ver Link</a>` 
                    : `<span class="text-muted">N/A</span>`}
            </td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input estado-switch" type="checkbox" 
                        ${ad.estado === "activo" ? "checked" : ""} 
                        data-id="${ad.ads_id}">
                </div>
            </td>
            <td>
                <button id="${ad.ads_id}" class="btn btn-sm btn-primary">Editar</button>
            </td>
        `;

        tbody.appendChild(row);
    });

    // Agregar listener para los switches
    document.querySelectorAll(".estado-switch").forEach(switchEl => {
        switchEl.addEventListener("change", function() {
            const id = this.dataset.id;
            const nuevoEstado = this.checked ? "activo" : "inactivo";

            // Aquí puedes hacer tu llamada a la API o función para actualizar en la DB
            console.log(`ID ${id} nuevo estado: ${nuevoEstado}`);

            let FormDatas = new FormData();
            FormDatas.append('action','cambiar_estado_ads');
            FormDatas.append('estado',nuevoEstado);
            FormDatas.append('ads_id',id);
            
                axios.post(`${dominio.value}/controllers/actions_board.php`,FormDatas,{headers:{
                'Content-Type': 'multipart/form-data'
                //'Authorization': `Bearer ${token_get}`
            }}).then(data=>{

                console.log(data.data);


            }).catch(log=>{

                    console.log('Error '+log);

            });


        });
    });


    //Agregar evento de editar

    let btn_editar = document.querySelectorAll('table .btn-primary');
    let id_banner;

    btn_editar.forEach(key => {
        key.addEventListener("click", function (e) {
            

            abrirModalNuevo(e.target.id);
        });
    });




}





    function cargar_banners(){


        let FormDatas = new FormData();
        FormDatas.append('action','cargar_banners');
    

        axios.post(`${dominio.value}/controllers/actions_board.php`,FormDatas,{headers:{
                'Content-Type': 'multipart/form-data'
                //'Authorization': `Bearer ${token_get}`
            }}).then(data=>{

                renderAdsTable(data.data);
                console.log(data.data);

            }).catch(log=>{

                    console.log('Error '+log);

            });




    }


    function modificar_banner(){

        //se abre un modal para modificar el banner

        
    }


    function activar_banner(){
    
        //Se cambia el estado del banner a activado para ser leido por el sistema



    }


    function desactivar_banner(){
        //Se cambia el estado del banner a desactivado para no ser leido


    }



    function guardar_banner(){


        let descripcion = document.getElementById("descripcion").value;
        let titulo = document.getElementById("titulo").value;
        let foto = document.getElementById("imagen_banner").files[0];
        let id_usuario = document.getElementById("id_usuario").value;
        let tipo = document.getElementById("tipo").value;
        let script_banner = document.getElementById('script_banner').value;
        let posicion_banner =document.getElementById('posicion').value;
        let link_banner = "";


            // Aquí puedes enviar la información al backend con fetch/axios
            console.log("Descripción:", descripcion);
            console.log("Foto:", foto);

            if(descripcion ==''|| titulo==''){

                alertify.message('Debes colocar un titulo  o descripcion');
                return;
            }
            let FormDatas = new FormData();
            FormDatas.append('action','guardar_ads');
            FormDatas.append('id_usuario',id_usuario);
            FormDatas.append('titulo',titulo);
            FormDatas.append('descripcion', descripcion);
            FormDatas.append('tipo',tipo);
            FormDatas.append('script_banner',script_banner);
            FormDatas.append('posicion',posicion_banner);
            FormDatas.append('link_banner',link_banner);

            if (foto) {
            
                FormDatas.append('imagen_ads',foto);

            }else{

                FormDatas.append('imagen_ads','');

            }

            
        axios.post(`${dominio.value}/controllers/actions_board.php`, FormDatas, {
            headers: {
                'Content-Type': 'multipart/form-data'
                /*'Authorization': `Bearer ${token}`*/
            }
        }).then(response => { 

            if(response.status=='success'){

                alertify.message('EL banner fue creado correctamente');

            }else{

                alertify.error('Error al guardar banner');
            }

            console.log(response.data);

        }).catch(error=>{

            console.error('No se pudo guardar el banner');

        });
} 


function actualizar_banner() {
    let id_banner = document.getElementById("id_banner").value; // Asegúrate de tener un input hidden con el id
    let descripcion = document.getElementById("descripcion").value;
    let titulo = document.getElementById("titulo").value;
    let foto = document.getElementById("imagen_banner").files[0];
    let id_usuario = document.getElementById("id_usuario").value;
    let tipo = document.getElementById("tipo").value;
    let script_banner = document.getElementById('script_banner').value;
    let posicion_banner = document.getElementById('posicion').value;
    let link_banner = ""; // Si quieres puedes agregar un input para el link

    if (descripcion == '' || titulo == '') {
        alertify.message('Debes colocar un titulo o descripción');
        return;
    }

    let FormDatas = new FormData();
    FormDatas.append('action', 'actualizar_ads');
    FormDatas.append('id_banner', id_banner);
    FormDatas.append('id_usuario', id_usuario);
    FormDatas.append('titulo', titulo);
    FormDatas.append('descripcion', descripcion);
    FormDatas.append('tipo', tipo);
    FormDatas.append('script_banner', script_banner);
    FormDatas.append('posicion', posicion_banner);
    FormDatas.append('link_banner', link_banner);

    if (foto) {
        FormDatas.append('imagen_ads', foto);
    } else {
        FormDatas.append('imagen_ads', '');
    }

    axios.post(`${dominio.value}/controllers/actions_board.php`, FormDatas, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(response => {
        // Aquí revisamos la respuesta del backend
        if (response.data.status === 'success') {
            alertify.message('El banner fue actualizado correctamente');
        } else {
            alertify.error('Error al actualizar banner');
        }
        console.log(response.data);
    }).catch(error => {
        console.error('No se pudo actualizar el banner', error);
    });
}
