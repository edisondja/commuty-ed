<?php

  require '../config/config.php';
  require '../models/User.php';
  require '../models/Board.php'; 
  require '../models/Config.php';
  require '../models/Notificacion.php';
  require '../models/Coment.php';
  require '../models/Mail.php';
  require '../models/Like.php';
  require '../models/Report.php';
  require '../models/View.php';
  require '../models/Favorito.php';
  require '../models/Ads.php';
  use setasign\Fpdi\Fpdi;
  require 'Core.php';
 // require '../modeles/Mail.php';

 //$_POST = json_decode(file_get_contents("php://input"),true);

if (isset($_POST['action'])) {
      $action = $_POST['action'];
  } elseif (isset($_GET['action'])) {
      $action = $_GET['action'];
  } else {
      // Si no hay action, puede ser un error de tamaño de archivo
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          header('Content-Type: application/json');
          echo json_encode([
              'error' => 'El archivo es demasiado grande. Tamaño máximo permitido: 250MB',
              'code' => 'FILE_TOO_LARGE'
          ]);
          exit;
      }
      $action = null;
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


        case 'save_transferred_video':

                $board = new Board();
                $board->description = $_POST['video_txt'];
                $board->imagen_tablero = '';
                $board->id_usuario = $_POST['id_user'];
                $board->guardar_tablero();
        
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
            header('Content-Type: application/json');
            $config = new Config();
            $imagen_actual =  $config->Cargar_configuracion('asoc');
            
            // Preservar URLs existentes si no se suben nuevas imágenes
            if ($imagen_actual) {
                $config->sitio_logo_url = $imagen_actual->sitio_logo_url ?? '';
                $config->favicon_url = $imagen_actual->favicon_url ?? '';
            }
        
            #Verificando si se enviaron multimedias
            if(isset($_FILES['logo_sitio']) && isset($_FILES['favicon_sitio']) ){
                $config->DetectarMultimedias($_FILES['logo_sitio'], $_FILES['favicon_sitio']);
            }else if(isset($_FILES['logo_sitio']) && !isset($_FILES['favicon_sitio']) ){
                $config->DetectarMultimedias($_FILES['logo_sitio'], null);
            }else if(!isset($_FILES['logo_sitio']) && isset($_FILES['favicon_sitio'])){
                $config->DetectarMultimedias(null, $_FILES['favicon_sitio']);
            }
            
            // Asignar valores del POST con valores por defecto
            $config->dominio = $_POST['dominio'] ?? '';
            $config->nombre_sitio = $_POST['nombre_sitio'] ?? '';
            $config->descripcion_slogan = $_POST['descripcion_slogan'] ?? '';
            $config->descripcion_sitio = $_POST['descripcion_sitio'] ?? '';
            $config->copyright_descripcion = $_POST['copyright_descripcion'] ?? '';
            $config->email_sitio = $_POST['email_sitio'] ?? '';
            $config->busqueda_descripcion = $_POST['busqueda_descripcion'] ?? '';
            $config->pagina_descripcion = $_POST['pagina_descripcion'] ?? '';
            $config->titulo_descripcion = $_POST['titulo_descripcion'] ?? '';
            $config->busqueda_hastag = $_POST['busqueda_hastag'] ?? '';
            $config->email_remitente = $_POST['email_remitente'] ?? '';
            $config->nombre_remitente = $_POST['nombre_remitente'] ?? '';
            $config->servidor_smtp = $_POST['servidor_smtp'] ?? '';
            $config->puerto_smtp = $_POST['puerto_smtp'] ?? '';
            $config->usuario_smtp = $_POST['usuario_smtp'] ?? '';
            $config->clave_smtp = $_POST['clave_smtp'] ?? '';
            // Convertir autenticacion_ssl a entero (la base de datos espera INTEGER)
            $autenticacion_ssl_value = $_POST['autenticacion_ssl'] ?? '';
            if (empty($autenticacion_ssl_value) || strtolower($autenticacion_ssl_value) === 'no') {
                $config->autenticacion_ssl = 0;
            } elseif (strtolower($autenticacion_ssl_value) === 'si' || $autenticacion_ssl_value === '1' || $autenticacion_ssl_value === 1) {
                $config->autenticacion_ssl = 1;
            } else {
                $config->autenticacion_ssl = (int)$autenticacion_ssl_value;
            }
            // Convertir publicar_sin_revision a entero
            $publicar_sin_revision_value = $_POST['publicar_sin_revision'] ?? 'NO';
            if (empty($publicar_sin_revision_value) || strtolower($publicar_sin_revision_value) === 'no') {
                $config->publicar_sin_revision = 0;
            } elseif (strtolower($publicar_sin_revision_value) === 'si' || $publicar_sin_revision_value === '1' || $publicar_sin_revision_value === 1) {
                $config->publicar_sin_revision = 1;
            } else {
                $config->publicar_sin_revision = (int)$publicar_sin_revision_value;
            }
            
            // Convertir verificar_cuenta a entero
            $verificar_cuenta_value = $_POST['verificar_cuenta'] ?? 'NO';
            if (empty($verificar_cuenta_value) || strtolower($verificar_cuenta_value) === 'no') {
                $config->verificar_cuenta = 0;
            } elseif (strtolower($verificar_cuenta_value) === 'si' || $verificar_cuenta_value === '1' || $verificar_cuenta_value === 1) {
                $config->verificar_cuenta = 1;
            } else {
                $config->verificar_cuenta = (int)$verificar_cuenta_value;
            }
            
            // Convertir rabbit_mq a entero
            $rabbit_mq_value = $_POST['rabbit_mq'] ?? 'NO';
            if (empty($rabbit_mq_value) || strtolower($rabbit_mq_value) === 'no') {
                $config->rabbit_mq = 0;
            } elseif (strtolower($rabbit_mq_value) === 'si' || $rabbit_mq_value === '1' || $rabbit_mq_value === 1) {
                $config->rabbit_mq = 1;
            } else {
                $config->rabbit_mq = (int)$rabbit_mq_value;
            }
            
            // Convertir ffmpeg a entero
            $ffmpeg_value = $_POST['ffmpeg'] ?? 'NO';
            if (empty($ffmpeg_value) || strtolower($ffmpeg_value) === 'no') {
                $config->ffmpeg = 0;
            } elseif (strtolower($ffmpeg_value) === 'si' || $ffmpeg_value === '1' || $ffmpeg_value === 1) {
                $config->ffmpeg = 1;
            } else {
                $config->ffmpeg = (int)$ffmpeg_value;
            }
            
            // Convertir redis_cache a entero
            $redis_cache_value = $_POST['redis_cache'] ?? 'NO';
            if (empty($redis_cache_value) || strtolower($redis_cache_value) === 'no') {
                $config->redis_cache = 0;
            } elseif (strtolower($redis_cache_value) === 'si' || $redis_cache_value === '1' || $redis_cache_value === 1) {
                $config->redis_cache = 1;
            } else {
                $config->redis_cache = (int)$redis_cache_value;
            }
            
            // Google Analytics ID
            $config->google_analytics_id = $_POST['google_analytics_id'] ?? '';
            
            // Preservar estilos_json existente si no se envía
            if ($imagen_actual && isset($imagen_actual->estilos_json)) {
                $config->estilos_json = $imagen_actual->estilos_json;
            }

            if ($config->VerificarConfiguracion() > 0){
                #Si ya existe una configuracion guardada se llama el metodo actualizar
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

        case 'save_styles':
            header('Content-Type: application/json');
            require '../config/config.php';
            $config = new Config();
            
            // Obtener estilos del POST
            $estilos = [
                'color_primario' => $_POST['color_primario'] ?? '#20c997',
                'color_secundario' => $_POST['color_secundario'] ?? '#09b9e1',
                'color_fondo' => $_POST['color_fondo'] ?? '#1a1c1d',
                'color_texto' => $_POST['color_texto'] ?? '#cfd8dc',
                'color_enlace' => $_POST['color_enlace'] ?? '#20c997',
                'color_enlace_hover' => $_POST['color_enlace_hover'] ?? '#17a085',
                'color_boton' => $_POST['color_boton'] ?? '#20c997',
                'color_boton_hover' => $_POST['color_boton_hover'] ?? '#17a085',
                'color_tarjeta' => $_POST['color_tarjeta'] ?? '#2d2d2d',
                'color_borde' => $_POST['color_borde'] ?? '#444',
                'color_header' => $_POST['color_header'] ?? '#1a1a1a'
            ];
            
            // Guardar estilos como JSON en la tabla de configuración
            $estilos_json = json_encode($estilos);
            
            // Usar la conexión del objeto Config
            $conexion = $config->conection;
            
            if ($config->VerificarConfiguracion() > 0) {
                // Actualizar estilos existentes
                $sql = "update configuracion set estilos_json = ? limit 1";
                $stmt = $conexion->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('s', $estilos_json);
                    $stmt->execute();
                    $stmt->close();
                    echo json_encode(['success' => true, 'message' => 'Estilos guardados correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conexion->error]);
                }
            } else {
                // Crear nueva configuración con estilos
                $sql = "insert into configuracion (estilos_json) values (?)";
                $stmt = $conexion->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('s', $estilos_json);
                    $stmt->execute();
                    $stmt->close();
                    echo json_encode(['success' => true, 'message' => 'Estilos guardados correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conexion->error]);
                }
            }
        break;

        case 'load_styles':
            header('Content-Type: application/json');
            $config = new Config();
            
            if ($config->VerificarConfiguracion() > 0) {
                $config_data = $config->Cargar_configuracion('asoc');
                
                // Intentar obtener estilos desde la configuración
                $estilos = [];
                if (isset($config_data->estilos_json) && !empty($config_data->estilos_json)) {
                    $estilos = json_decode($config_data->estilos_json, true);
                }
                
                // Si no hay estilos guardados, usar valores por defecto
                if (empty($estilos)) {
                    $estilos = [
                        'color_primario' => '#20c997',
                        'color_secundario' => '#09b9e1',
                        'color_fondo' => '#1a1c1d',
                        'color_texto' => '#cfd8dc',
                        'color_enlace' => '#20c997',
                        'color_enlace_hover' => '#17a085',
                        'color_boton' => '#20c997',
                        'color_boton_hover' => '#17a085',
                        'color_tarjeta' => '#2d2d2d',
                        'color_borde' => '#444',
                        'color_header' => '#1a1a1a'
                    ];
                }
                
                echo json_encode(['success' => true, 'estilos' => $estilos]);
            } else {
                // Valores por defecto si no hay configuración
                $estilos = [
                    'color_primario' => '#20c997',
                    'color_secundario' => '#09b9e1',
                    'color_fondo' => '#1a1c1d',
                    'color_texto' => '#cfd8dc',
                    'color_enlace' => '#20c997',
                    'color_enlace_hover' => '#17a085',
                    'color_boton' => '#20c997',
                    'color_boton_hover' => '#17a085',
                    'color_tarjeta' => '#2d2d2d',
                    'color_borde' => '#444',
                    'color_header' => '#1a1a1a'
                ];
                echo json_encode(['success' => true, 'estilos' => $estilos]);
            }
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
            $board->activar_tablero();

        break;


        case 'ExistUser':



        case 'guardar_ads':
            
        
            $publicidad = new Ads();
            $publicidad->titulo  =$_POST['titulo'];
            $publicidad->descripcion = $_POST['descripcion'];
            $publicidad->posicion = $_POST['posicion'];
            $publicidad->tipo = $_POST['tipo'];
            $publicidad->script_banner =$_POST['script_banner'];
            $publicidad->link_banner =$_POST['link_banner'];
            $publicidad->GuardarAds();


        break;

        case 'cargar_banners':
            //cargar todas las ads
            $publicidad = new Ads();
            $publicidad->CargarAds();

        break;

        case 'cargar_ads_s':
            //cargar la data de una sola ads con su ID
            $publicidad = new Ads();
            $publicidad->ads_id = $_POST['ads_id'];
            $publicidad->Cargar_1_ads();
  
        break;

        
        case 'cambiar_estado_ads':
           //Desactiva la ads para no visualizada de forma publica
           $publicidad = new Ads();
           $publicidad->ads_id = $_POST['ads_id'];
           $publicidad->estado = $_POST['estado'];
           $publicidad->cambiar_estado_ads();
        
        break;

           case 'actualizar_ads':
           //Desactiva la ads para no visualizada de forma publica
           $publicidad = new Ads();
           $publicidad->ads_id = $_POST['ads_id'];
           $publicidad->estado = $_POST['estado'];
           $publicidad->titulo = $_POST['titulo'];
           $publicidad->descripcion = $_POST['descripcion'];
           $publicidad->script_banner = $_POST['script_banner'];
           $publicidad->posicion = $_POST['posicion'];
           $publicidad->tipo = $_POST['tipo'];
           $publicidad->link_banner = $_POST['link_banner'];
            if(isset($_FILES['imagen_banner']['tmp_name'])){

                $nombre_archivo=date('YmdHis').$_FILES['imagen_banner']['name'];
                $ruta_archivo_banner = "imagenes_tablero/".$nombre_archivo;
               if(move_uploaded_file($_FILES['imagen_banner']['tmp_name'],"../".$ruta_archivo_banner)){

                    $publicidad->imagen_ruta = $ruta_archivo_banner;
               }
            }else{

                $publicidad->imagen_ruta = $_POST['imagen_original'];
            }
           $publicidad->Actualizar_ads();
        
        break;
    
    

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
            $tablero->description = $_POST['descripcion'];
            $tablero->board_id = $_POST['id_tablero'];

            if (isset($_FILES['foto']['tmp_name'])) {
                // Si se envió una nueva imagen, subirla y actualizar la ruta
                $tablero->asignar_portada_tablero($tablero->board_id,
                                                   $_FILES['foto']['tmp_name'],
                                                   '../imagenes_tablero/' . $_FILES['foto']['name']);

            } else {
                // Si no se envió una nueva imagen, mantener la imagen actual
                $tablero->imagen_tablero = $_POST['imagen_actual'];
            }

            $tablero->id_usuario = $_POST['id_usuario'];
            $tablero->actualizar_tablero();

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

        case 'load_info_board':
            $board = new Board();
            $id_tablero = (int) $_POST['id_tablero'];
            $board->cargar_solo_tablero($id_tablero,'json');
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
            // Verificar si se envió un archivo
            if (isset($_FILES['image']['tmp_name'])) {
                $image_path = $usuario->uploadImage($_FILES['image']);
            }else{

                $image_path = $_POST['imagen_actual'];
            }
            $usuario->foto_url = $image_path;
            // Si no se subió nueva imagen, mantener la foto actual
            $usuario->apellido = $_POST['last_name'];
            $usuario->bio = $_POST['bio'];
            $usuario->id_user = (int)$_POST['user_id'];
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

        case 'public_report':

            $report = new Report();
            $report->id_tablero = $_POST['id_tablero'];
            $report->id_usuario = $_POST['id_user'];
            $report->descripcion = $_POST['descripcion'];
            $report->save_report();


        case 'load_report_user':
            //Carga los reportes que ha hecho un usuario en particular
            $report = new Report();
            $report->id_usuario = $_POST['id_user'];
            $report->cargar_reportes_usuario();

        break;

        case 'buscar_reporte':
            $texto = $_POST['texto'];
            $report = new Report();
            $report->buscar_reportes($texto);
        break;

        case 'load_report_admin':
            //Carga todo los reportes de lo usuarios en el panel del admin
            $report = new Report();
            $report->cargar_reportes_usuario('admin');

        break;
        case 'anular_report':

            $report = new Report();
            $report->id_usuario = $_POST['id_user'];
            $report->id_report = $_POST['id_report'];
            $report->anular_report();

        break;

        case 'add_favorite':
            $favorite = new Favorito();
            $favorite->id_tablero = $_POST['id_board'];
            $favorite->id_usuario = $_POST['id_user'];
            $favorite->agregar_a_favorito();
        break;

        case 'reply_coment':
            // Establecer header JSON
            header('Content-Type: application/json; charset=utf-8');
            
            $id_coment = isset($_POST['id_coment']) ? (int)$_POST['id_coment'] : 0;
            $id_user = isset($_POST['id_user']) ? (int)$_POST['id_user'] : 0;
            $text_coment = isset($_POST['text_coment']) ? trim($_POST['text_coment']) : '';
            
            if (!$id_coment || !$id_user || !$text_coment) {
                echo json_encode(['error' => 'Faltan parámetros requeridos']);
                exit;
            }
            
            try {
                $reply_coment = new Coment();
                $reply_coment->reply_coment($id_coment, $id_user, $text_coment);
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

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

         case 'guardar_view':

            $view = new View();
            $view->id_tablero = $_POST['id_tablero'];
            $view->id_usuario = $_POST['id_usuario']; // Puede ser 0 para usuarios anónimos
            $view->guardar_view();


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


        case 'save_like':
            
          $like = new Like();
          $like->id_tablero = $_POST['id_tablero'];
          $like->id_usuario = $_POST['id_usuario'];
          $like->guardar_like();


        break;


        case 'cargar_likes_board':
          
         $like = new Like();
         $like->id_tablero  = $_GET['id_tablero'];
         $like->cargar_likes_board();


        break;

        
        case 'contar_likes_board':
            header('Content-Type: application/json');
            try {
                $like = new Like();
                $like->id_tablero = (int)($_GET['id_tablero'] ?? 0);
                $result = $like->contar_lk('asoc');
                echo json_encode($result);
            } catch (Exception $e) {
                echo json_encode(['likes' => 0, 'error' => $e->getMessage()]);
            }
        break;

        case 'verificar_mi_like':
            header('Content-Type: application/json');
            try {
                $like = new Like();
                $like->id_tablero = (int)($_GET['id_tablero'] ?? 0);
                $like->id_usuario = (int)($_GET['id_usuario'] ?? 0);
                $result = $like->verificar_mi_like();
                echo json_encode(['status' => $result]);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
            }
        break;

        case 'save_rating':
            require '../models/Rating.php';
            header('Content-Type: application/json');
            
            $rating = new Rating();
            $rating->id_tablero = (int)$_POST['id_tablero'];
            $rating->id_user = (int)$_POST['id_usuario'];
            $rating->puntuacion = (int)$_POST['puntuacion'];
            
            // Validar que la puntuación esté entre 1 y 5
            if ($rating->puntuacion < 1 || $rating->puntuacion > 5) {
                echo json_encode(['error' => 'La puntuación debe estar entre 1 y 5']);
                break;
            }
            
            $rating->guardar_rating();
        break;

        case 'get_rating_average':
            require '../models/Rating.php';
            header('Content-Type: application/json');
            
            $rating = new Rating();
            $rating->id_tablero = (int)$_GET['id_tablero'];
            $rating->obtener_promedio('json');
        break;

        case 'get_my_rating':
            require '../models/Rating.php';
            header('Content-Type: application/json');
            
            $rating = new Rating();
            $rating->id_tablero = (int)$_GET['id_tablero'];
            $rating->id_user = (int)$_GET['id_usuario'];
            $rating->obtener_mi_calificacion('json');
        break;

        
        case 'cargar_un_tablero':
            header('Content-Type: application/json');
            $tablero = new Board();
            $tablero->cargar_solo_tablero($_GET['id_tablero'],'json');
        break; 

        //Apis de favoritos

        case 'agregar_a_favorito':



        break;


        case'eliminar_de_favorito':


        break;


        //Api de registros de logs

        
        case 'cargar_logs':


        break;

        case 'desactivar_log_db':


        break;


        //Modulo de notifiaciones


        case 'emitir_notificacion':


        break;


        case 'cargar_mis_notifiaciones':


        break;

            
        case 'create_pdf':

            // Asegúrate de que el autoload de Composer esté incluido
        
            // Verifica si se han recibido archivos
            if (!empty($_FILES['images']['name'][0])) {
                // Crear instancia de FPDF
                $pdf = new Fpdi();
        
                // Directorio donde se guardará el PDF final
                $pdfDir = '../uploads/';
                if (!is_dir($pdfDir)) {
                    mkdir($pdfDir, 0777, true);
                }
        
                // Procesa cada imagen recibida
                foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                    $fileType = mime_content_type($tmpName);
        
                    // Solo procesar si el archivo es una imagen
                    if (strpos($fileType, 'image') !== false) {
                        // Añade una nueva página al PDF
                        $pdf->AddPage();
        
                        // Mueve la imagen al directorio temporal
                        $imgPath = $pdfDir . basename($_FILES['images']['name'][$index]);
                        move_uploaded_file($tmpName, $imgPath);
        
                        // Obtén las dimensiones de la imagen
                        list($width, $height) = getimagesize($imgPath);
        
                        // Ajusta la imagen al tamaño de la página
                        $pdf->Image($imgPath, 10, 10, 190, 0);
        
                        // Elimina la imagen temporal después de usarla
                        unlink($imgPath);
                    }
                }
        
                // Define la ruta para el PDF generado
                $pdfOutputPath = $pdfDir . 'Generado_' . time() . '.pdf';
        
                // Guarda el PDF en el servidor
                $pdf->Output('F', $pdfOutputPath);
        
                // Devuelve la respuesta en JSON con el enlace al PDF
                echo json_encode([
                    'success' => true,
                    'pdfUrl' => $pdfOutputPath
                ]);
            } else {
                // No se recibieron archivos, devuelve un error
                echo json_encode([
                    'success' => false,
                    'message' => 'No se recibieron imágenes.'
                ]);
            }
        
            break;

        // ==================== REPRODUCTORES VAST ====================
        
        case 'listar_reproductores':
            header('Content-Type: application/json');
            try {
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                if ($db->connect_error) {
                    throw new Exception("Error de conexión: " . $db->connect_error);
                }
                
                $result = $db->query("SELECT * FROM reproductores_vast ORDER BY es_default DESC, id_reproductor ASC");
                $reproductores = [];
                
                while ($row = $result->fetch_assoc()) {
                    $reproductores[] = $row;
                }
                
                echo json_encode(['success' => true, 'reproductores' => $reproductores]);
                $db->close();
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'obtener_reproductor':
            header('Content-Type: application/json');
            try {
                $id = (int)$_POST['id_reproductor'];
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                
                $stmt = $db->prepare("SELECT * FROM reproductores_vast WHERE id_reproductor = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $reproductor = $result->fetch_assoc();
                $stmt->close();
                $db->close();
                
                echo json_encode(['success' => true, 'reproductor' => $reproductor]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'crear_reproductor':
            header('Content-Type: application/json');
            try {
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                
                $nombre = $_POST['nombre'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $vast_url = $_POST['vast_url'] ?? '';
                $vast_url_mid = $_POST['vast_url_mid'] ?? '';
                $vast_url_post = $_POST['vast_url_post'] ?? '';
                $skip_delay = (int)($_POST['skip_delay'] ?? 5);
                $mid_roll_time = (int)($_POST['mid_roll_time'] ?? 30);
                $activo = (int)($_POST['activo'] ?? 1);
                $es_default = (int)($_POST['es_default'] ?? 0);
                
                // Si es default, quitar default a los demás
                if ($es_default == 1) {
                    $db->query("UPDATE reproductores_vast SET es_default = 0");
                }
                
                $stmt = $db->prepare("INSERT INTO reproductores_vast 
                    (nombre, descripcion, vast_url, vast_url_mid, vast_url_post, skip_delay, mid_roll_time, activo, es_default) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssiiii', $nombre, $descripcion, $vast_url, $vast_url_mid, $vast_url_post, 
                                  $skip_delay, $mid_roll_time, $activo, $es_default);
                $stmt->execute();
                $newId = $db->insert_id;
                $stmt->close();
                $db->close();
                
                echo json_encode(['success' => true, 'message' => 'Reproductor creado', 'id' => $newId]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'actualizar_reproductor':
            header('Content-Type: application/json');
            try {
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                
                $id = (int)$_POST['id_reproductor'];
                $nombre = $_POST['nombre'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $vast_url = $_POST['vast_url'] ?? '';
                $vast_url_mid = $_POST['vast_url_mid'] ?? '';
                $vast_url_post = $_POST['vast_url_post'] ?? '';
                $skip_delay = (int)($_POST['skip_delay'] ?? 5);
                $mid_roll_time = (int)($_POST['mid_roll_time'] ?? 30);
                $activo = (int)($_POST['activo'] ?? 1);
                $es_default = (int)($_POST['es_default'] ?? 0);
                
                // Si es default, quitar default a los demás
                if ($es_default == 1) {
                    $db->query("UPDATE reproductores_vast SET es_default = 0");
                }
                
                $stmt = $db->prepare("UPDATE reproductores_vast SET 
                    nombre = ?, descripcion = ?, vast_url = ?, vast_url_mid = ?, vast_url_post = ?,
                    skip_delay = ?, mid_roll_time = ?, activo = ?, es_default = ?
                    WHERE id_reproductor = ?");
                $stmt->bind_param('sssssiiiii', $nombre, $descripcion, $vast_url, $vast_url_mid, $vast_url_post,
                                  $skip_delay, $mid_roll_time, $activo, $es_default, $id);
                $stmt->execute();
                $stmt->close();
                $db->close();
                
                echo json_encode(['success' => true, 'message' => 'Reproductor actualizado']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'set_reproductor_default':
            header('Content-Type: application/json');
            try {
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                $id = (int)$_POST['id_reproductor'];
                
                // Quitar default a todos
                $db->query("UPDATE reproductores_vast SET es_default = 0");
                
                // Poner default al seleccionado
                $stmt = $db->prepare("UPDATE reproductores_vast SET es_default = 1 WHERE id_reproductor = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
                $db->close();
                
                echo json_encode(['success' => true, 'message' => 'Reproductor establecido como predeterminado']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'eliminar_reproductor':
            header('Content-Type: application/json');
            try {
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                $id = (int)$_POST['id_reproductor'];
                
                // No permitir eliminar el default
                $check = $db->query("SELECT es_default FROM reproductores_vast WHERE id_reproductor = $id");
                $row = $check->fetch_assoc();
                if ($row && $row['es_default'] == 1) {
                    throw new Exception("No se puede eliminar el reproductor predeterminado");
                }
                
                $stmt = $db->prepare("DELETE FROM reproductores_vast WHERE id_reproductor = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
                $db->close();
                
                echo json_encode(['success' => true, 'message' => 'Reproductor eliminado']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'obtener_reproductor_default':
            header('Content-Type: application/json');
            try {
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                
                $result = $db->query("SELECT * FROM reproductores_vast WHERE es_default = 1 AND activo = 1 LIMIT 1");
                $reproductor = $result->fetch_assoc();
                $db->close();
                
                echo json_encode(['success' => true, 'reproductor' => $reproductor]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'obtener_reproductor_tablero':
            header('Content-Type: application/json');
            try {
                $id_tablero = (int)($_POST['id_tablero'] ?? $_GET['id_tablero'] ?? 0);
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                
                // Obtener el reproductor asignado al tablero
                $stmt = $db->prepare("SELECT r.* FROM reproductores_vast r 
                                      INNER JOIN tableros t ON t.id_reproductor = r.id_reproductor 
                                      WHERE t.id_tablero = ? AND r.activo = 1");
                $stmt->bind_param('i', $id_tablero);
                $stmt->execute();
                $result = $stmt->get_result();
                $reproductor = $result->fetch_assoc();
                $stmt->close();
                
                // Si no tiene asignado, buscar el default
                if (!$reproductor) {
                    $result = $db->query("SELECT * FROM reproductores_vast WHERE es_default = 1 AND activo = 1 LIMIT 1");
                    $reproductor = $result->fetch_assoc();
                }
                
                $db->close();
                
                echo json_encode(['success' => true, 'reproductor' => $reproductor]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'asignar_reproductor_tablero':
            header('Content-Type: application/json');
            try {
                $id_tablero = (int)($_POST['id_tablero'] ?? 0);
                $id_reproductor = $_POST['id_reproductor'] ?? '';
                
                // Si está vacío, poner NULL
                $id_reproductor = $id_reproductor === '' ? null : (int)$id_reproductor;
                
                $db = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
                
                if ($id_reproductor === null) {
                    $stmt = $db->prepare("UPDATE tableros SET id_reproductor = NULL WHERE id_tablero = ?");
                    $stmt->bind_param('i', $id_tablero);
                } else {
                    $stmt = $db->prepare("UPDATE tableros SET id_reproductor = ? WHERE id_tablero = ?");
                    $stmt->bind_param('ii', $id_reproductor, $id_tablero);
                }
                
                $stmt->execute();
                $stmt->close();
                $db->close();
                
                echo json_encode(['success' => true, 'message' => 'Reproductor asignado correctamente']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;

       }
