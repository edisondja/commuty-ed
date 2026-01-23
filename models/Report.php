<?php


Class Report extends EncryptToken{



	public  $id_report;
	public  $id_tablero;
	public  $id_usuario;
    public  $descripcion;
    public  $estado_rp='';

	function __construct(){
		

		$this->SetConection();

	 }  

   public function save_report() {
        /*
            Este metodo es utilizado para guardar los reportes
            que hacen los usuarios a las publicaciones
        */
        $fecha = date('Y-m-d H:i:s'); // formato compatible con DATETIME
        $sql = "insert into reportes(fecha_creacion, id_board, id_usuario, descripcion, estado) 
                values (?, ?, ?, ?, ?)";

        $this->estado_rp = $this->enable();

        try {
            // Validaciones básicas
            if (empty($this->id_tablero) || empty($this->id_usuario) || empty($this->descripcion)) {
                echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
                return false;
            }

            $save  = $this->conection->prepare($sql);
            if (!$save) {
                throw new Exception("Error en prepare: " . $this->conection->error);
            }

            // Tipos correctos: s (fecha) i (id_tablero) i (id_usuario) s (descripcion) i (estado)
            $save->bind_param('siiss', $fecha, $this->id_tablero, $this->id_usuario, $this->descripcion, $this->estado_rp);

            if (!$save->execute()) {
                throw new Exception("Error en execute: " . $save->error);
            }

            echo json_encode(['status' => 'success', 'message' => 'Reporte enviado exitosamente']);
            return true;

        } catch (Exception $e) {
            $this->TrackingLog(
                'No se está guardando el reporte en la base de datos ' . $fecha . 
                ' Error: ' . $e->getMessage(), 
                'errores'
            );
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return false;
        }   
    }

    
     public function anular_report() {
        /*
            Este metodo es utilizado para anular los reportes
            que hacen los usuarios a las publicaciones
        */

        $this->estado_rp = $this->disable();

        $sql = "update reportes set estado = ? where id_report = ? and id_usuario = ?";

        try {
            $update  = $this->conection->prepare($sql);
            $update->bind_param('sii', $this->estado_rp, $this->id_report, $this->id_usuario);
            $update->execute();
            echo "Reporte anulado exitosamente";// Reporte anulado exitosamente
        } catch (Exception $e) {
            echo "error al anular el reporte";
            $this->TrackingLog('No se está anulando el reporte en la base de datos ' . ' Error: ' . $e->getMessage(), 'errores');
            return false; // Error al anular el reporte
        } 

     }


     public function cargar_reportes_usuario($confi='usuario') {
        /*
            Este metodo es utilizado para cargar los reportes
            que hacen los usuarios a las publicaciones
        */


        if($confi=='admin'){

            $sql = "select r.descripcion as razon,
                          r.fecha_creacion,t.descripcion,
                          t.id_tablero,
                          u.usuario,t.imagen_tablero,
                          r.estado as estado_reporte,
                          t.estado as estado_tablero from reportes r 
                          inner join tableros t on r.id_board=t.id_tablero
                          inner join users u on r.id_usuario=u.id_user
                          where r.estado = ? order by r.fecha_creacion desc limit 100 ";
        }else{

            $sql = "select r.descripcion as razon,
                          r.fecha_creacion,t.descripcion,
                          t.id_tablero,
                          u.usuario,t.imagen_tablero,
                          r.estado as estado_reporte,
                          t.estado as estado_tablero from reportes r 
                          inner join tableros t on r.id_board=t.id_tablero
                          inner join users u on r.id_usuario=u.id_user
                          where r.id_usuario=? and r.estado = ? order by r.fecha_creacion desc limit 100";
        }
        // Preparamos la consulta
        $data = $this->conection->prepare($sql);
    
        if ($data === false) {
            // Si no se pudo preparar la consulta, lanzamos un error
            $this->TrackingLog(date('y-m-d h:i:s') . " Error preparando la consulta SQL: " . $this->conection->error, 'errores');
            return false;
        }
    
        // Asignamos los parámetros
        $this->estado_rp = $this->enable();
        if($confi!='admin'){

            $data->bind_param('is', $this->id_usuario, $this->estado_rp);
        }else{
            $data->bind_param('s', $this->estado_rp);
        }

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
    
            // Retornamos todos los reportes del usuario
            
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            
        } catch (Exception $e) {
            $this->TrackingLog('No se están cargando los reportes en la base de datos ' . ' Error: ' . $e->getMessage(), 'errores');
            return false; // Error al cargar los reportes
        }

     }

    
     public function buscar_reportes($texto) {
        /*
            Este metodo es utilizado para buscar los reportes
            que hacen los usuarios a las publicaciones
        */

        $texto = "%".$texto."%";
        $this->estado_rp = $this->enable();
        $sql = "select r.descripcion as razon,
                       r.fecha_creacion,t.descripcion,
                       t.id_tablero,
                       u.usuario,t.imagen_tablero,
                       r.estado as estado_reporte,
                       t.estado as estado_tablero from reportes r 
                       inner join tableros t on r.id_board=t.id_tablero
                       inner join users u on r.id_usuario=u.id_user
                       where (t.descripcion LIKE ? or u.usuario LIKE ? or r.descripcion LIKE ?)
                       and r.estado = ? limit 100";

        // Preparamos la consulta
        $data = $this->conection->prepare($sql);
    
        if ($data === false) {
            // Si no se pudo preparar la consulta, lanzamos un error
            $this->TrackingLog(date('y-m-d h:i:s') . " Error preparando la consulta SQL: " . $this->conection->error, 'errores');
            return false;
        }
    
        // Asignamos los parámetros
        $data->bind_param('ssss', $texto, $texto, $texto, $this->estado_rp);
    
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
    
            // Retornamos todos los reportes del usuario
            
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            
        } catch (Exception $e) {
            $this->TrackingLog('No se están cargando los reportes en la base de datos ' . ' Error: ' . $e->getMessage(), 'errores');
            return false; // Error al cargar los reportes
        }
    }

}
