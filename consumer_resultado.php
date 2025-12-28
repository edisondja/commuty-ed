<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$conn = new AMQPStreamConnection(
    host_rabbit_mq,
    port_rabbit_mq,
    user_rabbit_mq,
    password_rabbit_mq,
    vhost_rabbit_mq
);

$channel = $conn->channel();
$channel->queue_declare('multimedia_resultado', false, true, false, false);
$channel->basic_qos(null, 1, null);

echo "🎧 Escuchando resultados de multimedia...\n";

$callback = function ($msg) {

    $data = json_decode($msg->body, true);

    try {

        if (!$data || $data['status'] !== 'ok') {
            throw new Exception($data['error'] ?? 'Mensaje inválido');
        }

        $board_id  = (int)$data['board_id'];
        $preview   = $data['preview'];
        $thumbnail = $data['thumbnail'];

        echo "✔ Resultado recibido para board {$board_id}\n";

        $conexion = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);

        if ($conexion->connect_error) {
            throw new Exception("MySQL Error: " . $conexion->connect_error);
        }

        // --- UPDATE TABLEROS ---
        $sql = "update tableros 
                set imagen_tablero = ?
                where id_tablero = ?";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception($conexion->error);
        }

        $stmt->bind_param('si', $thumbnail, $board_id);
        $stmt->execute();
        $stmt->close();

        // --- UPDATE ASIGNAR MULTIMEDIA ---
        $sql = "update asignar_multimedia_t 
                set ruta_multimedia = ?
                where id_asignar = ?";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception($conexion->error);
        }

        $stmt->bind_param('si', $preview, $board_id);
        $stmt->execute();
        $stmt->close();

        $conexion->close();

        $msg->ack();

    } catch (Exception $e) {

        error_log("❌ Error consumer: " . $e->getMessage());
        $msg->nack(false, false); // no reencolar
    }
};

$channel->basic_consume(
    'multimedia_resultado',
    '',
    false,
    false,
    false,
    false,
    $callback
);

while ($channel->is_consuming()) {
    $channel->wait();
}
