<?php


Class Ads extends EncryptToken{


    

	public $ads_id;
    public $titulo;
    public $tipo;
    public $descripcion;
    public $imagen_ruta;
    public $script_banner;
    public $posicion;
    public $link_banner;
    public $conection;
    public $estado;



	function __construct(){
		

		$this->SetConection();

	 }


    public function GuardarAds() {
        $fecha = date('Y-m-d H:i:s'); // formato correcto de fecha
        $sql = "insert into ads(
                    titulo,
                    descripcion,
                    imagen_ruta,
                    posicion,
                    fecha_ads,
                    script_banner,
                    tipo,
                    link_banner,
                    estado
                ) VALUES (?,?,?,?,?,?,?,?,?)";

        try {
            $guardar = $this->conection->prepare($sql);

            if (!$guardar) {
                throw new Exception("Error en prepare: " . $this->conection->error);
            }

            $this->estado = $this->enable();
            
            $guardar->bind_param(
                'sssisssss',
                $this->titulo,
                $this->descripcion,
                $this->imagen_ruta,
                $this->posicion,
                $fecha,
                $this->script_banner,
                $this->tipo,
                $this->link_banner,
                $this->estado
            );

            if ($guardar->execute()) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "msg" => $guardar->error]);
            }

            $guardar->close();
            $this->conection->close();
        } catch (Exception $e) {
            $this->TrackingLog($fecha . ' Error registrando ads ' . $e->getMessage(), 'errores');
            echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
        }

        $this->TrackingLog($fecha . ' Ads registrada con exito', 'eventos');
    }

    public function Actualizar_ads() {
        $fecha = date('Y-m-d H:i:s'); // formato correcto de fecha
        $sql = "update ads set
                    titulo = ?,
                    descripcion = ?,
                    imagen_ruta = ?,
                    posicion = ?,
                    fecha_ads = ?,
                    script_banner = ?,
                    tipo = ?,
                    link_banner = ?,
                    estado = ?
                where ads_id = ?";

        try {
            $guardar = $this->conection->prepare($sql);

            if (!$guardar) {
                throw new Exception("Error en prepare: " . $this->conection->error);
            }
            
            $estado=''; 

            if($this->estado=='activo'){
            
                $estado = $this->enable();

            }else if($this->estado=='inactivo'){

                $estado = $this->disable();
            }   
         

            $guardar->bind_param(
                'sssisssssi',
                $this->titulo,
                $this->descripcion,
                $this->imagen_ruta,
                $this->posicion,
                $fecha,
                $this->script_banner,
                $this->tipo,
                $this->link_banner, 
                $this->estado,
                $this->ads_id 
            );

            if ($guardar->execute()) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "msg" => $guardar->error]);
            }

            $guardar->close();
            $this->conection->close();
        } catch (Exception $e) {
            $this->TrackingLog($fecha . ' Error actualizando ads ' . $e->getMessage(), 'errores');
            echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
        }

        $this->TrackingLog($fecha . ' Ads actualizado con exito', 'eventos');
    }


     public function CargarAds(){

        $sql = "select * from ads";

        $cargar =  $this->conection->prepare($sql);
        $cargar->prepare($sql);

        try{

            $cargar->execute();
            $data = $cargar->get_result();      
            $results =[];

            foreach($data as $key){

                $results[] = $key;
            }

            echo json_encode($results);

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

     public function cambiar_estado_ads(){

        $sql = "update ads set estado=? where ads_id=?";
        $estado= "";

        if($this->estado==$this->disable()){

            $estado = $this->disable();
            echo "desactivar estado";

        }else if($this->estado==$this->enable()){
            $estado = $this->enable();
            echo "activar estado "; 


        }else{  
            /*Se detiene el procedimiento para eviar guardar
              estados que no cumplan el estandar.
            */
            return;
        }


        try{

            $data = $this->conection->prepare($sql);
            $data->bind_param('is',$this->ads_id,$estado);
            if($data->execute()){

                echo json_encode(["status" => "success"]);

            }else{

                echo json_encode(["status" => "error"]);
            }
            
        }catch(Exception $e){

            
            $this->TrackingLog(date('y-m-d h:i:s').' Error eliminando ads '.$e,'errores');


        }
                

     }


     public function cargar_ads_pos(){

        $sql = "select * from ads where posicion=?";
        $data = $this->conection->prepare($sql);
        $data->bind_param('i',$this->posicion);

        try{
        $result = $data->get_result();

    
        }catch(exception $e){
   
            $this->TrackingLog(date('y-m-d h:i:s').'Error eliminando ads '.$e,'errores');

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