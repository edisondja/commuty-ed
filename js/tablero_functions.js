var tipo_multimedias = new Array();

tipo_multimedias['imagen']=`<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
<path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
<path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"/>
</svg>`;

tipo_multimedias['video']=`<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-camera-video-fill" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2V5z"/>
</svg>`;

tipo_multimedias['audio']=`<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-speaker-fill" viewBox="0 0 16 16">
<path d="M9 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-2.5 6.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
<path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm6 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 7a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7z"/>
</svg>`;


//alert(tipo_multimedias['imagen']);


function render_multimedia(tipo,url){


    switch(tipo){

        case'video':

            return `<video src='${url}' controls='true' style='width:150px;height:100px;'></video>`;

        break;

        case'imagen':

            return `<img src='${url}' style='width:150px;height:100px;' />`;

        break;

        case'audio':

            return `<audio src='${url}' width='100'></audio>`;

        break;

    }
}


function cargar_metadatos_multimedia(id_multimedia){

       $.ajax({
            url:'gestos_on.php',
            type:'post',
            data:{
                action:'cargar_metadatos_de_tablero',
                id_multimedia:id_multimedia
            }

       }).done(data=>{

            var data = JSON.parse(data);

            document.querySelector('#texto').value= data.texto;
            document.querySelector('#precio').value= data.precio;
           $("#method_pay").val(data.metodo_de_pago);


       });


}


function agregar_evento_a_boton_retroceder(){


    let back = document.querySelector('#back');
    back.addEventListener('click',function(){

        document.querySelector('#tablero').click();

    });
}


function asignar_metadatos_a_multimedia(id_multimedia,texto,precio,metodo_de_pago){

    $.ajax({
        url:'gestos_on.php',
        type:'post',
        data:{
            action:'asignar_metadatos_a_multimedia',
            id_multimedia:id_multimedia,
            texto:texto,
            precio:precio,
            metodo_de_pago:metodo_de_pago
        }

    }).done(data=>{

        alertify.message('metados asignados con exito');
    });

}

function carga_tablero(id){

    $.ajax({
        url:'gestos_on.php',
        type:'post',
        data:{
            id_tablero:id,
            action:'cargar_tablero'
        }

    }).done(data=>{
        
        let data_t  = JSON.parse(data);
        $(`#dataMain`).html(`<div>
            <h4>Actualizar Tablero</h4><hr/>
            <button class='btn btn-dark' style='float:right; margin:5px;' id='back'>Retroceder</button>

            <form method='post'  enctype='multipart/form-data' action='gestos_on.php'>
                <strong>Titulo</strong></br>
                <input type='text' class='form-control' name='titulo' value='${data_t.titulo}'><br/>
                <strong>Descripcion</strong><br/>
                <textarea class='form-control'name='descripcion'>${data_t.descripcion}</textarea><br/>
                <strong>Imagen</strong></br>
                <input type='file' class='form-control' name='imagen'><br/>
                <input type='hidden' class='form-control' name='id_tablero' value='${data_t.id_tablero}'/>
                <input type='hidden' class='form-control' name='action' value='actualizar_tablero'/>
                <input type='submit' value='Actualizar' class='btn btn-info'>
            </form><hr/>
             <div  style='overflow-y:scroll;height:400px;'>
                 <table id='actualizar_tablero_form' class='table'>				
             </div>
         <div>

          `);

          agregar_evento_a_boton_retroceder();

          cargar_multimedias_de_tablero(id);


    });


}

function cargar_multimedias_de_tablero(id){

 $.ajax({
     url:'gestos_on.php',
        type:'post',
        data:{
            id_tablero:id,
            action:'cargar_multimedias_de_tablero'
        }
     
 }).done(data=>{

     var cabezera_tabla =`<tr>
                             <td>Tipo multimedia</td>
                             <td>Visualidad</td>
                             <td>Asignar accion</td>
                             <td>Eliminar</td>
                         </tr>`;

     var data = JSON.parse(data);

     data.forEach(e=>{


         cabezera_tabla+=`<tr id='rm${e.id_asignar}'>
                             <td>
                                 ${tipo_multimedias[e.tipo_multimedia]}
                             </td>
                             <td>${render_multimedia(e.tipo_multimedia,e.ruta_multimedia)}</td>
                             <td>
                                <button  id='${e.id_asignar}' class='btn btn-dark'>Asignar datos</button>
                             </td>
                             <td><svg xmlns="http://www.w3.org/2000/svg" 
                             id='${e.id_asignar}' style='cursor:pointer' width="30" height="40" fill="currentColor" class="bi bi-trash2-fill" viewBox="0 0 16 16">
                             <path d="M2.037 3.225A.703.703 0 0 1 2 3c0-1.105 2.686-2 6-2s6 .895 6 2a.702.702 0 0 1-.037.225l-1.684 10.104A2 2 0 0 1 10.305 15H5.694a2 2 0 0 1-1.973-1.671L2.037 3.225zm9.89-.69C10.966 2.214 9.578 2 8 2c-1.58 0-2.968.215-3.926.534-.477.16-.795.327-.975.466.18.14.498.307.975.466C5.032 3.786 6.42 4 8 4s2.967-.215 3.926-.534c.477-.16.795-.327.975-.466-.18-.14-.498-.307-.975-.466z"/>
                            </svg></td>
                         </tr>`;

     });

     
        document.getElementById('actualizar_tablero_form').innerHTML = cabezera_tabla;
        let elemento = document.querySelector('#actualizar_tablero_form');
        let buttons  = elemento.querySelectorAll('button');
        let eliminar_btn = elemento.querySelectorAll('svg');


        //agregando funcionalildaes a los elementos de los tableros

        buttons.forEach(button=>{

              button.addEventListener('click',(e)=>{


                
                alertify.confirm('Asignar metados a multimedia',`
                <p>Escriba un encabezado para el multimedia</p><hr/>
                <textarea id='texto' placholder='asignar texto al element' class='form-control'></textarea><hr/>
                <p>Asignar precio</p><hr/>
                <input id='precio' plachedoler='0.00' type='number' class='form-control'/><hr/>
                <p>Asignar metodo de pago</p><hr/>
                <select class='form-control' id='method_pay'>
                    <option value='none'>Ninguno</option>
                    <option value='paypal'>Paypal</option>
                    <option value='btc'>Bitcoin</option>
                    <option value='transfer_bank'>Transferencia bancaria</option>
                    <option value='paypal,btc'>Paypal y Bitcoin</option>
                    <option value='paypal,bitcoin,transfer_bank'>Paypal,Bitcoin y transferencia bancaria</option>
                </select>
                ${cargar_metadatos_multimedia(e.target.id)}
                `,function(){

                    let id_multimedia = e.target.id;
                    let texto = document.querySelector('#texto').value;
                    let precio = document.querySelector('#precio').value;
                    let metodo_de_pago = document.querySelector('#method_pay').value;
                    asignar_metadatos_a_multimedia(id_multimedia,texto,precio,metodo_de_pago);

                    //document.querySelector('#titulo').value='';
                },function(){});   
    

                });


        });

        
        eliminar_btn.forEach(data=>{


            data.addEventListener('click',()=>{

                    eliminar_multimedia_de_tablero(data.id);

            });

        });

 });



}


function subir_multimedia(id){

    $(`#dataMain`).html(`<hr/>
        <h2>Seleccione los archivos que quiere subir al tablero</h2><hr/>
        <form method='post' action='gestos_on.php' enctype='multipart/form-data'><br/>
            <input type='hidden' name='id_tablero' value='${id}'/>
            <input type='hidden' name='action' value='asignar_multimedia_a_tablero'/>
            <input type='file' name='multimedia[]' multiple/></br><br/>
             <div class="progress progress-striped active">
           <div class="progress-bar bg-warning"  role="progressbar" aria-valuenow="0" aria-valuemin="0" 
           aria-valuemax="100" style="width: 0%;background:#28a745;">
             <span class="sr-only">0% Complete</span>
           </div>
         </div><hr/>
            <button class='btn btn-primary'>Subir</button>
        </form>
    `);

}


function eliminar_tablero(id){

        alertify.confirm("Deseas eliminar este tablero?","si pulzas este tablero sera eliminado permanentemente",function(){

            $.ajax({
                url:'gestos_on.php',
                type:'post',
                data:{
                    action:'eliminar_tablero',
                    id_tablero:id
                }

            }).done(data=>{
                console.log(data);
            });
        
        },function(){

            alertify.success("eliminado con exito");
            $(`#tab${id}`).hide('slow',function(){

                    $(`#tab${id}`).remove();

            });
        });

}

function eliminar_multimedia_de_tablero(id){


     alertify.confirm('Estas seguro que deseas eliminar este archivo','ok',function(){
         
         document.querySelector(`#rm${id}`).remove();
         //alertify.message(`elimina con exito ${id}`);
         $.ajax({
             url:'gestos_on.php',
                type:'post',
                data:{
                    action:'eliminar_multimedia_de_tablero',
                    id_multimedia:id
                }
         }).done(data=>{

             alertify.message('elemento eliminado con exito');

         });

     },function(){});



}
