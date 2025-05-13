<?php
session_start();
require 'config/database.php';
$db = new database();
$con = $db->conectar();

function actualizarCarrito($idProducto, $nuevaCantidad) {
    if ($nuevaCantidad <= 0) {
        unset($_SESSION['carrito'][$idProducto]);
    } else {
        $_SESSION['carrito'][$idProducto] = $nuevaCantidad;
    }
}

if (isset($_GET['remover'])) {
    $idProducto = (int) $_GET['remover'];

    // Verificar si el producto existe en el carrito
    if (isset($_SESSION['carrito'][$idProducto])) {
        // Consultar la cantidad actual del producto en la base de datos
        $stmt = $con->prepare("SELECT cantidad FROM productos WHERE id = ?");
        $stmt->execute([$idProducto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            // Restaurar la cantidad en la base de datos (sumar la cantidad eliminada)
            $cantidad_en_carrito = $_SESSION['carrito'][$idProducto];
            $nuevaCantidad = $producto['cantidad'];

            // Actualizar la base de datos con la nueva cantidad
            $update = $con->prepare("UPDATE productos SET cantidad = ? WHERE id = ?");
            $update->execute([$nuevaCantidad, $idProducto]);

            // Eliminar el producto del carrito
            unset($_SESSION['carrito'][$idProducto]);

            $_SESSION['mensaje'] = "Carrito vacio";
        } else {
            $_SESSION['error'] = "Producto no encontrado en la base de datos.";
        }
    }
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
$carrito = &$_SESSION['carrito'];

// Procesar actualización de cantidades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cantidades'])) {
    foreach ($_POST['cantidades'] as $id => $cantidad) {
        $id = (int)$id;
        $cantidad = (int)$cantidad;

        if ($cantidad > 0) {
            $carrito[$id] = $cantidad;
        } else {
            unset($carrito[$id]);
        }
    }
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total = 0;

$productos_carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$cantidad_carrito = count($productos_carrito);

if (isset($_GET['vaciar'])) {

    // Verificar si el carrito no está vacío
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        
        // Recorrer todos los productos en el carrito
        foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
            
            // Consultar la cantidad actual del producto en la base de datos
            $stmt = $con->prepare("SELECT cantidad FROM productos WHERE id = ?");
            $stmt->execute([$idProducto]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($producto) {
                // Restaurar la cantidad en la base de datos (sumar la cantidad eliminada)
                $nuevaCantidad = $producto['cantidad'];

                // Actualizar la base de datos con la nueva cantidad
                $update = $con->prepare("UPDATE productos SET cantidad = ? WHERE id = ?");
                $update->execute([$nuevaCantidad, $idProducto]);
            }
        }

        // Vaciar el carrito
        unset($_SESSION['carrito']);

        // Mensaje de éxito
        $_SESSION['mensaje'] = "El carrito ha sido vaciado.";
    } else {
        $_SESSION['error'] = "El carrito ya está vacío.";
    }
    
    // Redirigir para evitar la recarga del formulario
    header('Location: carrito.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Carrito de Compras - Bolsos Chic</title>
    <meta name="description" content="Tu carrito de compras con los bolsos más chic">
    <meta name="keywords" content="carrito, compras, bolsos, accesorios, tienda">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #fafafa;
            color: #333;
        }

        #contenedor {
            max-width: 1400px;
            margin: auto;
            padding: 10px 20px;
        }

        header {
            background: linear-gradient(120deg, #fbd3e9 0%, #bbdff7 100%);
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        #logo img {
            width: 90px;
            height: 90px;
        }

        #slogan h1 {
            font-size: 26px;
            font-weight: 700;
            color: #2c2c2c;
            margin: 0;
        }

        nav {
            background-color: #ffffff;
            padding: 10px 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin: 15px 0;
            border-top: 2px solid #e4e4e4;
            border-bottom: 2px solid #e4e4e4;
        }

        nav a {
            text-decoration: none;
            color: #444;
            padding: 10px;
            font-weight: 500;
            transition: transform 0.3s, background-color 0.3s;
            border-radius: 4px;
        }

        nav a:hover {
            background-color: #ffe3f0;
            color: #bb4a84;
            transform: translateY(-2px);
        }

        main {
            display: flex;
            gap: 20px;
        }

        aside {
            background-color: #ffffff;
            width: 280px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        section {
            flex: 1;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: 600;
        }

        .product-img {
            width: 80px;
            border-radius: 8px;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .acciones {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .acciones button {
            padding: 10px 20px;
            background-color: #cce5e0;
            border: none;
            cursor: pointer;
            font-weight: bold;
            color: #333;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .acciones button:hover {
            background-color: #b7d8d3;
        }

        footer {
            background-color: #f8f8f8;
            text-align: center;
            padding: 30px 20px;
            margin-top: 40px;
            border-top: 1px solid #ddd;
            border-radius: 8px;
            color: #666;
        }

        input[type="number"] {
            width: 60px;
            padding: 6px;
            text-align: center;
            margin: 0;
        }
    </style>
</head>
<body>
    <div id="contenedor">
        <header>
            <div id="logo">
                <img src="imagenes/bolsos_chic.png" alt="Logo Tienda">
            </div>
            <div id="slogan">
                <h1>Tu bolso ideal te espera aquí</h1>
            </div>
        </header>

        <nav>
            <a href="inicio.php">Inicio</a>
            <a href="producto.php">Productos</a>
            <a href="categorias.html">Categorías</a>
            <a href="carrito.php">Carrito (<?php echo $cantidad_carrito; ?>)</a>
            <a href="contacto.php">Contacto</a>
        </nav>

        <main>
            <aside>
                <h3>Consejo de compra</h3>
                <p>Revisa los productos antes de finalizar tu pedido. Puedes modificar las cantidades o eliminar artículos desde aquí.</p>
            </aside>

            <section>
                <h1>Carrito de Compras</h1>

                <?php
                    if (isset($_SESSION['mensaje'])) {
                        echo "<div style='background-color: #d4edda; padding: 10px; margin-bottom: 10px;'>{$_SESSION['mensaje']}</div>";
                        unset($_SESSION['mensaje']);
                    }

                    if (isset($_SESSION['error'])) {
                        echo "<div style='background-color: #f8d7da; padding: 10px; margin-bottom: 10px;'>{$_SESSION['error']}</div>";
                        unset($_SESSION['error']);
                    }
                ?>

                <form method="POST" action="carrito.php">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            if (!empty($carrito)) {
                $ids = implode(',', array_keys($carrito));
                $sql = $con->prepare("SELECT id, nombre, descripcion, precio FROM productos WHERE id IN ($ids)");
                $sql->execute();
                $productos = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach ($productos as $producto) {
                    $id = $producto['id'];
                    $cantidad = $carrito[$id];
                    $subtotal = $producto['precio'] * $cantidad;
                    $total += $subtotal;
            ?>
            <tr>
                <td><img class="product-img" src="imagenes/productos/<?php echo $producto['id']; ?>.jpg" alt="<?php echo htmlspecialchars($producto['nombre']); ?>"></td>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                <td>
                    <input type="number" name="cantidades[<?php echo $id; ?>]" value="<?php echo $cantidad; ?>" min="1" style="width: 60px;">
                </td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td><a href="carrito.php?remover=<?php echo $producto['id']; ?>" style="color: red;">Eliminar</a></td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="7">Tu carrito está vacío.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="total">
        Total a pagar: $<?php echo number_format($total, 2); ?> MXN
    </div>

    <div class="acciones">
        <a href="carrito.php?vaciar=true"><button type="button">Vaciar carrito</button></a>
        <button type="submit" name="actualizar">Actualizar carrito</button>
        <a href="finalizar.php"><button type="button">Finalizar compra</button></a>
    </div>
</form>
            </section>
        </main>

        <footer>
            <p>&copy; 2025 Luis Mario Vargas Cárdenas, Jorge Rubio Ramírez</p>
            <p>Correo: webstores@wmgcustomerservice.com</p>
        </footer>
    </div>
</body>
</html>