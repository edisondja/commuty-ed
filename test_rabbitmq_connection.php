<?php
/**
 * Script de diagnóstico de conexión RabbitMQ
 * Uso: php test_rabbitmq_connection.php
 */

require_once 'config/config.php';
require_once 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

echo "🔍 Diagnóstico de Conexión RabbitMQ\n";
echo str_repeat("=", 50) . "\n\n";

// Obtener configuración
$rabbit_host = defined('host_rabbit_mq') ? host_rabbit_mq : 'localhost';
$rabbit_port = defined('port_rabbit_mq') ? port_rabbit_mq : 5672;
$rabbit_user = defined('user_rabbit_mq') ? user_rabbit_mq : 'guest';
$rabbit_pass = defined('password_rabbit_mq') ? password_rabbit_mq : 'guest';
$rabbit_vhost = defined('vhost_rabbit_mq') ? vhost_rabbit_mq : '/';

echo "📋 Configuración:\n";
echo "   Host: $rabbit_host\n";
echo "   Puerto: $rabbit_port\n";
echo "   Usuario: $rabbit_user\n";
echo "   VHost: $rabbit_vhost\n";
echo "\n";

// 1. Verificar conectividad de red
echo "1️⃣ Verificando conectividad de red...\n";
$socket = @fsockopen($rabbit_host, $rabbit_port, $errno, $errstr, 5);
if ($socket) {
    echo "   ✅ Puerto $rabbit_port alcanzable en $rabbit_host\n";
    fclose($socket);
} else {
    echo "   ❌ No se puede conectar a $rabbit_host:$rabbit_port\n";
    echo "   Error: $errstr ($errno)\n";
    echo "\n";
    echo "💡 Soluciones:\n";
    echo "   - Verificar que RabbitMQ esté corriendo: sudo systemctl status rabbitmq-server\n";
    echo "   - Verificar que el puerto esté abierto: netstat -tlnp | grep $rabbit_port\n";
    echo "   - Verificar firewall: sudo ufw status\n";
    exit(1);
}
echo "\n";

// 2. Verificar conexión AMQP
echo "2️⃣ Intentando conexión AMQP...\n";
try {
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
        5.0, // connection_timeout
        5.0  // read_write_timeout
    );
    
    echo "   ✅ Conexión AMQP exitosa\n";
    
    // 3. Verificar canales
    echo "\n3️⃣ Verificando canales...\n";
    $channel = $connection->channel();
    echo "   ✅ Canal creado correctamente\n";
    
    // 4. Verificar colas
    echo "\n4️⃣ Verificando colas...\n";
    
    try {
        list($queue, $message_count, $consumer_count) = $channel->queue_declare('procesar_multimedia', true);
        echo "   ✅ Cola 'procesar_multimedia':\n";
        echo "      - Mensajes en cola: $message_count\n";
        echo "      - Consumidores activos: $consumer_count\n";
    } catch (Exception $e) {
        echo "   ⚠️  Cola 'procesar_multimedia': " . $e->getMessage() . "\n";
    }
    
    try {
        list($queue, $message_count, $consumer_count) = $channel->queue_declare('multimedia_resultado', true);
        echo "   ✅ Cola 'multimedia_resultado':\n";
        echo "      - Mensajes en cola: $message_count\n";
        echo "      - Consumidores activos: $consumer_count\n";
    } catch (Exception $e) {
        echo "   ⚠️  Cola 'multimedia_resultado': " . $e->getMessage() . "\n";
    }
    
    $channel->close();
    $connection->close();
    
    echo "\n✅ Todas las verificaciones pasaron correctamente\n";
    
} catch (Exception $e) {
    echo "   ❌ Error de conexión AMQP: " . $e->getMessage() . "\n";
    echo "   Código: " . $e->getCode() . "\n";
    echo "\n";
    
    // Diagnóstico adicional
    $error_msg = $e->getMessage();
    
    if (strpos($error_msg, 'ACCESS_REFUSED') !== false || 
        strpos($error_msg, 'authentication') !== false ||
        strpos($error_msg, 'Login') !== false) {
        echo "💡 Problema de autenticación detectado:\n";
        echo "   - Verificar usuario y contraseña en config/config.php\n";
        echo "   - Verificar que el usuario tenga permisos en el vhost '$rabbit_vhost'\n";
        echo "   - Comando: sudo rabbitmqctl list_users\n";
        echo "   - Comando: sudo rabbitmqctl list_permissions -p '$rabbit_vhost'\n";
    }
    
    if (strpos($error_msg, 'Connection refused') !== false ||
        strpos($error_msg, 'No route to host') !== false) {
        echo "💡 Problema de red detectado:\n";
        echo "   - Verificar que RabbitMQ esté escuchando en $rabbit_host:$rabbit_port\n";
        echo "   - Comando: sudo netstat -tlnp | grep $rabbit_port\n";
        echo "   - Verificar configuración de RabbitMQ: sudo rabbitmqctl environment\n";
    }
    
    if (strpos($error_msg, 'timeout') !== false) {
        echo "💡 Timeout detectado:\n";
        echo "   - El servidor RabbitMQ puede estar sobrecargado\n";
        echo "   - Verificar logs: sudo journalctl -u rabbitmq-server -n 50\n";
    }
    
    exit(1);
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ Diagnóstico completado exitosamente\n";
