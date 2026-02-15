<?php
/**
 * Helper de compatibilidad para PHP 7.2 y PHP 8+
 */

class CompatibilityHelper {
    
    /**
     * Obtiene un objeto desde un mysqli_result de forma compatible
     * Compatible con PHP 7.2 y PHP 8+
     */
    public static function fetchObject($result) {
        if ($result instanceof mysqli_result) {
            // En PHP 8+, fetch_object() puede necesitar manejo especial
            if (PHP_VERSION_ID >= 80000) {
                $object = $result->fetch_object();
                return $object !== null ? $object : false;
            } else {
                return mysqli_fetch_object($result);
            }
        }
        return false;
    }
    
    /**
     * Obtiene un array asociativo desde un mysqli_result de forma compatible
     */
    public static function fetchAssoc($result) {
        if ($result instanceof mysqli_result) {
            if (PHP_VERSION_ID >= 80000) {
                $array = $result->fetch_assoc();
                return $array !== null ? $array : false;
            } else {
                return mysqli_fetch_assoc($result);
            }
        }
        return false;
    }
    
    /**
     * Convierte un mysqli_result a array de forma compatible
     */
    public static function resultToArray($result) {
        $data = [];
        if ($result instanceof mysqli_result) {
            // En PHP 8+, foreach funciona directamente con mysqli_result
            if (PHP_VERSION_ID >= 80000) {
                foreach ($result as $row) {
                    $data[] = $row;
                }
            } else {
                // PHP 7.2 - usar fetch_assoc
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }
    
    /**
     * Verifica si una conexión mysqli es válida y activa
     */
    public static function isConnectionValid($connection) {
        if (!($connection instanceof mysqli)) {
            return false;
        }
        
        // En PHP 8+, usar ping() si está disponible
        if (PHP_VERSION_ID >= 80000 && method_exists($connection, 'ping')) {
            try {
                return $connection->ping();
            } catch (Exception $e) {
                return false;
            }
        }
        
        // PHP 7.2 - verificar connect_error
        return !$connection->connect_error;
    }
    
    /**
     * Maneja errores de mysqli de forma compatible
     */
    public static function getMysqliError($connection) {
        if ($connection instanceof mysqli) {
            return $connection->error;
        }
        return 'Conexión no válida';
    }
}
