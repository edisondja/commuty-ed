<?php


Class Notifiacion extends EncryptToken{


	public int $id_post;
	public int $id_user;
	public string $comentario;
	public string $tipo_post;
	public string $data_og;
	public string $estado;
	public  $conection;


	function __construct(){
		

		$this->SetConection();

	 }


         function leer_comentarios($id_post,$tipo_post='video'){
		

			$estado = $this->enable();
			
			if($tipo_post=='board'){
					
				$sql ="select * from comentario as c inner join user as u on 
				 c.usuario_id=u.id_user 
				  where c.id_tablero=? 
				  and c.tipo_post=? 
				  and c.estado=?  
				  order by id_comentario desc";

				}else{

				$sql ="select * from comentario as c
				 inner join user as u on  c.usuario_id=u.id_user 
				  where c.id_post=? and c.tipo_post=?
				  and c.estado=? 
				  order by id_comentario desc";
				}
			
				$read = $this->conection->prepare($sql);
				$read->bind_param('iss',$id_post,$tipo_post,$estado);
				$read->execute();
				$ejecutar = $read->get_result();
		
				foreach ($ejecutar as $key) {
						$data[] = $key;
				}
				echo json_encode($data);
		}






}
