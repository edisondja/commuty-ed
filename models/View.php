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
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $this->id_tablero, $this->id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['total'] > 0) {
                // El usuario ya ha visto el tablero, no hacemos nada
               // Usuario anónimo: solo aumentamos contador en tableros
                $sql = "UPDATE views SET cantidad = cantidad + 1 WHERE id_tablero = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $this->id_tablero);
                $stmt->execute();
            } else {
                // El usuario no ha visto el tablero, insertamos un nuevo registro
                $sql = "INSERT INTO views (id_tablero, id_usuario, cantidad) VALUES (?, ?, 1)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii", $this->id_tablero, $this->id_usuario);
                $stmt->execute();
            }

          
        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d H:i:s')." No se pudo guardar la vista: ".$e, 'errores');
        }
    }



    public function contar_views(){

        try {
            $sql = "SELECT COUNT(*) as total_views FROM views WHERE id_tablero = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $this->id_tablero);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['total_views'];
            } else {
                return 0; // No hay vistas
            }
        } catch (Exception $e) {
            // Manejo de errores
            $this->TrackingLog(date('y-m-d h:i:s')."No se pudo contar las vistas".$e,'errores');
            return 0;
        }
    }
     
	


}

