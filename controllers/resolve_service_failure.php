<?php
/**
 * API para marcar fallos de servicios como resueltos
 */

header('Content-Type: application/json; charset=utf-8');
require_once('../config/config.php');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception('ID de fallo no proporcionado');
    }
    
    $id = (int)$data['id'];
    $resolved_by = $data['resolved_by'] ?? 'admin';
    $mark_as_resolved = isset($data['resolved']) ? (bool)$data['resolved'] : true;
    
    $conn = new mysqli(HOST, USER, PASS, DB);
    
    if ($conn->connect_error) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    $conn->set_charset("utf8mb4");
    
    if ($mark_as_resolved) {
        $sql = "UPDATE service_failures SET resolved = 1, resolved_at = NOW(), resolved_by = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $resolved_by, $id);
    } else {
        $sql = "UPDATE service_failures SET resolved = 0, resolved_at = NULL, resolved_by = NULL WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
    }
    
    if (!$stmt->execute()) {
        throw new Exception('Error al actualizar: ' . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
    
    $response = [
        'success' => true,
        'message' => $mark_as_resolved ? 'Fallo marcado como resuelto' : 'Fallo marcado como no resuelto'
    ];
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
