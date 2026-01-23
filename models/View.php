<?php


Class View extends EncryptToken{




	public  $id_view;
	public  $id_tablero;
	public  $id_usuario;
   

	function __construct(){
		

		$this->SetConection();

	 }  

        
    public function guardar_view() {
        try {

            // Verificamos si el usuario ya ha visto el tablero
            $sql = "SELECT COUNT(*) as total FROM views WHERE id_tablero = ? AND id_usuario = ?";
            $stmt = $this->conection->prepare($sql);
            $stmt->bind_param("ii", $this->id_tablero, $this->id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['total'] > 0) {
                // El usuario ya ha visto el tablero, no hacemos nada
               // Usuario anónimo: solo aumentamos contador en tableros
                $sql = "UPDATE views SET cantidad = cantidad + 1 WHERE id_tablero = ?";
                $stmt = $this->conection->prepare($sql);
                $stmt->bind_param("i", $this->id_tablero);
                $stmt->execute();
            } else {
                // El usuario no ha visto el tablero, insertamos un nuevo registro
                if ($this->id_usuario > 0) {
                    $sql = "INSERT INTO views (id_tablero, id_usuario, cantidad) VALUES (?, ?, 1)";
                    $stmt = $this->conection->prepare($sql);
                    $stmt->bind_param("ii", $this->id_tablero, $this->id_usuario);
                    $stmt->execute();
                } else {
                    // Usuario anónimo: solo aumentamos contador en tableros
                    $sql = "UPDATE views SET cantidad = cantidad + 1 WHERE id_tablero = ?";
                    $stmt = $this->conection->prepare($sql);
                    $stmt->bind_param("i", $this->id_tablero);
                    $stmt->execute();
                }
            }

        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d H:i:s')." No se pudo guardar la vista: ".$e, 'errores');
        }
    }



    public function contar_views(){

        $sql = "SELECT cantidad as total_views FROM views WHERE id_tablero = ?";
        $data = $this->conection->prepare($sql);
        
        try{
            $data->bind_param('i',
                $this->id_tablero
            );
            $data->execute();
            $result = $data->get_result();
            // Compatible con PHP 7.2 y PHP 8+
            if (PHP_VERSION_ID >= 80000) {
                $views_count = $result->fetch_object();
            } else {
                $views_count = mysqli_fetch_object($result);
            }

            return $views_count ? $views_count->total_views : 0;

        }catch(Exception $e){
        
            $this->TrackingLog(date('y-m-d h:i:s')."No se pudo contar las vistas del ID: $this->id_tablero".$e,'errores');

        }


    }
     
	


}

