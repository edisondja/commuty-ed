
window.onload=function(){


    let avanzar = document.getElementById('avanzar');

    let retroceder = document.getElementById('retroceder');

    var dominio = document.getElementById('dominio').value;


    var pagina = parseInt(document.getElementById('pagina').value);


        avanzar.addEventListener('click',()=>{

                pagina++;


               location=`/controllers/index.php?boards=${pagina}`;
            
        });



        retroceder.addEventListener('click',()=>{

                
                pagina--;

                if(pagina<=0){

                    pagina=1;
                    location=`/controllers/index.php?boards=${pagina}`;

                }else{

                    location=`/controllers/index.php?boards=${pagina}`;

                }
            


        });




}