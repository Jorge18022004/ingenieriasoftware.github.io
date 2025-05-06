<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// RECIBIR EL ID DEL PRODUCTO POR POST
$id_producto = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id_producto > 0) {  
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto] += 1;
    } else {
        $_SESSION['carrito'][$id_producto] = 1;
    }
}

// Redirigir al carrito
header('Location: carrito.php');
exit;
?>