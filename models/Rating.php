<?php

class Rating extends EncryptToken {
    
    public $conection;
    public $id_user;
    public $id_tablero;
    public $puntuacion;
    public $estado;
    
    function __construct() {
        $this->SetConection();
    }
    
    /**
     * Guarda o actualiza una calificación
     */
    public function guardar_rating() {
        $estado = $this->enable();
        
        // Verificar si ya existe una calificación de este usuario para este tablero
        $sql_check = "select id_rating, puntuacion from ratings where id_user = ? and id_tablero = ? limit 1";
        $check = $this->conection->prepare($sql_check);
        
        if ($check === false) {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $this->conection->error]);
            return;
        }
        
        $check->bind_param('ii', $this->id_user, $this->id_tablero);
        $check->execute();
        $result = $check->get_result();
        $existing = null;
        
        if (PHP_VERSION_ID >= 80000) {
            $existing = $result->fetch_assoc();
        } else {
            $existing = mysqli_fetch_assoc($result);
        }
        $check->close();
        
        if ($existing) {
            // Actualizar calificación existente
            $sql = "update ratings set puntuacion = ?, estado = ?, fecha_rating = now() where id_rating = ?";
            $stmt = $this->conection->prepare($sql);
            
            if ($stmt === false) {
                echo json_encode(['error' => 'Error al preparar la consulta: ' . $this->conection->error]);
                return;
            }
            
            $stmt->bind_param('isi', $this->puntuacion, $estado, $existing['id_rating']);
            $stmt->execute();
            $stmt->close();
            
            echo json_encode([
                'success' => true,
                'message' => 'Calificación actualizada',
                'updated' => true,
                'old_rating' => $existing['puntuacion'],
                'new_rating' => $this->puntuacion
            ]);
        } else {
            // Crear nueva calificación
            $sql = "insert into ratings (id_user, id_tablero, puntuacion, estado, fecha_rating) values (?, ?, ?, ?, now())";
            $stmt = $this->conection->prepare($sql);
            
            if ($stmt === false) {
                echo json_encode(['error' => 'Error al preparar la consulta: ' . $this->conection->error]);
                return;
            }
            
            $stmt->bind_param('iiis', $this->id_user, $this->id_tablero, $this->puntuacion, $estado);
            $stmt->execute();
            $stmt->close();
            
            echo json_encode([
                'success' => true,
                'message' => 'Calificación guardada',
                'updated' => false,
                'rating' => $this->puntuacion
            ]);
        }
    }
    
    /**
     * Obtiene el promedio de calificaciones de un tablero
     */
    public function obtener_promedio($config = 'json') {
        $estado = $this->enable();
        
        $sql = "select 
                    avg(puntuacion) as promedio,
                    count(*) as total_calificaciones,
                    sum(case when puntuacion = 5 then 1 else 0 end) as cinco_estrellas,
                    sum(case when puntuacion = 4 then 1 else 0 end) as cuatro_estrellas,
                    sum(case when puntuacion = 3 then 1 else 0 end) as tres_estrellas,
                    sum(case when puntuacion = 2 then 1 else 0 end) as dos_estrellas,
                    sum(case when puntuacion = 1 then 1 else 0 end) as una_estrella
                from ratings 
                where id_tablero = ? and estado = ?";
        
        $stmt = $this->conection->prepare($sql);
        
        if ($stmt === false) {
            if ($config == 'json') {
                echo json_encode(['error' => 'Error al preparar la consulta: ' . $this->conection->error]);
            }
            return null;
        }
        
        $stmt->bind_param('is', $this->id_tablero, $estado);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = null;
        if (PHP_VERSION_ID >= 80000) {
            $data = $result->fetch_assoc();
        } else {
            $data = mysqli_fetch_assoc($result);
        }
        $stmt->close();
        
        if ($data) {
            $data['promedio'] = round($data['promedio'], 2);
            $data['promedio_redondeado'] = round($data['promedio']);
        } else {
            $data = [
                'promedio' => 0,
                'promedio_redondeado' => 0,
                'total_calificaciones' => 0,
                'cinco_estrellas' => 0,
                'cuatro_estrellas' => 0,
                'tres_estrellas' => 0,
                'dos_estrellas' => 0,
                'una_estrella' => 0
            ];
        }
        
        if ($config == 'json') {
            echo json_encode($data);
        } else {
            return $data;
        }
    }
    
    /**
     * Obtiene la calificación del usuario actual para un tablero
     */
    public function obtener_mi_calificacion($config = 'json') {
        $estado = $this->enable();
        
        $sql = "select puntuacion from ratings where id_user = ? and id_tablero = ? and estado = ? limit 1";
        $stmt = $this->conection->prepare($sql);
        
        if ($stmt === false) {
            if ($config == 'json') {
                echo json_encode(['puntuacion' => 0]);
            }
            return 0;
        }
        
        $stmt->bind_param('iis', $this->id_user, $this->id_tablero, $estado);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = null;
        if (PHP_VERSION_ID >= 80000) {
            $data = $result->fetch_assoc();
        } else {
            $data = mysqli_fetch_assoc($result);
        }
        $stmt->close();
        
        $puntuacion = $data ? (int)$data['puntuacion'] : 0;
        
        if ($config == 'json') {
            echo json_encode(['puntuacion' => $puntuacion]);
        } else {
            return $puntuacion;
        }
    }
    
    /**
     * Elimina una calificación (la desactiva)
     */
    public function eliminar_rating() {
        $estado = $this->disable();
        
        $sql = "update ratings set estado = ? where id_user = ? and id_tablero = ?";
        $stmt = $this->conection->prepare($sql);
        
        if ($stmt === false) {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $this->conection->error]);
            return;
        }
        
        $stmt->bind_param('sii', $estado, $this->id_user, $this->id_tablero);
        $stmt->execute();
        $stmt->close();
        
        echo json_encode(['success' => true, 'message' => 'Calificación eliminada']);
    }
}

?>
