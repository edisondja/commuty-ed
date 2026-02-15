<?php
/**
 * Script para crear las tablas de la base de datos si no existen
 */

require_once 'config/config.php';

// Crear conexión
$conexion = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");

echo "Verificando y creando tablas...\n\n";

// Leer el archivo SQL
$sql_file = __DIR__ . '/database/db.sql';
if (!file_exists($sql_file)) {
    die("Error: No se encontró el archivo db.sql\n");
}

$sql_content = file_get_contents($sql_file);

// Dividir el contenido en sentencias individuales
$statements = array_filter(
    array_map('trim', explode(';', $sql_content)),
    function($stmt) {
        return !empty($stmt) && !preg_match('/^(CREATE DATABASE|USE)/i', $stmt);
    }
);

$success_count = 0;
$error_count = 0;

foreach ($statements as $statement) {
    if (empty(trim($statement))) {
        continue;
    }
    
    // Ejecutar la sentencia
    if ($conexion->query($statement)) {
        $success_count++;
        // Extraer el nombre de la tabla si es CREATE TABLE
        if (preg_match('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`?(\w+)`?/i', $statement, $matches)) {
            echo "✓ Tabla '{$matches[1]}' creada o ya existe\n";
        }
    } else {
        $error_count++;
        $error = $conexion->error;
        // Ignorar errores de "table already exists"
        if (strpos($error, 'already exists') === false && strpos($error, 'Duplicate') === false) {
            echo "✗ Error: $error\n";
            echo "  SQL: " . substr($statement, 0, 100) . "...\n\n";
        } else {
            // Extraer el nombre de la tabla
            if (preg_match('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`?(\w+)`?/i', $statement, $matches)) {
                echo "✓ Tabla '{$matches[1]}' ya existe\n";
            }
            $success_count++;
            $error_count--;
        }
    }
}

echo "\n";
echo "========================================\n";
echo "Resumen:\n";
echo "  ✓ Exitosas: $success_count\n";
echo "  ✗ Errores: $error_count\n";
echo "========================================\n";

// Verificar que las tablas principales existan
echo "\nVerificando tablas principales...\n";
$required_tables = ['user', 'tableros', 'configuracion'];
foreach ($required_tables as $table) {
    $result = $conexion->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "✓ Tabla '$table' existe\n";
    } else {
        echo "✗ Tabla '$table' NO existe\n";
    }
}

$conexion->close();
echo "\n¡Proceso completado!\n";
