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

        $conexion = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
        if ($conexion->connect_error) {
            throw new Exception("MySQL Error: " . $conexion->connect_error);
        }

        // --- 1. UPDATE TABLEROS (Esto es seguro repetir) ---
        $sql = "UPDATE tableros SET imagen_tablero = ?, preview_tablero = ? WHERE id_tablero = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssi', $thumbnail, $preview, $board_id);
        $stmt->execute();
        $stmt->close();

        // --- 2. VALIDACIÓN: ¿YA EXISTE ESTA MULTIMEDIA? ---
        $checkSql = "select id_asignar_multimedia_t FROM asignar_multimedia_t 
                     where id_tablero = ? AND ruta_multimedia = ? limit 1";
        $stmtCheck = $conexion->prepare($checkSql);
        $stmtCheck->bind_param('is', $board_id, $video_completo);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        $yaExiste = $stmtCheck->num_rows > 0;
        $stmtCheck->close();

        if (!$yaExiste) {
            // --- 3. INSERTAR SOLO SI NO EXISTE ---
            $sql = "insert into asignar_multimedia_t (id_tablero, ruta_multimedia, tipo_multimedia, estado) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) throw new Exception($conexion->error);

            echo "📁 Guardando multimedia en BD para board {$board_id}\n";
            $estado = 'activo';
            $tipo_multimedia = 'video';

            $stmt->bind_param('isss', $board_id, $video_completo, $tipo_multimedia, $estado);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "⚠ El registro ya existe en BD para board {$board_id}. Saltando insert.\n";
        }

        $conexion->close();
        $msg->ack(); // Confirmamos a RabbitMQ

    } catch (Exception $e) {
        error_log("❌ Error consumer: " . $e->getMessage());
        // nack con requeue=false para que no se quede en un bucle infinito si el error es de SQL
        $msg->nack(false, false); 
    }
};

$channel->basic_consume('multimedia_resultado', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}