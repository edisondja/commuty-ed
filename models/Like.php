<?php


Class Like extends EncryptToken{




	public int $id_like;
	public int $id_tablero;
	public int $id_usuario;
    public string $estado_lk='';

	function __construct(){
		

		$this->SetConection();

	 }  

     public function validar_like() {
        /* 
            Este metodo es utilizado para ver si 
            ya el usuario hizo like al tablero
        */
        
        $sql = "select id_like,estado from likes where id_tablero = ? and id_user = ?";
        
        // Preparamos la consulta
        $data = $this->conection->prepare($sql);
    
        if ($data === false) {
            // Si no se pudo preparar la consulta, lanzamos un error
            $this->TrackingLog(date('y-m-d h:i:s') . " Error preparando la consulta SQL: " . $this->conection->error, 'errores');
            return false;
        }
    
        // Asignamos los parámetros
        $data->bind_param('ii', $this->id_tablero, $this->id_usuario);
    
        try {
            // Ejecutamos la consulta
            if (!$data->execute()) {
                // Si falla la ejecución, lanzamos un error
                $this->TrackingLog(date('y-m-d h:i:s') . " Error ejecutando la consulta SQL: " . $data->error, 'errores');
                return false;
            }
    
            // Obtenemos el resultado correctamente
            $result = $data->get_result();
    
            if ($result === false) {
                // Si falla al obtener el resultado
                $this->TrackingLog(date('y-m-d h:i:s') . " Error obteniendo resultados: " . $data->error, 'errores');
                return false;
            }
    
            // Verificamos si el usuario ya dio like
            if ($result->num_rows > 0) {

                $estado_like =  $result->fetch_object();
                /*Cuando un like guardado de la publicacion con el usuario consutlamos el estado
                    para si esta desactivado se coloque en activo.
                */
                $this->estado_lk = $estado_like->estado;

                return false; // Ya existe un like
            } else {
                return true; // No hay like aún
            }
    
            // Cerramos el resultado y la consulta
            $result->close();
            $data->close();
    
        } catch (Exception $e) {
            // Manejamos cualquier excepción
            $this->TrackingLog(date('y-m-d h:i:s') . " Error validando like del usuario: $this->id_usuario. " . $e->getMessage(), 'errores');
            return false;
        }
    }
    

     public function guardar_like(){

        $estado = $this->enable();
        $fecha = date('y-m-d h:i:s');

        if($this->validar_like()){

            $sql = "insert into likes(id_user,id_tablero,estado,fecha_like)values(?,?,?,?)";
            $guardar = $this->conection->prepare($sql);	
           
           try{
                $guardar->bind_param('iiss',
                    $this->id_usuario,
                    $this->id_tablero,
                    $estado,
                    $fecha
                );
                $guardar->execute();
                $guardar->close();  

                 echo $this->estado_lk."_success";

           }catch(Exception $e){
        
                $this->TrackingLog(date('y-m-d h:i:s')."No se pudo guardar el like del ID: $this->id_usuario".$e,'errores');

           }

        }else{
            /*
                Se desactiva el like por que el usuario pulzo nuevamente
                el boton donde ya hay un like existente...
            */

            $this->quitar_like();

        }

 

     }

     public function contar_lk($config='json'){

        $estado = $this->enable();
        $sql = "select COUNT(id_like) as likes from likes where id_tablero=? and estado=?";
        $data = $this->conection->prepare($sql);
        
        try{
            $data->bind_param('is',
                $this->id_tablero,
                $estado
            );
            $data->execute();
            $likes_count = $data->get_result();
            $likes_count = mysqli_fetch_object($likes_count);

            if($config=='json'){

                echo json_encode($likes_count);

            }else{

                return $likes_count->likes;
            }

        }catch(Exception $e){

            $this->TrackingLog(date('y-m-d h:i:s')."Error contado los likes de la publicación".$e,'errores');

        }

     }


     public function cargar_likes_board($config='json'){

        $estado = $this->enable();

        $sql = "SELECT * FROM likes as lk inner join us on lk.id_user=us.id_user 
        where id_tablero=? and estado=?
        order by fecha_like desc";
        try{

            $cargar = $this->conection->prepare($sql);
            $cargar->bind_param('is',$this->id_tablero,$estado);
            $data = $cargar->get_result();
            $likes = [];
            foreach($data as $key){
    
                $likes[] = $key;
            } 
     
            $cargar->close();

            if($config=='json'){
    
                echo json_encode($likes);
    
            }else{

                return $likes;
            }


        }catch(Exception $e){

            $this->TrackingLog(date('y-m-d h:i:s')."No pudo cargar los likes".$e,'errores');

        }
      

     }
     
     public function quitar_like(){


        
        if($this->estado_lk==$this->disable()){

            $estado = $this->enable();

        }else{

            $estado = $this->disable();

        }
            
        $sql = "update likes set estado=? where id_user=? and id_tablero=?";
        $delete = $this->conection->prepare($sql);	
        try{

            $delete->bind_param('sii',
                $estado,
                $this->id_usuario,
                $this->id_tablero
        );
            $delete->execute() or die('error deleting coment');

            echo $estado."_success";

        }catch(Exception $e){

            $this->TrackingLog(date('y-m-d h:i:s')."No se pudo remover el like $this->id_like".$e,'errores');
        }

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