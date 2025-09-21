<?php

    session_start();
   // session_destroy();
    require_once 'vendor/autoload.php';
    require_once 'config/config.php';
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
            "scheme" => "tcp",
            "host"   => "127.0.0.1",
            "port"   => 6379,
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
        $smarty->assign('logosite',$config_data->sitio_logo_url);
        $smarty->assign('copyright', $config_data->copyright_descripcion);
        $smarty->assign('favicon',$config_data->favicon_url);
        $smarty->assign('email_sitio',$config_data->email_sitio);
        $smarty->assign('dominio', $config_data->dominio);
        if($config_data->dominio==''){
            $smarty->assign('dominio', DOMAIN);
        }

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
