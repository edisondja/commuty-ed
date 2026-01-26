<?php
/**
 * API para monitoreo de servicios en tiempo real
 */

header('Content-Type: application/json; charset=utf-8');
require_once('../config/config.php');
require_once('../vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;

$response = [
    'timestamp' => date('Y-m-d H:i:s'),
    'status' => 'ok',
    'services' => []
];

// Verificar servicios systemd
$services = ['commuty-consumer', 'commuty-resultado', 'rabbitmq-server'];

foreach ($services as $service) {
    $active = trim(shell_exec("systemctl is-active $service 2>/dev/null"));
    $enabled = trim(shell_exec("systemctl is-enabled $service 2>/dev/null"));
    $status_output = shell_exec("systemctl status $service --no-pager -l 2>/dev/null");
    
    // Extraer información adicional
    $uptime = '';
    $memory = '';
    $cpu = '';
    
    if ($active === 'active') {
        // Obtener uptime
        $uptime_cmd = "systemctl show $service -p ActiveEnterTimestamp --value 2>/dev/null";
        $uptime = shell_exec($uptime_cmd);
        
        // Obtener uso de recursos si es un servicio PHP
        if (strpos($service, 'commuty') !== false) {
            $pid = trim(shell_exec("systemctl show $service -p MainPID --value 2>/dev/null"));
            if ($pid && is_numeric($pid)) {
                $ps_output = shell_exec("ps -p $pid -o %mem,%cpu --no-headers 2>/dev/null");
                if ($ps_output) {
                    $parts = preg_split('/\s+/', trim($ps_output));
                    $memory = isset($parts[0]) ? round($parts[0], 2) . '%' : 'N/A';
                    $cpu = isset($parts[1]) ? round($parts[1], 2) . '%' : 'N/A';
                }
            }
        }
    }
    
    $response['services'][$service] = [
        'name' => $service,
        'active' => $active === 'active',
        'enabled' => $enabled === 'enabled',
        'status' => $active,
        'uptime' => $uptime ? date('Y-m-d H:i:s', strtotime($uptime)) : null,
        'memory' => $memory,
        'cpu' => $cpu
    ];
}

// Verificar RabbitMQ específicamente
try {
    $connection = new AMQPStreamConnection(
        RABBIT_HOST ?? 'localhost',
        RABBIT_PORT ?? 5672,
        RABBIT_USER ?? 'guest',
        RABBIT_PASS ?? 'guest'
    );
    
    $channel = $connection->channel();
    
    // Obtener información de colas
    $queues = [];
    try {
        $queue_info = $channel->queue_declare('procesar_multimedia', true, false, false, false);
        $queues['procesar_multimedia'] = [
            'messages' => $queue_info[1] ?? 0,
            'consumers' => $queue_info[2] ?? 0
        ];
    } catch (Exception $e) {
        $queues['procesar_multimedia'] = ['error' => $e->getMessage()];
    }
    
    try {
        $queue_info = $channel->queue_declare('resultado_procesamiento', true, false, false, false);
        $queues['resultado_procesamiento'] = [
            'messages' => $queue_info[1] ?? 0,
            'consumers' => $queue_info[2] ?? 0
        ];
    } catch (Exception $e) {
        $queues['resultado_procesamiento'] = ['error' => $e->getMessage()];
    }
    
    $response['rabbitmq'] = [
        'connected' => true,
        'queues' => $queues
    ];
    
    $channel->close();
    $connection->close();
    
} catch (Exception $e) {
    $response['rabbitmq'] = [
        'connected' => false,
        'error' => $e->getMessage()
    ];
}

// Verificar procesos PHP
$php_processes = [];
$ps_output = shell_exec("ps aux | grep -E 'consumer_service\.php|consumer_resultado\.php' | grep -v grep");
if ($ps_output) {
    $lines = explode("\n", trim($ps_output));
    foreach ($lines as $line) {
        if (preg_match('/\s+(\d+)\s+[\d.]+\s+([\d.]+)\s+([\d.]+)\s+.*?(consumer_\w+\.php)/', $line, $matches)) {
            $php_processes[] = [
                'pid' => $matches[1],
                'cpu' => $matches[2] . '%',
                'memory' => $matches[3] . '%',
                'script' => $matches[4]
            ];
        }
    }
}

$response['php_processes'] = $php_processes;
$response['php_processes_count'] = count($php_processes);

// Obtener logs recientes
$logs = [];
if (file_exists('/var/log/commuty/consumer.log')) {
    $logs['consumer'] = array_slice(
        array_filter(explode("\n", shell_exec("tail -5 /var/log/commuty/consumer.log 2>/dev/null"))),
        -5
    );
}
if (file_exists('/var/log/commuty/consumer-error.log')) {
    $logs['consumer_error'] = array_slice(
        array_filter(explode("\n", shell_exec("tail -5 /var/log/commuty/consumer-error.log 2>/dev/null"))),
        -5
    );
}
if (file_exists('/var/log/commuty/resultado.log')) {
    $logs['resultado'] = array_slice(
        array_filter(explode("\n", shell_exec("tail -5 /var/log/commuty/resultado.log 2>/dev/null"))),
        -5
    );
}

$response['logs'] = $logs;

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
