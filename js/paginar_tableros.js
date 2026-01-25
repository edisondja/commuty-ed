
window.onload=function(){


    let avanzar = document.getElementById('avanzar');

    let retroceder = document.getElementById('retroceder');

    var dominio = document.getElementById('dominio').value;
    // Usar BASE_URL para soporte de subdirectorios (XAMPP)
    var baseUrl = window.BASE_URL || '';


    var pagina = parseInt(document.getElementById('pagina').value);


        avanzar.addEventListener('click',()=>{

                pagina++;


               location=`${baseUrl}/page/${pagina}`;
            
        });



        retroceder.addEventListener('click',()=>{

                
                pagina--;

                if(pagina<=0){

                    pagina=1;
                    location=`${baseUrl}/page/${pagina}`;

                }else{

                    location=`${baseUrl}/page/${pagina}`;

                }
            


        });




}