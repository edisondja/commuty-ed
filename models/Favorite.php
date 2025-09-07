<?php


Class Favorite extends EncryptToken{


	public  $id_favorito;
	public  $id_tablero;
	public  $id_usuario;
    public  $estado_favortio='';

	function __construct(){
		

		$this->SetConection();

	 }  

     public function agregar_favorito() {
        /*
            Este metodo es utilizado para guardar los favoritos
            que hacen los usuarios a las publicaciones
        */
        $fecha = new Date('ymdiss');
        $sql = "insert into favoritos(fecha_favorito,id_tablero,id_user,estado)VALUES(?,?,?,?)";
        $this->estado_favortio = $this->enable();
        try {
            $save  = $this->conection->prepare($sql);
            $save->bind_param('siii', $fecha, $this->id_tablero, $this->id_usuario, $this->estado_favortio);
            $save->execute();
            return true; // Favorito guardado exitosamente
        } catch (Exception $e) {
            $this->TrackingLog('No se está guardando el favorito en la base de datos ' . $fecha . ' Error: ' . $e->getMessage(), 'errores');
            return false; // Error al guardar el favorito
        }
     }
    



}

