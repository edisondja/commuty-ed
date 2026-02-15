<?php
/**
 * API para registrar fallos de servicios
 * Se llama automáticamente cuando un servicio falla
 */

header('Content-Type: application/json; charset=utf-8');
require_once('../config/config.php');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['service_name']) || empty($data['error_message'])) {
        throw new Exception('Datos incompletos');
    }
    
    $service_name = $data['service_name'];
    $error_message = $data['error_message'];
    $error_type = $data['error_type'] ?? 'unknown';
    $stack_trace = $data['stack_trace'] ?? '';
    $additional_data = $data['additional_data'] ?? [];
    
    // Crear directorio de logs si no existe
    $log_dir = __DIR__ . '/../logs/services';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0777, true);
    }
    
    // Nombre del archivo de log (un archivo por día)
    $log_file = $log_dir . '/service_failures_' . date('Y-m-d') . '.log';
    
    // Formato del log
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'service' => $service_name,
        'error_type' => $error_type,
        'error_message' => $error_message,
        'stack_trace' => $stack_trace,
        'additional_data' => $additional_data,
        'server' => [
            'hostname' => gethostname(),
            'php_version' => PHP_VERSION,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true)
        ]
    ];
    
    // Escribir en archivo de log
    $log_line = json_encode($log_entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
    
    // También guardar en base de datos si está disponible
    try {
        $conn = new mysqli(HOST, USER, PASS, DB);
        if (!$conn->connect_error) {
            // Verificar si existe la tabla
            $table_check = $conn->query("SHOW TABLES LIKE 'service_failures'");
            
            if ($table_check->num_rows == 0) {
                // Crear tabla si no existe
                $create_table = "CREATE TABLE IF NOT EXISTS `service_failures` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `service_name` VARCHAR(100) NOT NULL,
                    `error_type` VARCHAR(50) DEFAULT NULL,
                    `error_message` TEXT NOT NULL,
                    `stack_trace` TEXT DEFAULT NULL,
                    `additional_data` JSON DEFAULT NULL,
                    `resolved` TINYINT(1) DEFAULT 0,
                    `resolved_at` DATETIME DEFAULT NULL,
                    `resolved_by` VARCHAR(100) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `idx_service` (`service_name`),
                    KEY `idx_timestamp` (`timestamp`),
                    KEY `idx_resolved` (`resolved`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
                
                $conn->query($create_table);
            }
            
            // Insertar registro
            $stmt = $conn->prepare("INSERT INTO service_failures 
                (service_name, error_type, error_message, stack_trace, additional_data) 
                VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt) {
                $additional_json = json_encode($additional_data, JSON_UNESCAPED_UNICODE);
                $stmt->bind_param('sssss', 
                    $service_name, 
                    $error_type, 
                    $error_message, 
                    $stack_trace,
                    $additional_json
                );
                $stmt->execute();
                $stmt->close();
            }
            
            $conn->close();
        }
    } catch (Exception $db_error) {
        // Si falla la BD, solo loguear en archivo (ya hecho arriba)
        error_log("Error guardando en BD: " . $db_error->getMessage());
    }
    
    $response = [
        'success' => true,
        'message' => 'Error registrado correctamente',
        'log_file' => basename($log_file)
    ];
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
