<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//Recibe la ruta temporal del archivo subido,el nombre del archivo y el tipo de archivo
 // Conectar Rabbit MQ 
$conn = new AMQPStreamConnection(
        host_rabbit_mq,
        port_rabbit_mq,
        user_rabbit_mq,
        password_rabbit_mq,
        vhost_rabbit_mq
    );


try{
    $ruta_temporal = $_POST['temp_ruta'];
    $nombre_archivo = $_POST['nombre_archivo'];
    $tipo_archivo = $_POST['tipo_archivo'];
    $board_id = $_POST['board_id'];
}catch(Exception $e){
    $ruta_temporal = '';
    $nombre_archivo = '';
    $tipo_archivo = '';
    $board_id = '';
    echo "Error al recibir los datos: " . $e->getMessage() . "\n";
}


$channel = $conn->channel();

$msg = new AMQPMessage(
    json_encode(
        ['mensaje' => 'Encolando multimedia para ser procesada..',
         'fecha' => date('Y-m-d H:i:s'),
         'usuario' => 'sistema',
         'temp_ruta' =>  $ruta_temporal,
         'nombre_archivo' =>  $nombre_archivo,
         'tipo_archivo'=> $tipo_archivo,
         'board_id' => $board_id
        ]),
    ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);

$channel->queue_declare('procesar_multimedia', false, true, false, false);

try{
    echo "Mensaje enviado\n";
                                
    $channel->basic_publish($msg, '', 'procesar_multimedia');

}catch(Exception $e){
    echo "Error al enviar el mensaje: " . $e->getMessage() . "\n";
    exit(1);
}


$channel->close();
$conn->close();