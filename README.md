# canjuro
Red social

<p>
  Canjuro es un proyecto basado en una red social donde los usuarios podran compartir su contenido
  libre de expresion, teniendo sus propias tiendas si lo desean y sus pagos podran ser recibidos por paypal
  o cryptomonedas, tendran la opcion de vender proudctos digitales como audiovisual,imagenes y musica si lo desean,
  no solo producto digitales tambien fisico.
</p>



<h3>Configuracion del proyecto</h3>
<p>En primer lugar en la raiz del proyecto vamos a ejecutar el comando <code> php composer install</code> para que se instalen todas
las dependencias que vamos a necesitar para trabajar el proyecto. ✔</p>


<p>En segundo lugar vamos a comenzar con la configuracion que se encuentra en este directorio ✔</p>
<table>
  <td>Configurar proyecto en el entorno que estes</td>
  <td>Directorio</td>
  <td>./config/config.php</td>
</table>

<code>
  
    define("DOMAIN","https://localhost.com");
    #These are the data for the connection of the database 
    define("HOST_BD","localhost");
    define("USER_BD","seus");
    define("PASSWORD_BD","password");
    define("NAME_DB","edtube");
    #Config with scope complete used for the site tube
    define("NAME_SITE","EDtube");
    define("DESCRIPTION_SLOGAN","Donde encontraras los video mas buscados");
    define("DESCRIPTION_SITE","El mejor tube para ver los mejores videos");
    #Favicon for the site very important 
    define("FAVICON",DOMAIN."/assets/favicon.ico");
    #The dimesion for the site logo is 230px of width and 50px of height
    define("LOGOSITE",DOMAIN."/assets/hotpipe.png");
    define("COPYRIGHT_DESCRIPTION","Copyright © 2022 EDTUBE. All Righ-ts Reserved.");
    define("MAIL_SITE","suppor@edtube.com");
    define("SEARCH_DESCRIPTION","Baddie Sweet one Sex Tape");
    define("PAGE_DESCRIPTION","Uckers badders from United Kingdom NSFW XXX");
    #The title description is the tag used for the browser for example..
    define("TITLE_DESCRIPTION","Better Uckers badders Sex Tapes - edtube");-
    #Description for search using hastag
    define("SEARCH_HASTAG","The Best badders Hashtag - edtube")
 
</code>
<hr>


<p>Si usted desea crear nuevas entidades lo hace en este directorio ✔</p>
<table>
  <td>Trabajar con entidades</td>
  <td>Directorio</td>
  <td>./Modelos/User.php</td>
<table>

 <h3>Crear apis para la aplicacion</h3>
 <p>Para usted crear apis puede hacerlo de una manera simple, solo tiene que entrar al directorio Controllers/actions_board.php.
  usted va observar que existe un switch, y una varaible post que recibe el atributo action, este atributo sera utilizado para 
  definir la accion que desea ejuctar. por ejemplo $_POST['action'] = 'load_user_info', esta accion carga la informacion del usuario con
  con el id  correspondiente. ejemplo..<br/>
  <hr>
  <?php
  <br>
  require('../Models/User.php');
  <br><br>
  switch($_POST['action']){
    
   case 'load_user_info':<br><br>
        &nbsp;$user = new User();<br>
        &nbsp;$user->get_info_user($_POST['user_id']);
   
   break;
   
   
  }
  ?>
   <p>Esta accion retornaria la informacion completa del usuario</p>
  </p>
  
  <h3>Trabajando con los templates y componentes</h3>
    <table>
      <td>Trabajar con templates</td>
      <td>Directorio</td>
      <td>./template/header.tpl</td>
    <table>
  <p>Si usted desea desarrollar templates y nuevos comoponentes, puede hacerlo en el directorio template,creas
      el componente profile.tpl por ejemplo y luego lo incluyes en las condiciones del header.tpl ✔... ejemplo<br>
  </p>
  <p>
    Creando el template profile.tpl preste atencion aqui.
  </p>
  <code>
    <div class="col-md-3">
      
    </div> 
          <div class="col-md-6">
                  <div class="card" style='padding:20px;'>
                  <div>
                      <table >
                          <tr>
                              <td><img src='{$dominio}/{$data_profile->foto_url}' style='margin:5px;height:100px;width:100px;border-radius:100px'/></td>
                              <td>
                              <strong style='margin:auto'>{$data_profile->usuario} <i class="fa-solid fa-square-check" style='color:#1bd093'></i>
                              </strong>
                              <p>Followers 485,000 m</p>
                              <button class='btn btn-dark fa-solid fa-user-plus'></button>
                              </td>
                              <td>

                              </td>
                              <i class="fa-regular fa-sun" style='float:right;cursor:pointer'></i>

                          </tr>
                           <tr>

                          </tr>
                      </table>

                      <p style='margin:auto;text-align: -webkit-center;color:#190d0d'>{$data_profile->bio} 
                      </p>

          </div>

          </div>
      </div>


      </div> <br/>
       <div class='row'>
                  {foreach from=$boards item=tablero}

                              {include file="board.tpl"}

                  {/foreach}

          </div><br/>


      {literal}
          <script type="text/javascript" src=''></script>
      {/literal}


  
  </code>
  <p>
    Ya que tenemos el componente o template profile.tpl creado, vamos proceder a registrarlo en header.tpl,
    en la condicion de content_config.
  </p>
  
                
      <?php        
      if $content_config=='boards'
                
                   elseif $content_config=='single_board'
                   
                      {include file="single_board.tpl"}
                      
                    elseif $content_config=='profile'
                       
                       include file="profile.tpl"  <-----
                              
                            Observe como aqui se esta incluyendo el tpl profile, 
                            inidicando en la varaible content_config=='profile'                                        
                    else
                        include file='default.tpl'

               if
    ?>
  
    <p style="color:red">
        header.tpl, es el template master, que incluye, el titulo del sitio web,logotipo,favicon y menu, por esta razon
        comenzamos a desarrollar a partir de el.
    </p>
    
    
 <h3>Como llamar el template o componente creado</h3>
 <p>Llamando nuestro componente profile.tpl por ejemplo, simplemente tenemos que hacer los siguiente
 creamos un arhcivo user_profile.php y lo seteamos de esta manera.✔
 </p>
                         
 <code>
    require('bootstrap.php');
    require('models/User.php');
    require('models/Board.php');


    $smarty->assign('titulo',"Profile by user ".NAME_SITE);
    $smarty->assign('descripcion',NAME_SITE." plataform free for alls share your contents");
    $smarty->assign('og_imagen',LOGOSITE);
    
    $smarty->assign('url_board',"$dominio");


    if(isset($_GET['user'])){

        $profile = new User();
        $data_user = $profile->LoadProfileUser($_GET['user']);
        $get_user_id =  $profile->get_id_from_user($_GET['user']);
        $get_user_id = $get_user_id->id_user;
        $boards = new Board();
        $data = $boards->cargar_tableros($get_user_id,'objects');
        //print_r( $data_user);
        $smarty->assign('content_config','profile'); al setear prfoile en content_config header.tpl sabra que lo tiene que llamar el profile.tpl
        $smarty->assign('boards',$data);
        $smarty->assign('name',$data_user->foto_url);
        $smarty->assign('og_imagen',$data_user->foto_url);
        $smarty->assign('data_profile',$data_user);
        $smarty->display('../template/header.tpl');  >---

    }

 
 
 </code>
                         
 <h3>Como funciona la autenticación de canjuro? ✔</h3>  
 <p>Es totalmente simple la forma en que funciona la autenticación de canjuro, cuando un usuario inicia sesion, se llama actions_board.php,
    el archivo que mostramos con anticipacion donde se llaman las apis, se envia una accion llamda 'login_user' el switch va learla
    y llama la funcion de iniciar sesion del modelo ./Models/User.php, una vez la sesion fue exitosa nos va retornar un Json web token,
    el cual sera almacenado en el localStorage, capturandolo de esta forma "localStorage.getItem(token)".
  </p>     
  
  <p>Ejemplo utilizado para enviar el token por cabezeras en caso de guardar un comentario observe.✔ </p>
  <code>
    function guardar_comentario(id_usuario,id_tablero,texto,tipo_post){
                          
                          let token = localStorage.getItem('token');

                                let FormDatas = new FormData();
                                FormDatas.append('action','save_post');
                                FormDatas.append('id_user',id_usuario);
                                FormDatas.append('id_board',id_tablero);
                                FormDatas.append('text',texto);
                                FormDatas.append('data_og',set_data_og);
                                FormDatas.append('type_post',tipo_post);

               
                                axios.post(`${dominio}/controllers/actions_board.php`,FormDatas,{headers:{
                                        'Content-Type': 'multipart/form-data',
                                        'Authorization': `Bearer ${token}`
                                }
                                }).then(info=>{

                                        cargar_comentarios(id_tablero,tipo_post);
                                        action_comment = 'normal';
                                        console.log(info);

                                }).catch(error=>{

                                console.log(error);

                                });
                }
  
  </code>
  
  
  <h3>Como cargar CDN globales en cajuro</h3>
    <p>My simple vamos al directorio donde se cargar las librerias globales </p>
    <table>
        <td>Cargando librerias globales CDN</td>
        <td>Directorio</td>
        <td>./Controllers/libs/connect_cdn.php</td>
    </table>
   <p>
      Luego de abrir el archivo connec_cdn.php, vamos a ver las librerias cdn cargadas por defecto aqui puedes poner la que deseas y llamarla
      en cualquier lugar del proyecto ya que esta es cargada por el archivo bootstrap.php, que es el primero que se cuando se inicia la app.
   </p>
   
   <code>


    /*Cdn readye for function now with a scope global*/



    return[
        '<script src="https://cdn.example.org/example.js"></script>',  <--- ejemplo
        '<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>',
        '<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>',
        '<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>',
        '<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>',
        '<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>',
        '<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>',
        '<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>',
        "<script src='$dominio/js/paginar_tableros.js'></script>",
        "<script src='$dominio/js/BoardOperation.js'></script>",
           
    ];

    

   
   </code>
    
    <h3>Donde crear y utilizar los scripts para las vistas</h3>
    <table>
        <tr>
            <td>Trabajar con scripts en vistas</td>
            <td>Directorio</td>
             <td>js/profile.js</td>
        </tr>
    
    </table>
  
   
   
   
   
