<?php


Class Notifiacion extends EncryptToken{


	public int $id_notificacion;
	public int $id_tablero;
	public int $id_comentario;
	public string $tipo;

	function __construct(){
		

		$this->SetConection();

	 }


        public function notificar(){
		

			$estado = $this->enable();
			$read = $this->conection->prepare($sql);


			
		}

		public function cargar_mis_notifiaciones(){




		}

		public function notifiacion_vista(){






		}


}
