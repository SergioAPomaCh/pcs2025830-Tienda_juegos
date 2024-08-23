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

// Insertar o actualizar datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_juego = $_POST['id_juego'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $plataforma = $_POST['plataforma'] ?? '';
    $desarrollador = $_POST['desarrollador'] ?? '';
    $genero = $_POST['genero'] ?? '';

    if ($_POST['action'] == 'create') {
        $stmt = $conn->prepare("INSERT INTO juegos_fisicos (titulo, descripcion, precio, stock, plataforma, desarrollador, genero) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdisss", $titulo, $descripcion, $precio, $stock, $plataforma, $desarrollador, $genero);
        $stmt->execute();
        $stmt->close();
    } elseif ($_POST['action'] == 'update' && $id_juego) {
        $stmt = $conn->prepare("UPDATE juegos_fisicos SET titulo=?, descripcion=?, precio=?, stock=?, plataforma=?, desarrollador=?, genero=? WHERE id_juego=?");
        $stmt->bind_param("ssdisssi", $titulo, $descripcion, $precio, $stock, $plataforma, $desarrollador, $genero, $id_juego);
        $stmt->execute();
        $stmt->close();
    }
}

// Eliminar un juego
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id_juego = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM juegos_fisicos WHERE id_juego=?");
    $stmt->bind_param("i", $id_juego);
    $stmt->execute();
    $stmt->close();
}

// Leer y mostrar los juegos
$result = $conn->query("SELECT * FROM juegos_fisicos");
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Juegos Físicos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="nav-links">
            <a href="#">Option 1</a>
            <a href="#">Option 2</a>
            <a href="#">Option 3</a>
        </div>
        <button class="logout-button">Delogeo</button>
    </div>

    <h1>CRUD de Juegos Físicos</h1>

    <!-- Formulario para crear un nuevo juego -->
    <form action="juegos_fis.php" method="POST">
        <input type="hidden" name="id_juego" id="id_juego">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"></textarea>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" id="precio" name="precio" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>

        <label for="plataforma">Plataforma:</label>
        <input type="text" id="plataforma" name="plataforma">

        <label for="desarrollador">Desarrollador:</label>
        <input type="text" id="desarrollador" name="desarrollador">

        <label for="genero">Género:</label>
        <input type="text" id="genero" name="genero">

        <button type="submit" name="action" value="create">Crear Juego</button>
    </form>

    <h2>Listado de Juegos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Plataforma</th>
                <th>Desarrollador</th>
                <th>Género</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_juego']); ?></td>
                        <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($row['precio']); ?></td>
                        <td><?php echo htmlspecialchars($row['stock']); ?></td>
                        <td><?php echo htmlspecialchars($row['plataforma']); ?></td>
                        <td><?php echo htmlspecialchars($row['desarrollador']); ?></td>
                        <td><?php echo htmlspecialchars($row['genero']); ?></td>
                        <td>
                            <a href="editarjuegos.php?id_juego=<?php echo htmlspecialchars($row['id_juego']); ?>">Editar</a>
                            <a href="?delete=<?php echo htmlspecialchars($row['id_juego']); ?>" onclick="return confirm('¿Está seguro de que desea eliminar este juego?');">Eliminar</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="9">No hay juegos disponibles.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>


<?php
$conn->close();
?>
