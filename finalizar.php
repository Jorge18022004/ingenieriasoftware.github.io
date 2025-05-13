<?php
session_start();

// Verifica si el carrito tiene productos
$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)) {
    echo "<h3>No hay productos en el carrito.</h3>";
    echo '<a href="inicio.php">Volver al inicio</a>';
    exit;
}

// Genera un código aleatorio de pedido
$codigo = strtoupper(substr(md5(uniqid()), 0, 8));

// Aquí puedes guardar el pedido en una base de datos si quieres

// Limpia el carrito
unset($_SESSION['carrito']);
?>

<h2>¡Compra finalizada!</h2>
<p>Tu código de pedido es: <strong><?php echo $codigo; ?></strong></p>
<p>Llévalo contigo para hacer el pago.</p>
<a href="inicio.php">Volver a la tienda</a>