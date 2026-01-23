<?php
/**
 * Controlador para monitoreo de RabbitMQ y procesos de multimedia
 */

require_once '../bootstrap.php';
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

header('Content-Type: application/json');

// Verificar que el usuario sea admin
if (!isset($_SESSION['id_user']) || $_SESSION['type_user'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? 'status';

try {
    switch ($action) {
        case 'status':
            verificarEstadoRabbitMQ();
            break;
            
        case 'queues':
            obtenerEstadoColas();
            break;
            
        case 'processes':
            obtenerEstadoProcesos();
            break;
            
        case 'start_service':
            iniciarServicio($_POST['service'] ?? '');
            break;
            
        case 'stop_service':
            detenerServicio($_POST['service'] ?? '');
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function verificarEstadoRabbitMQ() {
    try {
        $conn = new AMQPStreamConnection(
            host_rabbit_mq,
            port_rabbit_mq,
            user_rabbit_mq,
            password_rabbit_mq,
            vhost_rabbit_mq
        );
        
        $channel = $conn->channel();
        
        echo json_encode([
            'success' => true,
            'connected' => true,
            'host' => host_rabbit_mq,
            'port' => port_rabbit_mq,
            'user' => user_rabbit_mq,
            'vhost' => vhost_rabbit_mq
        ]);
        
        $channel->close();
        $conn->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'connected' => false,
            'message' => $e->getMessage(),
            'host' => host_rabbit_mq,
            'port' => port_rabbit_mq,
            'user' => user_rabbit_mq,
            'vhost' => vhost_rabbit_mq
        ]);
    }
}

function obtenerEstadoColas() {
    try {
        $conn = new AMQPStreamConnection(
            host_rabbit_mq,
            port_rabbit_mq,
            user_rabbit_mq,
            password_rabbit_mq,
            vhost_rabbit_mq
        );
        
        $channel = $conn->channel();
        
        // Declarar colas para obtener información
        $queues = [];
        
        // Cola de procesar multimedia
        try {
            list($queue, $message_count, $consumer_count) = $channel->queue_declare('procesar_multimedia', true);
            $queues[] = [
                'name' => 'procesar_multimedia',
                'messages' => $message_count,
                'consumers' => $consumer_count
            ];
        } catch (Exception $e) {
            $queues[] = [
                'name' => 'procesar_multimedia',
                'messages' => 0,
                'consumers' => 0,
                'error' => $e->getMessage()
            ];
        }
        
        // Cola de resultados
        try {
            list($queue, $message_count, $consumer_count) = $channel->queue_declare('multimedia_resultado', true);
            $queues[] = [
                'name' => 'multimedia_resultado',
                'messages' => $message_count,
                'consumers' => $consumer_count
            ];
        } catch (Exception $e) {
            $queues[] = [
                'name' => 'multimedia_resultado',
                'messages' => 0,
                'consumers' => 0,
                'error' => $e->getMessage()
            ];
        }
        
        echo json_encode([
            'success' => true,
            'queues' => $queues
        ]);
        
        $channel->close();
        $conn->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

function obtenerEstadoProcesos() {
    $processes = [
        'consumer_service' => false,
        'consumer_resultado' => false,
        'consumer_pid' => null,
        'resultado_pid' => null,
        'active_processes' => []
    ];
    
    // Verificar si consumer_service.php está corriendo
    $consumerPid = verificarProceso('consumer_service.php');
    if ($consumerPid) {
        $processes['consumer_service'] = true;
        $processes['consumer_pid'] = $consumerPid;
    }
    
    // Verificar si consumer_resultado.php está corriendo
    $resultadoPid = verificarProceso('consumer_resultado.php');
    if ($resultadoPid) {
        $processes['consumer_resultado'] = true;
        $processes['resultado_pid'] = $resultadoPid;
    }
    
    // Obtener procesos activos de multimedia desde la base de datos
    try {
        global $conexion;
        $sql = "SELECT id_tablero, fecha_creacion, estado 
                FROM tableros 
                WHERE estado = 'procesando' OR preview_tablero IS NULL 
                ORDER BY fecha_creacion DESC 
                LIMIT 10";
        
        $result = $conexion->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $processes['active_processes'][] = [
                    'board_id' => $row['id_tablero'],
                    'status' => $row['estado'],
                    'time' => $row['fecha_creacion']
                ];
            }
        }
    } catch (Exception $e) {
        // Ignorar errores de BD
    }
    
    echo json_encode([
        'success' => true,
        'processes' => $processes
    ]);
}

function verificarProceso($scriptName) {
    // Verificar en diferentes sistemas operativos
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows
        $command = "tasklist /FI \"IMAGENAME eq php.exe\" /FO CSV | findstr /i \"$scriptName\"";
    } else {
        // Linux/Mac
        $command = "ps aux | grep -i '$scriptName' | grep -v grep";
    }
    
    $output = [];
    $return_var = 0;
    exec($command, $output, $return_var);
    
    if (!empty($output)) {
        // Extraer PID
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // En Windows, el PID está en la segunda columna del CSV
            foreach ($output as $line) {
                $parts = str_getcsv($line);
                if (count($parts) > 1) {
                    return trim($parts[1], '"');
                }
            }
        } else {
            // En Linux/Mac, el PID está en la segunda columna
            foreach ($output as $line) {
                $parts = preg_split('/\s+/', trim($line));
                if (count($parts) > 1 && strpos($line, $scriptName) !== false) {
                    return $parts[1];
                }
            }
        }
    }
    
    return false;
}

/**
 * Inicia un servicio de consumer
 */
function iniciarServicio($service) {
    $services = [
        'consumer_service' => __DIR__ . '/../consumer_service.php',
        'consumer_resultado' => __DIR__ . '/../consumer_resultado.php'
    ];
    
    if (!isset($services[$service])) {
        echo json_encode(['success' => false, 'message' => 'Servicio no válido']);
        return;
    }
    
    $scriptPath = $services[$service];
    
    // Verificar si ya está corriendo
    $pid = verificarProceso(basename($scriptPath));
    if ($pid) {
        echo json_encode(['success' => false, 'message' => 'El servicio ya está en ejecución (PID: ' . $pid . ')']);
        return;
    }
    
    // Iniciar el servicio en background
    $command = '';
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows
        $phpPath = 'php'; // Ajustar según tu instalación
        $command = "start /B $phpPath \"$scriptPath\" > NUL 2>&1";
    } else {
        // Linux/Mac
        $phpPath = 'php'; // Ajustar según tu instalación (puede ser /usr/bin/php)
        $command = "nohup $phpPath \"$scriptPath\" > /dev/null 2>&1 &";
    }
    
    exec($command, $output, $return_var);
    
    // Esperar un momento para verificar que se inició
    sleep(1);
    $pid = verificarProceso(basename($scriptPath));
    
    if ($pid) {
        echo json_encode([
            'success' => true, 
            'message' => 'Servicio iniciado correctamente',
            'pid' => $pid
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'No se pudo iniciar el servicio. Verifique los logs.'
        ]);
    }
}

/**
 * Detiene un servicio de consumer
 */
function detenerServicio($service) {
    $services = [
        'consumer_service' => 'consumer_service.php',
        'consumer_resultado' => 'consumer_resultado.php'
    ];
    
    if (!isset($services[$service])) {
        echo json_encode(['success' => false, 'message' => 'Servicio no válido']);
        return;
    }
    
    $scriptName = $services[$service];
    
    // Buscar el PID del proceso
    $pid = verificarProceso($scriptName);
    
    if (!$pid) {
        echo json_encode(['success' => false, 'message' => 'El servicio no está en ejecución']);
        return;
    }
    
    // Detener el proceso
    $command = '';
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows
        $command = "taskkill /F /PID $pid";
    } else {
        // Linux/Mac
        $command = "kill -9 $pid";
    }
    
    exec($command, $output, $return_var);
    
    // Verificar que se detuvo
    sleep(1);
    $pidCheck = verificarProceso($scriptName);
    
    if (!$pidCheck) {
        echo json_encode([
            'success' => true, 
            'message' => 'Servicio detenido correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'No se pudo detener el servicio completamente'
        ]);
    }
}

?>
