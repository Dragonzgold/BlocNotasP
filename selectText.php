<?php
function seleccionarArchivoTXT() {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se seleccionó un archivo
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
      $archivoTmp = $_FILES['archivo']['tmp_name'];
      $nombreArchivo = $_FILES['archivo']['name'];
      
      $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
      if ($extension === 'txt') {
        $contenido = file_get_contents($archivoTmp);
        
        return $contenido;
      } else {
        return 'Error: El archivo seleccionado no es un archivo .TXT';
      }
    } else {
      return 'Error: No se seleccionó ningún archivo.';
    }
  }
}

// Ejemplo de uso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $contenidoArchivo = seleccionarArchivoTXT();
  if ($contenidoArchivo) {
    echo 'Contenido del archivo: <br>';
    echo nl2br(htmlspecialchars($contenidoArchivo));
  }
}


if (isset($_POST['editarTexto'])){
  $textoEditado = $_POST['textoEditado'];
  $archivo = 'archivoEditado.txt';

  $fp = fopen($archivo, "w");
  fwrite($fp, $textoEditado);
  fclose($fp);

  echo '<p>Archivo editado y guardado con exito</p>';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bloc de notas</title>
</head>
<body>
</body>
</html>