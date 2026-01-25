
    /*
        Modulo configuracion
    
    */

       

        var config = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }};

         var api_config = '/controllers/actions_board.php';
    

        function guardar_configuracion(){

            let dominio = document.getElementById('dominio_c').value;
            let nombre_sitio = document.getElementById('nombre_sitio').value;
            let descripcion_slogan = document.getElementById('descripcion_slogan').value;
            let descripcion_sitio = document.getElementById('descripcion_sitio').value;
            let logo_sitio = document.getElementById('sitio_logo').files[0];
            let favicon_sitio = document.getElementById('favicon').files[0];
            let copyright_descripcion =  document.getElementById('copyright_descripcion').value;
            let email_sitio = document.getElementById('email_sitio').value;
            let busqueda_descripcion = document.getElementById('busqueda_descripcion').value;
            let pagina_descripcion = document.getElementById('pagina_descripcion').value;
            let titulo_descripcion = document.getElementById('titulo_descripcion').value;
            let busqueda_hastag = document.getElementById('busqueda_hastag').value;
            let email_remitente = document.getElementById('email_remitente').value;
            let nombre_remitente = document.getElementById('nombre_remitente').value;
            let servidor_smtp = document.getElementById('servidor_smtp').value;
            let puerto_smtp = document.getElementById('puerto_smtp').value;
            let usuario_smtp = document.getElementById('usuario_smtp').value;
            let clave_smtp = document.getElementById('contrasena_smtp').value;
            let autenticacion_ssl = document.getElementById('autenticacion_ssl').value;
       
            let publicar_sin_revision = '';
            if(document.getElementById('publicar_sin_revision').checked==true){
                 publicar_sin_revision = 'SI';
                //alertify.message('Los nuevos articulos se publicaran sin revision');
            }else{
                publicar_sin_revision = 'NO';
                //alertify.message('Los nuevos articulos se publicaran con revision');
            }
            let verificar_cuenta = '';
            if(document.getElementById('verificar_cuenta').checked==true){
                verificar_cuenta = 'SI';
               // alertify.message('Los nuevos usuarios deberan verificar su cuenta');
            }else{
                verificar_cuenta = 'NO';
                //alertify.message('Los nuevos usuarios no deberan verificar su cuenta');
            }

            let rabbit_mq ='';

            if(document.getElementById('rabbit_mq').checked==true){

                    rabbit_mq = 'SI';
            }else{

                    rabbit_mq = 'NO';
            }

            let ffmpeg ='';

            if(document.getElementById('ffmpeg').checked==true){

                 ffmpeg = 'SI';
            }else{
                
                ffmpeg = 'NO';
            }


            let redis_cache ='';

            if(document.getElementById('redis_cache').checked==true){

                redis_cache = 'SI';
            }else{

                redis_cache = 'NO';
            }

            let FormDatas = new FormData();
            FormDatas.append('action','config_site_text');
            FormDatas.append('dominio',dominio);
            FormDatas.append('nombre_sitio',nombre_sitio);
            FormDatas.append('descripcion_slogan',descripcion_slogan);
            FormDatas.append('favicon_sitio',favicon_sitio);
            FormDatas.append('logo_sitio',logo_sitio);
            FormDatas.append('descripcion_sitio',descripcion_sitio);
            FormDatas.append('copyright_descripcion',copyright_descripcion);
            FormDatas.append('pagina_descripcion',pagina_descripcion);
            FormDatas.append('email_sitio',email_sitio);
            FormDatas.append('busqueda_descripcion',busqueda_descripcion);
            FormDatas.append('titulo_descripcion',titulo_descripcion);
            FormDatas.append('busqueda_hastag',busqueda_hastag);
            FormDatas.append('email_remitente',email_remitente);
            FormDatas.append('nombre_remitente',nombre_remitente);
            FormDatas.append('email_remitente',email_remitente);
            FormDatas.append('servidor_smtp',servidor_smtp);
            FormDatas.append('puerto_smtp',puerto_smtp);
            FormDatas.append('usuario_smtp',usuario_smtp);
            FormDatas.append('clave_smtp',clave_smtp);
            FormDatas.append('autenticacion_ssl',autenticacion_ssl);
            FormDatas.append('publicar_sin_revision',publicar_sin_revision);
            FormDatas.append('verificar_cuenta',verificar_cuenta);
            FormDatas.append('rabbit_mq',rabbit_mq);
            FormDatas.append('ffmpeg',ffmpeg);
            FormDatas.append('redis_cache',redis_cache);

            axios.post(api_config,
                FormDatas,
                config).then(response=>{
                // Manejar respuesta JSON o texto
                let data = response.data;
                if (typeof data === 'string') {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        // Si no es JSON, mostrar el mensaje directamente
                        alertify.success(data);
                        cagar_configuracion();
                        return;
                    }
                }
                
                if (data.success) {
                    alertify.success(data.message || 'Configuración guardada correctamente');
                } else {
                    alertify.error(data.message || 'Error al guardar la configuración');
                }
                
                console.log(data);
                cagar_configuracion();

            }).catch(error=>{
                console.error('Error:', error);
                let errorMsg = 'Error actualizando sitio';
                
                if (error.response && error.response.data) {
                    if (typeof error.response.data === 'string') {
                        try {
                            const errorData = JSON.parse(error.response.data);
                            errorMsg = errorData.message || errorMsg;
                        } catch (e) {
                            errorMsg = error.response.data.substring(0, 100);
                        }
                    } else if (error.response.data.message) {
                        errorMsg = error.response.data.message;
                    }
                }
                
                alertify.error(errorMsg);
            });


    }

        cagar_configuracion();


            document.getElementById('guardar_config').addEventListener('click', function(event) {

                    guardar_configuracion();
        
            });

            document.getElementById('guardar_config_correo').addEventListener('click',function(){

                guardar_configuracion();
                    
            })

            function cagar_configuracion(){


                let FormDatas = new FormData();
                FormDatas.append('action','config_load_site');


                axios.post(api_config,
                    FormDatas,
                    config).then(data=>{

                        document.getElementById('dominio_c').value = data.data.dominio;
                        document.getElementById('nombre_sitio').value = data.data.nombre_sitio;
                        document.getElementById('descripcion_slogan').value = data.data.descripcion_slogan;
                        document.getElementById('descripcion_sitio').value = data.data.descripcion_sitio;
                        //document.getElementById('favicon').value = data.data.favicon_url;
                        //document.getElementById('sitio_logo').value = data.data.sitio_logo_url;
                        document.getElementById('copyright_descripcion').innerHTML = data.data.copyright_descripcion;
                        document.getElementById('email_sitio').value = data.data.email_sitio;
                        document.getElementById('busqueda_descripcion').innerHTML= data.data.busqueda_descripcion;
                        document.getElementById('pagina_descripcion').innerHTML = data.data.pagina_descripcion;
                        document.getElementById('titulo_descripcion').innerHTML= data.data.titulo_descripcion;
                        document.getElementById('busqueda_hastag').value = data.data.busqueda_hastag;
                        document.getElementById('favicon_img').src =`/${data.data.favicon_url}`;
                        document.getElementById('logo_img').src =`/${data.data.sitio_logo_url}`;
                        //datos de cofiguracion de correo
                        document.getElementById('email_remitente').value = data.data.email_remitente;
                        document.getElementById('nombre_remitente').value = data.data.nombre_remitente;
                        document.getElementById('servidor_smtp').value = data.data.servidor_smtp;
                        document.getElementById('puerto_smtp').value = data.data.puerto_smtp;
                        document.getElementById('usuario_smtp').value = data.data.usuario_smtp;
                        document.getElementById('contrasena_smtp').value = data.data.clave_smtp;
                        // autenticacion_ssl se maneja abajo con los switches


                        // Marcar switches según el valor (puede ser 1, '1', 'SI' o true)
                        const isChecked = (val) => val == 1 || val === '1' || val === 'SI' || val === true;
                        
                        document.getElementById('rabbit_mq').checked = isChecked(data.data.rabbit_mq);
                        document.getElementById('ffmpeg').checked = isChecked(data.data.ffmpeg);
                        document.getElementById('redis_cache').checked = isChecked(data.data.redis_cache);
                        document.getElementById('publicar_sin_revision').checked = isChecked(data.data.publicar_sin_revision);
                        document.getElementById('verificar_cuenta').checked = isChecked(data.data.verificar_cuenta);
                        
                        // También marcar autenticación SSL si existe
                        if (document.getElementById('autenticacion_ssl')) {
                            document.getElementById('autenticacion_ssl').value = isChecked(data.data.autenticacion_ssl) ? 'si' : 'no';
                        }

                }).catch(error=>{

                    console.log(error);
                    alertify.message('Error actualizando sitio');

                });





            }




