<?php
    require('bootstrap.php');


    //cargar_tableros($id_usuario='general',$opcion='json')

    $smarty->assign('content_config','profile');
    $smarty->assign('titulo',"Profile by user ".NAME_SITE);
    $smarty->assign('descripcion',NAME_SITE." plataform free for alls share your contents");
    $smarty->assign('og_imagen',LOGOSITE);
    
    $smarty->assign('url_board',"$dominio/");


    if( isset($_GET['user'])) {

        $profile = new User();
        $data_user = $profile->LoadProfileUser($_GET['user']);
        $get_user_id =  $profile->get_id_from_user($_GET['user']);
        $get_user_id = $get_user_id->id_user;
        $boards = new Board();
        //print_r( $data_user);

        if(isset($_GET['user']) && isset($_GET['leaf']) ){

            $data = $boards->paginar_tableros($_GET['leaf'],'paginar_usuario',$get_user_id);

        }else{

            $data = $boards->cargar_tablerosx($get_user_id,'asoc');

        }

        /*
            Aqui paginador scroll es para configurar
            la plantilla para que pueda navegar entre
            los resultados del perfil
        
        */
        $smarty->assign('paginador_scroll','user_profile');
        $smarty->assign('boards',$data);
        $smarty->assign('data_profile',$data_user);
        $smarty->display('template/header.tpl');

    }



?>