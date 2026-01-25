<?php

    session_start();
   // session_destroy();
    require_once 'vendor/autoload.php';
    require_once 'config/config.php';
    
    // ============================================
    // Auto-migración de base de datos
    // Verifica y agrega columnas faltantes
    // ============================================
    function runAutoMigrations() {
        static $migrationRan = false;
        if ($migrationRan) return;
        $migrationRan = true;
        
        try {
            // Usar 127.0.0.1 en lugar de localhost para evitar problemas de socket
            $host = (HOST_BD === 'localhost') ? '127.0.0.1' : HOST_BD;
            $conn = @new mysqli($host, USER_BD, PASSWORD_BD, NAME_DB);
            if ($conn->connect_error) {
                // Intentar con localhost como fallback
                $conn = @new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                if ($conn->connect_error) return;
            }
            
            // Verificar si la columna publicar_sin_revision existe
            $result = $conn->query("SHOW COLUMNS FROM configuracion LIKE 'publicar_sin_revision'");
            if ($result && $result->num_rows == 0) {
                // Agregar columnas faltantes
                $conn->query("ALTER TABLE configuracion ADD COLUMN publicar_sin_revision tinyint(1) DEFAULT 1");
                $conn->query("ALTER TABLE configuracion ADD COLUMN verificar_cuenta tinyint(1) DEFAULT 0");
                $conn->query("ALTER TABLE configuracion ADD COLUMN rabbit_mq tinyint(1) DEFAULT 0");
                $conn->query("ALTER TABLE configuracion ADD COLUMN ffmpeg tinyint(1) DEFAULT 0");
                $conn->query("ALTER TABLE configuracion ADD COLUMN redis_cache tinyint(1) DEFAULT 0");
            }
            
            // Verificar estilos_json
            $result = $conn->query("SHOW COLUMNS FROM configuracion LIKE 'estilos_json'");
            if ($result && $result->num_rows == 0) {
                $conn->query("ALTER TABLE configuracion ADD COLUMN estilos_json text DEFAULT NULL");
            }
            
            // Verificar id_reproductor en tableros
            $result = $conn->query("SHOW COLUMNS FROM tableros LIKE 'id_reproductor'");
            if ($result && $result->num_rows == 0) {
                $conn->query("ALTER TABLE tableros ADD COLUMN id_reproductor int(11) DEFAULT NULL");
            }
            
            // Crear tabla ratings si no existe
            $conn->query("CREATE TABLE IF NOT EXISTS ratings (
                id_rating int(11) NOT NULL AUTO_INCREMENT,
                id_tablero int(11) NOT NULL,
                id_usuario int(11) NOT NULL,
                rating tinyint(1) NOT NULL,
                fecha_creacion datetime DEFAULT current_timestamp(),
                PRIMARY KEY (id_rating),
                UNIQUE KEY unique_user_board_rating (id_tablero, id_usuario)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            
            // Crear tabla reproductores_vast si no existe
            $conn->query("CREATE TABLE IF NOT EXISTS reproductores_vast (
                id_reproductor int(11) NOT NULL AUTO_INCREMENT,
                nombre varchar(100) NOT NULL,
                descripcion text DEFAULT NULL,
                vast_url text DEFAULT NULL,
                vast_url_mid text DEFAULT NULL,
                vast_url_post text DEFAULT NULL,
                skip_delay int(11) DEFAULT 5,
                mid_roll_time int(11) DEFAULT 30,
                activo tinyint(1) DEFAULT 1,
                es_default tinyint(1) DEFAULT 0,
                fecha_creacion datetime DEFAULT current_timestamp(),
                fecha_actualizacion datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (id_reproductor)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            
            $conn->close();
        } catch (Exception $e) {
            // Silenciar errores de migración
        }
    }
    
    // Ejecutar migraciones automáticas
    runAutoMigrations();
    
    // Funciones de compatibilidad para PHP 7.2 y PHP 8+
    if (!function_exists('php_compat_fetch_object')) {
        function php_compat_fetch_object($result) {
            if ($result instanceof mysqli_result) {
                if (PHP_VERSION_ID >= 80000) {
                    return $result->fetch_object();
                } else {
                    return mysqli_fetch_object($result);
                }
            }
            return false;
        }
    }
    
    if (!function_exists('php_compat_result_to_array')) {
        function php_compat_result_to_array($result) {
            $data = [];
            if ($result instanceof mysqli_result) {
                if (PHP_VERSION_ID >= 80000) {
                    foreach ($result as $row) {
                        $data[] = $row;
                    }
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                }
            }
            return $data;
        }
    }
    require_once 'models/User.php';
    require_once 'models/Board.php';
    require_once 'models/Config.php';
    require_once 'models/Mail.php';
    require_once 'models/Notificacion.php';
    require_once 'models/Like.php';
    
    $dominio = DOMAIN;
    $libs = include 'libs/connect_cdn.php';
    $id_user=0;

     // Inicializar Redis
    try {
        $redis = new Predis\Client([
            "scheme" => scheme_redis_cache,
            "host"   => host_redis_cache,
            "port"   => port_redis_cache,
        ]);
        $redisAvailable = true;
        
    } catch (Exception $e) {
        $redisAvailable = false; // Redis no disponible, seguimos con BD
    }
   
    /*
        load cdns
    */
    $libs_string = '';
    foreach ($libs as $lib) {
        $libs_string .= $lib."\r";
    }

    $smarty = new Smarty();
    $smarty->setTemplateDir('template');
  //  $smarty->debugging = true;
//    $smarty->caching = true;
    $smarty->setConfigDir('config');
    $smarty->assign('libs_cdn', $libs_string);
    $smarty->setCompileDir('compile');
    $smarty->setCacheDir('cache');
    $smarty->assign('api_transfer_video', API_TRANSFER_VIDEO);
    //Verificar si existe cofinguracion en la base de datos 

    $configuracion = new Config();

    if($configuracion->VerificarConfiguracion()>0){
        /*
            Si existe una configuracion registrada en la base de 
            datos se cargaran en lugar de tomarse desde el archivo
            de cofiguracion estatico /config/congfig.php.
        */
        $config_data = $configuracion->Cargar_configuracion('asoc');
        $name_site = $config_data->nombre_sitio;

        $smarty->assign('name',$config_data->nombre_sitio);
        
        // Asegurar que logosite no tenga URL duplicada
        $logosite = $config_data->sitio_logo_url;
        if (!empty($logosite) && strpos($logosite, 'http') === 0) {
            // Si ya tiene http, usar tal cual
            $smarty->assign('logosite', $logosite);
        } else {
            // Si es relativa, agregar dominio
            $dominio_config = !empty($config_data->dominio) ? $config_data->dominio : DOMAIN;
            $smarty->assign('logosite', $dominio_config . '/' . ltrim($logosite, '/'));
        }
        
        $smarty->assign('copyright', $config_data->copyright_descripcion);
        
        // Asegurar que favicon esté definido
        $favicon_url = !empty($config_data->favicon_url) ? $config_data->favicon_url : 'assets/favicon.ico';
        $smarty->assign('favicon', $favicon_url);
        
        $smarty->assign('email_sitio',$config_data->email_sitio);
        
        // Asegurar que dominio esté definido
        $dominio_final = !empty($config_data->dominio) ? $config_data->dominio : DOMAIN;
        $smarty->assign('dominio', $dominio_final);
        
        // Cargar estilos personalizados si existen
        $estilos = [];
        if (isset($config_data->estilos_json) && !empty($config_data->estilos_json)) {
            $estilos = json_decode($config_data->estilos_json, true);
        }
        
        // Si no hay estilos, usar valores por defecto
        if (empty($estilos)) {
            $estilos = [
                'color_primario' => '#20c997',
                'color_secundario' => '#09b9e1',
                'color_fondo' => '#1a1c1d',
                'color_texto' => '#cfd8dc',
                'color_enlace' => '#20c997',
                'color_enlace_hover' => '#17a085',
                'color_boton' => '#20c997',
                'color_boton_hover' => '#17a085',
                'color_tarjeta' => '#2d2d2d',
                'color_borde' => '#444',
                'color_header' => '#1a1a1a'
            ];
        }
        
        $smarty->assign('estilos', $estilos);

    }else{ 

        /*
            Cargando configuracion desde archivo 
            estatico.
        */
        $name_site = NAME_SITE;
        $smarty->assign('name', $name_site);
        $smarty->assign('logosite', LOGOSITE);
        $smarty->assign('copyright', COPYRIGHT_DESCRIPTION);
        $smarty->assign('dominio', DOMAIN);
        $smarty->assign('favicon', FAVICON);
        
        // Estilos por defecto cuando no hay configuración en BD
        $estilos = [
            'color_primario' => '#20c997',
            'color_secundario' => '#09b9e1',
            'color_fondo' => '#1a1c1d',
            'color_texto' => '#cfd8dc',
            'color_enlace' => '#20c997',
            'color_enlace_hover' => '#17a085',
            'color_boton' => '#20c997',
            'color_boton_hover' => '#17a085',
            'color_tarjeta' => '#2d2d2d',
            'color_borde' => '#444',
            'color_header' => '#1a1a1a'
        ];
        $smarty->assign('estilos', $estilos);

    }


    if (isset($_SESSION['id_user'])) {

        //Cargando las notifiaciones de este usuario
        $notifiaciones = new Notificacion();
        $data = $notifiaciones->cargar_mis_notificaciones($_SESSION['id_user']);
        $smarty->assign('notificaciones',$data);
        $smarty->assign('cantidad_notificacion',count($data));
        
        $id_user = $_SESSION['id_user'];
        $smarty->assign('id_user', $_SESSION['id_user']);
        $smarty->assign('foto_perfil', $dominio.'/'.$_SESSION['foto_url']);
        $smarty->assign('user_session', $_SESSION['usuario']);
        $smarty->assign('type_user', $_SESSION['type_user']);
        $smarty->assign('nombre',$_SESSION['nombre']);
        $smarty->assign('apellido',$_SESSION['apellido']);
        
        $Board = new Board();
        $Board->id_usuario = $_SESSION['id_user'];

        $Like = new Like();
        $Like->id_usuario =$_SESSION['id_user'];
        $smarty->assign('cantidad_tableros_usuario',$Board->contar_tableros_usuario());
        $smarty->assign('cantidad_tableros_likes',$Like->cargar_likes_board_usuario());


    } else {
        $smarty->assign('cantidad_tableros_usuario','');
        $smarty->assign('cantidad_tableros_likes','');
        $smarty->assign('type_user', '');
        $smarty->assign('id_user', '');
        $smarty->assign('foto_perfil', '');
        $smarty->assign('user_session', '');
        $smarty->assign('nombre','');
        $smarty->assign('apellido','');
    }
