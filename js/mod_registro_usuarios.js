
/* 
    Modulo de registro de usuarios
    Edejesusa 3-08-2024
*/

var baseUrl = window.BASE_URL || '';
var FormDatas_u = new FormData();


async function VerificarUsuario(usuario){
        
        FormDatas_u.append('action','verify_user_exist');
        FormDatas_u.append('username',document.getElementById('username').value);

        await axios.post(baseUrl + '/controllers/actions_board.php',FormDatas_u).then(data=>{        
                
        if(data.data==usuario){

            document.querySelector('#alert_user_field').setAttribute('style','display:block');
          //document.getElementById('alert_user_field').innerHTML ='Usuario no disponible';
            alertify.message('este usuario ya existe');
            return;
            
        }
        
        }).catch(error=>{
        
            alertify.message(error);
        
        });


    }


    async function VerificarCorreo(email){

        FormDatas_u.append('action','verify_email_exist');
        FormDatas_u.append('email',email);

        await axios.post(baseUrl + '/controllers/actions_board.php',FormDatas_u).then(data=>{
                
            
        if(data.data==email){

            document.querySelector('#alert_email_field').setAttribute('style','display:block');
           document.getElementById('alert_email_field').innerHTML ='Este correo ya esta en uso';
            return;
        }else{

            document.querySelector('#alert_email_field').setAttribute('style','display:none');

        }
        
        }).catch(error=>{
        
           // alertify.message('no se pudo verificar el correo electornico');
            return;
        });


    }


    function validateEmail(inputId) {
        // Obtener el valor del input por su ID
        const email = document.getElementById(inputId).value;
        
        // Expresión regular para validar el formato de correo electrónico
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        // Verificar si el correo electrónico es válido
        if (emailPattern.test(email)) {
            return true; // Es un correo válido
        } else {
            return false; // No es un correo válido
        }
    }
    

    
let btn_join = document.getElementById('btn_join');

    btn_join.addEventListener('click',function(){

        //alert('Probando adecuadamente');

        let password = document.getElementById('password').value;
        let password2 = document.getElementById('password2').value;
        let username = document.getElementById('username').value;
        let email = document.getElementById('email').value;

        if(password!==password2){

          //alertify.message('Las contraseñas no son iguales');
            document.querySelector('#alert_passowrd_field').setAttribute('style','display:block');
            return;

        }else{

            document.querySelector('#alert_passowrd_field').setAttribute('style','display:none');

        }   
        

        if(validateEmail('email')){

            
            document.querySelector('#alert_email_field').setAttribute('style','display:none');

        }else{

            document.querySelector('#alert_email_field').setAttribute('style','display:block');

            return;

        }   

        document.querySelector('#alert_passowrd_field').setAttribute('style','display:none');

         VerificarUsuario(username);
         VerificarCorreo(email);

        FormDatas_u.append('action','create_user');
        FormDatas_u.append('username',username);
        FormDatas_u.append('password',password);
        FormDatas_u.append('email',email);
        FormDatas_u.append('name',document.getElementById('name').value);
        FormDatas_u.append('sex',document.getElementById('sex').value);
        FormDatas_u.append('last_name',document.getElementById('last_name').value);
        FormDatas_u.append('bio',document.getElementById('bio_u').value);
        

        //Desactiva el botón
        var boton = document.getElementById('btn_join');   
        boton.disabled = true;

        axios.post(baseUrl + '/controllers/actions_board.php',FormDatas_u).then(data=>{
        
          //  alertify.message(data.data);
          //alert(data.data);

            console.log(data.data);
          boton.disabled = false;
          let resp = data.data.trim();

          switch(resp){

            case 'User_no_avaible':

                document.querySelector('#alert_user_field').style.display = 'block';
                

            break;

            case 'Email_no_avaible':

                 document.querySelector('#alert_email_field').style.display = 'block';
                 document.querySelector('#alert_email_field').innerHTML = 'Correo no disponible';

            break;

            case 'success':

                alertify.message('Usuario registrado con exito');
                document.querySelector('#alert_email_field').style.display = 'none';
                document.querySelector('#alert_user_field').style.display = 'none';
                document.querySelector('#form_reg').style.display = 'none';
                document.querySelector('#welcome_fmb').style.display = 'block';
                document.querySelector('#user_text').innerHTML= `Hola ${username},`;

            break;

            default :

                //alertify.message(data.data);

            break;


          }

  
        
        }).catch(error=>{
        
            alertify.message('Error registrando el usuario');       

        })




    });

 



