<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id_orden = isset($_GET['id_orden']) ? intval($_GET['id_orden']) : 0;
$recibo_total = 0;
$recibo_detalle = '';

// Get order details
$stmt = $conn->prepare("SELECT email, total FROM ordenes WHERE id_orden = ?");
$stmt->bind_param("i", $id_orden);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($email, $total_pagado);
$stmt->fetch();
$stmt->close();

if ($email) {
    // Calculate total amount
    $stmt = $conn->prepare("SELECT j.titulo, j.precio, d.cantidad FROM detalle_ordenes d JOIN juegos_fisicos j ON d.id_juego = j.id_juego WHERE d.id_orden = ?");
    $stmt->bind_param("i", $id_orden);
    $stmt->execute();
    $stmt->bind_result($titulo_juego, $precio_unitario, $cantidad);

    while ($stmt->fetch()) {
        $subtotal = $precio_unitario * $cantidad;
        $recibo_total += $subtotal;
        $recibo_detalle .= "Juego: $titulo_juego, Precio Unitario: $precio_unitario, Cantidad: $cantidad, Subtotal: $subtotal\n";
    }
    $stmt->close();

    $cambio = $total_pagado - $recibo_total;

    // Insert receipt
    $stmt = $conn->prepare("INSERT INTO recibos (id_orden, total, detalle) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $id_orden, $recibo_total, $recibo_detalle);
    if ($stmt->execute()) {
        echo "<h1>Recibo</h1>";
        echo "<p><strong>Correo Electrónico:</strong> $email</p>";
        echo "<p><strong>Total Pagado:</strong> $total_pagado</p>";
        echo "<p><strong>Total Compra:</strong> $recibo_total</p>";
        echo "<p><strong>Cambio:</strong> $cambio</p>";
        echo "<h2>Detalle de la Compra:</h2>";
        echo "<pre>$recibo_detalle</pre>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: No se encontró el pedido.";
}

$conn->close();
?>
