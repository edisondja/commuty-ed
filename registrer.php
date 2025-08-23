   <?php
   
   require('bootstrap.php');
   


    $smarty->assign('titulo',"Registrer user ".NAME_SITE);
    $smarty->assign('descripcion',NAME_SITE." plataform free for alls share your contents");
    $smarty->assign('og_imagen',LOGOSITE);
    $smarty->assign('url_board',"$dominio");
    $smarty->assign('nombre',NAME_SITE);
    $smarty->assign('content_config','registrer');
    $smarty->assign('color','#e16565;padding:2px;');
    $smarty->display('../template/header.tpl');



?>