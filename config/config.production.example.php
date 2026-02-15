<?php
/**
 * Archivo de configuración para PRODUCCIÓN
 * Copia este archivo a config.php y ajusta los valores
 */

// Suprimir errores en producción (mostrar solo errores críticos)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// ========================================
// CONFIGURACIÓN DEL DOMINIO - MUY IMPORTANTE
// ========================================
// Cambiar a tu dominio real CON https
define("DOMAIN", "https://tu-dominio.com");

// ========================================
// CONFIGURACIÓN DE BASE DE DATOS
// ========================================
define("HOST_BD", "localhost");      // o 127.0.0.1
define("USER_BD", "tu_usuario_db");
define("PASSWORD_BD", "tu_contraseña_db");
define("NAME_DB", "nombre_base_datos");

// ========================================
// CONFIGURACIÓN DEL SITIO
// ========================================
define("NAME_SITE", "Nombre del Sitio");
define("DESCRIPTION_SLOGAN", "Tu slogan aquí");
define("DESCRIPTION_SITE", "Descripción del sitio");
define("FAVICON", DOMAIN . "/assets/favicon.ico");
define("LOGOSITE", DOMAIN . "/assets/logo.png");
define("COPYRIGHT_DESCRIPTION", "Copyright © 2024 Tu Sitio. Todos los derechos reservados.");
define("MAIL_SITE", "contacto@tu-dominio.com");
define("SEARCH_DESCRIPTION", "");
define("PAGE_DESCRIPTION", "");
define("TITLE_DESCRIPTION", "");
define("SEARCH_HASTAG", "");

// ========================================
// CONFIGURACIÓN DE RABBITMQ
// ========================================
define('host_rabbit_mq', 'localhost');
define('port_rabbit_mq', '5672');
define('user_rabbit_mq', 'guest');
define('password_rabbit_mq', 'guest');
define('vhost_rabbit_mq', '/');

// ========================================
// CONFIGURACIÓN DE REDIS CACHE
// ========================================
define("host_redis_cache", "localhost");
define("port_redis_cache", "6379");
define("scheme_redis_cache", "tcp");

// ========================================
// API EXTERNA (opcional)
// ========================================
define("API_TRANSFER_VIDEO", "");

?>
