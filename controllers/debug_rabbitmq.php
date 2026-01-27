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

// Definir variables de configuración de RabbitMQ (disponibles en todo el script)
$rabbit_host = defined('host_rabbit_mq') ? host_rabbit_mq : 'localhost';
$rabbit_port = defined('port_rabbit_mq') ? port_rabbit_mq : 5672;
$rabbit_user = defined('user_rabbit_mq') ? user_rabbit_mq : 'guest';
$rabbit_pass = defined('password_rabbit_mq') ? password_rabbit_mq : 'guest';
$rabbit_vhost = defined('vhost_rabbit_mq') ? vhost_rabbit_mq : '/';

// 1. Verificar conexión a RabbitMQ
try {
    
    $response['checks']['rabbitmq_config'] = [
        'host' => $rabbit_host,
        'port' => $rabbit_port,
        'user' => $rabbit_user,
        'vhost' => $rabbit_vhost,
        'config_loaded' => defined('host_rabbit_mq')
    ];
    
    // Intentar conexión con timeout más corto para diagnóstico
    $connection = new AMQPStreamConnection(
        $rabbit_host,
        $rabbit_port,
        $rabbit_user,
        $rabbit_pass,
        $rabbit_vhost,
        false, // $insist
        'AMQPLAIN', // login_method
        null, // login_response
        'en_US', // locale
        3.0, // connection_timeout (3 segundos)
        3.0  // read_write_timeout (3 segundos)
    );
    
    $response['checks']['rabbitmq_connection'] = [
        'status' => 'success',
        'message' => 'Conexión a RabbitMQ exitosa',
        'host' => $rabbit_host,
        'port' => $rabbit_port,
        'vhost' => $rabbit_vhost
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
    $error_message = $e->getMessage();
    $error_code = $e->getCode();
    
    // Diagnóstico adicional
    $diagnosis = [];
    
    // Verificar si el host es alcanzable
    if (function_exists('fsockopen')) {
        $socket = @fsockopen($rabbit_host, $rabbit_port, $errno, $errstr, 2);
        if ($socket) {
            $diagnosis['network'] = 'Host y puerto alcanzables';
            fclose($socket);
        } else {
            $diagnosis['network'] = "No se puede conectar: $errstr ($errno)";
        }
    }
    
    // Verificar si es un problema de autenticación
    if (strpos($error_message, 'ACCESS_REFUSED') !== false || 
        strpos($error_message, 'authentication') !== false ||
        strpos($error_message, 'Login') !== false) {
        $diagnosis['auth'] = 'Posible problema de autenticación';
    }
    
    // Verificar si es un problema de red
    if (strpos($error_message, 'Connection refused') !== false ||
        strpos($error_message, 'No route to host') !== false ||
        strpos($error_message, 'timeout') !== false) {
        $diagnosis['network'] = 'Problema de conectividad de red';
    }
    
    $response['checks']['rabbitmq_connection'] = [
        'status' => 'error',
        'message' => 'Error conectando a RabbitMQ',
        'error' => $error_message,
        'error_code' => $error_code,
        'host' => $rabbit_host,
        'port' => $rabbit_port,
        'user' => $rabbit_user,
        'vhost' => $rabbit_vhost,
        'diagnosis' => $diagnosis,
        'suggestions' => [
            'Verificar que RabbitMQ esté corriendo: sudo systemctl status rabbitmq-server',
            'Verificar que el puerto esté abierto: netstat -tlnp | grep 5672',
            'Verificar credenciales en config/config.php',
            'Verificar firewall: sudo ufw status',
            'Probar conexión manual: telnet ' . $rabbit_host . ' ' . $rabbit_port
        ]
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
    'domain' => defined('DOMAIN') ? DOMAIN : 'not_defined',
    'base_url' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'unknown',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'unknown',
    'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown',
    'remote_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];

// 4.1. Verificar conectividad de red a RabbitMQ
if (isset($rabbit_host) && isset($rabbit_port)) {
    $socket_test = @fsockopen($rabbit_host, $rabbit_port, $errno, $errstr, 2);
    $response['checks']['network_test'] = [
        'host' => $rabbit_host,
        'port' => $rabbit_port,
        'reachable' => $socket_test !== false,
        'error' => $socket_test === false ? "$errstr ($errno)" : null
    ];
    if ($socket_test) {
        fclose($socket_test);
    }
}

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
        // Usar las constantes correctas de config.php
        $rabbit_host = defined('host_rabbit_mq') ? host_rabbit_mq : 'localhost';
        $rabbit_port = defined('port_rabbit_mq') ? port_rabbit_mq : 5672;
        $rabbit_user = defined('user_rabbit_mq') ? user_rabbit_mq : 'guest';
        $rabbit_pass = defined('password_rabbit_mq') ? password_rabbit_mq : 'guest';
        $rabbit_vhost = defined('vhost_rabbit_mq') ? vhost_rabbit_mq : '/';
        
        $connection = new AMQPStreamConnection(
            $rabbit_host,
            $rabbit_port,
            $rabbit_user,
            $rabbit_pass,
            $rabbit_vhost
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
