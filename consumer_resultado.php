<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$conn = new AMQPStreamConnection(
    host_rabbit_mq, port_rabbit_mq, user_rabbit_mq, password_rabbit_mq, vhost_rabbit_mq
);

echo "Inicio de cola de resultados multimedia...\n";

$channel = $conn->channel();
$channel->queue_declare('multimedia_resultado', false, true, false, false);
$channel->basic_qos(null, 1, null);

echo "Escuchando resultados de multimedia...\n";

$callback = function ($msg) {
    $data = json_decode($msg->body, true);

    try {
        if (!$data || $data['status'] !== 'ok') {
            throw new Exception($data['error'] ?? 'Mensaje inválido');
        }

        $board_id  = (int)$data['board_id'];
        $preview   = $data['preview'];
        $thumbnail = $data['thumbnail'];
        $video_completo  = $data['video_completo'];

        echo "✔ Resultado recibido para board {$board_id}\n";

        // Usar 127.0.0.1 para forzar conexión TCP/IP (evita error de socket en CLI)
        $host = (HOST_BD === 'localhost') ? '127.0.0.1' : HOST_BD;
        $conexion = new mysqli($host, USER_BD, PASSWORD_BD, NAME_DB);
        if ($conexion->connect_error) {
            throw new Exception("MySQL Error: " . $conexion->connect_error);
        }

        // --- 1. UPDATE TABLEROS (Esto es seguro repetir) ---
        $sql = "UPDATE tableros SET imagen_tablero = ?, preview_tablero = ? WHERE id_tablero = ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error prepare UPDATE tableros: " . $conexion->error);
        }
        $stmt->bind_param('ssi', $thumbnail, $preview, $board_id);
        $stmt->execute();
        $stmt->close();

        // --- 2. VALIDACIÓN: ¿YA EXISTE ALGÚN VIDEO PARA ESTE TABLERO? ---
        $checkSql = "SELECT id_asignar FROM asignar_multimedia_t 
                     WHERE id_tablero = ? AND tipo_multimedia = 'video' LIMIT 1";
        $stmtCheck = $conexion->prepare($checkSql);
        if (!$stmtCheck) {
            throw new Exception("Error prepare SELECT asignar_multimedia_t: " . $conexion->error);
        }
        $stmtCheck->bind_param('i', $board_id);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        $yaExiste = $stmtCheck->num_rows > 0;
        
        $id_existente = null;
        if ($yaExiste) {
            $stmtCheck->bind_result($id_existente);
            $stmtCheck->fetch();
        }
        $stmtCheck->close();

        $estado = 'activo';
        $tipo_multimedia = 'video';

        if ($yaExiste && $id_existente) {
            // --- 3A. ACTUALIZAR SI YA EXISTE ---
            $sql = "UPDATE asignar_multimedia_t SET ruta_multimedia = ? WHERE id_asignar = ?";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) throw new Exception($conexion->error);

            echo "🔄 Actualizando multimedia en BD para board {$board_id}\n";
            $stmt->bind_param('si', $video_completo, $id_existente);
            $stmt->execute();
            $stmt->close();
        } else {
            // --- 3B. INSERTAR SI NO EXISTE ---
            $sql = "INSERT INTO asignar_multimedia_t (id_tablero, ruta_multimedia, tipo_multimedia, estado) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) throw new Exception($conexion->error);

            echo "📁 Guardando multimedia en BD para board {$board_id}\n";
            $stmt->bind_param('isss', $board_id, $video_completo, $tipo_multimedia, $estado);
            $stmt->execute();
            $stmt->close();
        }

        $conexion->close();
        $msg->ack(); // Confirmamos a RabbitMQ

    } catch (Exception $e) {
        echo "❌ Error consumer: " . $e->getMessage() . "\n";
        error_log("❌ Error consumer: " . $e->getMessage());
        // nack con requeue=false para que no se quede en un bucle infinito si el error es de SQL
        $msg->nack(false, false); 
    }
};

$channel->basic_consume('multimedia_resultado', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}