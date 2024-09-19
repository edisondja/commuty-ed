<?php


Class Coment extends EncryptToken{




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


		public function guardar_comentario() {
			// Sanitizar el comentario

			$estado = $this->enable();
			$comentario = str_replace("script", "000", $this->comentario);
		
			// Obtener la fecha actual
			$fecha = date('Ymdhis');
		
			// Determinar la consulta SQL en función del tipo de post
			if ($this->tipo_post == 'board') {
				$sql = "INSERT INTO comentario (id_tablero, usuario_id, texto, fecha_publicacion, tipo_post, data_og,estado)
				 VALUES (?,?,?,?,?,?,?)";
			} else {
				$sql = "INSERT INTO comentario (id_post, usuario_id, texto, fecha_publicacion, tipo_post, data_og,estado)VALUES(?,?,?,?,?,?,?)";
			}
		
			// Debug output
			echo "$this->id_post,$this->id_user,$comentario,$fecha,$this->tipo_post,$this->data_og";
		
			try {
				// Preparar la consulta
				$execute = $this->conection->prepare($sql);
				if ($execute === false) {
					throw new Exception("Error al preparar la consulta: " . $this->conection->error);
				}
				// Vincular los parámetros
				$bind = $execute->bind_param('iisssss', $this->id_post, $this->id_user, $comentario, $fecha, $this->tipo_post, $this->data_og,$estado);
				if ($bind === false) {
					throw new Exception("Error al vincular los parámetros: " . $execute->error);
				}
				// Ejecutar la consulta
				$exec = $execute->execute();
				if ($exec === false) {
					throw new Exception("Error al ejecutar la consulta: " . $execute->error);
				}
		
				echo "Guardado con éxito";
			} catch (Exception $error) {
				echo "Error: " . $error->getMessage();
			} finally {
				// Cerrar el statement
				if (isset($execute) && $execute !== false) {
					$execute->close();
					$this->TrackingLog(date('y-m-d h:i:s').' Comentario del user ID:'.$this->id_user,'eventos');
				}
			}
		}
		
        public function eliminar_comentario($id_comentario){

			//Edejesusa 10-09-2024

			$estado = $this->disable();
			$sql = "update comentario set estado=? where id_comentario=?";
			
			try{
			$eliminar = $this->conection->prepare($sql);
			$eliminar->bind_param('si',$estado,$id_comentario);
			$eliminar->execute() or die('error delete coment');

			}catch(Exception $e){

				$this->TrackingLog(date('y-m-d h:i:s').' No se pudo eliminar el comentario '.$e,'errores');
			}
			
			$this->TrackingLog(date('y-m-d h:i:s').' Comentario eliminado con exito '.$id_coment,'eventos');


		}

		function reply_coment($id_coment,$id_user,$text_coment){


			$fecha = date('Ymdhis');
			$sql = "insert into reply_coment (coment_id,user_id,text_coment,fecha_creacion)VALUES(?,?,?,?)";
			
			try{
				
				$insertar = $this->conection->prepare($sql);	
				$insertar->bind_param('iiss',$id_coment,$id_user,$text_coment,$fecha);
				$insertar->execute() or die('error saving coment');

			}catch(Exception $e){

				$this->TrackingLog(date('y-m-d h:i:s').'El usuario ID:'.$id_user.' no pudo responder comentario'.$e,'errores');

			}

			$this->TrackingLog(date('y-m-d h:i:s').'El usuario ID:'.$id_user.' Respondio comentario'.$e,'eventos');


		}

		function load_childs_coment($id_coment){

			$sql = "select * from reply_coment where coment_id=?";
			$load = $this->conection->prepare($sql);	
			
			try{
				$load->bind_param('i',$id_coment);
				$load->execute();
				$data = $load->get_result();
				$coments_childs = [];

				foreach($data as $key){

					$data[] = $key;

				}

				echo json_encode($coments_childs);

		   }catch(Exception $e){


				$this->TrackingLog(date('y-m-d h:i:s').'El comentario ID:'.$id_coment.' No pudo cargar sus comentarios hijos'.$e,'errores');

		   }

		}


		function delete_coment_reply($id_coment){
			
			$estado = $this->enable();

			$sql = "update reply_coment set estado=? where id_coment=?";
			
			try{

				$delete = $this->conection->prepare($sql);	
				$delete->bind_param('si',$estado,$id_coment);
				$delete->execute() or die('error deleting coment');

			}catch(Exception $e){
				
				$this->TrackingLog(date('y-m-d h:i:s').' No se pudo eliminar el comentario '.$id_coment,'errores');

			}

			$this->TrackingLog(date('y-m-d h:i:s').'Comentario eliminado con exito ID:'.$id_coment,'eventos');


		}




}

/*
$guardar = new Coment();
$guardar->id_post = 1;
$guardar->id_user = 1;
$guardar->comentario = 'probando objeto de comentario';
$guardar->tipo_post = 'board';
$guardar->data_og ='';
*/