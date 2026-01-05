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

        // --- UPDATE TABLEROS ---
        $sql = "update tableros 
                set imagen_tablero = ?,
                    preview_tablero = ?
                where id_tablero = ?";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception($conexion->error);
        }

        $stmt->bind_param('ssi', $thumbnail, $preview, $board_id);
        $stmt->execute();
        $stmt->close();
        

        $sql = "insert into asignar_multimedia_t 
                (id_tablero,ruta_multimedia,tipo_multimedia) values (?,?,?)";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception($conexion->error);
        }
        $tipo_multimedia = 'video';

        $stmt->bind_param('iss', $board_id, $video_completo, $tipo_multimedia);
        $stmt->execute();
        $stmt->close();

        // --- UPDATE COMENTARIOS ---


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
