<?php
/**
 * Script de debugging para verificar contraseñas
 * USAR SOLO EN DESARROLLO - ELIMINAR EN PRODUCCIÓN
 */

require_once 'config/config.php';

// Crear conexión
$conexion = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");

echo "<h2>Debug de Contraseñas</h2>";

// Obtener todos los usuarios
$sql = "SELECT id_user, usuario, email, clave, estado FROM users LIMIT 10";
$result = $conexion->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Usuario</th><th>Email</th><th>Clave (MD5)</th><th>Estado</th><th>Longitud</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        $clave_length = strlen($row['clave']);
        $is_md5 = ($clave_length == 32 && ctype_xdigit($row['clave']));
        
        echo "<tr>";
        echo "<td>" . $row['id_user'] . "</td>";
        echo "<td>" . $row['usuario'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . substr($row['clave'], 0, 20) . "...</td>";
        echo "<td>" . $row['estado'] . "</td>";
        echo "<td>" . $clave_length . " " . ($is_md5 ? "✓ MD5" : "✗ NO MD5") . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No hay usuarios en la base de datos.";
}

echo "<hr>";
echo "<h3>Probar Login</h3>";
echo "<form method='POST'>";
echo "Usuario/Email: <input type='text' name='test_user'><br>";
echo "Contraseña: <input type='password' name='test_pass'><br>";
echo "<input type='submit' value='Probar Login'>";
echo "</form>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['test_user']) && isset($_POST['test_pass'])) {
    $test_user = trim($_POST['test_user']);
    $test_pass = trim($_POST['test_pass']);
    $test_pass_md5 = md5($test_pass);
    
    echo "<h4>Resultados de la prueba:</h4>";
    echo "Usuario ingresado: " . htmlspecialchars($test_user) . "<br>";
    echo "Contraseña MD5: " . $test_pass_md5 . "<br><br>";
    
    // Buscar usuario
    $sql = "SELECT * FROM users WHERE usuario=? OR email=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ss', $test_user, $test_user);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        echo "Usuario encontrado: " . $user_data['usuario'] . "<br>";
        echo "Clave en BD: " . $user_data['clave'] . "<br>";
        echo "Clave ingresada (MD5): " . $test_pass_md5 . "<br>";
        
        if ($user_data['clave'] === $test_pass_md5) {
            echo "<strong style='color:green'>✓ Las contraseñas COINCIDEN</strong><br>";
        } else {
            echo "<strong style='color:red'>✗ Las contraseñas NO coinciden</strong><br>";
            echo "Diferencia: " . ($user_data['clave'] !== $test_pass_md5 ? "Son diferentes" : "Son iguales") . "<br>";
        }
        
        // Probar con la consulta completa
        $sql2 = "SELECT * FROM users WHERE (email=? OR usuario=?) AND clave=?";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bind_param('sss', $test_user, $test_user, $test_pass_md5);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        
        echo "<br>Resultado de consulta completa: " . ($result2->num_rows > 0 ? "<strong style='color:green'>✓ Login exitoso</strong>" : "<strong style='color:red'>✗ Login fallido</strong>");
    } else {
        echo "<strong style='color:red'>Usuario no encontrado</strong>";
    }
}

$conexion->close();
?>
