
    /*
        Modulo enviar_correos
    
    */
   
    var baseUrl = '';
    var config = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }};

    // Función para obtener baseUrl
    function getBaseUrl() {
        if (window.BASE_URL) return window.BASE_URL;
        var dominioEl = document.getElementById('dominio');
        if (dominioEl && dominioEl.value) {
            var domVal = dominioEl.value;
            if (domVal.indexOf('http') === 0) {
                var match = domVal.match(/^https?:\/\/[^\/]+(\/.*)?$/);
                if (match && match[1]) return match[1].replace(/\/$/, '');
            } else if (domVal.indexOf('/') === 0) {
                return domVal.replace(/\/$/, '');
            }
        }
        return '';
    }

    function envar_correo(){
        let texto_correo = document.getElementById('texto_correo').value;
        let api_config = (baseUrl || window.BASE_URL || '') + '/controllers/actions_board.php';
        let asunto = document.getElementById('asunto').value;

        let FormDatas = new FormData();
        FormDatas.append('action','send_mail_all');
        FormDatas.append('mensaje',texto_correo);
        FormDatas.append('usuario','admin');
        FormDatas.append('asunto',asunto);

        axios.post(api_config, FormDatas, config).then(data=>{
            alertify.message(data.data);
            console.log(data.data);
            alertify.message('Correo enviado con exito');
        }).catch(error=>{
            alertify.message(error);
        });
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        baseUrl = getBaseUrl();
        console.log('bk_modulo_mail baseUrl:', baseUrl);
        
        alertify.message('modulo activo y listo');

        let send_mail = document.querySelector('#send_mail');
        if (send_mail) {
            send_mail.addEventListener('click', function(){
                envar_correo();
            });
        }
    });

  