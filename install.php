<?php
/**
 * ============================================
 * INSTALADOR DE COMMUTY-ED
 * ============================================
 * Este script configura automáticamente:
 * - Base de datos
 * - Archivo de configuración
 * - Usuario administrador
 * - Permisos de directorios
 * 
 * ELIMINAR ESTE ARCHIVO DESPUÉS DE LA INSTALACIÓN
 */

session_start();

// Evitar acceso si ya está instalado
if (file_exists('config/config.php') && !isset($_GET['force'])) {
    $configContent = file_get_contents('config/config.php');
    if (strpos($configContent, 'APP_INSTALLED') !== false) {
        die('<div style="font-family: Arial; text-align: center; margin-top: 100px;">
            <h1>⚠️ Commuty-ED ya está instalado</h1>
            <p>Si deseas reinstalar, elimina el archivo <code>config/config.php</code></p>
            <a href="index.php">Ir al sitio</a>
        </div>');
    }
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$errors = [];
$success = [];

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Paso 2: Verificar conexión a base de datos
    if (isset($_POST['action']) && $_POST['action'] === 'test_db') {
        $host = $_POST['db_host'];
        $user = $_POST['db_user'];
        $pass = $_POST['db_pass'];
        $name = $_POST['db_name'];
        
        $conn = @new mysqli($host, $user, $pass);
        
        if ($conn->connect_error) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
        } else {
            // Intentar crear la base de datos si no existe
            $conn->query("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            if ($conn->select_db($name)) {
                echo json_encode(['success' => true, 'message' => 'Conexión exitosa']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo crear/seleccionar la base de datos']);
            }
            $conn->close();
        }
        exit;
    }
    
    // Paso 3: Instalar base de datos y configuración
    if (isset($_POST['action']) && $_POST['action'] === 'install') {
        header('Content-Type: application/json');
        
        try {
            $db_host = $_POST['db_host'];
            $db_user = $_POST['db_user'];
            $db_pass = $_POST['db_pass'];
            $db_name = $_POST['db_name'];
            $domain = rtrim($_POST['domain'], '/');
            $site_name = $_POST['site_name'];
            $admin_email = $_POST['admin_email'];
            $admin_user = $_POST['admin_user'];
            $admin_pass = $_POST['admin_pass'];
            
            // Conectar a MySQL
            $conn = new mysqli($db_host, $db_user, $db_pass);
            if ($conn->connect_error) {
                throw new Exception('Error de conexión: ' . $conn->connect_error);
            }
            
            // Crear base de datos
            $conn->query("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $conn->select_db($db_name);
            $conn->set_charset('utf8mb4');
            
            // Ejecutar scripts SQL
            // Usar schema.sql que tiene toda la estructura actualizada y limpia
            $sqlFiles = ['database/schema.sql'];
            
            foreach ($sqlFiles as $file) {
                if (file_exists($file)) {
                    $sql = file_get_contents($file);
                    // Ejecutar múltiples queries
                    $conn->multi_query($sql);
                    // Consumir todos los resultados
                    while ($conn->next_result()) {
                        if ($result = $conn->store_result()) {
                            $result->free();
                        }
                    }
                }
            }
            
            // Crear usuario administrador
            $hashedPass = md5($admin_pass); // El sistema usa MD5
            $stmt = $conn->prepare("INSERT INTO users (nombre, apellido, email, clave, usuario, type_user, estado, foto_url) 
                                    VALUES (?, 'Admin', ?, ?, ?, 'admin', 'activo', 'assets/user_profile.png')
                                    ON DUPLICATE KEY UPDATE clave = ?, type_user = 'admin'");
            $stmt->bind_param('sssss', $admin_user, $admin_email, $hashedPass, $admin_user, $hashedPass);
            $stmt->execute();
            $stmt->close();
            
            // Insertar configuración inicial
            $stmt = $conn->prepare("INSERT INTO configuracion (dominio, nombre_sitio, descripcion_slogan, email_sitio, publicar_sin_revision, verificar_cuenta) 
                                    VALUES (?, ?, 'Bienvenido a tu comunidad', ?, 1, 0)
                                    ON DUPLICATE KEY UPDATE dominio = ?, nombre_sitio = ?");
            $stmt->bind_param('sssss', $domain, $site_name, $admin_email, $domain, $site_name);
            $stmt->execute();
            $stmt->close();
            
            $conn->close();
            
            // Crear archivo de configuración
            $configContent = '<?php
/**
 * Configuración de Commuty-ED
 * Generado automáticamente por el instalador
 * Fecha: ' . date('Y-m-d H:i:s') . '
 */

define("APP_INSTALLED", true);
define("APP_ENV", "production");

// Modo de errores
if (APP_ENV === "production") {
    error_reporting(0);
    ini_set(\'display_errors\', 0);
    ini_set(\'log_errors\', 1);
    ini_set(\'error_log\', __DIR__ . \'/../logs/php_errors.log\');
} else {
    error_reporting(E_ALL);
    ini_set(\'display_errors\', 1);
}

// Dominio
define("DOMAIN", "' . $domain . '");

// Base de datos
define("HOST_BD", "' . $db_host . '");
define("USER_BD", "' . $db_user . '");
define("PASSWORD_BD", "' . addslashes($db_pass) . '");
define("NAME_DB", "' . $db_name . '");

// Información del sitio
define("NAME_SITE", "' . addslashes($site_name) . '");
define("DESCRIPTION_SLOGAN", "Bienvenido a tu comunidad");
define("DESCRIPTION_SITE", "' . addslashes($site_name) . ' - Red social");
define("FAVICON", DOMAIN . "/assets/favicon.ico");
define("LOGOSITE", DOMAIN . "/assets/logo.png");
define("COPYRIGHT_DESCRIPTION", "Copyright © ' . date('Y') . ' ' . addslashes($site_name) . '. All Rights Reserved.");
define("MAIL_SITE", "' . $admin_email . '");
define("SEARCH_DESCRIPTION", "");
define("PAGE_DESCRIPTION", "");
define("TITLE_DESCRIPTION", "");
define("SEARCH_HASTAG", "");

// RabbitMQ (configurar si usas procesamiento de video)
define(\'host_rabbit_mq\', \'localhost\');
define(\'port_rabbit_mq\', \'5672\');
define(\'user_rabbit_mq\', \'guest\');
define(\'password_rabbit_mq\', \'guest\');
define(\'vhost_rabbit_mq\', \'/\');

// Redis Cache (configurar si usas caché)
define("host_redis_cache", "localhost");
define("port_redis_cache", "6379");
define("scheme_redis_cache", "tcp");

// API Transfer Video
define("API_TRANSFER_VIDEO", "");

?>';
            
            if (!file_put_contents('config/config.php', $configContent)) {
                throw new Exception('No se pudo crear el archivo de configuración. Verifica los permisos del directorio config/');
            }
            
            // Crear directorios necesarios
            $dirs = ['uploads', 'videos', 'previa', 'imagenes_tablero', 'compile', 'traking', 'temp_downloads', 'logs'];
            foreach ($dirs as $dir) {
                if (!is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }
                @chmod($dir, 0775);
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Instalación completada exitosamente',
                'redirect' => 'install.php?step=4'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}

// Funciones de verificación
function checkRequirement($name, $check, $required = true) {
    return [
        'name' => $name,
        'status' => $check,
        'required' => $required
    ];
}

function getRequirements() {
    $requirements = [];
    
    // PHP Version
    $requirements[] = checkRequirement(
        'PHP 7.4 o superior',
        version_compare(PHP_VERSION, '7.4.0', '>=')
    );
    
    // Extensiones requeridas
    $extensions = ['mysqli', 'json', 'mbstring', 'session', 'fileinfo'];
    foreach ($extensions as $ext) {
        $requirements[] = checkRequirement(
            "Extensión PHP: $ext",
            extension_loaded($ext)
        );
    }
    
    // Extensiones opcionales
    $optionalExt = ['gd', 'curl', 'xml', 'zip'];
    foreach ($optionalExt as $ext) {
        $requirements[] = checkRequirement(
            "Extensión PHP: $ext (opcional)",
            extension_loaded($ext),
            false
        );
    }
    
    // Permisos de escritura
    $writableDirs = ['config', 'uploads', 'videos', 'previa', 'imagenes_tablero', 'compile', 'traking', 'logs'];
    foreach ($writableDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $requirements[] = checkRequirement(
            "Directorio escribible: $dir/",
            is_writable($dir) || @mkdir($dir, 0775, true)
        );
    }
    
    return $requirements;
}

function allRequirementsMet($requirements) {
    foreach ($requirements as $req) {
        if ($req['required'] && !$req['status']) {
            return false;
        }
    }
    return true;
}

$requirements = getRequirements();
$canProceed = allRequirementsMet($requirements);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador de Commuty-ED</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #009688;
            --primary-dark: #00796b;
            --secondary: #20c997;
            --dark: #1a1c1d;
            --darker: #121314;
        }
        
        body {
            background: linear-gradient(135deg, var(--darker) 0%, var(--dark) 100%);
            min-height: 100vh;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .installer-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .installer-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .installer-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .installer-card {
            background: rgba(45, 52, 54, 0.9);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            gap: 10px;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            position: relative;
        }
        
        .step.active {
            background: var(--primary);
            color: #fff;
        }
        
        .step.completed {
            background: var(--secondary);
            color: #fff;
        }
        
        .step-line {
            width: 60px;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            align-self: center;
        }
        
        .step-line.completed {
            background: var(--secondary);
        }
        
        .requirement-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin-bottom: 10px;
        }
        
        .requirement-item .icon-success {
            color: #51cf66;
        }
        
        .requirement-item .icon-error {
            color: #ff6b6b;
        }
        
        .requirement-item .icon-warning {
            color: #ffd43b;
        }
        
        .form-control, .form-select {
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            padding: 12px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(0, 0, 0, 0.4);
            border-color: var(--secondary);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(32, 201, 151, 0.2);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        .form-label {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .btn-installer {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            border: none;
            color: #fff;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-installer:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(32, 201, 151, 0.3);
            color: #fff;
        }
        
        .btn-installer:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-test {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        
        .btn-test:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        
        .alert-installer {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }
        
        .section-title {
            font-size: 1.1rem;
            color: var(--secondary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .success-animation {
            text-align: center;
            padding: 40px;
        }
        
        .success-icon {
            font-size: 80px;
            color: var(--secondary);
            margin-bottom: 20px;
            animation: bounce 0.5s ease;
        }
        
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        
        .loading-spinner {
            display: none;
        }
        
        .loading-spinner.show {
            display: inline-block;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .input-group-password {
            position: relative;
        }
        
        .input-group-password .form-control {
            padding-right: 45px;
        }
    </style>
</head>
<body>
    <div class="installer-container">
        <div class="installer-header">
            <h1><i class="fa-solid fa-rocket"></i> Commuty-ED</h1>
            <p class="text-muted">Instalador del Sistema</p>
        </div>
        
        <!-- Indicador de pasos -->
        <div class="step-indicator">
            <div class="step <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : ''; ?>">
                <?php echo $step > 1 ? '<i class="fa-solid fa-check"></i>' : '1'; ?>
            </div>
            <div class="step-line <?php echo $step > 1 ? 'completed' : ''; ?>"></div>
            <div class="step <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : ''; ?>">
                <?php echo $step > 2 ? '<i class="fa-solid fa-check"></i>' : '2'; ?>
            </div>
            <div class="step-line <?php echo $step > 2 ? 'completed' : ''; ?>"></div>
            <div class="step <?php echo $step >= 3 ? ($step > 3 ? 'completed' : 'active') : ''; ?>">
                <?php echo $step > 3 ? '<i class="fa-solid fa-check"></i>' : '3'; ?>
            </div>
            <div class="step-line <?php echo $step > 3 ? 'completed' : ''; ?>"></div>
            <div class="step <?php echo $step >= 4 ? 'active' : ''; ?>">4</div>
        </div>
        
        <div class="installer-card">
            
            <?php if ($step === 1): ?>
            <!-- PASO 1: Verificar requisitos -->
            <h3 class="mb-4"><i class="fa-solid fa-clipboard-check"></i> Paso 1: Verificar Requisitos</h3>
            
            <div class="requirements-list">
                <?php foreach ($requirements as $req): ?>
                <div class="requirement-item">
                    <span><?php echo $req['name']; ?></span>
                    <?php if ($req['status']): ?>
                        <i class="fa-solid fa-check-circle icon-success"></i>
                    <?php elseif (!$req['required']): ?>
                        <i class="fa-solid fa-exclamation-circle icon-warning"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-times-circle icon-error"></i>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (!$canProceed): ?>
            <div class="alert alert-danger alert-installer mt-4">
                <i class="fa-solid fa-exclamation-triangle"></i>
                Algunos requisitos no se cumplen. Por favor, corrígelos antes de continuar.
            </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="?step=2" class="btn btn-installer <?php echo !$canProceed ? 'disabled' : ''; ?>">
                    Continuar <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            
            <?php elseif ($step === 2): ?>
            <!-- PASO 2: Configurar base de datos -->
            <h3 class="mb-4"><i class="fa-solid fa-database"></i> Paso 2: Base de Datos</h3>
            
            <div id="db-alert"></div>
            
            <div class="mb-3">
                <label class="form-label">Host de MySQL</label>
                <input type="text" class="form-control" id="db_host" value="localhost" placeholder="localhost">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Usuario de MySQL</label>
                <input type="text" class="form-control" id="db_user" placeholder="root">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Contraseña de MySQL</label>
                <div class="input-group-password">
                    <input type="password" class="form-control" id="db_pass" placeholder="••••••••">
                    <span class="password-toggle" onclick="togglePassword('db_pass')">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Nombre de la Base de Datos</label>
                <input type="text" class="form-control" id="db_name" value="edcommunity" placeholder="edcommunity">
                <small class="text-muted">Se creará automáticamente si no existe</small>
            </div>
            
            <div class="d-flex gap-3">
                <button class="btn btn-test" onclick="testDatabase()">
                    <i class="fa-solid fa-plug"></i> Probar Conexión
                </button>
                <button class="btn btn-installer" id="btn-next-step2" onclick="goToStep3()" disabled>
                    Continuar <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            
            <?php elseif ($step === 3): ?>
            <!-- PASO 3: Configurar sitio y admin -->
            <h3 class="mb-4"><i class="fa-solid fa-cog"></i> Paso 3: Configuración</h3>
            
            <div id="install-alert"></div>
            
            <!-- Datos del sitio -->
            <h5 class="section-title"><i class="fa-solid fa-globe"></i> Información del Sitio</h5>
            
            <div class="mb-3">
                <label class="form-label">URL del Sitio</label>
                <input type="url" class="form-control" id="domain" 
                       value="<?php echo 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']); ?>" 
                       placeholder="https://tudominio.com">
                <small class="text-muted">Sin barra final (/)</small>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Nombre del Sitio</label>
                <input type="text" class="form-control" id="site_name" placeholder="Mi Comunidad">
            </div>
            
            <!-- Datos del administrador -->
            <h5 class="section-title"><i class="fa-solid fa-user-shield"></i> Usuario Administrador</h5>
            
            <div class="mb-3">
                <label class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="admin_user" placeholder="admin">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email del Administrador</label>
                <input type="email" class="form-control" id="admin_email" placeholder="admin@tudominio.com">
            </div>
            
            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <div class="input-group-password">
                    <input type="password" class="form-control" id="admin_pass" placeholder="••••••••">
                    <span class="password-toggle" onclick="togglePassword('admin_pass')">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </div>
                <small class="text-muted">Mínimo 6 caracteres</small>
            </div>
            
            <!-- Campos ocultos con datos de BD -->
            <input type="hidden" id="db_host" value="<?php echo $_GET['db_host'] ?? 'localhost'; ?>">
            <input type="hidden" id="db_user" value="<?php echo $_GET['db_user'] ?? ''; ?>">
            <input type="hidden" id="db_pass" value="<?php echo $_GET['db_pass'] ?? ''; ?>">
            <input type="hidden" id="db_name" value="<?php echo $_GET['db_name'] ?? 'edcommunity'; ?>">
            
            <div class="text-center">
                <button class="btn btn-installer" onclick="runInstall()" id="btn-install">
                    <span class="loading-spinner"><i class="fa-solid fa-spinner fa-spin"></i></span>
                    <span class="btn-text"><i class="fa-solid fa-rocket"></i> Instalar Commuty-ED</span>
                </button>
            </div>
            
            <?php elseif ($step === 4): ?>
            <!-- PASO 4: Instalación completada -->
            <div class="success-animation">
                <div class="success-icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <h2 class="mb-3">¡Instalación Completada!</h2>
                <p class="text-muted mb-4">Commuty-ED se ha instalado correctamente.</p>
                
                <div class="alert alert-warning alert-installer mb-4">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    <strong>Importante:</strong> Por seguridad, elimina el archivo <code>install.php</code>
                </div>
                
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="index.php" class="btn btn-installer">
                        <i class="fa-solid fa-home"></i> Ir al Sitio
                    </a>
                    <a href="backcoffe.php" class="btn btn-test">
                        <i class="fa-solid fa-cog"></i> Panel Admin
                    </a>
                </div>
            </div>
            
            <?php endif; ?>
            
        </div>
        
        <p class="text-center text-muted mt-4">
            <small>Commuty-ED © <?php echo date('Y'); ?> - Versión 1.0</small>
        </p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let dbVerified = false;
        
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        function showAlert(containerId, type, message) {
            const container = document.getElementById(containerId);
            container.innerHTML = `
                <div class="alert alert-${type} alert-installer">
                    <i class="fa-solid fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                    ${message}
                </div>
            `;
        }
        
        function testDatabase() {
            const formData = new FormData();
            formData.append('action', 'test_db');
            formData.append('db_host', document.getElementById('db_host').value);
            formData.append('db_user', document.getElementById('db_user').value);
            formData.append('db_pass', document.getElementById('db_pass').value);
            formData.append('db_name', document.getElementById('db_name').value);
            
            axios.post('install.php', formData)
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        showAlert('db-alert', 'success', data.message);
                        document.getElementById('btn-next-step2').disabled = false;
                        dbVerified = true;
                    } else {
                        showAlert('db-alert', 'danger', data.message);
                        document.getElementById('btn-next-step2').disabled = true;
                    }
                })
                .catch(error => {
                    showAlert('db-alert', 'danger', 'Error al probar la conexión');
                });
        }
        
        function goToStep3() {
            if (!dbVerified) {
                showAlert('db-alert', 'danger', 'Primero debes probar la conexión');
                return;
            }
            
            const params = new URLSearchParams({
                step: 3,
                db_host: document.getElementById('db_host').value,
                db_user: document.getElementById('db_user').value,
                db_pass: document.getElementById('db_pass').value,
                db_name: document.getElementById('db_name').value
            });
            
            window.location.href = 'install.php?' + params.toString();
        }
        
        function runInstall() {
            const domain = document.getElementById('domain').value.trim();
            const siteName = document.getElementById('site_name').value.trim();
            const adminUser = document.getElementById('admin_user').value.trim();
            const adminEmail = document.getElementById('admin_email').value.trim();
            const adminPass = document.getElementById('admin_pass').value;
            
            // Validaciones
            if (!domain || !siteName || !adminUser || !adminEmail || !adminPass) {
                showAlert('install-alert', 'danger', 'Todos los campos son obligatorios');
                return;
            }
            
            if (adminPass.length < 6) {
                showAlert('install-alert', 'danger', 'La contraseña debe tener al menos 6 caracteres');
                return;
            }
            
            // Mostrar loading
            const btn = document.getElementById('btn-install');
            btn.disabled = true;
            btn.querySelector('.loading-spinner').classList.add('show');
            btn.querySelector('.btn-text').textContent = ' Instalando...';
            
            const formData = new FormData();
            formData.append('action', 'install');
            formData.append('db_host', document.getElementById('db_host').value);
            formData.append('db_user', document.getElementById('db_user').value);
            formData.append('db_pass', document.getElementById('db_pass').value);
            formData.append('db_name', document.getElementById('db_name').value);
            formData.append('domain', domain);
            formData.append('site_name', siteName);
            formData.append('admin_user', adminUser);
            formData.append('admin_email', adminEmail);
            formData.append('admin_pass', adminPass);
            
            axios.post('install.php', formData)
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        showAlert('install-alert', 'danger', data.message);
                        btn.disabled = false;
                        btn.querySelector('.loading-spinner').classList.remove('show');
                        btn.querySelector('.btn-text').innerHTML = '<i class="fa-solid fa-rocket"></i> Instalar Commuty-ED';
                    }
                })
                .catch(error => {
                    showAlert('install-alert', 'danger', 'Error durante la instalación: ' + error.message);
                    btn.disabled = false;
                    btn.querySelector('.loading-spinner').classList.remove('show');
                    btn.querySelector('.btn-text').innerHTML = '<i class="fa-solid fa-rocket"></i> Instalar Commuty-ED';
                });
        }
    </script>
</body>
</html>
