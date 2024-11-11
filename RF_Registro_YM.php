<?php
require_once("conexion.php");
require("Header_YM.php");
require("Funciones.php");
$con = conectar_bd();
require("Conexion_Cloud.php");
use Cloudinary\Api\Upload\UploadApi;

    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $contrasenia = $_POST["pass"];
    $ubicacion = $_POST["Ubicación"];
    $biografia = $_POST["biografia"];

    // Verificar si se ha subido un archivo
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $tipoArchivo = $_FILES["file"]["type"];
        $tamañoArchivo = $_FILES["file"]["size"];
        $rutaTemporal = $_FILES["file"]["tmp_name"];

        // Validar si el archivo es una imagen y no excede los 2MB
        if (strpos($tipoArchivo, "image") !== false && $tamañoArchivo <= 2000000) {
            try {
                // Subir la imagen a Cloudinary usando la clase UploadApi
                $resultado = (new UploadApi())->upload($rutaTemporal, [
                    "folder" => "Subida/",  // Carpeta en Cloudinary donde se guardará la imagen
                    "public_id" => "imagen_" . uniqid(),  // Nombre único para la imagen
                    "resource_type" => "image"
                ]);

                // Obtener la URL de la imagen subida
                $fotoPerfil = $resultado['secure_url'];
                echo "Archivo subido con éxito a Cloudinary.";

            } catch (Exception $e) {
                echo "Error al subir el archivo a Cloudinary: " . $e->getMessage();
                exit;
            }
        } else {
            echo "Archivo no válido o demasiado grande.";
            exit;
        }
    } else {
        // Si no se subió ningún archivo, usar imagen predeterminada
        echo "No se subió ninguna foto de perfil.";
        $fotoPerfil = "https://res.cloudinary.com/dlii53bu7/image/upload/v1729653392/Subida/Predefinido/ttzuye5mxckrwvd25kng.webp";
    }

    // Verificar si el usuario ya existe
    $existe_usr = consultar_existe_usr($con, $tabla = 'usuarios', $columna = 'Correo', $email);

    // Insertar los datos en la base de datos, incluyendo la URL de la imagen subida a Cloudinary
    insertar_datos($con, $nombre, $email, $contrasenia, $ubicacion, $biografia, $fotoPerfil, $existe_usr);
