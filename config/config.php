<?php
   
    //define("DOMAIN","https://943b-152-166-176-57.ngrok.io/edtube");
    //define("DOMAIN","https://ventasrd.com");
    define("DOMAIN","http://localhost/ventasrd");
    #These are the data for the connection of the database 
    define("HOST_BD","localhost");
    define("USER_BD","root");
    define("PASSWORD_BD","");
    define("NAME_DB","VentasRD");
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
    define("TITLE_DESCRIPTION","");-
    #Description for search using hastag
    define("SEARCH_HASTAG","Ventazs rd");

    $conexion = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Aquí puedes ejecutar consultas y realizar otras operaciones con la base de datos
    // Cuando termines, no olvides cerrar la conexión
    $conexion->close();


    


   











?>