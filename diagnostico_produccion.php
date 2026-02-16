<?php
/**
 * Diagnóstico de servicios en producción
 * Ejecutar en el servidor: php diagnostico_produccion.php
 * O como www-data: sudo -u www-data php diagnostico_produccion.php
 */

$BASE_DIR = dirname(__FILE__);
chdir($BASE_DIR);

echo "\n";
echo "========================================\n";
echo "  DIAGNÓSTICO COMMUTY-ED (Producción)\n";
echo "========================================\n\n";

$errores = 0;

// 1. Config
echo "1. CONFIGURACIÓN\n";
if (!is_file($BASE_DIR . '/config/config.php')) {
    echo "   ❌ No existe config/config.php\n";
    $errores++;
} else {
    require_once $BASE_DIR . '/config/config.php';
    echo "   DOMAIN: " . (defined('DOMAIN') ? DOMAIN : 'NO DEFINIDO') . "\n";
    if (!defined('DOMAIN') || empty(DOMAIN) || DOMAIN === 'http://localhost/commuty-ed') {
        echo "   ⚠️  En producción DOMAIN debe ser tu URL real (ej: https://tudominio.com)\n";
        $errores++;
    }
    echo "   HOST_BD: " . (defined('HOST_BD') ? HOST_BD : 'NO') . "\n";
    echo "   NAME_DB: " . (defined('NAME_DB') ? NAME_DB : 'NO') . "\n";
    echo "   RabbitMQ: " . (defined('host_rabbit_mq') ? host_rabbit_mq : 'NO') . ":" . (defined('port_rabbit_mq') ? port_rabbit_mq : '?') . "\n";
}
echo "\n";

// 2. PHP y extensiones
echo "2. PHP\n";
echo "   Versión: " . PHP_VERSION . "\n";
$ext_requeridas = ['mysqli', 'mbstring', 'curl', 'json'];
foreach ($ext_requeridas as $ext) {
    $ok = extension_loaded($ext);
    echo "   " . ($ok ? '✅' : '❌') . " $ext\n";
    if (!$ok) $errores++;
}
echo "\n";

// 3. Directorios
echo "3. DIRECTORIOS (deben existir y ser escribibles)\n";
$dirs = ['uploads', 'videos', 'previa', 'imagenes_tablero', 'logs', 'tmp'];
foreach ($dirs as $d) {
    $path = $BASE_DIR . '/' . $d;
    $existe = is_dir($path);
    $escribible = $existe && is_writable($path);
    echo "   " . ($existe && $escribible ? '✅' : '❌') . " $d " . ($existe ? '' : '(no existe)') . ($existe && !$escribible ? ' (no escribible)' : '') . "\n";
    if (!$existe || !$escribible) $errores++;
}
echo "\n";

// 4. MySQL
echo "4. BASE DE DATOS\n";
if (defined('HOST_BD') && defined('USER_BD') && defined('NAME_DB')) {
    $host = (HOST_BD === 'localhost') ? '127.0.0.1' : HOST_BD;
    $conn = @new mysqli($host, USER_BD, PASSWORD_BD, NAME_DB);
    if ($conn->connect_error) {
        echo "   ❌ Conexión fallida: " . $conn->connect_error . "\n";
        $errores++;
    } else {
        echo "   ✅ Conexión OK\n";
        $conn->close();
    }
} else {
    echo "   ❌ Config de BD incompleta\n";
    $errores++;
}
echo "\n";

// 5. RabbitMQ
echo "5. RABBITMQ\n";
if (defined('host_rabbit_mq') && defined('port_rabbit_mq')) {
    $sock = @fsockopen(host_rabbit_mq, port_rabbit_mq, $errno, $errstr, 3);
    if ($sock) {
        echo "   ✅ Puerto " . port_rabbit_mq . " alcanzable\n";
        fclose($sock);
    } else {
        echo "   ❌ No se puede conectar a " . host_rabbit_mq . ":" . port_rabbit_mq . " - $errstr ($errno)\n";
        echo "   Comprobar: sudo systemctl status rabbitmq-server\n";
        $errores++;
    }
} else {
    echo "   ❌ Config RabbitMQ no definida\n";
    $errores++;
}
echo "\n";

// 6. Archivos del servicio
echo "6. ARCHIVOS DEL SERVICIO\n";
$archivos = ['consumer_service.php', 'consumer_resultado.php', 'producer_service.php', 'consumer_wrapper.sh', 'resultado_wrapper.sh'];
foreach ($archivos as $f) {
    $path = $BASE_DIR . '/' . $f;
    $existe = file_exists($path);
    $ejecutable = ($existe && preg_match('/\.sh$/', $f)) ? (is_executable($path)) : true;
    echo "   " . ($existe ? '✅' : '❌') . " $f" . ($existe && !$ejecutable && preg_match('/\.sh$/', $f) ? ' (no ejecutable: chmod +x)' : '') . "\n";
    if (!$existe) $errores++;
}
echo "\n";

// 7. Usuario actual (útil si se corre como www-data)
echo "7. ENTORNO\n";
echo "   Usuario: " . (function_exists('posix_getpwuid') && function_exists('posix_geteuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user()) . "\n";
echo "   WorkingDirectory: " . getcwd() . "\n";
echo "\n";

// Resumen
echo "========================================\n";
if ($errores > 0) {
    echo "  ❌ Se encontraron $errores problema(s). Revisa arriba.\n";
} else {
    echo "  ✅ Diagnóstico OK. Si el servicio sigue fallando, revisa logs:\n";
    echo "     journalctl -u commuty-consumer -n 100 --no-pager\n";
    echo "     journalctl -u commuty-resultado -n 100 --no-pager\n";
    echo "     tail -100 /var/log/commuty/consumer-error.log\n";
}
echo "========================================\n\n";

exit($errores > 0 ? 1 : 0);
