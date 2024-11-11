<?php
session_start();
require_once("conexion.php");
require("Conexion_Cloud.php");
require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

if (!isset($_SESSION["email"])) {
    header("Location: Login_YM.php");
    exit();
}

$con = conectar_bd();

// Validación del artista
$email = $_SESSION["email"];
$consulta = "SELECT CorrArti FROM artistas WHERE CorrArti = ? AND Verificacion IS NOT NULL";
$stmt = $con->prepare($consulta);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    header("Location: Login_YM.php");
    exit();
}

$stmt->close();

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreAlbum = $_POST['NomAlbum'];
    $categoria = $_POST['Categoria'];
    $fechaLanzamiento = $_POST['FechaLan'];
    $nomCred = $email;
    
    // Validar que se haya subido una imagen
    if (!isset($_FILES['ImgAlbu']) || $_FILES['ImgAlbu']['error'] !== UPLOAD_ERR_OK) {
        echo "Error: No se ha subido ninguna imagen o ha ocurrido un error en la subida.";
        exit();
    }

    try {
        // Subir imagen a Cloudinary
        $resultado = (new UploadApi())->upload($_FILES['ImgAlbu']['tmp_name'], [
            "folder" => "Albumes/",  // Carpeta en Cloudinary
            "public_id" => "album_" . uniqid(),  // Nombre único
            "resource_type" => "image",
            // Opcional: Agregar transformaciones de imagen
            "transformation" => [
                [
                    "width" => 800,  // Ancho máximo
                    "height" => 800, // Alto máximo
                    "crop" => "limit",
                    "quality" => "auto"
                ]
            ]
        ]);

        // Obtener la URL segura de la imagen
        $rutaImagen = $resultado['secure_url'];

        // Insertar datos en la base de datos
        $consulta = "INSERT INTO albun (NomAlbum, Categoria, FechaLan, ImgAlbu, NomCred) 
                     VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($consulta);
        $stmt->bind_param("sssss", $nombreAlbum, $categoria, $fechaLanzamiento, $rutaImagen, $nomCred);

        if ($stmt->execute()) {
            $id_insertado = $con->insert_id;
            // Redireccionar a la página de subida de música con el ID del álbum
            header("Location: Subida_Musica.php?album=" . $id_insertado . "&categoria=" . $categoria);
            exit();
        } else {
            // Si hay error en la base de datos, intentar eliminar la imagen de Cloudinary
            try {
                $admin = new Cloudinary\Api\Admin\AdminApi();
                $publicId = $resultado['public_id'];
                $admin->deleteAssets($publicId);
            } catch (Exception $e) {
                error_log("Error al eliminar imagen de Cloudinary después de fallo en BD: " . $e->getMessage());
            }
            
            echo "Error al subir el álbum: " . $stmt->error;
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Error al subir la imagen a Cloudinary: " . $e->getMessage());
        echo "Error al procesar la imagen del álbum. Por favor, inténtelo de nuevo.";
    }
}

$con->close();
?>