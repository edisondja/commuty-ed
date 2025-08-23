<?php

class Notificacion extends EncryptToken {

    public $id_notificacion;
    public $id_tablero;
    public $id_comentario = 0;
    public $id_usuario_emisor = 0;
    public $id_usuario_receptor = 0;
    public $tipo;
    public $estado;

    function __construct() {
        $this->SetConection(); // Conectar a base de datos
    }

    public function notificar_a_usuario() {
        $estado = $this->enable(); // Guardar con estado habalitado
        $fecha =  date('Y-m-d H:i:s'); // Formato de fecha corregido

        $sql = "insert into notificacion (tipo,fecha,id_tablero, 
                 id_usuario_emisor,id_usuario_receptor,estado)
                 VALUES (?,?,?,?,?,?)";                 
        try {
            // Preparar la consulta con mysqli
            $guardar_notificacion = $this->conection->prepare($sql);
                $guardar_notificacion->bind_param('ssiiis',
                    $this->tipo,
                    $fecha,
                    $this->id_tablero,
                    $this->id_usuario_emisor,
                    $this->id_usuario_receptor,
                    $estado
                );
                $guardar_notificacion->execute();

                if ($guardar_notificacion->affected_rows > 0) {
                    $this->TrackingLog(date('Y-m-d H:i:s') . ' - Notificación guardada correctamente', 'eventos');
                } else {
                    $this->TrackingLog(date('Y-m-d H:i:s') . ' - La consulta no afectó ninguna fila', 'errores');
                }
                $guardar_notificacion->close(); // Cerrar la consulta preparada
                
        } catch (Exception $e) {
            // Mejor manejo del log de errores
            $this->TrackingLog(date('Y-m-d H:i:s') . ' - No se pudo guardar la notificación: ' . $e->getMessage(), 'errores');
        }
    }

    public function cargar_mis_notificaciones($id_usuario) {

        $sql = "select * from notificacion as nt inner join tableros as tb 
        on nt.id_tablero=tb.id_tablero inner join user us on nt.id_usuario_emisor=us.id_user
         where nt.id_usuario_receptor = ? ORDER BY fecha DESC";
         
        try {
            if ($consulta = $this->conection->prepare($sql)) {
                $consulta->bind_param('i', $id_usuario);
                $consulta->execute();
                $resultado = $consulta->get_result();
                $notificaciones = $resultado->fetch_all(MYSQLI_ASSOC);
                $consulta->close(); // Cerrar la consulta preparada
                return $notificaciones;
            } else {
                throw new Exception('Error al preparar la consulta para cargar notificaciones');
            }
        } catch (Exception $e) {
            $this->TrackingLog(date('Y-m-d H:i:s') . ' - Error al cargar notificaciones: ' . $e->getMessage(), 'errores');
            return [];
        }
    }

    public function notificacion_vista($id_notificacion) {
        $sql = "UPDATE notificacion SET estado = 'vista' WHERE id_notificacion = ?";

        try {
            if ($actualizar = $this->conection->prepare($sql)) {
                $actualizar->bind_param('i', $id_notificacion);
                $actualizar->execute();
                $actualizar->close(); // Cerrar la consulta preparada
            } else {
                throw new Exception('Error al preparar la consulta para marcar notificación como vista');
            }
        } catch (Exception $e) {
            $this->TrackingLog(date('Y-m-d H:i:s') . ' - Error al marcar notificación como vista: ' . $e->getMessage(), 'errores');
        }
    }
}
