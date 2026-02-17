<?php
   
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    define("DOMAIN","http://localhost/commuty-ed");
    #These are the data for the connection of the database 
    define("HOST_BD","localhost");
    define("USER_BD","root");
    define("PASSWORD_BD","");
    define("NAME_DB","edcommunity");
    #Config with scope complete used for the site tube
    define("NAME_SITE","Ventas RD");
    define("DESCRIPTION_SLOGAN","El mejor lugar para comprar tus articulos");
    define("DESCRIPTION_SITE","Nunca vender fue tan facil como en ventasrd");
    #Favicon for the site very important 
    define("FAVICON",DOMAIN."/assets/favicon.ico");
    #The dimesion for the site logo is 230px of width and 50px of height
    define("LOGOSITE",DOMAIN."/assets/ventasRD.png");
    define("COPYRIGHT_DESCRIPTION","Copyright © 2024 VentasRD. All Righ-ts Reserved.");
    define("MAIL_SITE","jhon@ventasrd.com");
    define("SEARCH_DESCRIPTION","");
    define("PAGE_DESCRIPTION","");
    #The title description is the tag used for the browser for example..
    define("TITLE_DESCRIPTION","");
    #Conexion Rabbit MQ
    define('host_rabbit_mq','localhost');
    define('port_rabbit_mq','5672');
    define('user_rabbit_mq','guest');
    define('password_rabbit_mq','guest');
    define('vhost_rabbit_mq','/');
    #Conexion Redis Cache 
    define("host_redis_cache","localhost");
    define("port_redis_cache","6379");
    define("scheme_redis_cache","tcp");

    #Description for search using hastag
    define("SEARCH_HASTAG","Ventazs rd");

    # Consumer FFmpeg: límite de hilos para reducir uso de RAM (2–4 recomendado para VPS 8GB)
    if (!defined('FFMPEG_THREADS')) {
        define('FFMPEG_THREADS', 2);
    }
    # Prioridad nice para FFmpeg (5–10 = no acaparar CPU; menor = más prioridad)
    if (!defined('FFMPEG_NICE')) {
        define('FFMPEG_NICE', 5);
    }

    #api_tranfer_video 
    define("API_TRANSFER_VIDEO","https://videosegg.com/download_video.php");
    /*El retorno debe de ser asi 
        {
            "status":"ok",
            "url_video":"https://tusitio.com/ruta_del_video.mp4"
        }   

        parametro ruta=url del video a transferir metodo get
    */

?>