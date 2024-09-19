<?php
    require('bootstrap.php');

    //solo cargar un tablero
  

    $smarty->assign('content_config','single_board');
   
    if(isset($_GET['id'])) {
        
        $Board = new Board();   
        $data_board =(array) $Board->cargar_solo_tablero($_GET['id']);
        $multimedias_tableros =$Board->cargar_multimedias_de_tablero($_GET['id'],'asoc');
        $smarty->assign('board',$data_board);
        $smarty->assign('estado',$data_board['estado']);
        $smarty->assign('titulo',$data_board['titulo']);
        $smarty->assign('descripcion',$data_board['descripcion']);
        $smarty->assign('id_tablero',$data_board['id_tablero']);
        $smarty->assign('og_imagen',$dominio."/".$data_board['imagen_tablero']);
        $smarty->assign('usuario',$data_board['usuario']);
        $smarty->assign('foto_usuario',$dominio."/".$data_board['foto_url']);
        $smarty->assign('multimedias_t',$multimedias_tableros);
        $smarty->assign('url_board',"$dominio/controllers/single_board.php?id=$_GET[id]");
        $smarty->display('../template/header.tpl');

    }











?>