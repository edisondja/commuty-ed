<?php


Class Ads extends EncryptToken{


    

	public $ads_id;
    public $titulo;
    public $descripcion;
    public $imagen_ruta;
    public $tipo;
    public $scrip_banner;
    public $posicion;



	function __construct(){
		

		$this->SetConection();

	 }


     public function GardarAds(){


        $sql = "insert into ads(titulo,descripcio,imagen_ruta,posicion,fecha_ads)
        values(?,?,?,?,?)
        ";

        $fecha = new date('y-m-d h:i:s');

        try{

            $guardar = $this->conection->prepare($sql);
            $guardar->bind_param('sssss',$this->titulo,$this->descripcion,$this->$this->imagen_ruta,$fecha);
            $guardar->execute();

            
        }catch(Exception $e){


            $this->TrackingLog($fecha.' Error registrando ads '.$e,'errores');
        }

        $this->TrackingLog($fecha.' Ads registrada con exito','eventos');

     }


     public function ActuactlizarAds(){


         $actualizar = "update ads set titulo=?,descripcion=?,imagen_ruta=?,posicion=?,fecha_ads=? 
          where id ads_id=?
         ";

         $procesar = $this->conection->prepare($sql);


        try{

         $procesar->bind_param('sssis',
                $this->titulo,
                $this->descripcion,
                $this->imagen_ruta,
                $this->posicion,
                $fecha
            );

         $procesar->excute();

        }catch(Exception $e){

            $this->TrackingLog($fecha.' Error actualizando ads '.$e,'errores');

        }

     }


     public function CargarAds(){


        $sql = "select * from ads where ads_id=?";

        $cargar =  $this->conection->prepare($sql);
        $cargar->prepare($sql);

        try{

            $cargar->bind_param('i',$this->ads_id);
            $cargar->execute();
            $cargar->get_result();   

        }catch(Exception $e){

            $this->TrackingLog($date('y-m-d h:i:s').' Error Cargando ads '.$e,'errores');
        }
   

     }
       

     public function EliminarAds(){

        $sql = "delete from ads where ads_id=?";

        try{

            $eliminar = $this->conection->prepare($sql);
            $eliminar->bind_param('i',$this->ads_id);
            $eliminar->execute();

        }catch(Exception $e){

            
            $this->TrackingLog($date('y-m-d h:i:s').' Error eliminando ads '.$e,'errores');

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