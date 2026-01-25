var id =0;
var id_reply_coment = 0;
var id_coment = 0;
var baseUrl = window.BASE_URL || '';





function Component_comentario_hijo(data){
    
    
    
    let btn_eliminar ='';

    if(id_usuario==data.user_id){

        console.log(id_usuario+' '+data.user_id);
        btn_eliminar=`<i id="${data.id_reply}" class="fa-solid fa-delete-left" style="cursor:pointer;float:right" ></i>`;
    
    }else{

        btn_eliminar ='';    
    }


    return `<li class="list-group-item comments box_comment" id="child_coment${data.id_reply}">
            <img src="/${data.foto_url}" class="rounded" style="width:38px;height:38px;">
            <strong class='fontUserComent'>${data.usuario} 
            <span class='fechaText' style='float: right;'>${data.fecha_creacion}</span></strong><br/>
            &nbsp;<span class='fontComent'>${data.text_coment}</span>
            ${btn_eliminar}
           </li>`;
}



function Component_child_c(data,id_coment_master){

    /*
        esta function se encarga de insertar los comentarios hijos
        al padre correspondiente, recibiendo el id del comentario maestro.
    */

    console.log('Entro a la funcion Component_child_c');
    console.log('Data recibida:', data);
    console.log('ID comentario master:', id_coment_master);
    
    // Validar que id_coment_master sea un número válido
    if (!id_coment_master || isNaN(id_coment_master)) {
        console.error('Error: id_coment_master no es válido:', id_coment_master);
        alertify.error('Error: ID de comentario inválido');
        return;
    }

    // La respuesta de Axios viene como { data: {...} }
    // El servidor retorna JSON directamente, así que data.data será el objeto parseado
    let comentarioData = null;
    
    if (data && data.data) {
        // Si data.data es un string, parsearlo
        if (typeof data.data === 'string') {
            try {
                comentarioData = JSON.parse(data.data);
            } catch (e) {
                console.error('Error parsing JSON:', e, 'String recibido:', data.data);
                // Si el parseo falla, puede que ya sea un objeto
                comentarioData = data.data;
            }
        } else {
            comentarioData = data.data;
        }
    } else if (data) {
        comentarioData = data;
    }
    
    if (!comentarioData || (typeof comentarioData === 'object' && Object.keys(comentarioData).length === 0)) {
        console.error('Error: No se pudo obtener los datos del comentario', data);
        if (typeof alertify !== 'undefined') {
            alertify.error('Error: No se recibieron datos del comentario');
        }
        return;
    }
    
    console.log('Datos del comentario recibidos:', comentarioData);

    let child_c = Component_comentario_hijo(comentarioData);

    // Buscar el contenedor de comentarios hijos
    let container = document.querySelector(`#comments_child${id_coment_master}`);
    
    if (!container) {
        console.warn(`Contenedor #comments_child${id_coment_master} no encontrado, intentando crearlo...`);
        
        // Buscar el comentario padre por diferentes selectores posibles
        let parentComment = document.querySelector(`#comment_del${id_coment_master}`) ||
                           document.querySelector(`#coment${id_coment_master}`) ||
                           document.querySelector(`[data-coment-id="${id_coment_master}"]`) ||
                           document.querySelector(`li[id*="${id_coment_master}"]`);
        
        // Si no se encuentra, buscar por el botón de reply que tiene el ID
        // El formato del ID es: usuario_id_comentario, así que buscamos por _id_comentario
        if (!parentComment) {
            // Buscar por selector que termine con _id_comentario
            const replyButton = document.querySelector(`.reply_coment[id$="_${id_coment_master}"]`) ||
                               document.querySelector(`.reply_coment[id*="_${id_coment_master}"]`);
            if (replyButton) {
                parentComment = replyButton.closest('li');
                console.log('Encontrado por botón reply (primera búsqueda):', parentComment);
            }
        }
        
        // Si aún no se encuentra, buscar de forma más exhaustiva
        if (!parentComment) {
            const allReplyButtons = document.querySelectorAll('.reply_coment');
            for (let btn of allReplyButtons) {
                if (btn.id) {
                    // Verificar si el ID termina con _id_comentario
                    const idParts = btn.id.split('_');
                    if (idParts.length >= 2 && idParts[idParts.length - 1] === id_coment_master.toString()) {
                        parentComment = btn.closest('li');
                        console.log('Encontrado por botón reply (búsqueda exhaustiva):', parentComment, 'ID del botón:', btn.id);
                        break;
                    }
                }
            }
        }
        
        if (parentComment) {
            // Verificar si ya existe un contenedor dentro del comentario padre
            container = parentComment.querySelector(`#comments_child${id_coment_master}`);
            
            if (!container) {
                // Crear el contenedor si no existe
                container = document.createElement('ul');
                container.id = `comments_child${id_coment_master}`;
                container.className = 'list-group';
                parentComment.appendChild(container);
                console.log('Contenedor creado exitosamente');
            }
        } else {
            console.error(`No se pudo encontrar el comentario padre con ID ${id_coment_master}`);
            console.log('ID del comentario buscado:', id_coment_master);
            console.log('Comentarios disponibles:', document.querySelectorAll('li[id*="comment"]'));
            console.log('Botones reply disponibles:', document.querySelectorAll('.reply_coment'));
            
            if (typeof alertify !== 'undefined') {
                alertify.error('No se pudo encontrar el comentario para responder');
            } else {
                alert('No se pudo encontrar el comentario para responder');
            }
            return;
        }
    }
    
    // Asegurar que el contenedor existe antes de insertar
    if (!container) {
        console.error('Error crítico: El contenedor no se pudo crear');
        console.error('ID comentario master:', id_coment_master);
        console.error('Contenedor buscado:', `#comments_child${id_coment_master}`);
        alertify.error('Error al insertar la respuesta: contenedor no encontrado');
        return;
    }
    
    // Validar que child_c no esté vacío
    if (!child_c || child_c.trim() === '') {
        console.error('Error: El HTML del comentario hijo está vacío');
        alertify.error('Error: No se pudo generar el comentario');
        return;
    }
    
    try {
        container.insertAdjacentHTML('afterbegin', child_c);
        console.log('Comentario hijo insertado correctamente');
    } catch (error) {
        console.error('Error al insertar comentario hijo:', error);
        alertify.error('Error al insertar la respuesta');
    }

   /*
        Agregando evento de poder eliminar el comentario hijo
        al momento de agregarlo
   */
   EventEliminarChild_C();

}


function reply_coment(id_coment,text_coment,id_user){

    if (!id_coment || !text_coment || !id_user) {
        console.error('Error: Faltan parámetros para responder comentario', {id_coment, text_coment, id_user});
        if (typeof alertify !== 'undefined') {
            alertify.error('Error: Faltan datos para responder');
        } else {
            alert('Error: Faltan datos para responder');
        }
        return;
    }

    // Limpiar el texto del comentario (remover el @usuario si existe)
    let texto_limpio = text_coment.replace(/^@\w+\s+/, '').trim();
    if (!texto_limpio) {
        if (typeof alertify !== 'undefined') {
            alertify.error('El comentario no puede estar vacío');
        } else {
            alert('El comentario no puede estar vacío');
        }
        return;
    }

    let FormDatas = new FormData();
    FormDatas.append('id_coment',id_coment);
    FormDatas.append('text_coment',texto_limpio);
    FormDatas.append('id_user',id_user);
    FormDatas.append('action','reply_coment');

    console.log('Enviando respuesta al comentario:', {id_coment, text_coment: texto_limpio, id_user});

    axios.post(baseUrl + '/controllers/actions_board.php',FormDatas).then(datos=>{

            console.log('Respuesta del servidor completa:', datos);
            console.log('Datos recibidos:', datos.data);
 
            // Verificar que la respuesta tenga los datos correctos
            if (datos && datos.data) {
                // Pequeño delay para asegurar que el DOM esté listo
                setTimeout(() => {
                    Component_child_c(datos, id_coment);
                    action_comment='normal';
                    
                    // Limpiar el campo de texto
                    const textField = document.querySelector('.textComent');
                    if (textField) {
                        textField.value = '';
                    }
                    
                    // Mostrar mensaje de éxito
                    if (typeof alertify !== 'undefined') {
                        alertify.success('Respuesta enviada correctamente');
                    }
                }, 100);
            } else {
                console.error('Error: La respuesta no contiene datos válidos', datos);
                if (typeof alertify !== 'undefined') {
                    alertify.error('Error: No se recibieron datos del servidor');
                } else {
                    alert('Error: No se recibieron datos del servidor');
                }
            }

    }).catch(error=>{

        console.error('Error al enviar respuesta:', error);
        if (typeof alertify !== 'undefined') {
            alertify.error('Error al enviar la respuesta');
        } else {
            alert('Error al enviar la respuesta');
        }

    });


}


function notify_reply_coment(){




}


function add_reply_comment(id_usuario){

    // Seleccionamos el contenedor de comentarios
    var reply_btn = document.querySelector('#data_coments');
    if (!reply_btn) {
        console.warn('Contenedor #data_coments no encontrado');
        return;
    }
    
    // Seleccionamos todos los comentarios que tienen la clase 'reply_coment'
    // Usamos delegación de eventos para manejar elementos dinámicos
    reply_btn.addEventListener('click', function(e) {
        // Verificar si el click fue en un botón de reply
        if (e.target && e.target.classList.contains('reply_coment')) {
            e.preventDefault();
            e.stopPropagation();
            
            // Indicamos que la acción actual es una respuesta (reply)
            action_comment = 'reply';
         
            // Obtenemos el id del comentario
            let id = e.target.id.split('_');
            if (id.length >= 2) {
                id_coment = id[id.length - 1]; // El último elemento es el ID del comentario
                console.log('ID del comentario para responder:', id_coment);

                // Seleccionamos el campo de texto donde se va a insertar el @ del usuario
                let response = document.querySelector('.textComent');
                if (response) {
                    // Insertamos el @ seguido del nombre del usuario en el campo de texto
                    response.value = `@${id[0]} `;
                    response.focus(); // Enfocar el campo de texto
                } else {
                    console.error('Campo de texto .textComent no encontrado');
                }
            } else {
                console.error('Formato de ID inválido:', e.target.id);
            }
        }
    });
}

