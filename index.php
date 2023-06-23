<?php
session_start();

// Función para crear una carpeta
function crearCarpeta($nombre)
{
    $folder = getcwd();
    $ruta = $folder . '/' . $nombre;
    
    if (!is_dir($ruta)) {
        if (mkdir($ruta, 0777)) {
            echo "<p class='success-messageC'>Carpeta '$nombre' creada exitosamente.</p>";
            echo "<meta http-equiv='refresh' content='3'>";
        } else {
            echo "<p>Error al crear la carpeta.</p>";
        }
    } else {
        echo "<p>La carpeta '$nombre' ya existe.</p>";
    }
}

// Función para guardar un archivo de texto en el directorio actual
function guardarArchivo($titulo, $contenido)
{
    $folder = getcwd();
    $file = $folder . '/' . $titulo . '.txt';
    $texto = $contenido;
    $fp = fopen($file, "w");
    if ($fp) {
        fwrite($fp, $texto, strlen($texto));
        fclose($fp);
        echo "<p class='success-message'>Archivo '$titulo.txt' guardado exitosamente.</p>";
        echo "<meta http-equiv='refresh' content='3'>";
    } else {
        echo "<p>Error al guardar el archivo.</p>";
    }
}

// Función para mostrar el contenido de una carpeta
function mostrarArchivosTxt($carpeta)
{
    $rutaCarpeta = './' . $carpeta;
    $archivos = scandir($rutaCarpeta);
    $archivos = array_filter($archivos, function ($archivo) {
        return pathinfo($archivo, PATHINFO_EXTENSION) === 'txt';
    });

    if (count($archivos) > 0) {
        echo "<h2>Archivos .txt:</h2>";
        echo "<ul>";
        foreach ($archivos as $archivo) {
            echo "<li><strong>Archivo:</strong> $archivo 
                  <button onclick=\"verArchivo('$carpeta', '$archivo')\">Ver</button>
                  <form action='index.php' method='post' style='display:inline'>
                      <input type='hidden' name='eliminarArchivo' value='1'>
                      <input type='hidden' name='carpeta' value='$carpeta'>
                      <input type='hidden' name='archivo' value='$archivo'>
                      <input type='submit' value='Eliminar'>
                  </form>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron archivos .txt en esta carpeta.</p>";
    }
}

function mostrarCarpetas($carpeta)
{
    $rutaCarpeta = './' . $carpeta;
    $carpetas = scandir($rutaCarpeta);
    $carpetas = array_filter($carpetas, function ($elemento) use ($rutaCarpeta) {
        return is_dir($rutaCarpeta . '/' . $elemento) && !in_array($elemento, ['.', '..']);
    });

    if (count($carpetas) > 0) {
        echo "<h2>Carpetas:</h2>";
        echo "<ul>";
        foreach ($carpetas as $carpeta) {
            echo "<li><strong>Carpeta:</strong> $carpeta 
                  <button onclick=\"verContenido('$carpeta')\">Ver Contenido</button>
                  <form action='index.php' method='post' style='display:inline'>
                      <input type='hidden' name='eliminarCarpeta' value='1'>
                      <input type='hidden' name='carpeta' value='$carpeta'>
                      <input type='submit' value='Eliminar'>
                  </form>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron carpetas en esta carpeta.</p>";
    }
}

// Función para eliminar un archivo
function eliminarArchivo($carpeta, $archivo)
{
    $rutaArchivo = './' . $carpeta . '/' . $archivo;

    if (file_exists($rutaArchivo)) {
        unlink($rutaArchivo);
        echo "<p class='success-message-red'>Archivo '$archivo' eliminado exitosamente.</p>";
        echo "<meta http-equiv='refresh' content='3'>";
    } else {
        echo "<p>Error: El archivo '$archivo' no existe.</p>";
    }
}

// Función para eliminar una carpeta y su contenido recursivamente
function eliminarCarpeta($nombre)
{
    $folder = getcwd();
    $ruta = $folder . '/' . $nombre;
    
    if (is_dir($ruta)) {
        if (rmdir($ruta)) {
            echo "<p class='success-message-red'>Carpeta '$nombre' eliminada exitosamente.</p>";
            echo "<meta http-equiv='refresh' content='3'>";
        } else {
            echo "<p>Error al eliminar la carpeta.</p>";
        }
    } else {
        echo "<p>La carpeta '$nombre' no existe.</p>";
    }
}

// Manejar la creación de carpeta y guardado de archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['titleText']) && isset($_POST['contText'])) {
        $titleText = $_POST['titleText'];
        $contText = $_POST['contText'];
        guardarArchivo($titleText, $contText);
    }

    if (isset($_POST['nombreCarpeta'])) {
        $nombreCarpeta = $_POST['nombreCarpeta'];
        crearCarpeta($nombreCarpeta);
    }

    if (isset($_POST['eliminarArchivo']) && isset($_POST['carpeta']) && isset($_POST['archivo'])) {
        $carpeta = $_POST['carpeta'];
        $archivo = $_POST['archivo'];
        eliminarArchivo($carpeta, $archivo);
    }

    if (isset($_POST['eliminarCarpeta']) && isset($_POST['carpeta'])) {
        $carpeta = $_POST['carpeta'];
        eliminarCarpeta($carpeta);
    }
}

// Mostrar el contenido de la carpeta seleccionada
if (isset($_POST['verContenido']) && isset($_POST['carpeta'])) {
    $carpetaSeleccionada = $_POST['carpeta'];
    $_SESSION['carpetaSeleccionada'] = $carpetaSeleccionada;
}

// Manejar la navegación entre directorios
if (isset($_POST['ruta'])) {
    $rutaSeleccionada = $_POST['ruta'];
    $_SESSION['carpetaSeleccionada'] = $rutaSeleccionada;
}

// Función para obtener la ruta de la carpeta anterior
function obtenerRutaAnterior()
{
    $rutaAnterior = '';

    if (isset($_SESSION['carpetaSeleccionada'])) {
        $carpetaSeleccionada = $_SESSION['carpetaSeleccionada'];
        $carpetas = explode('/', $carpetaSeleccionada);
        array_pop($carpetas);
        $rutaAnterior = implode('/', $carpetas);
    }

    return $rutaAnterior;
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloc de notas P</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: end;
            margin-top: -70vh;
            margin-right: 15vw;
        }

        .folder-container {
            margin-bottom: 20px;
            text-align: center;
            color: white;
        }

        .folder-container h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .folder-container ul {
            padding-left: 0;
            list-style-type: none;
        }

        .folder-container li {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .btn-back {
            background-color: gray;
            border: 2px solid black;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 16px;
            color: white;
            cursor: pointer;
        }

        .btn-back:hover {
            background-color: darkgray;
        }

        .success-message {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 128, 0, 0.8);
        padding: 10px;
        color: white;
        z-index: 9999;
        transition: opacity 0.5s;
        opacity: 1;
    }

    .success-message.hide {
        opacity: .5;
    }

    .success-messageC {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #C8D9E2;
        padding: 10px;
        color: black;
        z-index: 9999;
        transition: opacity 0.5s;
        opacity: 1;
    }

    .success-messageC.hide {
        opacity: 0;
    }

    .success-message-red {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #FF0000;
        padding: 10px;
        color: white;
        z-index: 9999;
        transition: opacity 0.5s;
        opacity: 1;
    }

    .success-message-red.hide {
        opacity: 0;
    }
    </style>

    <script>
        function verContenido(carpeta) {
            document.getElementById('carpeta').value = carpeta;
            document.getElementById('verContenidoForm').submit();
        }

        function volverAtras() {
            document.getElementById('ruta').value = '<?php echo obtenerRutaAnterior(); ?>';
            document.getElementById('navegarForm').submit();
        }

        function verArchivo(carpeta, archivo) {
            window.location.href = 'editarArchivo.php?carpeta=' + carpeta + '&archivo=' + archivo;
        }       
    </script>
    <style>
        #tabla-carpeta {
            display: flex;
            justify-content: space-evenly;
            align-items: flex-start;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Bloc de notas personal</h1>
    </div>
    <form action="index.php" method="post">
        <div class="title-input">
            <label for="titleText">Título del texto</label>
            <input type="text" name="titleText" id="titleText" placeholder="Introduzca su título">
        </div>
        <div class="title-input">
            <label for="contText">Contenido</label>
            <textarea placeholder="Introduzca todo lo que quiera aqui" name="contText" id="contText"></textarea>
        </div>
        <div class="btn-save">
            <input type="submit" value="Guardar Texto">
        </div>
    </form>

    <form action="index.php" method="post">
        <div class="create-folder">
            <label for="nombreCarpeta">Nombre de la carpeta (opcional)</label>
            <input placeholder="Aqui puede crear una carpeta" type="text" name="nombreCarpeta" id="nombreCarpeta">
            <input type="submit" value="Crear Carpeta">
        </div>
    </form>

    <form id="verContenidoForm" action="index.php" method="post" style="display: none;">
        <input type="hidden" name="verContenido" value="1">
        <input type="hidden" name="carpeta" id="carpeta">
    </form>

    <form id="navegarForm" action="index.php" method="post" style="display: none;">
        <input type="hidden" name="ruta" id="ruta">
    </form>

    <div class="container">
        <?php
        if (isset($_SESSION['carpetaSeleccionada'])) {
            $carpetaSeleccionada = $_SESSION['carpetaSeleccionada'];

            echo "<div class='folder-container'>";
            echo "<h2>Archivos .txt:</h2>";
            mostrarArchivosTxt($carpetaSeleccionada);
            echo "</div>";

            echo "<div class='folder-container'>";
            echo "<h2>Carpetas:</h2>";
            mostrarCarpetas($carpetaSeleccionada);
            echo "</div>";
        } else {
            echo "<p><strong>No se ha seleccionado ninguna carpeta.</strong></p>";
        }
        ?>

        <button class="btn-back" onclick="volverAtras()">Volver atrás</button>
    </div>

    <form id="verContenidoForm" action="index.php" method="post" style="display: none;">
        <input type="hidden" name="verContenido" value="1">
        <input type="hidden" name="carpeta" id="carpeta">
    </form>
</body>

</html>