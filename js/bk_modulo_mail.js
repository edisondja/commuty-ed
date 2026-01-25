
    /*
        Modulo enviar_correos
    
    */
   

    alertify.message('modulo activo y listo');

    var config = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }};


            
        function envar_correo(){

            
            let texto_correo = document.getElementById('texto_correo').value;

            let api_config ='/controllers/actions_board.php';

            let asunto = document.getElementById('asunto').value;

            let FormDatas = new FormData();
            FormDatas.append('action','send_mail_all');
            FormDatas.append('mensaje',texto_correo);
            FormDatas.append('usuario','admin');
            FormDatas.append('asunto',asunto);

            
            axios.post(api_config,
                FormDatas,
                config).then(data=>{
                alertify.message(data.data);
                console.log(data.data);
                alertify.message('Correo enviado con exito');

            }).catch(error=>{

                alertify.message(error);


            });


    }


    let send_mail = document.querySelector('#send_mail');

    send_mail.addEventListener('click',function(){

            envar_correo();
         

    });

  