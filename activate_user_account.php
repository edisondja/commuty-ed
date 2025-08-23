<?php
    require('bootstrap.php');

    //solo cargar un tablero
  

    $smarty->assign('content_config','activate_user_account');

    $smarty->assign('titulo',"Registrer user ".NAME_SITE);
    $smarty->assign('name_site',NAME_SITE);
    $smarty->assign('descripcion',NAME_SITE." plataform free for alls share your contents");
    $smarty->assign('og_imagen',LOGOSITE);
    $smarty->assign('url_board',"$dominio");
    $smarty->assign('titulo',"Activar acuenta");
    $smarty->assign('descripcion',"Bienvenido a  $name_site");
    if( isset($_GET['username'])){

        $smarty->assign('usuario',$_GET['username']);
        /*
            Llamar metodo de activar la cuenta 

        */
        $username = new User();
        $data_u=$username->get_id_from_user($_GET['username'],'asoc');
        $username->id_user = $data_u->id_user;
        $username->ActivarUsuario();

    }else{

        $smarty->assign('usuario','username');

    }


    $smarty->display('../template/header.tpl');




