$('document').ready(function(){
	//probando nuevos cambios

		$('#retroceder').click(()=>{

	
			var pag =$('#pagina_actual').val();
			var result = pag -1;
		

			window.location.href=`https://videosegg.com/index.php?page=${result}`;


			

	});


//se deben de guardar
		$('#siguiente').click(()=>{

	
			var pag =$('#pagina_actual').val();
			var result = pag ++;
		

			window.location.href=`https://videosegg.com/index.php?page=${result}`;


			

	});










});