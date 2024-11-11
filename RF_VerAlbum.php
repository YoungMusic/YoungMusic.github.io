<?php
require_once("conexion.php");
require_once("Funciones.php");

function verificarLike($musicId, $email) {
    $conn = conectar_bd();
    $query = "SELECT 1 FROM likeu WHERE IdMusi = ? AND CorrUsu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $musicId, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $liked = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $liked;
}


// Actualizar procesar_comentario.php para manejar el borrado:
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!isset($_POST['commentId'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID de comentario no proporcionado'
        ]);
        exit;
    }
    
    try {
        $commentId = intval($_POST['commentId']);
        if (eliminarComentario($commentId, $_SESSION["email"])) {
            echo json_encode([
                'success' => true,
                'message' => 'Comentario eliminado correctamente'
            ]);
        } else {
            throw new Exception('Error al eliminar el comentario');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}



?>