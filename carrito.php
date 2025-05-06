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
    unset($_SESSION['carrito'][$idProducto]);
}

if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total = 0;

$productos_carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$cantidad_carrito = count($productos_carrito);
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

        input[type="email"] {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div id="contenedor">
        <header>
            <div id="logo">
                <img src="Imagenes/logo2.png" alt="Logo Tienda">
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
            <a href="contacto.html">Contacto</a>
        </nav>

        <main>
            <aside>
                <h3>Consejo de compra</h3>
                <p>Revisa los productos antes de finalizar tu pedido. Puedes modificar las cantidades o eliminar artículos desde aquí.</p>
            </aside>

            <section>
                <h1>Carrito de Compras</h1>
                <table>
                    <thead>
                        <tr>
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
if (!empty($carrito)) {
    // Obtener los IDs de productos
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
    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
    <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
    <td>$<?php echo number_format($producto['precio'], 2); ?></td>
    <td><?php echo $cantidad; ?></td>
    <td>$<?php echo number_format($subtotal, 2); ?></td>
    <td>
        <a href="?remover=<?php echo $id; ?>" class="accion">Remover</a>
    </td>
</tr>
<?php
    }
} else {
    echo '<tr><td colspan="6">Tu carrito está vacío.</td></tr>';
}
?>
                    </tbody>
                </table>

                <div class="total">
                    Total a pagar: $<?php echo number_format($total, 2); ?> MXN
                </div>

                <div class="acciones">
                    <a href="?vaciar=true"><button>Vaciar Carrito</button></a>
                    <button>Finalizar Compra</button>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; 2025 Luis Mario Vargas Cárdenas, Jorge Rubio Ramírez</p>
            <p>Correo: webstores@wmgcustomerservice.com</p>
            <label for="email">E-mail:</label><br>
            <input type="email" name="email" placeholder="Ingresa tu correo">
        </footer>
    </div>
</body>
</html>