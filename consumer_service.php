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

$count = 0;

try{

    $channel->queue_declare('procesar_multimedia', false, true, false, false);

}catch(Exception $e){
    echo "Error al declarar la cola: " . $e->getMessage() . "\n";
    exit(1);
}
echo "Esperando multimedia para ser procesada.\n";

$callback = function ($msg) use (&$count) {
    $count++;

    echo "Procesando {$count} {$msg->body}\n";
    $msg->ack();
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('procesar_multimedia', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}
