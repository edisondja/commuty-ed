<?php
/**
 * API para obtener registros de fallos de servicios
 */

header('Content-Type: application/json; charset=utf-8');
require_once('../config/config.php');

$response = ['success' => false, 'data' => []];

try {
    $conn = new mysqli(HOST, USER, PASS, DB);
    
    if ($conn->connect_error) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    $conn->set_charset("utf8mb4");
    
    // Parámetros de paginación
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = ($page - 1) * $limit;
    
    // Filtros
    $service_filter = isset($_GET['service']) ? $_GET['service'] : '';
    $resolved_filter = isset($_GET['resolved']) ? $_GET['resolved'] : '';
    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
    
    // Construir query
    $where_conditions = [];
    $params = [];
    $types = '';
    
    if (!empty($service_filter)) {
        $where_conditions[] = "service_name = ?";
        $params[] = $service_filter;
        $types .= 's';
    }
    
    if ($resolved_filter !== '') {
        $where_conditions[] = "resolved = ?";
        $params[] = (int)$resolved_filter;
        $types .= 'i';
    }
    
    if (!empty($date_from)) {
        $where_conditions[] = "timestamp >= ?";
        $params[] = $date_from;
        $types .= 's';
    }
    
    if (!empty($date_to)) {
        $where_conditions[] = "timestamp <= ?";
        $params[] = $date_to . ' 23:59:59';
        $types .= 's';
    }
    
    $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
    
    // Contar total
    $count_sql = "SELECT COUNT(*) as total FROM service_failures $where_clause";
    $count_stmt = $conn->prepare($count_sql);
    
    if (!empty($params)) {
        $count_stmt->bind_param($types, ...$params);
    }
    
    $count_stmt->execute();
    $total_result = $count_stmt->get_result();
    $total = $total_result->fetch_assoc()['total'];
    $count_stmt->close();
    
    // Obtener registros
    $sql = "SELECT * FROM service_failures $where_clause ORDER BY timestamp DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;
        $stmt->bind_param($types, ...$params);
    } else {
        $stmt->bind_param('ii', $limit, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $failures = [];
    while ($row = $result->fetch_assoc()) {
        $row['additional_data'] = json_decode($row['additional_data'], true);
        $failures[] = $row;
    }
    
    $stmt->close();
    $conn->close();
    
    $response = [
        'success' => true,
        'data' => $failures,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => (int)$total,
            'pages' => ceil($total / $limit)
        ]
    ];
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
