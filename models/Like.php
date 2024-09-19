<?php


Class Coment extends EncryptToken{




	public int $id_like;
	public int $id_tablero;
	public int $id_usuario;

	function __construct(){
		

		$this->SetConection();

	 }  


     public function validar_like(){
            /* 
                Este metodo es utilizado para ver si 
                ya el usuario hizo like a el tablero
            */
        $sql = "SELECT * FROM likes where id_tablero=? and id_user=?";
        $data= $this->conection->prepare($sql);
        $data->bind_param('ii',
            $this->id_tablero,
            $this->id_usuario
        );
        try{
            $result = $data->execute();
            $result = $result->get_result();
            $result->close();

            return $result->num_rows;

        }catch(Exception $e){

            return $e;
        }
            
     }


     public function guardar_like(){


        $sql = "INSERT INTO likes(id_user,id_tablero)VALUES(?,?)";
		$guardar = $this->conection->prepare($sql);	
        $guardar->bind_param('ii',
            $this->id_usuario,
            $this->id_tablero
        );
        $guardar->execute();
        $guardar->close();

     }

     public function cargar_likes_board($config){

        $sql = "SELECT * FROM likes where id_tablero=?";
        $cargar = $this->conection->prepare($sql);
        $cargar->bind_param('i',$this->id_tablero);
        $data = $cargar->get_result();
         
        if($config=='')
        
        $cargar->close();

     }
     
     public function quitar_like(){


        
     }


		function delete_coment_reply($id_coment){

			$sql = "delete from reply_coment where id_coment=?";
			$delete = $this->conection->prepare($sql);	
			$delete->bind_param('i',$id_coment);
			$delete->execute() or die('error deleting coment');

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