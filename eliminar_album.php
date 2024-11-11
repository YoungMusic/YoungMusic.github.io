<?php
session_start();
require_once("conexion.php");
require_once("Funciones.php");
require_once("Conexion_Cloud.php");
require 'vendor/autoload.php';

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Admin\AdminApi;

header('Content-Type: application/json');

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'No has iniciado sesión']);
    exit;
}

// Obtener y decodificar los datos JSON
$datos = json_decode(file_get_contents('php://input'), true);
$albumId = isset($datos['albumId']) ? intval($datos['albumId']) : 0;

if ($albumId <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de álbum inválido']);
    exit;
}

try {
    $con = conectar_bd();
    
    // Verificar permisos
    $album = obtenerDetallesAlbum($albumId);
    if (!$album || ($_SESSION['email'] !== $album['CorrArti'] && !esAdmin($_SESSION['email']))) {
        echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar este álbum']);
        exit;
    }
    
    $admin = new AdminApi();

    // 1. Eliminar imagen del álbum de Cloudinary
    if (!empty($album['ImgAlbu'])) {
        $urlPartes = explode('/', $album['ImgAlbu']);
        $nombreArchivo = end($urlPartes);
        $publicId = 'Albumes/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
        try {
            $admin->deleteAssets($publicId);
        } catch (Exception $e) {
            error_log("Error al eliminar imagen del álbum: " . $e->getMessage());
        }
    }

    // 2. Obtener y eliminar todas las canciones y sus archivos
    $canciones = obtenerCancionesAlbum($albumId);
    foreach ($canciones as $cancion) {
        // Eliminar archivo MP3
        if (!empty($cancion['Archivo'])) {
            $urlPartes = explode('/', $cancion['Archivo']);
            $nombreArchivo = end($urlPartes);
            $publicId = 'Musica/Canciones/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
            try {
                $admin->deleteAssets($publicId);
            } catch (Exception $e) {
                error_log("Error al eliminar MP3: " . $e->getMessage());
            }
        }

        // Eliminar imagen de la canción
        if (!empty($cancion['ImgMusi'])) {
            $urlPartes = explode('/', $cancion['ImgMusi']);
            $nombreArchivo = end($urlPartes);
            $publicId = 'Musica/portadas/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
            try {
                $admin->deleteAssets($publicId);
            } catch (Exception $e) {
                error_log("Error al eliminar imagen de canción: " . $e->getMessage());
            }
        }
    }

    // 3. Eliminar registros de la base de datos
    $con->begin_transaction();
    
    try {
        // Eliminar géneros asociados a las canciones del álbum
        $stmt = $con->prepare("DELETE g FROM generos g 
                              INNER JOIN musica m ON g.IdMusi = m.IdMusi 
                              WHERE m.Album = ?");
        $stmt->bind_param("i", $albumId);
        $stmt->execute();

        // Eliminar comentarios del álbum
        $stmt = $con->prepare("DELETE FROM comentarios WHERE IdAlbum = ?");
        $stmt->bind_param("i", $albumId);
        $stmt->execute();

        // Eliminar canciones del álbum
        $stmt = $con->prepare("DELETE FROM musica WHERE Album = ?");
        $stmt->bind_param("i", $albumId);
        $stmt->execute();

        // Eliminar el álbum
        $stmt = $con->prepare("DELETE FROM albun WHERE IdAlbum = ?");
        $stmt->bind_param("i", $albumId);
        $stmt->execute();

        $con->commit();
        echo json_encode(['success' => true, 'message' => 'Álbum eliminado correctamente']);
    } catch (Exception $e) {
        $con->rollback();
        throw $e;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($con)) {
        $con->close();
    }
}