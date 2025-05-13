<?php

require 'config/database.php';
session_start();

$db = new database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio, cantidad FROM productos WHERE activo=1 ORDER BY fecha DESC LIMIT 4");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

$productos_carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$cantidad_carrito = count($productos_carrito);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Bolsos Chic - Tienda Oficial</title>
    <meta name="description" content="Tienda de bolsos de moda">
    <meta name="keywords" content="bolsos, carteras, moda, accesorios, compras">
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
            font-family: 'Roboto', sans-serif;
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
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            margin-top: 20px;
        }

        .product {
            width: 220px;
            background-color: #fafafa;
            border: 1px solid #eee;
            border-radius: 10px;
            text-align: center;
            padding: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product img {
            width: 180px;
            height: 180px;
            border-radius: 10px;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }

        .product-description {
            margin-top: 10px;
            font-size: 15px;
            color: #555;
        }

        .price {
            margin-top: 5px;
            font-weight: bold;
            color: #c84a85;
        }

        .product button {
            background-color: #ff6f61;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .product button:hover {
            background-color: #ff3b2a;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .product button:focus {
            outline: none;
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
    <div style="display: flex; gap: 50px; margin-top: 30px; align-items: flex-start;">
        
        <!-- Aside de "Sobre Nosotros" -->
        <aside style="flex: 1.2; background-color: #f8f8f8; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); height: 800px;">
            <h3>Sobre Nosotros</h3>
            <p>En Bolsos Chic encontrarás una variedad exclusiva de bolsos de moda para toda ocasión. Calidad, estilo y buen precio.</p>
        </aside>

        <!-- Sección principal -->
        <div style="flex: 3;">
            <h1>Carrito de Compras</h1>
            <?php if ($cantidad_carrito > 0): ?>
                <p>Tu carrito tiene <?php echo $cantidad_carrito; ?> productos.</p>
            <?php else: ?>
                <p>Tu carrito está vacío.</p>
            <?php endif; ?>
            <a href="carrito.php">
                <div class="product">
                <button>Ver Carrito</button>
            </div>
            </a>

            <hr style="margin: 30px 0; border: 1px solid #e0e0e0;">

            <h2 style="text-align: center; color: #444;">Novedades</h2>
            <div class="product-grid">
                <?php foreach($resultado as $row) { ?>
                    <div class="product">
                        <img src="imagenes/productos/<?php echo $row['id']; ?>.jpg" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                        <div class="product-description"><?php echo htmlspecialchars($row['nombre']); ?></div>
                        <div><?php 
    $cantidad = (int)$row['cantidad']; // Nos aseguramos de que sea un número entero
    echo htmlspecialchars($cantidad) . ' ' . ($cantidad === 1 ? 'Disponible' : 'Disponibles');
    ?>
    </div>
                        <div class="price">$<?php echo number_format($row['precio'], 2); ?> MXN</div>
                        <form action="agregar_carrito.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button>Agregar al carrito</button>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>
</main>

        <footer>
            <p>&copy; 2025 Luis Mario Vargas Cárdenas, Jorge Rubio Ramírez</p>
            <p>Correo: webstores@wmgcustomerservice.com</p>
        </footer>
    </div>
</body>
</html>