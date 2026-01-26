<?php
/**
 * Debug endpoint para verificar RabbitMQ y procesamiento de videos
 * Acceder: /controllers/debug_rabbitmq.php
 */

header('Content-Type: application/json; charset=utf-8');

require_once('../config/config.php');
require_once('../vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$response = [
    'timestamp' => date('Y-m-d H:i:s'),
    'status' => 'ok',
    'checks' => []
];

// 1. Verificar conexión a RabbitMQ
try {
    $connection = new AMQPStreamConnection(
        RABBIT_HOST ?? 'localhost',
        RABBIT_PORT ?? 5672,
        RABBIT_USER ?? 'guest',
        RABBIT_PASS ?? 'guest'
    );
    
    $response['checks']['rabbitmq_connection'] = [
        'status' => 'success',
        'message' => 'Conexión a RabbitMQ exitosa',
        'host' => RABBIT_HOST ?? 'localhost',
        'port' => RABBIT_PORT ?? 5672
    ];
    
    // 2. Verificar colas
    $channel = $connection->channel();
    
    // Listar colas
    $queues = [];
    try {
        $queue_declare = $channel->queue_declare('procesar_multimedia', true, false, false, false);
        $queues['procesar_multimedia'] = [
            'exists' => true,
            'messages' => $queue_declare[1] ?? 0,
            'consumers' => $queue_declare[2] ?? 0
        ];
    } catch (Exception $e) {
        $queues['procesar_multimedia'] = [
            'exists' => false,
            'error' => $e->getMessage()
        ];
    }
    
    try {
        $queue_declare = $channel->queue_declare('resultado_procesamiento', true, false, false, false);
        $queues['resultado_procesamiento'] = [
            'exists' => true,
            'messages' => $queue_declare[1] ?? 0,
            'consumers' => $queue_declare[2] ?? 0
        ];
    } catch (Exception $e) {
        $queues['resultado_procesamiento'] = [
            'exists' => false,
            'error' => $e->getMessage()
        ];
    }
    
    $response['checks']['rabbitmq_queues'] = [
        'status' => 'success',
        'queues' => $queues
    ];
    
    $channel->close();
    $connection->close();
    
} catch (Exception $e) {
    $response['checks']['rabbitmq_connection'] = [
        'status' => 'error',
        'message' => 'Error conectando a RabbitMQ',
        'error' => $e->getMessage()
    ];
    $response['status'] = 'error';
}

// 3. Verificar servicios systemd
$services = ['commuty-consumer', 'commuty-resultado'];
foreach ($services as $service) {
    $status = shell_exec("systemctl is-active $service 2>/dev/null");
    $enabled = shell_exec("systemctl is-enabled $service 2>/dev/null");
    
    $response['checks']['systemd_services'][$service] = [
        'active' => trim($status) === 'active',
        'enabled' => trim($enabled) === 'enabled',
        'status' => trim($status)
    ];
}

// 4. Verificar dominio y configuración
$response['checks']['configuration'] = [
    'domain' => DOMAIN,
    'base_url' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'unknown',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'unknown'
];

// 5. Verificar procesos PHP corriendo
$php_processes = shell_exec("ps aux | grep 'consumer_service.php\|consumer_resultado.php' | grep -v grep | wc -l");
$response['checks']['php_processes'] = [
    'count' => (int)trim($php_processes),
    'status' => (int)trim($php_processes) > 0 ? 'running' : 'not_running'
];

// 6. Verificar permisos de directorios
$dirs = ['../videos', '../uploads', '../imagenes_tablero'];
foreach ($dirs as $dir) {
    $path = realpath($dir);
    $response['checks']['directories'][basename($dir)] = [
        'exists' => $path !== false,
        'path' => $path,
        'writable' => is_writable($path),
        'permissions' => $path ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A'
    ];
}

// 7. Test de envío de mensaje (opcional)
if (isset($_GET['test_send']) && $_GET['test_send'] === '1') {
    try {
        $connection = new AMQPStreamConnection(
            RABBIT_HOST ?? 'localhost',
            RABBIT_PORT ?? 5672,
            RABBIT_USER ?? 'guest',
            RABBIT_PASS ?? 'guest'
        );
        $channel = $connection->channel();
        
        $test_message = [
            'test' => true,
            'timestamp' => time(),
            'domain' => DOMAIN
        ];
        
        $msg = new AMQPMessage(json_encode($test_message));
        $channel->basic_publish($msg, '', 'procesar_multimedia');
        
        $response['checks']['test_send'] = [
            'status' => 'success',
            'message' => 'Mensaje de prueba enviado correctamente'
        ];
        
        $channel->close();
        $connection->close();
    } catch (Exception $e) {
        $response['checks']['test_send'] = [
            'status' => 'error',
            'error' => $e->getMessage()
        ];
    }
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
