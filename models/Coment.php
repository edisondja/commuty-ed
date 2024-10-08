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


					$key['comentarios_hijos'] = $this->load_childs_coment($key['id_comentario']);
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

		function cargar_1_comentario_hijo($id,$config='json'){

			$sql = "select ch.text_coment, 
				ch.user_id, 
				ch.id_reply_id, 
				ch.fecha_creacion, 
				ch.estado, 
				ch.user_id, 
				us.usuario, 
				us.foto_url
				from reply_coment as ch
				inner join user as us on ch.user_id = us.id_user
				where ch.id_reply_id = ?";


			try{

				$cargar =$this->conection->prepare($sql);
				$cargar->bind_param('i',$id);
				$cargar->execute();
				$data = $cargar->get_result();
				$data = mysqli_fetch_object($data);

				if($config=='json'){

					echo json_encode($data);

				}else{

					return $data;
				}
			}catch(Exception $e){

				$this->TrackingLog(date('y-m-d h:i:s').'No se pudo cargar el comentario hijo'.$e,'errores');

			}

		}


		function reply_coment($id_coment,$id_user,$text_coment){

			$estado = $this->enable();
			$fecha = date('Ymdhis');
			$sql = "insert into reply_coment (coment_id,user_id,text_coment,fecha_creacion,estado)VALUES(?,?,?,?,?)";
			
			try{
				
				$insertar = $this->conection->prepare($sql);	
				$insertar->bind_param('iisss',$id_coment,$id_user,$text_coment,$fecha,$estado);
				$insertar->execute();
				$id_child_c = $this->conection->insert_id;
				$this->cargar_1_comentario_hijo($id_child_c);

			}catch(Exception $e){

				$this->TrackingLog(date('y-m-d h:i:s').'El usuario ID:'.$id_user.' no pudo responder comentario'.$e,'errores');

			}

			$this->TrackingLog(date('y-m-d h:i:s').'El usuario ID:'.$id_user.' Respondio comentario','eventos');


		}

		function load_childs_coment($id_coment,$config='asoc'){

			$estado = $this->enable();
			
			$sql = "select ch.text_coment,ch.user_id,ch.id_reply_id,
			ch.fecha_creacion,ch.estado,ch.user_id,us.usuario,us.foto_url from
			reply_coment as ch inner join user us on ch.user_id=us.id_user
			where ch.coment_id=? and ch.estado=? order by ch.fecha_creacion desc";
			
			$load = $this->conection->prepare($sql);	


			try{
				$load->bind_param('is',$id_coment,$estado);
				$load->execute();
				$data = $load->get_result();
				$comentarios = [];


				foreach($data as $key){

					$comentarios[] = $key;

				}
              
				if($config=='asoc'){

					return $comentarios;

				}else{

					echo json_encode($comentarios);


				}
			
		

		   }catch(Exception $e){


				$this->TrackingLog(date('y-m-d h:i:s').'El comentario ID:'.$id_coment.' No pudo cargar sus comentarios hijos'.$e,'errores');

		   }

		}


		function delete_coment_reply($id_coment){
			
			$estado = $this->disable();

			$sql = "update reply_coment set estado=? where id_reply_id=?";
			
			try{

				$delete = $this->conection->prepare($sql);	
				$delete->bind_param('si',$estado,$id_coment);
				$delete->execute() or die('error deleting coment');

			}catch(Exception $e){
				
				$this->TrackingLog(date('y-m-d h:i:s').' No se pudo eliminar el comentario hijo'.$id_coment,'errores');

			}

			$this->TrackingLog(date('y-m-d h:i:s').'Comentario hijo eliminado con exito ID:'.$id_coment,'eventos');


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