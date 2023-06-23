<?php
if (isset($_GET['carpeta']) && isset($_GET['archivo'])) {
    $carpeta = $_GET['carpeta'];
    $archivo = $_GET['archivo'];
    $rutaArchivo = './' . $carpeta . '/' . $archivo;

    if (pathinfo($rutaArchivo, PATHINFO_EXTENSION) === 'txt') {
        if (file_exists($rutaArchivo)) {
            $contenido = file_get_contents($rutaArchivo);
            echo "<h2>Contenido de $archivo:</h2>";
            echo "<pre>$contenido</pre>";
        } else {
            echo "<p>Error: El archivo '$archivo' no existe.</p>";
        }
    } else {
        echo "<p>Error: El archivo '$archivo' no es un archivo de texto.</p>";
    }
} else {
    echo "<p>Error: No se proporcionó el nombre de la carpeta y el archivo.</p>";
}
?>
<a href="index.php"><button>Volver</button></a>