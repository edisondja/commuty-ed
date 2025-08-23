


         
    var id_tablero = document.getElementById('id_tablero').value;
    var usuario = document.getElementById('usuario').value;
    var foto_url = document.getElementById('foto_url').value;
    var token ="";
    var id_usuario = document.getElementById('id_usuario').value;
    var dominio = document.getElementById('dominio').value;
    var contador_og = false;
    var action_comment = 'normal';
    var set_data_og ="[]";



         
        if(id_usuario!==0){
         
            token = localStorage.getItem('token');
                 
        }
         

        var comentarios = [
                {usuario:'edisondja',texto:'mantequilla se liquido y se fue mi gente',foto_url:'https://a4aa-148-255-206-48.ngrok.io/edtube/imagenes_perfil/2210022219foto.jpg'},
                {usuario:'edisondja',texto:'no hay forma con ese hombre',foto_url:'https://thumbs.dreamstime.com/b/hombre-gris-del-placeholder-de-la-foto-persona-136701248.jpg'}
            ];


        function like(id_board,id_user){

            axiso.post({


            }).then(data=>{


            }).catch(error=>{


            });


        }

        let likes = document.querySelector('#like');


            likes.addEventListener('click',function(key){

                let FormDatas = new FormData();
                FormDatas.append('action','save_like');
                FormDatas.append('id_usuario',id_usuario);
                FormDatas.append('id_tablero',id_tablero);

                //  alert(action_comment);
                axios.post(`${dominio}/controllers/actions_board.php`,FormDatas,{headers:{
                                    'Content-Type': 'multipart/form-data',
                                    'Authorization': `Bearer ${token}`}

                            }).then(data=>{

                                //capturando los likes
                              let like_c =  document.querySelector('#likes_c');
                              let cantidad_likes = parseInt(like_c.innerHTML);


                              switch(data.data.trim()){

                                case '_success':
                                /*La primera vez que se guarda el like*/
                                    likes.classList.replace("fa-regular","fa-solid");
                                    cantidad_likes+=1;
                                    console.log('entro al succes primer comentario');
                                break;
                                
                                
                                case 'inactivo_success':
                                    
                                    /*Si ya existe un like con este usuario  y pulsa de nuevo se desactiva 
                                     el servidor debe de devolver este estado.
                                    */
                                     likes.classList.replace("fa-solid","fa-regular");
                                     cantidad_likes-=1;
            
                                    console.log('entro a inactivar el comentario');
                                     
                                break;

                                case 'activo_success':

                                    cantidad_likes+=1;
                                    likes.classList.replace("fa-regular","fa-solid");
                                    console.log('entro a activar el comentario');
                                    
                                    /*
                                     Se vuelva a activar el like del usuario luego de aquitarlo       
                                    */

                                break;
                                

                              }

                              like_c.innerHTML = cantidad_likes;

                            }).catch(error=>{

                                console.log(error);

                            });


        });

            
        function guardar_comentario(id_usuario,id_tablero,texto,tipo_post){


                let FormDatas = new FormData();
                FormDatas.append('action','save_post');
                FormDatas.append('id_user',id_usuario);
                FormDatas.append('id_board',id_tablero);
                FormDatas.append('text',texto);
                FormDatas.append('data_og',set_data_og);
                FormDatas.append('type_post',tipo_post);

                                      //  alert(action_comment);
                        
                axios.post(`${dominio}/controllers/actions_board.php`,FormDatas,{headers:{
                                        'Content-Type': 'multipart/form-data',
                                        'Authorization': `Bearer ${token}`
                                }
                                }).then(info=>{
                                        cargar_comentarios(id_tablero);
                                        action_comment = 'normal';
                                        console.log(info);

                                }).catch(error=>{

                                console.log(error);

                                });
                    }

              //  guardar_comentario(1,2,'mantequilla','board');      

            function borrar_comentario(id_comentario,config){
                       // alert('dsdsd');
                        
                        alertify.confirm('eliminar comentario','Quieres eliminar este comentario?',function(e){
                                

                            if(config=='padre'){

                                 document.querySelector(`#comment_del${id_comentario}`).remove();

                            }else{

                                 document.querySelector(`#child_coment${id_comentario}`).remove();
                            }

                            let Borrar_comentario = new FormData();

                                if(config=='padre'){

                                      Borrar_comentario.append('action','delete_comment');

                                }else{
                                            /*
                                                Si no es el comentario padre elimina el comentario
                                                hijo
                                            */
                                    Borrar_comentario.append('action','delete_comment_child');

                                }

                                Borrar_comentario.append('id_comentario',id_comentario);

                                     axios.post(`${dominio}/controllers/actions_board.php`,Borrar_comentario).then(info=>{

                                                console.log(info.data);
                                        
                                    }).catch(error=>{

                                                alertify.warning('error deleting comment');

                                    });


                                },function(e){ console.log('no')});

                    }


            function interface_comentarios_hijos(data,id_coment_master){

                    let child_comments = `<ul id='comments_child${id_coment_master}'>`;
                    let btn_eliminar;
                        data.forEach(key=>{

                            child_comments+=Component_comentario_hijo(key);

                        });

                return child_comments+=`</ul>`;
                        
            }


                    function EventEliminarChild_C(){

                            // Asignar eventos a los comentarios hijos
                            let add_event_c_child = document.querySelectorAll(".fa-delete-left");
                                  add_event_c_child.forEach(data => {
                                      data.addEventListener('click', () => borrar_comentario(data.id,'hijo'));
                                  });
                    }

                    function EventElminarPadre_C(){
                        //Agregar evento para eliminar comentarios padre

                        let add_event = document.querySelectorAll(".comments svg");
                        add_event.forEach(data => {
                            data.addEventListener('click', () => borrar_comentario(data.id,'padre'));
                        });
                    }

                    function cargar_comentarios(id_tablero) {
                        let comentarios_html = '';
                        let interface_ogs = '';
                        let data_ogs = '';
                        let description_og;
                    
                        let FormDatas = new FormData();
                        FormDatas.append('action', 'load_comments');
                        FormDatas.append('id_board', id_tablero);
                    
                        axios.post(`${dominio}/controllers/actions_board.php`, FormDatas)
                            .then(info => {
                                //console.log("DEBUG "+data.info.comentarios_hijos);

                                console.log('Datos recibidos:', info.data); // Verifica los datos recibidos


                                let domain;
                                info.data.forEach(data => {

                          
                                    let childs_comments = interface_comentarios_hijos(data.comentarios_hijos,data.id_comentario);

                                    if (data.data_og !== "[]") {
                                        data_ogs = JSON.parse(data.data_og);
                                        domain = (new URL(data_ogs.url));
                                        description_og = data_ogs.description;
                                        description_og = description_og.substr(0, 80);
                                        interface_ogs = `
                                            <a class='flex-container' href='${data_ogs.url}'>
                                                <figure class='card_og' style='background:#f8f8f8;'>
                                                    <img src='${data_ogs.image}' class='img_card_og'/>
                                                    <hr/>
                                                    <p style='color:black'>${data_ogs.title}</p></br>
                                                    ${description_og}
                                                </figure>
                                            </a>
                                        `;
                                    } else {
                                        interface_ogs = `
                                            <figure class='card_og' style='display:none'>
                                                <figcaption><i class="fa-solid fa-delete-left" style='float:right; cursor:pointer'></i></figcaption>
                                            </figure>
                                        `;
                                    }
                    
                                    if (data.usuario_id == id_usuario) {
                                        comentarios_html += `
                                            <li id='comment_del${data.id_comentario}' class="list-group-item comments box_comment">
                                                <img src="${dominio}/${data.foto_url}" class="rounded" style="width:38px;height:38px;">
                                                <strong class='fontUserComent'>${data.usuario} <span class='fechaText' style='float: right;'>${data.fecha_publicacion}</span></strong><br/>
                                                ${interface_ogs}
                                                &nbsp;<span class='fontComent'>${data.texto}</span>
                                                &nbsp;<svg style='cursor:pointer;float:right' id='${data.id_comentario}' 
                                                    onclick="" xmlns="http://www.w3.org/2000/svg" 
                                                    width="16" height="16" fill="currentColor" class="bi bi-backspace-fill" viewBox="0 0 16 16">
                                                    <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 
                                                    1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 
                                                    2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 
                                                    2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 
                                                    0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0
                                                     1-.707-.708L7.976 8 5.829 5.854z"/>
                                                </svg><br/>
                                                <i class="fa-regular fa-thumbs-up" style='cursor:pointer'></i>
                                                <i class="fa-regular fa-thumbs-down" style='cursor:pointer'></i>
                                                <i class="fa-solid fa-heart-crack" style='cursor:pointer'></i>
                                                <i class="fa-solid fa-reply reply_coment" id='${data.usuario}_${data.id_comentario}' style='cursor:pointer'></i>  
                                                ${childs_comments}
                                          </li>`;
                                    } else {
                                        comentarios_html += `
                                            <li class="list-group-item box_comment" id_comment='0'>
                                                <img src="${dominio}/${data.foto_url}" class="rounded" style="width:38px;height:38px;">
                                                <strong class='fontUserComent'>${data.usuario} <span class='fechaText' style='float: right;'>${data.fecha_publicacion}</span></strong><br/>
                                                ${interface_ogs}
                                                &nbsp;<span class='fontComent'>${data.texto}</span><br/>
                                                <i class="fa-regular fa-thumbs-up" style='cursor:pointer'></i>
                                                <i class="fa-regular fa-thumbs-down" style='cursor:pointer'></i>
                                                <i class="fa-solid fa-heart-crack" style='cursor:pointer'></i>
                                                <i class="fa-solid fa-reply reply_coment" id='${data.usuario}_${data.id_comentario}' style='cursor:pointer'></i>
                                                 ${childs_comments}
                                            </li>`;
                                    }
                                });
                    
                                const comentariosElement = document.getElementById('data_coments');
                                if (comentariosElement) {
                                    comentariosElement.innerHTML = comentarios_html;
                                            
                                    
                                  //  console.log('Comentarios HTML:', comentarios_html); // Verifica el HTML insertado
                                } else {
                                    console.error('Elemento con id "data_coments" no encontrado.');
                                }
                    
                                // Asignar eventos a los comentarios padre
                                EventElminarPadre_C();
                                
                                // Asignar eventos a los comentarios hijos
                                EventEliminarChild_C();
                    
                                add_reply_comment(id_usuario);
                            })
                            .catch(error => {
                                console.error('Error al cargar comentarios:', error);
                            });
                    }
                    
                
              
                

                  let enviar_comentario = document.getElementById('send_coment');
                  
                  cargar_comentarios(id_tablero);

         

                  enviar_comentario.addEventListener("click", () => {

           
                          
                        let texto_comentario = document.querySelector('#text_coment').value; 


                        if(texto_comentario!==''){
             

                                comentarios.unshift({
                                        usuario:usuario,
                                        texto:texto_comentario,
                                        foto_url:foto_url

                                });
                                
                               // cargar_comentarios(id_tablero,'board');

                                if(action_comment=='reply'){
                                         //add_reply_comment(id_usuario);
                                        let  text_coment = document.querySelector('.textComent').value;

                                         reply_coment(id_coment,text_coment,id_usuario);
                                         //cargar_comentarios(id_tablero);

                                         action_comment= 'normal';

                                }else{
                                        guardar_comentario(id_usuario,id_tablero,texto_comentario,'board');
                                }


                                document.querySelector('#text_coment').value='';

                        }


                  });



    

                  
                  document.querySelector("#cerrar_comentarios").addEventListener('click',()=>{

                              
                              document.getElementById("coments").style.display="none";
                              document.getElementById("cerrar_comentarios").style.display="none";


                  });


                  function crear_vista_og(url='https://twitter.com/Crunchyroll/status/1586002419581861888/photo/1'){
                       

                        if(contador_og==false){
                                let FormDatas = new FormData();
                                FormDatas.append('action','get_metaog');
                                FormDatas.append('url',url);

                                axios.post(`${dominio}/controllers/actions_board.php`,FormDatas,{ headers: {
                                        'Content-Type': 'multipart/form-data',
                                        'Authorization': `Bearer ${token}`
                                }
                                }).then(data=>{

                                        contador_og=true;
                                        let dat_og = data.data;
                                      //  console.log(dat_og);

                                        if(dat_og.title!=null && dat_og.description!=null){

                                                set_data_og = JSON.stringify(data.data);
                                                let interface_og = `<div class='flex-container'>
                                                <figure class='card_og'>
                                                        <figcaption><i class="fa-solid fa-delete-left" id='remove_og' style='float:right; cursor:pointer'></i></figcaption>
                                                        <img src='${dat_og.image}' width='150'/>
                                                                ${dat_og.title}<br/>
                                                                ${dat_og.description}
                                                </figure>
                                                </div>
                                                `;
                                                document.querySelector('#interface_og').innerHTML = interface_og;
                                        }else{

                                                set_data_og="[]";
                                        }
                        
                                        

                                        document.querySelector('#remove_og').addEventListener('click',function(){

                                                document.querySelector('figure').remove();
                                                contador_og=false;
                                                set_data_og="[]";

                                        });
                        
                        
                                }).catch(error=>{
                        
                        
                                        //alertify.message('error');
                        
                                });
                                
                        }
                  }    

        var get_og = document.querySelector('#text_coment');

        get_og.addEventListener('change',function(e){

         

                crear_vista_og(e.target.value);

                
        });
              

    