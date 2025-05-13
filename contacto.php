<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y limpiar datos
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $correo = filter_var(trim($_POST["correo"]), FILTER_SANITIZE_EMAIL);
    $asunto = htmlspecialchars(trim($_POST["asunto"]));
    $mensaje = htmlspecialchars(trim($_POST["mensaje"]));

    // Validar campos
    if (!empty($nombre) && !empty($correo) && !empty($asunto) && !empty($mensaje) && filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        
        $destinatario = "webstores@wmgcustomerservice.com"; // Cambia esto si lo necesitas
        $titulo = "Nuevo mensaje desde el formulario de contacto";
        $contenido = "Nombre: $nombre\nCorreo: $correo\nAsunto: $asunto\nMensaje:\n$mensaje";
        $cabeceras = "From: $correo";

        if (mail($destinatario, $titulo, $contenido, $cabeceras)) {
            echo "<h2>¡Gracias por contactarnos, $nombre!</h2><p>Hemos recibido tu mensaje y te responderemos pronto.</p>";
        } else {
            echo "<h2>Oops... ocurrió un error.</h2><p>No pudimos enviar tu mensaje. Inténtalo más tarde.</p>";
        }
    } else {
        echo "<h2>Error en los datos enviados.</h2><p>Verifica que todos los campos estén completos y el correo sea válido.</p>";
    }
}
$productos_carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$cantidad_carrito = count($productos_carrito);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Contacto - Bolsos Chic</title>
    <meta name="description" content="Contacto directo con la tienda de bolsos de moda">
    <meta name="keywords" content="contacto, atención al cliente, bolsos, tienda, correo">
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

        .contact-info {
            line-height: 1.8;
            font-size: 16px;
            margin-top: 20px;
        }

        .contact-info strong {
            color: #bb4a84;
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
                <h3>Atención Personalizada</h3>
                <p>¿Tienes dudas, sugerencias o quieres saber más sobre nuestros productos? Estamos aquí para ayudarte.</p>
            </aside>

            <section>
    <h1>Contacto</h1>
    <form action="enviar_contacto.php" method="post" style="display: flex; flex-direction: column; gap: 12px;">
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre">

        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" required placeholder="tu@correo.com">

        <label for="asunto">Asunto:</label>
        <input type="text" id="asunto" name="asunto" required placeholder="Motivo del mensaje">

        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" rows="6" required placeholder="Escribe tu mensaje aquí..."></textarea>

        <button type="submit" style="background-color: #bb4a84; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
            Enviar mensaje
        </button>
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