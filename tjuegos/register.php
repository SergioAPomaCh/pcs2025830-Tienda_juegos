<?php
// Cargar variables de entorno desde el archivo .env
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configuración de la base de datos
$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre_usuario = $_POST['nombre_usuario'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$rol = $_POST['rol'];

// Insertar datos en la base de datos
$sql = "INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre_usuario, $email, $password, $rol);

if ($stmt->execute()) {
    echo "Registro exitoso. <a href='login.html'>Iniciar Sesión</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
