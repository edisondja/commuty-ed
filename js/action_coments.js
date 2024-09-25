var id =0;
var id_reply_coment = 0;
var id_coment = 0;




function Component_child_c(data){

    /*
        esta function se encarga de insertar los comentarios hijos
        al padre correspondiente, recibiendo el id del comentario maestro.
    */

    if(id_usuario==data.user_id){

        console.log(id_usuario+' '+key.user_id);
        btn_eliminar=`<i id="${key.id_reply_id}" class="fa-solid fa-delete-left" style="cursor:pointer;float:right" ></i>`;
    
    }else{

        btn_eliminar ='';    
    }

     let child_c=`<li class="list-group-item comments box_comment" id="child_coment${data.id_reply_id}">
            <img src="${dominio}/${data.foto_url}" class="rounded" style="width:38px;height:38px;">
            <strong class='fontUserComent'>${data.usuario} <span class='fechaText' style='float: right;'>${data.fecha_creacion}</span></strong><br/>
            &nbsp;<span class='fontComent'>${data.text_coment}</span>
            ${btn_eliminar}
        </li>
    `;


    document.querySelector(`comments_child4${id_coment_master}`).innerHTML='';


}



function reply_coment(id_coment,text_coment,id_user){

    let FormDatas = new FormData();
    FormDatas.append('id_coment',id_coment);
    FormDatas.append('text_coment',text_coment);
    FormDatas.append('id_user',id_user);
    FormDatas.append('action','reply_coment');

    axios.post(`${dominio}/controllers/actions_board.php`,FormDatas).then(data=>{

            let btn_eliminar ='';
            console.log(data.data);
 


    }).catch(error=>{

        console.log(error);

    });


}


function notify_reply_coment(){




}


function add_reply_comment(id_usuario){



    var reply_btn = document.querySelector('#data_coments');
    var comentarios = reply_btn.querySelectorAll('ul li .reply_coment');
   
    comentarios.forEach(data=>{


        data.addEventListener('click',(e)=>{

        

                action_comment= 'reply';
         
                var id = e.target.id.split('_');
                id_coment = id[1];
           
                var response = document.querySelector(`.textComent`);
                response.innerHTML=`@${id[0]}`;
       
        });

    });


}
