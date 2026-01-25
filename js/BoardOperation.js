
window.onload=function(){


 
    var login = document.querySelector('#login');
    var dominio = document.querySelector('#dominio').value;
    var baseUrl = window.BASE_URL || '';
    var public_post = document.querySelector('#public_post');
    var foto_perfil = document.querySelector('#foto_perfil').value;
    var nombre_usuario = document.querySelector('#nombre_usuario').value;
    var user_update = document.querySelector(".user_update");
    var token_get = '';
    let update_user_profile = document.querySelector('#user_update');
    let fa_pen = document.querySelectorAll('.fa-pen-to-square');
    var my_boards = document.querySelectorAll('.fa-trash');
    var files_json=[];
    var id_medias=0;
    var FormDatas_board = new FormData();
    var boards = 0; //contar la cantidad de tableros para paginar.

       
    /*Es te fragmento de codigo es para detectar todos los tableros del usuario que inicio sesión 
        asignandole el evento de poder eliminar su publicación si lo desea.
    */
    if(my_boards){

        my_boards.forEach(board=>{


            boards+=1;

            board.addEventListener('click',(e)=>{

                    let id_board=e.target.getAttribute('data-value');
                    
                    
                    alertify.confirm('Eliminar publicación','Estas seguro que deseas eliminar esta publicación',function(){

                      // alertify.message(`Eliminado ${id_board}`);
                        document.querySelector(`#board${id_board}`).remove();

                        let FormDatas = new FormData();
                        FormDatas.append('action','drop_board');
                        FormDatas.append('id_user',document.getElementById('id_usuario').value);
                        FormDatas.append('id_board',id_board);

                        //board${$table.id_tablero}
                        axios.post(baseUrl + '/controllers/actions_board.php',FormDatas,{headers:{
                            'Authorization': `Bearer ${localStorage.getItem('token')}`
                        }}).then(info=>{
                
                                console.log(info);
                                //alertify.message('Cambios guardados con exito');
                        
                        }).catch(error=>{

                            console.log(error);

                        });

                    },function(){

                    });
                        
                });

        });


    }else{

        console.log('no tienes publicaciones');

        
    }




    function cargar_data_board() {
        
        // Obtener los valores necesarios
        const id_board = id_board;
        const action = 'cargar_un_tablero';
    
        // Construir la URL con los parámetros
        const url = `/controllers/actions_board.php?action=${action}&id_board=${id_board}`;
    
        // Realizar la solicitud GET
        axios.get(url, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        }).then(info => {
            console.log(info);
            //alertify.message('Cambios guardados con exito');
        }).catch(error => {
            console.log(error);
        });
    }

    function actualizar_board(){


        let FormDatas = new FormData();
        FormDatas.append('action','actualizar_tablero');
        FormDatas.append('id_user',document.getElementById('id_usuario').value);
        FormDatas.append('texto',document.getElementById('id_usuario').value);
        FormDatas.append('id_board',id_board);

        //board${$table.id_tablero}
        axios.post(baseUrl + '/controllers/actions_board.php',FormDatas,{headers:{
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }}).then(info=>{

                console.log(info);
                 //alertify.message('Cambios guardados con exito');
        }).catch(error=>{

            console.log(error);

        });



    }


    if(fa_pen){

        
        fa_pen.forEach(data=>{

            data.addEventListener('click',function(e){
                           

                let id_bord = e.target.getAttribute('data-value');
                alert(id_bord);

            });


        });





    }




    if(update_user_profile){
        
        update_user_profile.addEventListener('click',function(){
            cargar_data_usuario();
        });

    }else{
        // El selector no existe, probablemente el usuario no está logueado
        // Esto es normal, no es un error crítico
        // console.log('selector no encontrado aqui - usuario no logueado');
    }



    function cargar_data_usuario(){
        
        token_get ="";


        if(document.getElementById('id_usuario').value!==0){

            token_get = localStorage.getItem('token');

        }

        let FormDatas = new FormData();
        FormDatas.append('action','user_info');
        FormDatas.append('user_id',document.getElementById('id_usuario').value);
;

        axios.post(baseUrl + '/controllers/actions_board.php',FormDatas,{headers:{
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${token_get}`
        }
        }).then(info=>{
            console.log(info);
            document.getElementById('usuario_form').value = info.data.usuario;
            document.getElementById('clave').value = ''; // Dejar vacío para que el usuario pueda ingresar una nueva contraseña
            document.getElementById('nombre').value = info.data.nombre;
            document.getElementById('apellido').value = info.data.apellido;
            document.getElementById('bio').value = info.data.bio;
            //document.getElementById('sexo').value = info.data.sexo || ''; // Manejar el caso donde el sexo puede ser null
            // Cargar la foto si es necesaria
            document.getElementById('foto_url').src = info.data.foto_url || '/assets/default_profile.png'; // Usar una imagen por defecto si no hay foto

            
            
       
        }).catch(error=>{

        console.log(error);

        });

    }




    user_update.addEventListener('click',function(){

        cargar_data_usuario();

    });

    


    
    login.addEventListener('click',function(){

        let usuario =  document.querySelector('#usuario').value;
        let clave =  document.querySelector('#clave').value;

        let FormDatas = new FormData();
        FormDatas.append('action','login');
        FormDatas.append('usuario',usuario);
        FormDatas.append('clave',clave);

       // localStorage.setItem('myCat', 'Tom');
       //const cat = localStorage.getItem('myCat');
       //localStorage.clear();
        /*
        headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${token}`
                 
              }
        */

        axios.post(`controllers/actions_board.php`,FormDatas).then(data=>{



            if(data.data.estado=='activo'){
            
                localStorage.setItem('token',data.data.token);
                localStorage.setItem('usuario',data.data.usuario);
                location.href=dominio;

            }else if(data.data.estado=='inactivo'){

                alertify.confirm('Notifiación',`Su cuenta aun no esta activada favor revisar
                    su correo electronico y entre al enlace que le enviamos para activar su cuenta.
                `,function(){},function(){});

                Singout_f('no_redirect');
            }else{

                alertify.confirm('Notifiación',`Usuario
                o contraseña incorrecto.
                `,function(){},function(){});

            }

            //alert(localStorage.getItem('token'));
            /*localStorage.setItem('name',token);
            localStorage.setItem('token',token);
            localStorage.setItem('token',token);*/

        }).catch(error=>{

            alertify.warning(error);

        });


    });


    document.querySelector('#update_changes').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission
       
                // alertify.message('Leyo el evento que mas desea hacer');
                // Gather form data
                let usuario = document.querySelector('#usuario_form').value;
                let fotoUrl = document.querySelector('#foto_url').files[0];
                let nombre = document.querySelector('#nombre').value;
                let apellido = document.querySelector('#apellido').value;
                let bio = document.querySelector('#bio').value;
                let sexo = document.querySelector('#sexo').value;
                // Create FormData object and append form values
                let formDatas = new FormData();
                formDatas.append('action', 'update_user');
                formDatas.append('imagen_actual',document.querySelector('#imagen_actual').value);
                formDatas.append('user_id', document.querySelector('#id_usuario').value);
                formDatas.append('image', fotoUrl);
                formDatas.append('username',usuario);
                formDatas.append('sex', sexo);
                formDatas.append('name', nombre);
                formDatas.append('last_name', apellido);
                formDatas.append('bio', bio);

                if(fotoUrl==null){
        
                        let index =  foto_perfil.indexOf('/images');  
                        let newUrl = foto_perfil.substring(index); 
                        formDatas.append('image',newUrl);
                
                }

                axios.post(baseUrl + '/controllers/actions_board.php',formDatas,{headers:{
                            'Content-Type': 'multipart/form-data',
                            'Authorization': `Bearer ${token_get}`

                }}).then(info=>{

                        console.log(info);
                        alertify.message('Cambios guardados con exito');
                
                }).catch(error=>{

                    console.log(error);

                });





    });





    var subir_imagen = document.querySelector('#upload_image');

  

    subir_imagen.addEventListener('click',function(){

            document.querySelector('#upload_images').click();
    });


    function generar_media(data){


        data.forEach(data=>{

            FormDatas_board.append('media[]',data.media);
           

        });

        console.log('Archivos media actual',data);
    }


    var subir_imagen =  document.querySelector('#upload_images');
    var count = 0;
    subir_imagen.addEventListener('change',data=>{

            console.log(data.target.files);
            
            let files = data.target.files.length;


            for(i=0;i<files;i++){
                id_medias++;
                console.log(data.target.files[i].name);
                var media = URL.createObjectURL(data.target.files[i]);
                count++;
                
                files_json.push({
                        archivo_id:`fig${id_medias}`,
                        media:data.target.files[i]
                });
                

                if(data.target.files[i].type=='image/jpeg' || data.target.files[i].type=='image/png'){
                        document.querySelector('#multimedia_view').innerHTML+=`
                        <div class="multimedia-item" id='fig${id_medias}'>
                            <div class="multimedia-overlay">
                                <button class="multimedia-delete-btn" id='${id_medias}' title="Eliminar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <img src='${media}' alt='Preview' class='multimedia-preview'>
                        </div>`;
                }else{
                        document.querySelector('#multimedia_view').innerHTML+=`
                        <div class="multimedia-item" id='fig${id_medias}'>
                            <div class="multimedia-overlay">
                                <button class="multimedia-delete-btn" id='${id_medias}' title="Eliminar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <video src='${media}' controls class='multimedia-preview'></video>
                        </div>`;
                }

             
            }                             


            // Actualizar event listeners para los nuevos botones de eliminar
            let deleteButtons = document.querySelectorAll('.multimedia-delete-btn');

            generar_media(files_json);

            deleteButtons.forEach(button=>{
                button.addEventListener('click', function(e){
                    e.stopPropagation();
                    let buttonId = this.id;
                    let id_archivo_json = `fig${buttonId}`;
                    
                    // Ocultar el elemento multimedia
                    const multimediaItem = document.querySelector(`#${id_archivo_json}`);
                    if (multimediaItem) {
                        multimediaItem.style.opacity = '0';
                        multimediaItem.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            multimediaItem.remove();
                        }, 300);
                    }
                    
                    // Actualizar el array de archivos
                    let actualizar_archivos = files_json.filter(archivo=>archivo.archivo_id!==id_archivo_json);
                    files_json = actualizar_archivos;

                    generar_media(files_json);
                });
            });



    });


    function subir_imagen(){


        var formData = new FormData();
        var imagefile = document.querySelector('#file');
        formData.append("image", imagefile.files[0]);
        axios.post('upload_file', formData, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        })


    }


    var post = document.querySelector('#post');


    post.addEventListener('click', function() {
        // Verifica si el campo de título del tablero está vacío
        if (document.querySelector('#board_title').value === '') {
            alertify.message('No puedes dejar el campo de texto vacío');
            return;
        }

        // Validar tamaño de archivos antes de enviar
        const maxSizeMB = 250; // Tamaño máximo en MB
        let totalSize = 0;
        
        // Verificar archivos en files_json
        if (files_json && files_json.length > 0) {
            for (let i = 0; i < files_json.length; i++) {
                if (files_json[i].media && files_json[i].media.size) {
                    totalSize += files_json[i].media.size;
                    const fileSizeMB = files_json[i].media.size / (1024 * 1024);
                    if (fileSizeMB > maxSizeMB) {
                        const errorMsg = `El archivo "${files_json[i].media.name || 'archivo'}" (${fileSizeMB.toFixed(2)}MB) excede el límite permitido de ${maxSizeMB}MB.`;
                        if (typeof alertify !== 'undefined') {
                            alertify.error(errorMsg);
                        } else {
                            alert(errorMsg);
                        }
                        return;
                    }
                }
            }
        }
        
        // Convertir a MB
        const totalSizeMB = totalSize / (1024 * 1024);
        if (totalSizeMB > maxSizeMB) {
            const errorMsg = `El tamaño total de los archivos (${totalSizeMB.toFixed(2)}MB) excede el límite permitido de ${maxSizeMB}MB.`;
            if (typeof alertify !== 'undefined') {
                alertify.error(errorMsg);
            } else {
                alert(errorMsg);
            }
            return;
        }

        // Agrega los datos del formulario a FormDatas_board
        FormDatas_board.append('action', 'create_board');
        FormDatas_board.append('description', document.querySelector('#board_title').value);
        FormDatas_board.append('user_id', document.querySelector('#id_usuario').value);
        

        //visualizando barra de subida de archivos

        // Mostrar barra de progreso moderna
        const progressSection = document.getElementById('progress_section');
        if (progressSection) {
            progressSection.style.display = 'block';
        }
        
        // Usar la función moderna si está disponible
        if (typeof updateUploadProgress === 'function') {
            updateUploadProgress(0);
        }
        // Configuración para el progreso de carga del archivo
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${token_get}`
            },
            onUploadProgress: function(progressEvent) {
                // Muestra la barra de progreso
                // Calcula el porcentaje completado
                const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                
                // Actualizar barra de progreso moderna
                const progressBar = document.getElementById('file');
                const porcentaje = document.getElementById('porcentaje');
                
                if (progressBar) {
                    progressBar.style.width = percentCompleted + '%';
                    progressBar.setAttribute('aria-valuenow', percentCompleted);
                }
                
                if (porcentaje) {
                    porcentaje.textContent = percentCompleted + '%';
                }
                
                // Usar función moderna si está disponible
                if (typeof updateUploadProgress === 'function') {
                    updateUploadProgress(percentCompleted);
                }

            }
        };
    
        // Envía la solicitud POST usando Axios
        axios.post(baseUrl + '/controllers/actions_board.php', FormDatas_board, config)
            .then(data => {
                // Oculta la barra de progreso al completar la solicitud

                //Caputra el id del usuario de sesión

                  let id_user_s = document.querySelector('#id_usuario').value;
                  // Ocultar barra de progreso moderna
                  const progressSection = document.getElementById('progress_section');
                  if (progressSection) {
                      progressSection.style.display = 'none';
                  }
                  
                  // Resetear progreso
                  const progressBar = document.getElementById('file');
                  if (progressBar) {
                      progressBar.style.width = '0%';
                      progressBar.setAttribute('aria-valuenow', 0);
                  }
                  
                  const porcentaje = document.getElementById('porcentaje');
                  if (porcentaje) {
                      porcentaje.textContent = '0%';
                  }
                  
                  if (typeof updateUploadProgress === 'function') {
                      updateUploadProgress(0);
                  }
                  console.log(data);
                  let response;
                  
                  // Parsear la respuesta correctamente
                  if (typeof data.data === 'string') {
                      try {
                          response = JSON.parse(data.data);
                      } catch (e) {
                          console.error('Error parsing JSON:', e);
                          response = data.data;
                      }
                  } else {
                      response = data.data;
                  }
                  
                  // Validar que response sea un objeto
                  if (!response || typeof response !== 'object') {
                      console.error('Respuesta inválida:', response);
                      // Si la respuesta es un string con error HTML, mostrar mensaje apropiado
                      if (typeof response === 'string') {
                            if (response.includes('Content-Length') || response.includes('exceeds the limit')) {
                                if (typeof alertify !== 'undefined') {
                                    alertify.error('El archivo es demasiado grande. Tamaño máximo: 250MB');
                                } else {
                                    alert('El archivo es demasiado grande. Tamaño máximo: 250MB');
                                }
                            } else {
                              if (typeof alertify !== 'undefined') {
                                  alertify.error('Error al procesar la respuesta del servidor');
                              }
                          }
                      } else {
                          if (typeof alertify !== 'undefined') {
                              alertify.error('Error al procesar la respuesta del servidor');
                          }
                      }
                      return;
                  }
                  
                  // Asegurar que los valores no sean undefined
                  const id_tablero = response.id_tablero || response.id_tablero || '';
                  const titulo = response.titulo || null;
                  const descripcion = response.descripcion || '';
                  const usuario = response.usuario || '';
                  const imagen_tablero = response.imagen_tablero || '';
                  const id_usuario = response.id_usuario || response.id_user || '';
                  
                  // Crear slug para la URL (usar descripción si no hay título)
                  const url_slug = titulo 
                      ? titulo.replace(/\s+/g, '_').substring(0, 50)
                      : (descripcion ? descripcion.replace(/\s+/g, '_').substring(0, 50) : '');
                  
                  console.log('Response parsed:', response);
                  console.log('URL slug:', url_slug);
                

                  let post_ready = `
                    <div class='card text-white bg-dark mb-3' id="board${id_tablero}">
                        <div class='body' style='padding:5px'>
                        <div class='title'>
                            <strong>
                            <a href='${window.BASE_URL || ''}/profile/${usuario}'>
                                <img class='imagenPerfil' src='${foto_perfil}'/>
                            </a>
                            ${nombre_usuario}
                            <a href="${window.BASE_URL || ''}/post/${id_tablero}${url_slug ? '/' + url_slug : ''}">
                                <i class="fa-solid fa-highlighter"></i>
                            </a>
                            </strong>
                        </div>
                        <p style='padding-left: 10px;'>${descripcion}</p>
                        <a href="${window.BASE_URL || ''}/post/${id_tablero}${url_slug ? '/' + url_slug : ''}">
                    `;
                    
                    if (imagen_tablero && imagen_tablero !== '' && imagen_tablero !== 'undefined' && imagen_tablero !== null) {
                        // Limpiar la ruta de la imagen
                        let imagen_path = imagen_tablero.startsWith('/') ? imagen_tablero.substring(1) : imagen_tablero;
                        // Asegurar que no tenga doble barra
                        imagen_path = imagen_path.replace(/^\/+/, '');
                        
                        // Obtener preview_tablero si existe
                        const preview_tablero = response.preview_tablero || '';
                        const hasPreview = preview_tablero && preview_tablero !== '' && preview_tablero !== 'undefined' && preview_tablero !== null;
                        let preview_path = '';
                        if (hasPreview) {
                            preview_path = preview_tablero.startsWith('/') ? preview_tablero.substring(1) : preview_tablero;
                            preview_path = preview_path.replace(/^\/+/, '');
                        }
                        
                        post_ready += `
                        <div class='content_image board-image-container' 
                             data-preview="${preview_path}" 
                             data-image="${imagen_path}"
                             data-has-preview="${hasPreview ? 'true' : 'false'}">
                        <img src="/${imagen_path}" 
                             class="card-img-top board-image board-main-image" 
                             alt="..."
                             data-preview-src="${hasPreview ? dominio + '/' + preview_path : ''}">
                    `;
                    }
                    
                    post_ready += `
                        </a>
                        ${imagen_tablero && imagen_tablero !== '' && imagen_tablero !== 'undefined' && imagen_tablero !== null ? '</div>' : ''}
                        </div>
                        <p class='p' style='padding:5px;'></p>
                        <div class="card-footer" style='float:right'>
                        <div style='float:right'>
                            <i class="fa-solid fa-thumbs-up" style='display:none'></i>
                            <i class="fa-solid fa-bookmark" style='display:none'></i>
                            <i class="fa-regular fa-share-from-square share-icon" data-tablero="${id_tablero}" style='cursor:pointer' title="Compartir"></i>
                            <i class="fa-regular fa-thumbs-up like-icon" data-tablero="${id_tablero}" style='cursor:pointer' title="Me gusta"></i>
                            <i class="fa-regular fa-comment-dots comment-icon" data-tablero="${id_tablero}" style='cursor:pointer' title="Comentar"></i>
                            <i class="fa-regular fa-bookmark bookmark-icon" style='cursor:pointer' title="Guardar"></i>`;
                            if(nombre_usuario !== '' && id_usuario==id_user_s){
                                post_ready+=` <i class="fa fa-trash" data-value='${id_tablero}' style="cursor: pointer;" aria-hidden="true"></i>`;
                            }
                        post_ready+=`
                        </div>
                        </div>
                    </div>
                    `;
                    
                 console.log(post_ready);

                 
                 // Verificar que el elemento existe antes de insertar
                 const containerElement = document.querySelector('.col-sm-5');
                 if (containerElement) {
                     containerElement.insertAdjacentHTML('afterbegin', post_ready);
                     
                     // Reinicializar la vista previa para el nuevo tablero agregado
                     if (typeof reinitBoardPreview === 'function') {
                         setTimeout(() => {
                             reinitBoardPreview();
                         }, 100);
                     }
                     
                     // Reinicializar interacciones (like, share, comment)
                     if (typeof reinitBoardInteractions === 'function') {
                         setTimeout(() => {
                             reinitBoardInteractions();
                         }, 200);
                     }
                 } else {
                     // Si no existe, buscar alternativas o recargar la página
                     console.warn('Elemento .col-sm-5 no encontrado, recargando página...');
                     // Opción 1: Recargar la página para mostrar el nuevo post
                     window.location.reload();
                     // Opción 2: Si prefieres no recargar, puedes buscar otro contenedor
                     // const altContainer = document.querySelector('#boards-container') || document.querySelector('.boards-list');
                     // if (altContainer) {
                     //     altContainer.insertAdjacentHTML('afterbegin', post_ready);
                     // }
                 }
                
                // Aquí puedes redirigir o actualizar la página según tus necesidades
                // location.href = dominio;

                // Cerrar el modal solo si existe
                const closeButton = document.querySelector('#exampleModal .btn-close');
                if (closeButton) {
                    closeButton.click();
                }

                

            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
                
                // Manejar diferentes tipos de errores
                let errorMessage = 'Error al publicar la publicación';
                
                if (error.response) {
                    // Error de respuesta del servidor
                    const responseData = error.response.data;
                    
                    if (typeof responseData === 'string') {
                        // Si la respuesta es HTML (error de PHP)
                        if (responseData.includes('Content-Length') || responseData.includes('exceeds the limit')) {
                            errorMessage = 'El archivo es demasiado grande. Tamaño máximo permitido: 250MB. Por favor, reduce el tamaño del archivo o comprímelo.';
                        } else if (responseData.includes('Unexpected token') || responseData.includes('<br />')) {
                            // Error de parsing JSON o respuesta HTML
                            console.error('Error parsing JSON o respuesta HTML:', responseData.substring(0, 200));
                            errorMessage = 'Error al procesar la respuesta del servidor. Verifica que los archivos no excedan el tamaño máximo.';
                        }
                    } else if (responseData && responseData.error) {
                        errorMessage = responseData.error;
                    }
                } else if (error.request) {
                    // Error de red
                    errorMessage = 'Error de conexión. Verifica tu conexión a internet.';
                } else {
                    // Otro tipo de error
                    errorMessage = error.message || 'Error desconocido';
                }
                
                // Ocultar barra de progreso en caso de error
                const progressSection = document.getElementById('progress_section');
                if (progressSection) {
                    progressSection.style.display = 'none';
                }
                
                if (typeof alertify !== 'undefined') {
                    alertify.error(errorMessage);
                } else {
                    alert(errorMessage);
                }
                
                const progressBar = document.getElementById('file');
                if (progressBar) {
                    progressBar.style.width = '0%';
                    progressBar.setAttribute('aria-valuenow', 0);
                }
                console.error('Error en la solicitud:', error);
                if (typeof alertify !== 'undefined') {
                    alertify.error('Ocurrió un error al enviar el formulario');
                } else {
                    alert('Ocurrió un error al enviar el formulario');
                }
            });
    });



    function Singout_f(config='redirect'){

        localStorage.clear();
        let FormDatas = new FormData();
        FormDatas.append('usuario',document.querySelector('#usuario').value);
        FormDatas.append('action','sigout');

        axios.post(baseUrl + '/controllers/actions_board.php',FormDatas).then(data=>{
                console.log(data.data);

            // alertify.message('cerrando sesión');
                localStorage.clear();
                if(config=='redirect'){
                    
                    location.href=dominio;      

                }
        }).catch(error=>{

            alertify.warning(error);
            console.log(error);

        });
    }


    var singout = document.querySelector('#singout');

    if(singout){

            singout.addEventListener('click',function(){
        
                Singout_f();
            });

    }

    function handleScroll() {

        var config_pag = document.querySelector('#paginador_scroll').value;

      

        switch(config_pag){

            case 'general':

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                // Si se alcanza el final de la página, carga más datos
               // alertify.message('llego al final de la pagina');
               // Obtener la URL actual y base URL
                const url = new URL(window.location.href);
                const baseUrl = window.BASE_URL || '';

                // Obtener el valor del parámetro 'leaf' o de la URL moderna /page/N
                let leafValue = url.searchParams.get('leaf');
                // Intentar extraer de URL moderna /page/N
                const pageMatch = window.location.pathname.match(/\/page\/(\d+)/);
                if (pageMatch) {
                    leafValue = pageMatch[1];
                }
                
                if(boards>5){
                    if(leafValue==null){

                            window.location=`${baseUrl}/page/2`;     
                            
                    }else{
        
                        let page = parseInt(leafValue) + 1;
        
                        window.location=`${baseUrl}/page/${page}`;     
        
        
                    }
                
                }
    
            }

            break;


            case 'user_profile':

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                // Si se alcanza el final de la página, carga más datos
               // alertify.message('llego al final de la pagina');
               // Obtener la URL actual

               
                const url = new URL(window.location.href);
                const baseUrl = window.BASE_URL || '';

                // Obtener el valor del parámetro 'leaf' o de la URL moderna
                let leafValue = url.searchParams.get('leaf');
                let UserNAME = url.searchParams.get('user');
                
                // Intentar extraer de URL moderna /profile/username o /profile/username/page/N
                const profileMatch = window.location.pathname.match(/\/profile\/([^\/]+)(?:\/page\/(\d+))?/);
                if (profileMatch) {
                    UserNAME = profileMatch[1];
                    if (profileMatch[2]) {
                        leafValue = profileMatch[2];
                    }
                }
                
                if(boards>5){

                    if(leafValue==null){

                            window.location=`${baseUrl}/profile/${UserNAME}/page/2`;     
                            
                    }else{
        
                        let page = parseInt(leafValue) + 1;
        
                        window.location=`${baseUrl}/profile/${UserNAME}/page/${page}`;     
        
        
                    }

                }   
    
            }


            break;
        }

        
    }
    
    // Evento de desplazamiento para detectar cuándo se alcanza el final de la página
    window.addEventListener('scroll', handleScroll);



    var transfer_video = document.querySelector('#btnTransferVideo');

    if(transfer_video){

        transfer_video.addEventListener('click',function(){

                let plataforma = document.querySelector('#platformSelect').value;
                let url_video = document.querySelector('#url_video').value;
                let video_txt = document.querySelector('#video_txt').value;

                if(plataforma==''){
                    alertify.message('Debe seleccionar una plataforma');
                    return;
                }

                if(url_video==''){
                    alertify.message('Debe ingresar la URL del video');
                    return;
                }

                let api_transfer_video = document.querySelector('#api_transfer_video').value;


                axios.get(`${api_transfer_video}`,{
                    params: {
                        ruta: url_video
                    }
                }).then(info=>{

                    let ruta_limpia = info.data.url_video;
                    const form = new FormData();
                    form.append('action', 'save_transferred_video');
                    form.append('id_user', document.querySelector('#id_usuario').value);
                    form.append('media', ruta_limpia);
                    form.append('video_txt', video_txt);

                    axios.post(baseUrl + '/controllers/actions_board.php', form, {
                        headers:{
                            'Authorization': `Bearer ${token_get}`
                        }
                    }).then(response => {
                        console.log(response);
                        alertify.message('Video transferido y guardado correctamente');
                    }).catch(error => {
                        console.error('Error al guardar el video transferido:', error);
                        alertify.error('Error al guardar el video transferido');
                    });

             });
          });    
    }
}

