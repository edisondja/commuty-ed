<?php
   
   require('bootstrap.php');
   


    if(isset($_SESSION['id_user'])){

        if($_SESSION['type_user']=='admin'){

            //Si el usuario es admin solo asi puede entrar al BackOffice
              
            if(isset($_GET['option'])){

                switch ($_GET['option']) {
                    /*
                        Este switch es usado para cambiar de contexto en el backoffice
                        luego cada opcion llamara el componente adecuado en la plantilla 
                        backoffice.tpl, si se desea agregar mas funcionales en el menu
                        se debe seguir el mismpo procedimiento
                    */
                    case 'users':
                        /*Si se envia  users en opcion se estara trabajando con usuarios*/
                        $smarty->assign('option','usuarios');
                    break; 

                    case 'boards':
                        /*Si se envia  users en opcion se estara trabajando con usuarios*/
                            $smarty->assign('option','publicaciones');
                    break;

                    case 'settings':
                        /*Congiruaciones del sitio */
                        $smarty->assign('option','configuraciones');
                
                    break;

                    case 'mails':
                       
                       $smarty->assign('option','envar_correos');

                    break;

                    case 'banners':

                        $smarty->assign('option','adm_banners');
                        
                    break;

                    case 'reports':

                        $smarty->assign('option','reportes');

                    break;

                    case 'rabbitmq':

                        $smarty->assign('option','monitoreo_rabbitmq');

                    break;

                    case 'estilos':

                        $smarty->assign('option','estilos');

                    break;

                    default:
                    
                     $smarty->assign('option','usuarios');
                            
                    break;


                } 
            }else{

                $smarty->assign('option','usuarios');

            }
            $smarty->assign('titulo',"Back Coffe ".NAME_SITE);
            $smarty->assign('descripcion',NAME_SITE." Admin users and Contents");
            $smarty->assign('og_imagen',LOGOSITE);
            $smarty->assign('url_board',"$dominio");
            $smarty->assign('content_config','backoffice');         
            $smarty->assign('color','#e16565;padding:2px;');
            $smarty->display('../template/header.tpl');

        }else{

              header('location:index.php');

        }
    }

?>