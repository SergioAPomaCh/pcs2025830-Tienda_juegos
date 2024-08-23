<?php
// Load environment variables
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection
$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $total = $_POST['total'];
    
    // Insert order
    $stmt = $conn->prepare("INSERT INTO ordenes (email, total) VALUES (?, ?)");
    $stmt->bind_param("sd", $email, $total);
    
    if ($stmt->execute()) {
        $id_orden = $stmt->insert_id; // Get the last inserted ID

        // Calculate total amount and redirect to recibo.php
        $redirect_url = 'recibo.php?id_orden=' . $id_orden;
        header('Location: ' . $redirect_url);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pedido</title>
</head>
<body>
    <h1>Realizar un Pedido de Compra</h1>
    <form method="POST" action="">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="total">Monto Total:</label>
        <input type="number" step="0.01" id="total" name="total" required><br><br>

        <h2>Artículos del Pedido</h2>
        <div id="items">
            <div class="item">
                <label for="nombre_juego">Nombre del Juego:</label>
                <input type="text" name="items[0][nombre_juego]" required><br>
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="items[0][cantidad]" required><br><br>
            </div>
        </div>
        <button type="button" onclick="addItem()">Agregar Más Artículos</button><br><br>
        <input type="submit" value="Realizar Pedido">
    </form>

    <script>
        let itemCount = 1;
        function addItem() {
            const itemsDiv = document.getElementById('items');
            const newItemDiv = document.createElement('div');
            newItemDiv.classList.add('item');
            newItemDiv.innerHTML = `
                <label for="nombre_juego">Nombre del Juego:</label>
                <input type="text" name="items[${itemCount}][nombre_juego]" required><br>
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="items[${itemCount}][cantidad]" required><br><br>
            `;
            itemsDiv.appendChild(newItemDiv);
            itemCount++;
        }
    </script>
</body>
</html>
