$('document').ready(function(){


		$('#retroceder').click(()=>{

	
			var pag =$('#pagina_actual').val();
			var result = pag -1;
				if(pag==1){

					return;
				}

			window.location.href=`https://videosegg.com/index.php?page=${result}`;


			

	});








});