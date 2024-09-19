var id =0;
var id_reply_coment = 0;
var id_coment = 0;




function reply_coment(id_coment,text_coment,id_user){

    let FormDatas = new FormData();
    FormDatas.append('id_coment',id_coment);
    FormDatas.append('text_coment',text_coment);
    FormDatas.append('id_user',id_user);
    FormDatas.append('action','reply_coment');

    axios.post(`${dominio}/controllers/actions_board.php`,FormDatas).then(data=>{

        //alert(data.data);

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
