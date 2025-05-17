<?php
session_start();

// Verifica si el carrito tiene productos
$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)) {
    echo '
    <html>
    <head>
        <title>Carrito vacío</title>
        <style>
            body {
                font-family: "Segoe UI", sans-serif;
                background-color: #f9f9f9;
                text-align: center;
                padding: 60px;
                color: #444;
            }
            a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #ff4d4f;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            a:hover {
                background-color: #d9363e;
            }
        </style>
    </head>
    <body>
        <h3>No hay productos en el carrito.</h3>
        <a href="inicio.php">Volver al inicio</a>
    </body>
    </html>';
    exit;
}

// Genera un código aleatorio de pedido
$codigo = strtoupper(substr(md5(uniqid()), 0, 8));

// Aquí puedes guardar el pedido en una base de datos si quieres

// Limpia el carrito
unset($_SESSION['carrito']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Finalizada</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background-color: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
        }
        .card h2 {
            color: #26a69a;
        }
        .card p {
            font-size: 18px;
            color: #555;
        }
        .codigo {
            font-size: 24px;
            color: #00796b;
            font-weight: bold;
            margin: 20px 0;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: #26a69a;
            padding: 12px 24px;
            border-radius: 8px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #00796b;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>¡Compra finalizada! 🛍️</h2>
        <p>Tu código de pedido es:</p>
        <div class="codigo"><?php echo $codigo; ?></div>
        <p>Llévalo contigo para hacer el pago.</p>
        <a href="inicio.php">Volver a la tienda</a>
    </div>
</body>
</html>