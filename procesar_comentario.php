<?php
session_start();
require_once("RF_VerAlbum.php");

// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Verificar si hay errores y registrarlos
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar la sesión
if (!isset($_SESSION["email"])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Debe iniciar sesión para comentar',
        'debug' => 'No hay sesión activa'
    ]);
    exit;
}

// Verificar los datos POST
if (!isset($_POST['albumId']) || !isset($_POST['comentario'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Datos incompletos',
        'debug' => [
            'post' => $_POST,
            'albumId' => isset($_POST['albumId']),
            'comentario' => isset($_POST['comentario'])
        ]
    ]);
    exit;
}

// Limpiar y validar los datos
$albumId = intval($_POST['albumId']);
$comentario = trim($_POST['comentario']);
$correoUsuario = $_SESSION["email"];

// Validar que el comentario no esté vacío
if (empty($comentario)) {
    echo json_encode([
        'success' => false, 
        'message' => 'El comentario no puede estar vacío'
    ]);
    exit;
}

// Validar que el álbum existe
if (!albumExiste($albumId)) {
    echo json_encode([
        'success' => false, 
        'message' => 'El álbum no existe'
    ]);
    exit;
}

try {
    if (agregarComentario($albumId, $correoUsuario, $comentario)) {
        echo json_encode([
            'success' => true,
            'message' => 'Comentario agregado exitosamente'
        ]);
    } else {
        throw new Exception('Error al guardar el comentario en la base de datos');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar el comentario',
        'debug' => $e->getMessage()
    ]);
}