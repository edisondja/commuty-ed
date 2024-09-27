var id =0;
var id_reply_coment = 0;
var id_coment = 0;





function Component_comentario_hijo(data){
    
    
    
    let btn_eliminar ='';

    if(id_usuario==data.user_id){

        console.log(id_usuario+' '+data.user_id);
        btn_eliminar=`<i id="${data.id_reply_id}" class="fa-solid fa-delete-left" style="cursor:pointer;float:right" ></i>`;
    
    }else{

        btn_eliminar ='';    
    }


    return `<li class="list-group-item comments box_comment" id="child_coment${data.id_reply_id}">
            <img src="${dominio}/${data.foto_url}" class="rounded" style="width:38px;height:38px;">
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

    console.log('Entro a la funcion '+data.data);


     let child_c= Component_comentario_hijo(data.data);

    document.querySelector(`#comments_child${id_coment_master}`).insertAdjacentHTML('afterbegin', child_c);

   /*
        Agregando evento de poder eliminar el comentario hijo
        al momento de agregarlo
   */
   EventEliminarChild_C();

}


function reply_coment(id_coment,text_coment,id_user){

    let FormDatas = new FormData();
    FormDatas.append('id_coment',id_coment);
    FormDatas.append('text_coment',text_coment);
    FormDatas.append('id_user',id_user);
    FormDatas.append('action','reply_coment');

    axios.post(`${dominio}/controllers/actions_board.php`,FormDatas).then(datos=>{

            //console.log(data.data.usuario);
 
            Component_child_c(datos,id_coment);
            action_comment='normal';

    }).catch(error=>{

        console.log(error);

    });


}


function notify_reply_coment(){




}


function add_reply_comment(id_usuario){

    // Seleccionamos el contenedor de comentarios
    var reply_btn = document.querySelector('#data_coments');
    // Seleccionamos todos los comentarios que tienen la clase 'reply_coment'
    var comentarios = reply_btn.querySelectorAll('ul li .reply_coment');
   
    // Iteramos sobre cada comentario
    comentarios.forEach(data => {

        // Añadimos un event listener a cada uno
        data.addEventListener('click', (e) => {

            // Indicamos que la acción actual es una respuesta (reply)
            action_comment = 'reply';
         
            // Obtenemos el id del comentario
            let id = e.target.id.split('_');
            id_coment = id[1];

            // Seleccionamos el campo de texto donde se va a insertar el @ del usuario
            let response = document.querySelector('.textComent');

            // Insertamos el @ seguido del nombre del usuario en el campo de texto
            response.value = `@${id[0]} `;  // Cambiado a 'value' para asegurarnos que se llene el campo de texto
       
        });

    });

}

