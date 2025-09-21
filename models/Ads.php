<?php


Class Ads extends EncryptToken{


    

	public $ads_id;
    public $titulo;
    public $tipo;
    public $descripcion;
    public $imagen_ruta;
    public $scrip_banner;
    public $posicion;
    public $conection;



	function __construct(){
		

		$this->SetConection();

	 }


     public function GuardarAds(){

        $sql = "insert into ads(titulo,descripcio,imagen_ruta,posicion,fecha_ads,tipo)
        values(?,?,?,?,?,?)";

        $fecha = date('y-m-d h:i:s');

        try{

            $guardar = $this->conection->prepare($sql);
            $guardar->bind_param('ssssss',$this->titulo,
                                          $this->descripcion,
                                          $this->imagen_ruta,
                                          $fecha,
                                          $this->tipo
                                        );
            $guardar->execute();

            
        }catch(Exception $e){


            $this->TrackingLog($fecha.' Error registrando ads '.$e,'errores');
        }

        $this->TrackingLog($fecha.' Ads registrada con exito','eventos');

     }


     public function ActuactlizarAds(){


         $actualizar = "update ads set titulo=?,
                                     descripcion=?,
                                     imagen_ruta=?,
                                     posicion=?,
                                     fecha_ads=?,
                                     tipo=? 
                                     where id ads_id=?";

         $procesar = $this->conection->prepare($actualizar);


        try{

         $procesar->bind_param('sssiss',
                $this->titulo,
                $this->descripcion,
                $this->imagen_ruta,
                $this->posicion,
                $this->tipo,
                $fecha = date('y-m-d h:i:s')

            );

         $procesar->excute();

        }catch(Exception $e){

            $this->TrackingLog($fecha.' Error actualizando ads '.$e,'errores');

        }

     }


     public function CargarAds(){


        $sql = "select * from ads";

        $cargar =  $this->conection->prepare($sql);
        $cargar->prepare($sql);

        try{

            $cargar->execute();
            $cargar->get_result();   

        }catch(Exception $e){

            $this->TrackingLog(date('y-m-d h:i:s'),' Error Cargando ads '.$e,'errores');
        }
   

     }

       

     public function EliminarAds(){

        $sql = "update ads set estado=? where ads_id=?";
        $estado = $this->disable();

        try{

            $eliminar = $this->conection->prepare($sql);
            $eliminar->bind_param('is',$this->ads_id,$estado);
            $eliminar->execute();

        }catch(Exception $e){

            
            $this->TrackingLog(date('y-m-d h:i:s').' Error eliminando ads '.$e,'errores');

        }

     }


     public function cargar_1_ads(){


        $sql = "select * from ads where ads_id=?";
        $data=  $this->conection->prepare($sql);
        $data->bind_param('i',$this->ads_id);

        try{
    
            $data->execute();
            $result = $data->get_result();
        
            echo json_encode(mysqli_fetch_object($result));
       
        }catch(Exception $e){


            $this->TrackingLog(date('y-m-d h:i:s').' Error eliminando ads '.$e,'errores');

        }

    }


     public function desactivar_ads(){

        $sql = "update ads set estado=? where ads_id=?";
        $estado = $this->disable();
        try{

            $data = $this->conection->prepare($sql);
            $data->bind_param('is',$this->ads_id,$estado);
            $data->execute();

       
        }catch(Exception $e){

            
            $this->TrackingLog(date('y-m-d h:i:s').' Error eliminando ads '.$e,'errores');


        }
                


     }


     public function activar_ads(){
         $sql = "update ads set estado=? where ads_id=?";
        $estado = $this->enable();
        try{

            $data = $this->conection->prepare($sql);
            $data->bind_param('is',$this->ads_id,$estado);
            $data->execute();

       
        }catch(Exception $e){

            
            $this->TrackingLog(date('y-m-d h:i:s').' Error eliminando ads '.$e,'errores');

            
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