<?php
/**
 * ARCHIVO DE CONFIGURACIÓN DE EJEMPLO
 * ====================================
 * 1. Copia este archivo como config.php
 * 2. Modifica los valores según tu entorno
 * 
 * IMPORTANTE: Nunca subas config.php a git
 */

// ============================================
// MODO DE DESARROLLO/PRODUCCIÓN
// ============================================
define("APP_ENV", "production"); // development | production

// En producción, ocultar errores
if (APP_ENV === "production") {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/php_errors.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

// ============================================
// DOMINIO DEL SITIO
// ============================================
define("DOMAIN", "https://tudominio.com"); // Sin barra final

// ============================================
// BASE DE DATOS MySQL
// ============================================
define("HOST_BD", "localhost");       // En Ubuntu: localhost o 127.0.0.1
define("USER_BD", "tu_usuario_db");   // Usuario de MySQL
define("PASSWORD_BD", "tu_password"); // Contraseña de MySQL
define("NAME_DB", "edcommunity");     // Nombre de la base de datos

// ============================================
// INFORMACIÓN DEL SITIO
// ============================================
define("NAME_SITE", "Nombre del Sitio");
define("DESCRIPTION_SLOGAN", "Tu slogan aquí");
define("DESCRIPTION_SITE", "Descripción para SEO");
define("FAVICON", DOMAIN . "/assets/favicon.ico");
define("LOGOSITE", DOMAIN . "/assets/logo.png");
define("COPYRIGHT_DESCRIPTION", "Copyright © 2024 Tu Empresa. All Rights Reserved.");
define("MAIL_SITE", "contacto@tudominio.com");
define("SEARCH_DESCRIPTION", "");
define("PAGE_DESCRIPTION", "");
define("TITLE_DESCRIPTION", "");
define("SEARCH_HASTAG", "");

// ============================================
// RABBITMQ (Opcional - para procesamiento de video)
// ============================================
define('host_rabbit_mq', 'localhost');
define('port_rabbit_mq', '5672');
define('user_rabbit_mq', 'guest');        // Cambiar en producción
define('password_rabbit_mq', 'guest');    // Cambiar en producción
define('vhost_rabbit_mq', '/');

// ============================================
// REDIS CACHE (Opcional - para caché)
// ============================================
define("host_redis_cache", "localhost");
define("port_redis_cache", "6379");
define("scheme_redis_cache", "tcp");

// ============================================
// API EXTERNA (Opcional)
// ============================================
define("API_TRANSFER_VIDEO", "https://tu-api.com/download_video.php");

?>
