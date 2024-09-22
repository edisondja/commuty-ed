<?php
  require('bootstrap.php');

    // Asignar variables a Smarty
    $usuario = $_POST['usuario'];
    $contacto = $_POST['contacto'];

    $smarty->assign([
        'dominio' => $dominio,
        'empresa' => NAME_SITE,
        'usuario' => $usuario,
        'contacto'=> $contacto,
        'logo' => LOGOSITE
    ]);

    $smarty->display('mail_registro.tpl');








?>