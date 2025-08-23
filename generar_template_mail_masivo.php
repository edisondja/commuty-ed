<?php
  require('bootstrap.php');

    // Asignar variables a Smarty
    $mensaje = $_POST['mensaje'];

    $smarty->assign([
        'mensaje'=>$mensaje,
        'dominio' => $dominio,
        'nombre_sitio' => NAME_SITE,
        'logo' => LOGOSITE
    ]);

    $smarty->display('mail_masivo.tpl');








?>