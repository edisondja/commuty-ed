<?php
    session_start();
    require("../vendor/smarty/smarty/libs/Smarty.class.php");
    require('../logic.php');
    

    
    $libs = include("libs/connect_cdn.php");
    $libs_string='';
    foreach($libs as $lib){

        $libs_string.=$lib."\r";

    }


    $smarty = new Smarty();
    $smarty->setTemplateDir('../template/');
    $smarty->setConfigDir('../config');
    $smarty->setCompileDir('../compile');
    $smarty->setCacheDir('../cache');
    $smarty->assign('name',NAME_SITE);
    $smarty->assign('logosite',LOGOSITE);
    $smarty->assign('dominio',$dominio);
    $smarty->assign('copyright',COPYRIGHT_DESCRIPTION);


    if(isset($_SESSION['id_user'])){

        $smarty->assign('id_user',$_SESSION['id_user']);
        $smarty->assign('foto_perfil',$dominio."/".$_SESSION['foto_url']);
        $smarty->assign('user_session',$_SESSION['usuario']);

       
    }else{

        $smarty->assign('id_user','');
        $smarty->assign('foto_perfil','');
        $smarty->assign('user_session','');

    }
    /*
        load cdns
    */
 
    $smarty->assign('libs_cdn',$libs_string);

?>