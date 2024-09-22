<?php

  require '../config/config.php';
  require '../models/User.php';
  require '../models/Board.php'; 
  require '../models/Config.php';
  require '../models/Coment.php';
  require '../models/Mail.php';

  require 'Core.php';
 // require '../modeles/Mail.php';

 //$_POST = json_decode(file_get_contents("php://input"),true);

  if (isset($_POST['action'])) {
      $action = $_POST['action'];
  } else {
      $action = $_GET['action'];
  }

       switch ($action) {

        case 'save_post':

              $guardar = new Coment();
              $guardar->id_post = $_POST['id_board'];
              $guardar->id_user = $_POST['id_user'];
              $guardar->comentario = $_POST['text'];
              $guardar->tipo_post = $_POST['type_post'];
              $guardar->data_og = $_POST['data_og'];
              $guardar->guardar_comentario();

        break;


        case 'verify_user_exist':

            $username = $_POST['username'];
            $user = new User();
            $user->usuario = $username;
            $user->ExistUser('print');

        break;


        case 'verify_email_exist':

            $email = $_POST['email'];
            $user = new User();
            $user->email = $email;
            $user->ExistEmailUser('print');

        break;



        case 'search_users':
            $contex = $_GET['context'];
            $config = $_GET['config'];
            $user = new User();
            $user->BuscarUsuarios($contex, $config);

        break;

        case 'config_site_text':
            $config = new Config();
            $imagen_actual =  $config->Cargar_configuracion('asoc');
            $config->sitio_logo_url =$imagen_actual->sitio_logo_url;
            $config->favicon_url = $imagen_actual->favicon_url;
        
            #Verificando si se enviaron multimedias
               if(isset($_FILES['logo_sitio']) && isset($_FILES['favicon_sitio']) ){

                        $config->DetectarMultimedias($_FILES['logo_sitio'], $_FILES['favicon_sitio']);
                    
                    }else if(isset($_FILES['logo_sitio']) && !isset($_FILES['favicon_sitio']) ){

                        $config->DetectarMultimedias($_FILES['logo_sitio'],null);
                        
                    }else if(!isset($_FILES['logo_sitio']) && isset($_FILES['favicon_sitio'])){

                        $config->DetectarMultimedias(null,$_FILES['favicon_sitio']);

                }
                $config->dominio = $_POST['dominio'];
                $config->nombre_sitio = $_POST['nombre_sitio'];
                $config->descripcion_slogan = $_POST['descripcion_slogan'];
                $config->descripcion_sitio = $_POST['descripcion_sitio'];
                $config->copyright_descripcion = $_POST['copyright_descripcion'];
                $config->email_sitio = $_POST['email_sitio'];
                $config->busqueda_descripcion = $_POST['busqueda_descripcion'];
                $config->pagina_descripcion = $_POST['pagina_descripcion'];
                $config->titulo_descripcion = $_POST['titulo_descripcion'];
                $config->busqueda_hastag = $_POST['busqueda_hastag'];
                $config->email_remitente = $_POST['email_remitente'];
                $config->nombre_remitente = $_POST['nombre_remitente'];
                $config->servidor_smtp = $_POST['servidor_smtp'];
                $config->puerto_smtp = $_POST['puerto_smtp'];
                $config->usuario_smtp = $_POST['usuario_smtp'];
                $config->clave_smtp =$_POST['clave_smtp'];
                $config->autenticacion_ssl=$_POST['autenticacion_ssl'];

                if ($config->VerificarConfiguracion()>0){

                    #Si ya existe una configuracion guardada  se llama el metodo actualziar
                    #cuando es mayor que 0 es por que ya existe un registro de configuracion
                    $config->Actualizar_configuracion();
                }else{  
                        
                    #Se guarda la configuracion por primera vez
                    $config->Guardar_configuracion();
                    
                }

        break;

        case 'config_load_site':
            $config = new Config();
            $config->Cargar_configuracion('json');

        break;

        case 'search_boards':
            $context = $_GET['context'];
            $config = $_GET['config'];
            $Board = new Board();
            $Board->search_tablero($context, $config);

        break;

        case 'block_board':
            $board = new Board();
            $board->id_usuario = $_POST['id_usuario'];
            $board->board_id = (int) $_POST['id_board'];

            $board->bloquear_tablero();

        break;

        case 'active_board':
            $board = new Board();
            $board->board_id = (int) $_POST['id_board'];
            $board->id_usuario = $_POST['id_usuario'];
            $board->bloquear_tablero();

        break;


        case 'ExistUser':



        break;

        case 'disable_user':
            $user = new User();
            $user->id_user = $_POST['id_user'];
            $user->DesactivarUsuario();

        break;

        case 'enable_user':
            $user = new User();
            $user->id_user = $_POST['id_user'];
            $user->ActivarUsuario();

        break;

        case 'create_board':
            $board = new Board();
            $board->description = $_POST['description'];
            $board->imagen_tablero = '';
            $board->id_usuario = $_POST['user_id'];
            $board->guardar_tablero();

        break;

        case 'drop_board':
            $board = new Board();
            $board->board_id = $_POST['id_board'];
            $board->id_usuario = $_POST['id_user'];
            $board->desactivar_tablero();

        break;

        case 'update_board':
            $tablero = new Board();
            $tablero->description = $_POST['description'];
            $tablero->imagen_tablero = 'imagen_actualizada.jpg';
            $tablero->id_usuario = $_POST['user_id'];
            $id_tablero = $_POST['bord_id'];
            $tablero->actualizar_tablero($id_tablero);

        break;

        case 'create_user':
            $usuario = new User();
            $usuario->usuario = $_POST['username'];
            $usuario->nombre = $_POST['name'];
            $usuario->sexo = $_POST['sex'];
            $usuario->foto_url = 'assets/user_profile.png';
            $usuario->email = $_POST['email'];
            $usuario->apellido = $_POST['last_name'];
            $usuario->bio = $_POST['bio'];
            $usuario->clave = $_POST['password'];
            $usuario->RegistrerUser();

        break;

        case 'user_info':
            $usuario = new User();
            $usuario->id_user = (int) $_POST['user_id'];
            $usuario->get_info_user();

        break;

        case 'update_user':
            $image_path = '';
            $usuario = new User();
            $usuario->usuario = $_POST['username'];
            $usuario->nombre = $_POST['name'];
            $usuario->sexo = $_POST['sex'];
            $image_path = $usuario->uploadImage($_FILES['image']);
            $usuario->foto_url = $image_path;
            $usuario->apellido = $_POST['last_name'];
            $usuario->bio = $_POST['bio'];
            $usuario->id_user = $_POST['user_id'];
            $usuario->updateUser();
        break;

        case 'update_coment':
        break;

        case 'delete_comment':

            $delete = new Coment();
            $delete->eliminar_comentario($_POST['id_comentario']);

        break;

        case 'delete_comment_child':

            $coment = new Coment();
            $coment->delete_coment_reply($_POST['id_comentario']);
        
        break;

        case 'load_comments':
            $id_board = $_POST['id_board'];
            $read_coment = new Coment();
            $read_coment->leer_comentarios($id_board, 'board');

        break;

        case 'add_favorit':
        break;

        case 'reply_coment':

            $id_coment = $_POST['id_coment'];
            $id_user = $_POST['id_user'];
            $text_coment = $_POST['text_coment'];
            $reply_coment = new Coment();
            $reply_coment->reply_coment($id_coment, $id_user, $text_coment);

        break;

        case 'delete_reply_coment':
            $id_coment = $_POST['id_coment'];
            $reply_coment = new Coment();
            $reply_coment->delete_coment_reply($id_coment);

        break;

        case 'load_childs_coments':
            $id_coment = $_POST['id_coment'];
            $coments = new Coment();
            $coments->load_childs_coment($id_coment);

        break;

        case 'point_to_board':
        break;

        case 'login':
            $usuario = new User();
            $usuario->Login($_POST['usuario'], $_POST['clave']);

        break;

        case 'sigout':
            $usuario = $_POST['usuario'];
            $user = new User();
            $user->SigOut($usuario);

        break;

        case 'get_metaog':
           // $usuario = new User();
           // $token =$usuario->VerifiyTokenExpired($usuario->getBearerToken());
            $url = $_POST['url'];
            Core::GetGrapth($url);

        break;

        case 'get_user_information':
            // $usuario = new User();
            // $token =$usuario->VerifiyTokenExpired($usuario->getBearerToken());
            
             $url = $_POST['url'];
             Core::GetGrapth($url);

         break;


         case 'send_mail_all':
            //Esta api envia correo a todos los usuarios de la 
            // plataforma
            $correo = new Mail();      
            $correo->mensaje = $_POST['mensaje'];
            $asunto = $_POST['asunto'];
            $username = $_POST['usuario'];
            $correo->EnviarCorreo(0,$asunto,'send_mail_all');

         break;

         case 'send_mail_to_user':

            $correo = new Mail();      
            $correo->mensaje = $_POST['mensaje'];
            $asunto = $_POST['asunto'];
            $id_user = $_POST['usuario'];
            $correo->EnviarCorreo($username ,$asunto,'send_mail_all');
            
         break;

         case 'all_mails':
            
            $user = new User();
            $user->CargarTodosLosCorreos();
        
         break;



       }
