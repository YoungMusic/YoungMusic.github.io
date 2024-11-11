<?php
session_start();
require_once("conexion.php");
require("Conexion_Cloud.php");
require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Admin\AdminApi;

if (!isset($_SESSION["email"])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$response = ['success' => false, 'message' => ''];
$con = conectar_bd();

try {
    // Verificar límites según la categoría
    $album_id = $_POST['album_id'];
    $query = "SELECT Categoria, COUNT(m.IdMusi) as total_canciones 
              FROM albun a 
              LEFT JOIN musica m ON a.IdAlbum = m.Album 
              WHERE a.IdAlbum = ?
              GROUP BY a.IdAlbum";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $album_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $album_info = $result->fetch_assoc();

    $limite = ($album_info['Categoria'] === 'EP') ? 6 : (($album_info['Categoria'] === 'Sencillo') ? 1 : PHP_INT_MAX);

    if ($album_info['total_canciones'] >= $limite) {
        throw new Exception('Has alcanzado el límite de canciones para este álbum');
    }

    $uploadApi = new UploadApi();
    $ruta_archivo_musica = '';
    $ruta_archivo_imagen = '';

    // Subir archivo de música a Cloudinary
    try {
        $resultado_musica = $uploadApi->upload($_FILES['Archivo']['tmp_name'], [
            "folder" => "Musica/Canciones/",
            "public_id" => "music_" . uniqid(),
            "resource_type" => "auto", // Permite subir archivos de audio
            "format" => pathinfo($_FILES['Archivo']['name'], PATHINFO_EXTENSION),
        ]);
        $ruta_archivo_musica = $resultado_musica['secure_url'];
    } catch (Exception $e) {
        throw new Exception('Error al subir el archivo de música: ' . $e->getMessage());
    }

    // Subir imagen de portada a Cloudinary
    try {
        $resultado_imagen = $uploadApi->upload($_FILES['ImgMusi']['tmp_name'], [
            "folder" => "Musica/portadas/",
            "public_id" => "cover_" . uniqid(),
            "resource_type" => "image",
            // Opcional: Agregar transformaciones de imagen
            "transformation" => [
                [
                    "width" => 800,
                    "height" => 800,
                    "crop" => "limit",
                    "quality" => "auto"
                ]
            ]
        ]);
        $ruta_archivo_imagen = $resultado_imagen['secure_url'];
    } catch (Exception $e) {
        // Si falla la subida de la imagen, eliminar el archivo de música
        if (!empty($ruta_archivo_musica)) {
            try {
                $admin = new AdminApi();
                $publicId = $resultado_musica['public_id'];
                $admin->deleteAssets($publicId);
            } catch (Exception $deleteErr) {
                error_log("Error al eliminar archivo de música después de fallo en imagen: " . $deleteErr->getMessage());
            }
        }
        throw new Exception('Error al subir la imagen de portada: ' . $e->getMessage());
    }

    // Insertar en la base de datos
    $con->begin_transaction(); // Iniciar transacción

    try {
        // Insertar la canción
        $query = "INSERT INTO musica (NomMusi, Archivo, Album, ImgMusi) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssis", $_POST['NomMusi'], $ruta_archivo_musica, $album_id, $ruta_archivo_imagen);
        $stmt->execute();
        
        $id_musica = $con->insert_id;
        
        // Insertar géneros
        $generos_seleccionados = $_POST['Generos'];
        foreach ($generos_seleccionados as $genero) {
            $query = "INSERT INTO generos (IdMusi, GeneMusi) VALUES (?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("is", $id_musica, $genero);
            $stmt->execute();
        }
        
        $con->commit(); // Confirmar transacción
        $response['success'] = true;
        $response['message'] = 'Canción agregada exitosamente';
        
    } catch (Exception $e) {
        $con->rollback(); // Revertir transacción

        // Eliminar archivos de Cloudinary si falla la BD
        try {
            $admin = new AdminApi();
            $admin->deleteAssets([
                $resultado_musica['public_id'],
                $resultado_imagen['public_id']
            ]);
        } catch (Exception $deleteErr) {
            error_log("Error al eliminar archivos de Cloudinary después de fallo en BD: " . $deleteErr->getMessage());
        }
        
        throw new Exception('Error al guardar en la base de datos: ' . $e->getMessage());
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Error en subida de música: " . $e->getMessage());
} finally {
    $con->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>