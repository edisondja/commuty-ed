


var get_domain = dominio.value;
var count= 0;
var config = {
    headers: {
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${token_get}`
    }};
    

    if(detectar_modulo()=='users'){

        BuscarUsuarios('');

    }else if(detectar_modulo()=='boards'){

        BuscarTableros('');

    }
    

   // var btn = document.querySelector('#flexSwitchCheckDefault');


    var token_get = localStorage.getItem('token');

      /*
        Modulo usuarios
    
    */
   function EditarTablero(id_tablero) {
        // Asignamos el ID al input hidden
        document.getElementById("idTablero").value = id_tablero;

        // Aquí podrías hacer un fetch/axios para cargar la info actual del tablero
        // Ejemplo:
        // axios.get(`/api/tableros/${id_tablero}`).then(response => {
        //     document.getElementById("descripcionTablero").value = response.data.descripcion;
        // });

         cargarInfoTablero(id_tablero)
        // Abrimos el modal
        let modal = new bootstrap.Modal(document.getElementById('modalActualizarTablero'));
        modal.show();
    }


    function cargarInfoTablero(id_tablero) {
        document.getElementById("idTablero").value = id_tablero;
        let FormDatas = new FormData();
        FormDatas.append('action','load_info_board');
        FormDatas.append('id_tablero', id_tablero);
        let api_user = `${get_domain}/controllers/actions_board.php`;

        axios.post(api_user, FormDatas, config).then(response => {
            let data = response.data;
            console.log(data);
            document.getElementById("descripcionTablero").value = data.descripcion;
            document.getElementById("vistaPreviaImagen").src = `${get_domain}/${data.imagen_tablero}`;
            
            // Cargar reproductores disponibles
            cargarReproductoresSelect(data.id_reproductor);
        });
    }

    // Cargar reproductores en el select
    function cargarReproductoresSelect(idReproductorActual) {
        let FormDatas = new FormData();
        FormDatas.append('action', 'listar_reproductores');
        let api_user = `${get_domain}/controllers/actions_board.php`;

        axios.post(api_user, FormDatas, config).then(response => {
            const data = response.data;
            const select = document.getElementById('selectReproductor');
            
            if (!select) return;
            
            // Limpiar opciones actuales excepto la primera
            select.innerHTML = '<option value="">Sin reproductor asignado</option>';
            
            if (data.reproductores && data.reproductores.length > 0) {
                data.reproductores.forEach(rep => {
                    const option = document.createElement('option');
                    option.value = rep.id_reproductor;
                    option.textContent = rep.nombre + (rep.es_default == 1 ? ' (Default)' : '');
                    
                    // Marcar el actual
                    if (idReproductorActual && parseInt(idReproductorActual) === parseInt(rep.id_reproductor)) {
                        option.selected = true;
                    }
                    
                    select.appendChild(option);
                });
            }
        }).catch(error => {
            console.error('Error cargando reproductores:', error);
        });
    }

    // Función para guardar cambios
    function guardarCambiosTablero() {
        let id_tablero = document.getElementById("idTablero").value;
        let descripcion = document.getElementById("descripcionTablero").value;
        let foto = document.getElementById("fotoPortada").files[0];
        let id_usuario = document.getElementById("id_usuario").value;
        let id_reproductor = document.getElementById("selectReproductor")?.value || '';

        console.log("ID Tablero:", id_tablero);
        console.log("Descripción:", descripcion);
        console.log("Foto:", foto);
        console.log("Reproductor:", id_reproductor);

        let FormDatas = new FormData();
        FormDatas.append('action','update_board');
        FormDatas.append('id_usuario', id_usuario);
        FormDatas.append('id_tablero', id_tablero);
        FormDatas.append('descripcion', descripcion);
        
        if (foto) {
            FormDatas.append('foto', foto);
        } else {
            FormDatas.append('imagen_actual', document.getElementById("vistaPreviaImagen").src.replace(`${get_domain}/`, ''));
        }

        let api_user = `${get_domain}/controllers/actions_board.php`;
        
        axios.post(api_user, FormDatas, config).then(response => {
            console.log("Respuesta del servidor:", response);
            
            // Guardar reproductor asignado
            if (document.getElementById("selectReproductor")) {
                let FormReproductor = new FormData();
                FormReproductor.append('action', 'asignar_reproductor_tablero');
                FormReproductor.append('id_tablero', id_tablero);
                FormReproductor.append('id_reproductor', id_reproductor);
                
                axios.post(api_user, FormReproductor, config).then(resp => {
                    console.log("Reproductor asignado:", resp.data);
                    alertify.success('Tablero actualizado correctamente');
                });
            } else {
                alertify.success('Tablero actualizado');
            }
        }).catch(error => {
            console.error("Error al guardar cambios:", error);
            alertify.error('Error al guardar cambios');
        });

        // Cerrar modal después de guardar
        let modalElement = document.getElementById('modalActualizarTablero');
        let modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) modalInstance.hide();

        cargarInfoTablero(id_tablero);
    }

    function BuscarUsuarios(contexto){

        let registros = '';
        let tabla = '';
        let FormDatas = new FormData();
        FormDatas.append('action','search_users');
        FormDatas.append('config','json');
        FormDatas.append('context',contexto);
        FormDatas.append('id_user',document.getElementById('id_usuario').value);
        let api_user = `${get_domain}/controllers/actions_board.php`;

        let url = new URL(api_user);
        url.searchParams.append('action', 'search_users');
        url.searchParams.append('config', 'json');
        url.searchParams.append('context', contexto);

        axios.get(url.toString(),config).then(data=>{
                        
                      registros = data.data;

                      registros.forEach(data=>{

                        console.log(data);
                        tabla+=tabla_usuario(data);
                 
                        document.getElementById("data_usuario").innerHTML=tabla;
                        AgregarEventoSwitch('users');


                    });
            
            
                        
                    }).catch(error=>{

                            console.log(error);
                    });


  

    }


    function DesactivarUsuario(id_usuario){

            let FormDatas = new FormData();
            FormDatas.append('action','disable_user');
            FormDatas.append('id_user',id_usuario);
            let api_user =`${get_domain}/controllers/actions_board.php`;

            axios.post(api_user,
                        FormDatas,
                        config).then(data=>{
                            
                            alertify.message(data.data);

                        }).catch(error=>{

                            alertify.message("No se pudo desactivar el usuario");

                        });
    }


    function  AgregarEventoSwitch(config){


        let asignar_switch = document.querySelectorAll('.form-check-input');

        
        asignar_switch.forEach(event=>{


                event.addEventListener('click',(object)=>{

                    let mensaje ='';

                    if(config=='users'){

                        mensaje =`Estas seguro que deseas
                        desactivar este usuario?`;                    

                    }else{

                        
                        mensaje =`Estas seguro que deseas
                        desactivar esta publicación`;                    
                        
                    }

                    alertify.confirm('Desactivar',mensaje
                    ,function(){

                        
                        if(object.target.checked==true){

                            if(config=='users'){
                                ActivarUsuario(object.target.value);

                            }else if(config=='boards'){

                                ActivarTablero(object.target.value);

                            }   
                           // alert(object.target.checked+' activar');
                        }else{

                            if(config=='users'){

                                //alert(object.target.checked+' desactivar');
                                DesactivarUsuario(object.target.value);

                            }else if(config=='boards'){

                                BloquearTablero(object.target.value);
                                
                            }
                        }

                    },function(){});
                     

                });


        });

        


    }

    

    function ActivarUsuario(id_user){

        let FormDatas = new FormData();
        FormDatas.append('action','enable_user');
        FormDatas.append('id_user',id_user);
        let api_user =`${get_domain}/controllers/actions_board.php`;

        axios.post(api_user,
                    FormDatas,
                    config).then(data=>{
                        
                        alertify.message(data.data);
                        
                    }).catch(error=>{

                    });
    }


    function tabla_usuario(data){
        let estadoBadge = data.estado == 'activo' 
            ? '<span class="bo-badge bo-badge-success">Activo</span>' 
            : '<span class="bo-badge bo-badge-danger">Inactivo</span>';
        
        let switchChecked = data.estado == 'activo' ? 'checked' : '';
        
        let Row = `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img class="bo-avatar" src="${get_domain}/${data.foto_url}" onerror="this.src='${get_domain}/assets/user_profile.png'"/>
                        <div>
                            <strong>${data.nombre} ${data.apellido}</strong>
                            <br><small style="color: rgba(255,255,255,0.5);">@${data.usuario || 'usuario'}</small>
                        </div>
                    </div>
                </td>
                <td>${data.email}</td>
                <td><span class="bo-badge bo-badge-info">${data.type_user}</span></td>
                <td>${estadoBadge}</td>
                <td>
                    <div class="bo-switch">
                        <input type="checkbox" ${switchChecked} value="${data.id_user}"/>
                    </div>
                </td>
            </tr>`;
        return Row;
    }

    function tabla_publicacion(){

        
    }



    function ReinciarCredenciales(){

        /*Enviar correo de comprobar de verificar usuario por aca*/

    }


    function BuscarPublicaciones(){

        
        let FormDatas = new FormData();
        FormDatas.append('action','search_boards');
        FormDatas.append('id_user',document.getElementById('id_usuario').value);
        let api_user =`${get_domain}/controllers/actions_board.php`;

        axios.get(api_user,
                    FormDatas,
                    headers).then(data=>{
                        
                        console.log(data);
                        
                    }).catch(error=>{

                    });
    }



    var buscar = document.querySelector('#search');


    buscar.addEventListener('keypress',data=>{

        let valor = data.target.value;

        /*La funcion detectar modulo captura
            el valode configuracion del modulo
            actual que se esta actulizando
            para asi usar la barra de busqueda
            para diferentes entidades
        */

        switch(detectar_modulo()){

            case 'users':

                BuscarUsuarios(valor);

            break;


            case 'boards':

                BuscarTableros(valor);

            break;


            case 'config':


            break;

        }


    });


    function detectar_modulo(){

        let mod = document.querySelector('#modulo_select').value;

        return mod;
    }


      /*
        Modulo Tableros
    
    */


    function BuscarTableros(contexto){


        let registros = '';
        let tabla = '';
        let FormDatas = new FormData();
        FormDatas.append('action','ssearch_boards');
        FormDatas.append('config','json');
        FormDatas.append('context',contexto);
        let api_user = `${get_domain}/controllers/actions_board.php`;

        let url = new URL(api_user);
        url.searchParams.append('action', 'search_boards');
        url.searchParams.append('config', 'json');
        url.searchParams.append('context', contexto);

        axios.get(url.toString(),config).then(data=>{
                        
                      registros = data.data;

                      registros.forEach(data=>{

                        console.log(data);
                        tabla+=tabla_board(data);
                 
                        document.getElementById("data_boards").innerHTML=tabla;
                        AgregarEventoSwitch('boards');

            
                    });
            
            
                        
                    }).catch(error=>{

                            console.log(error);
                    });



    }


    function tabla_board(data) {
        let estadoBadge = data.estado == 'activo' 
            ? '<span class="bo-badge bo-badge-success">Activo</span>' 
            : '<span class="bo-badge bo-badge-danger">Inactivo</span>';
        
        let switchChecked = data.estado == 'activo' ? 'checked' : '';
        let descripcion = data.descripcion ? data.descripcion.substring(0, 60) + '...' : 'Sin descripción';
        
        // Formatear fecha
        let fecha = data.fecha_creacion ? new Date(data.fecha_creacion).toLocaleDateString('es-ES') : '-';
        
        let Row = `
            <tr>
                <td>
                    <div style="max-width: 200px;">
                        <small style="color: rgba(255,255,255,0.7);">${descripcion}</small>
                    </div>
                </td>
                <td>
                    <img class="bo-avatar" style="border-radius: 8px; width: 50px; height: 50px;" 
                        src="${get_domain}/${data.imagen_tablero}" 
                        onerror="this.src='${get_domain}/assets/no_found.png'"/>
                </td>
                <td><small>${fecha}</small></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <img class="bo-avatar" style="width: 30px; height: 30px;" 
                            src="${get_domain}/${data.foto_url}" 
                            onerror="this.src='${get_domain}/assets/user_profile.png'"/>
                        <span>${data.usuario || 'Usuario'}</span>
                    </div>
                </td>
                <td>${estadoBadge}</td>
                <td>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <div class="bo-switch">
                            <input type="checkbox" ${switchChecked} value="${data.id_tablero}"/>
                        </div>
                        <button class="bo-btn bo-btn-primary bo-btn-sm" onClick="EditarTablero(${data.id_tablero})">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
        return Row;
    }
    


    
    function BloquearTablero(id_tablero){

        let FormDatas = new FormData();
        FormDatas.append('action','block_board');
        FormDatas.append('id_board',id_tablero);
        FormDatas.append('id_usuario',id_usuario.value);

        let api_user =`${get_domain}/controllers/actions_board.php`;

        axios.post(api_user,
                    FormDatas,
                    config).then(data=>{

                        alertify.message(data.data);

                    }).catch(error=>{

                        alertify.message("No se pudo desactivar el tablero");

                    });
    }


    function ActivarTablero(id_tablero){


        let FormDatas = new FormData();
        FormDatas.append('action','active_board');
        FormDatas.append('id_board',id_tablero);
        FormDatas.append('id_usuario',id_usuario.value);
        

        let api_user =`${get_domain}/controllers/actions_board.php`;

        axios.post(api_user,
                    FormDatas,
                    config).then(data=>{
                        
                        alertify.message(data.data);

                    }).catch(error=>{

                        alertify.message("No se pudo activar el tablero");

                    });
    }


