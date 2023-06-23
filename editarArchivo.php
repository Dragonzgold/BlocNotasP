<?php
if (isset($_GET['carpeta']) && isset($_GET['archivo'])) {
    $carpeta = $_GET['carpeta'];
    $archivo = $_GET['archivo'];
    $rutaArchivo = './' . $carpeta . '/' . $archivo;

    if (file_exists($rutaArchivo)) {
        $contenidoArchivo = file_get_contents($rutaArchivo);
    } else {
        echo "<p>Error: El archivo '$archivo' no existe.</p>";
        exit;
    }
} else {
    echo "<p>Error: No se han proporcionado los parámetros necesarios.</p>";
    exit;
}

// Manejar la actualización del archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contText'])) {
        $contenidoActualizado = $_POST['contText'];
        $fp = fopen($rutaArchivo, "w");
        if ($fp) {
            fwrite($fp, $contenidoActualizado, strlen($contenidoActualizado));
            fclose($fp);
            echo "<p class=\"success-message\">Archivo '$archivo' actualizado exitosamente.</p>";
            $contenidoArchivo = $contenidoActualizado; // Actualizar el contenido en la página
        } else {
            echo "<p>Error al actualizar el archivo.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Archivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="styleEdit.css">
</head>
<body>
    <div class="success-message">
        Archivo '<?php echo $archivo; ?>' actualizado exitosamente.
    </div>
    <div class="card custom-card">
        <div class="card-body">
            <?php
            // Obtener el título del archivo sin la extensión
            $tituloArchivo = pathinfo($archivo, PATHINFO_FILENAME);

            // Mostrar el título del archivo
            echo "<h5 class=\"card-title\">$tituloArchivo</h5>";
            ?>

            <form action="" method="post">
                <label for="contText"><b>Contenido</b></label>
                <br><br>
                <textarea name="contText" id="contText" cols="45" rows="10"><?php echo $contenidoArchivo; ?></textarea>
                <br><br>
                <div class="btn-container">
                    <input class="btnUpdate" type="submit" value="Actualizar Archivo">
                    <button class="btnAtras"><a href="index.php" class="btnAtras">Volver atrás</a></button>
                    
                </div>
            </form>
        </div>
    </div>
</body>
</html>