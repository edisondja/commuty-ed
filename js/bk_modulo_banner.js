


document.querySelector("#guardar_ads").addEventListener('click',()=>{


        guardar_banner();

});


    function cargar_banners(){


        let FormDatas = new FormData();
        FormDatas.append('action','cargar_banners');
    

        axios.get(`${dominio.value}/controllers/actions_board.php`,FormDatas,{headers:{
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${token_get}`
            }}).then(data=>{

                console.log(data.data);

            }).catch(log=>{

                    console.log('Error '+log);

            });




    }


    function modificar_banner(){

        //se abre un modal para modificar el banner

        
    }


    function activar_banner(){
    
        //Se cambia el estado del banner a activado para ser leido por el sistema



    }


    function desactivar_banner(){
        //Se cambia el estado del banner a desactivado para no ser leido


    }



    function guardar_banner(){


        let descripcion = document.getElementById("descripcion_ads").value;
        let titulo = document.getElementById("descripcion_ads").value;
        let foto = document.getElementById("imagen_ads").files[0];
        let id_usuario = document.getElementById("id_usuario").value;
        let tipo = document.getElementById("tipo").value;
        let script_banner = document.getElementById('script_banner').value;
        let posicion_banner =document.getElementById('posicion').value;


            // Aquí puedes enviar la información al backend con fetch/axios
            console.log("Descripción:", descripcion);
            console.log("Foto:", foto);

            if(descripcion ==''|| titulo==''){

                alertify.message('Debes colocar un titulo o descripcion');
                return;
            }
            let FormDatas = new FormData();
            FormDatas.append('action','guardar_ads');
            FormDatas.append('id_usuario',id_usuario);
            FormDatas.append('titulo',titulo);
            FormDatas.append('descripcion', descripcion);
            FormDatas.append('tipo',tipo);
            FormDatas.append('script_banner',script_banner);
            FormDatas.append('posicion',posicion_banner);

            if (foto) {
            
                FormDatas.append('imagen_ads',foto);

            }else{

                FormDatas.append('imagen_ads','');

            }

            
        axios.post(`${dominio.value}/controllers/actions_board.php`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${token_get}`
            }
        }).then(response => { 


            console.log(response.data);

        }).catch(error=>{

            console.error('No se pudo guardar el banner');

        });


}