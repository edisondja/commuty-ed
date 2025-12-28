<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Validaciones básicas
if (!isset($_FILES['archivo'], $_POST['tipo_archivo'], $_POST['board_id'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}

$archivo = $_FILES['archivo'];

if ($archivo['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['ok' => false, 'error' => 'Error al subir archivo']);
    exit;
}

// 🔥 MOVER EL ARCHIVO A UNA RUTA REAL
$directorio = __DIR__ . '/uploads/';
if (!is_dir($directorio)) {
    mkdir($directorio, 0777, true);
}

$nombreSeguro = time() . '_' . basename($archivo['name']);
$rutaFinal = $directorio . $nombreSeguro;

if (!move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
    echo json_encode(['ok' => false, 'error' => 'No se pudo guardar el archivo']);
    exit;
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
