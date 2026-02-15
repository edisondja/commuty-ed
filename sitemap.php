<?php
/**
 * Generador de Sitemap XML para Commuty-ED
 * Acceder via: /sitemap.xml (configurado en .htaccess)
 */

require_once('config/config.php');

header('Content-Type: application/xml; charset=utf-8');

// Conexión a la base de datos
$conn = new mysqli(HOST, USER, PASS, DB);
if ($conn->connect_error) {
    die('Error de conexión');
}
$conn->set_charset("utf8mb4");

// Obtener dominio
$dominio = DOMAIN;

// Fecha actual
$fecha_actual = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <!-- Página principal -->
    <url>
        <loc><?php echo $dominio; ?>/</loc>
        <lastmod><?php echo $fecha_actual; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    
    <!-- Páginas estáticas -->
    <url>
        <loc><?php echo $dominio; ?>/registrer.php</loc>
        <lastmod><?php echo $fecha_actual; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
<?php
// Obtener todas las publicaciones activas
$sql = "SELECT t.id_tablero, t.descripcion, t.fecha_creacion, t.imagen_tablero,
               COALESCE(am.ruta_multimedia, t.imagen_tablero) as imagen
        FROM tablero t
        LEFT JOIN asignar_multimedia_t am ON t.id_tablero = am.id_tablero AND am.tipo_multimedia = 'imagen'
        WHERE t.estado = 'activo'
        ORDER BY t.fecha_creacion DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_tablero'];
        $fecha = date('Y-m-d', strtotime($row['fecha_creacion']));
        
        // Crear slug del título
        $slug = crearSlug(substr($row['descripcion'], 0, 50));
        
        // URL de la publicación
        $url = $dominio . '/post/' . $id . '/' . $slug;
        
        // Imagen
        $imagen = '';
        if (!empty($row['imagen'])) {
            $img_path = str_replace('../', '', $row['imagen']);
            $imagen = $dominio . '/' . $img_path;
        }
        
        echo "    <url>\n";
        echo "        <loc>" . htmlspecialchars($url) . "</loc>\n";
        echo "        <lastmod>" . $fecha . "</lastmod>\n";
        echo "        <changefreq>weekly</changefreq>\n";
        echo "        <priority>0.8</priority>\n";
        
        if (!empty($imagen)) {
            echo "        <image:image>\n";
            echo "            <image:loc>" . htmlspecialchars($imagen) . "</image:loc>\n";
            echo "            <image:title>" . htmlspecialchars(substr($row['descripcion'], 0, 100)) . "</image:title>\n";
            echo "        </image:image>\n";
        }
        
        echo "    </url>\n";
    }
}

// Obtener perfiles de usuarios activos
$sql_users = "SELECT usuario, fecha_registro FROM usuarios WHERE estado = 'activo' ORDER BY fecha_registro DESC LIMIT 1000";
$result_users = $conn->query($sql_users);

if ($result_users && $result_users->num_rows > 0) {
    while ($user = $result_users->fetch_assoc()) {
        $fecha_user = date('Y-m-d', strtotime($user['fecha_registro']));
        echo "    <url>\n";
        echo "        <loc>" . htmlspecialchars($dominio . '/profile/' . $user['usuario']) . "</loc>\n";
        echo "        <lastmod>" . $fecha_user . "</lastmod>\n";
        echo "        <changefreq>weekly</changefreq>\n";
        echo "        <priority>0.5</priority>\n";
        echo "    </url>\n";
    }
}

$conn->close();

/**
 * Crear slug amigable para URLs
 */
function crearSlug($texto) {
    $texto = strtolower($texto);
    $texto = preg_replace('/[áàäâ]/u', 'a', $texto);
    $texto = preg_replace('/[éèëê]/u', 'e', $texto);
    $texto = preg_replace('/[íìïî]/u', 'i', $texto);
    $texto = preg_replace('/[óòöô]/u', 'o', $texto);
    $texto = preg_replace('/[úùüû]/u', 'u', $texto);
    $texto = preg_replace('/[ñ]/u', 'n', $texto);
    $texto = preg_replace('/[^a-z0-9\s-]/', '', $texto);
    $texto = preg_replace('/[\s-]+/', '-', $texto);
    $texto = trim($texto, '-');
    return $texto ?: 'publicacion';
}
?>
</urlset>
