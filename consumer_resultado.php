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

$channel->queue_declare(
    'multimedia_resultado',
    false,
    true,
    false,
    false
);

$channel->basic_qos(null, 1, null);

echo "Escuchando resultados de multimedia...\n";

$callback = function ($msg) use($conexion) {

    $data = json_decode($msg->body, true);

    try {

        if ($data['status'] !== 'ok') {
            throw new Exception($data['error'] ?? 'Error desconocido');
        }

        $board_id     = $data['board_id'];
        $preview   = $data['preview'];
        $thumbnail = $data['thumbnail'];

        echo "   Resultado recibido para job {$board_id}\n";
        echo "   Preview: {$preview}\n";
        echo "   Imagen : {$thumbnail}\n";
        
        $sql = "update tableros set 
                preview_video = ?,
                thumbnail_image = ?,
                where id_tablero = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $preview,
            $thumbnail,
            $board_id
        ]);

        $stmt->close();
        $msg->ack();

    } catch (Exception $e) {

        echo "Error procesando resultado: {$e->getMessage()}\n";

        // No reencolar para evitar loops
        $msg->nack(false, false);
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
