<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$conn = new AMQPStreamConnection(
    host_rabbit_mq,
    port_rabbit_mq,
    user_rabbit_mq,
    password_rabbit_mq,
    vhost_rabbit_mq
);

$channel = $conn->channel();

$channel->queue_declare('procesar_multimedia', false, true, false, false);
$channel->queue_declare('multimedia_resultado', false, true, false, false);

$channel->basic_qos(null, 1, null);

echo " Worker FFmpeg activo...\n";

$callback = function ($msg) use ($channel) {

    $data = json_decode($msg->body, true);

    try {

        if (!isset($data['ruta_tmp'], $data['board_id'])) {
            throw new Exception("Mensaje incompleto");
        }
        $rutaTmp = escapeshellarg($data['ruta_tmp']);
        $board_id   = $data['board_id'];

        $fecha = date('YmdHis');

        $preview   = "previa/{$fecha}_{$board_id}.mp4";
        $thumbnail = "imagenes/{$fecha}_{$board_id}.jpg";

        //Preview (5 segundos desde el segundo 30)
        shell_exec("ffmpeg -y -ss 00:00:30 -t 5 -i {$rutaTmp} {$preview} 2>/dev/null");

        //Thumbnail
        shell_exec("ffmpeg -y -i {$rutaTmp} -ss 00:00:05 -vframes 1 {$thumbnail} 2>/dev/null");


        $response = new AMQPMessage(
            json_encode([
                'board_id'    => $board_id,
                'preview'   => $preview,
                'thumbnail' => $thumbnail,
                'status'    => 'ok'
            ]),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $channel->basic_publish($response, '', 'multimedia_resultado');

        echo "Job {$board_id} procesado\n";

        $msg->ack();

    } catch (Exception $e) {

        $error = new AMQPMessage(
            json_encode([
                'board_id' => $data['board_id'] ?? null,
                'status' => 'error',
                'error'  => $e->getMessage()
            ])
        );

        $channel->basic_publish($error, '', 'multimedia_resultado');

        echo "Error: {$e->getMessage()}\n";

        $msg->nack(false, false);
    }
};

$channel->basic_consume(
    'procesar_multimedia',
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
