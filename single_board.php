<?php
    require('bootstrap.php');
    require('models/View.php');
    require('models/Ads.php');
    //solo cargar un tablero

    $smarty->assign('content_config','single_board');
   
    $cached_key="single_post:";

    if(isset($_GET['id'])) {
        
        $cached_key.="cap/".$_GET['id'];
        $Board = new Board();   


      try{
          if($cached = $redis->get($cached_key)){

            //Carga la cache del tablero
              $data_board = json_decode($cached,true);
          }else{

            $data_board =(array) $Board->cargar_solo_tablero($_GET['id']);
            $redis->setex($cached_key,300,json_encode($data_board));

          }
      
        }catch(Exception $e){

            //Carga publicacion de manera normal.
            $data_board =(array) $Board->cargar_solo_tablero($_GET['id']);

        }
     
        

        $Verificar_like = new Like();
        $Verificar_like->id_tablero = (int)$_GET['id'];
        $Verificar_like->id_usuario = (int)$id_user;


          /*
            Se devuelve false es por que existe un like registro con el
            usuario de la sesion en esta publicacion, con esta referencia
            marcamos el like a encendido por el template de smarty.
          */
        
        $View = new View();
        $View->id_tablero = $_GET['id'];
        $View->id_usuario = $id_user;
        $View->guardar_view();

        $cached_key.='/likes:';

        $ads = new Ads();
        $ads->estado = $ads->enable();
        $ads->posicion = 1;
        $smarty->assign('ads_1',$ads->cargar_ads_pos());
        $ads->posicion = 2;
        $smarty->assign('ads_2',$ads->cargar_ads_pos());


        $smarty->assign('likes',$Verificar_like->contar_lk('asoc'));
      

        $smarty->assign('like_login_user',$Verificar_like->verificar_mi_like());
        $multimedias_tableros =$Board->cargar_multimedias_de_tablero($_GET['id'],'asoc');

        $cached_key.='/views:';
        //Edejesusa 16/09/2025
        try{

          if($cached = $redis->get($cached_key)){

            $smarty->assign('total_views',json_decode($cached));

          }else{

              $smarty->assign('total_views',$View->contar_views());

              $redis->setext($cached_key,300,json_encode($View->contar_views()));

          }

       }catch(Exception $e){

          //Si falla redis se cargan los views de forma directo a la base de datos.  
          $smarty->assign('total_views',$View->contar_views());

       }

        $smarty->assign('board',$data_board);
        $smarty->assign('estado',$data_board['estado']);
        $smarty->assign('titulo',$data_board['titulo']);
        $smarty->assign('descripcion',$data_board['descripcion']);
        $smarty->assign('id_tablero',$data_board['id_tablero']);
        $smarty->assign('og_imagen',$data_board['imagen_tablero']);
        $smarty->assign('usuario',$data_board['usuario']);
        $smarty->assign('foto_usuario',$dominio."/".$data_board['foto_url']);
        $smarty->assign('multimedias_t',$multimedias_tableros);
        $smarty->assign('url_board',"$dominio/controllers/single_board.php?id=$_GET[id]");
        $smarty->display('../template/header.tpl');

    }











?>