<?php
// Cargar las variables de entorno
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Conectar a la base de datos
$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cargar los datos del juego para editar
$id_juego = $_GET['id_juego'] ?? null;
$form_data = null;
if ($id_juego) {
    $stmt = $conn->prepare("SELECT * FROM juegos_fisicos WHERE id_juego=?");
    $stmt->bind_param("i", $id_juego);
    $stmt->execute();
    $result = $stmt->get_result();
    $form_data = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $plataforma = $_POST['plataforma'] ?? '';
    $desarrollador = $_POST['desarrollador'] ?? '';
    $genero = $_POST['genero'] ?? '';

    if ($id_juego) {
        $stmt = $conn->prepare("UPDATE juegos_fisicos SET titulo=?, descripcion=?, precio=?, stock=?, plataforma=?, desarrollador=?, genero=? WHERE id_juego=?");
        $stmt->bind_param("ssdisssi", $titulo, $descripcion, $precio, $stock, $plataforma, $desarrollador, $genero, $id_juego);
        $stmt->execute();
        $stmt->close();
        header("Location: juegos_fis.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Juego Físico</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Juego Físico</h1>

    <!-- Formulario para editar un juego -->
    <form action="editarjuegos.php?id_juego=<?php echo htmlspecialchars($id_juego); ?>" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required value="<?php echo htmlspecialchars($form_data['titulo'] ?? ''); ?>">

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($form_data['descripcion'] ?? ''); ?></textarea>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" id="precio" name="precio" required value="<?php echo htmlspecialchars($form_data['precio'] ?? ''); ?>">

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required value="<?php echo htmlspecialchars($form_data['stock'] ?? ''); ?>">

        <label for="plataforma">Plataforma:</label>
        <input type="text" id="plataforma" name="plataforma" value="<?php echo htmlspecialchars($form_data['plataforma'] ?? ''); ?>">

        <label for="desarrollador">Desarrollador:</label>
        <input type="text" id="desarrollador" name="desarrollador" value="<?php echo htmlspecialchars($form_data['desarrollador'] ?? ''); ?>">

        <label for="genero">Género:</label>
        <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($form_data['genero'] ?? ''); ?>">

        <button type="submit">Actualizar Juego</button>
    </form>

    <a href="juegos_fis.php">Volver a la lista</a>
</body>
</html>

<?php
$conn->close();
?>
