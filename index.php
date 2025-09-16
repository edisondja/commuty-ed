<?php
require('bootstrap.php');

$Board = new Board();
$tableros = [];
$pagina = 0;



// Base de la clave de cache
$cache_key = "tableros:";

// Determinar la acción según los parámetros GET
if (!isset($_GET['leaf']) && !isset($_GET['search'])) {
    $cache_key .= "general";

    try {
        if ($redisAvailable && $cached = $redis->get($cache_key)) {
            $tableros = json_decode($cached, true);
        } else {
            $tableros = $Board->cargar_tablerosx('general', 'asoc');
            if ($redisAvailable) {
                $redis->setex($cache_key, 300, json_encode($tableros)); // 5 minutos
            }
        }
    } catch (Exception $e) {
        $tableros = $Board->cargar_tablerosx('general', 'asoc');
    }

} elseif (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $cache_key .= "search:" . md5($searchTerm); // clave única para la búsqueda

    try {
        if ($redisAvailable && $cached = $redis->get($cache_key)) {
            $tableros = json_decode($cached, true);
        } else {
            $tableros = $Board->search_tablero($searchTerm, 'ascoc');
            if ($redisAvailable) {
                $redis->setex($cache_key, 300, json_encode($tableros)); // 5 minutos
            }
        }
    } catch (Exception $e) {
        $tableros = $Board->search_tablero($searchTerm, 'ascoc');
    }

} elseif (isset($_GET['leaf'])) {
    $leafPage = intval($_GET['leaf']);
    $cache_key .= "leaf:" . $leafPage;

    try {
        if ($redisAvailable && $cached = $redis->get($cache_key)) {
            $tableros = json_decode($cached, true);
        } else {
            $tableros = $Board->paginar_tableros($leafPage);
            if ($redisAvailable) {
                $redis->setex($cache_key, 300, json_encode($tableros)); // 5 minutos
            }
        }
    } catch (Exception $e) {
        $tableros = $Board->paginar_tableros($leafPage);
    }
}

// Asignar variables a Smarty
$smarty->assign([
    'tableros' => $tableros,
    'pagina' => $pagina,
    'titulo' => "The best boards " . NAME_SITE,
    'descripcion' => NAME_SITE . " platform free for all to share your contents",
    'og_imagen' => LOGOSITE,
    'content_config' => 'boards',
    'paginador_scroll' => 'general',
    'url_board' => "$dominio/"
]);

$smarty->display('template/header.tpl');
