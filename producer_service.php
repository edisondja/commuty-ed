<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Validaciones básicas

if (isset($_FILES['archivo'])) {

    $archivo = $_FILES['archivo'];

}else{

     $archivo =$_POST['url_archivo'];
}




// 🔥 MOVER EL ARCHIVO A UNA RUTA REAL
$directorio = __DIR__ . '/uploads/';
if (!is_dir($directorio)) {
    mkdir($directorio, 0777, true);
}

$baseName = basename($archivo['name']);
if (pathinfo($baseName, PATHINFO_EXTENSION) === '') {
    $baseName .= '.mp4';
}
$nombreSeguro = date('YmdHis') . '_' . $baseName;
$rutaFinal = $directorio . $nombreSeguro;


if(isset($_FILES['archivo'])){

    if (!move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
        echo json_encode(['ok' => false, 'error' => 'No se pudo guardar el archivo']);
        exit;
    }
}else{


    $rutaFinal = $_POST['url_archivo'];

}


// Datos finales
$tipo_archivo = $_POST['tipo_archivo'];
$board_id     = (int)$_POST['board_id'];

// 🐰 Conectar RabbitMQ
$conn = new AMQPStreamConnection(
    host_rabbit_mq,
    port_rabbit_mq,
    user_rabbit_mq,
    password_rabbit_mq,
    vhost_rabbit_mq
);

$channel = $conn->channel();

$channel->queue_declare('procesar_multimedia', false, true, false, false);

// 🔥 SOLO SE ENVÍA LA RUTA (STRING)


$msg = new AMQPMessage(
    json_encode([
        'ruta_tmp'     => $rutaFinal,
        'tipo_archivo' => $tipo_archivo,
        'board_id'     => $board_id
    ]),
    ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);

$channel->basic_publish($msg, '', 'procesar_multimedia');

echo json_encode(['ok' => true, 'mensaje' => 'Archivo encolado para procesamiento']);

$channel->close();
$conn->close();
