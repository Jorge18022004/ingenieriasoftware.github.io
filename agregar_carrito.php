<?php
session_start();
require 'config/database.php';

$db = new database();
$con = $db->conectar();

// Asegurar que el carrito esté definido
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

$id_producto = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id_producto > 0) {
    // Consultar la cantidad actual y el estado activo del producto
    $stmt = $con->prepare("SELECT cantidad, activo FROM productos WHERE id = ?");
    $stmt->execute([$id_producto]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        if ($producto['cantidad'] > 0) {
            // Reducir la cantidad y activar el producto
            $nuevaCantidad = $producto['cantidad'];
            $activo = 1; // Producto activado si tiene cantidad mayor que 0

            // Actualizar la base de datos con la nueva cantidad y estado activo
            $update = $con->prepare("UPDATE productos SET cantidad = ?, activo = ? WHERE id = ?");
            $update->execute([$nuevaCantidad, $activo, $id_producto]);

            // Agregar o actualizar el producto en el carrito de la sesión
            if (isset($_SESSION['carrito'][$id_producto])) {
                $_SESSION['carrito'][$id_producto] = 1; // Incrementar cantidad
            } else {
                $_SESSION['carrito'][$id_producto] = 1; // Agregar al carrito
            }
        } else {
            $_SESSION['error'] = "Producto agotado.";
        }
    } else {
        $_SESSION['error'] = "Producto no encontrado.";
    }
}

header('Location: carrito.php');
exit;
?>