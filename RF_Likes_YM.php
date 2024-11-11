<?php
require_once("conexion.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['email'])) {
    $response = array('success' => false, 'message' => '');
    
    try {
        if (!isset($_POST['musicId']) || !isset($_POST['action'])) {
            throw new Exception('Datos incompletos');
        }
        
        $conn = conectar_bd();
        $email = $_SESSION['email'];
        $musicId = $_POST['musicId'];
        $action = $_POST['action'];
        
        // Verificar que la música existe
        $checkMusic = "SELECT 1 FROM musica WHERE IdMusi = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $checkMusic);
        mysqli_stmt_bind_param($stmt, "i", $musicId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) === 0) {
            throw new Exception('La música no existe');
        }
        
        if ($action === 'add') {
            // Verificar que no existe el like
            $checkLike = "SELECT 1 FROM likeu WHERE IdMusi = ? AND CorrUsu = ? LIMIT 1";
            $stmt = mysqli_prepare($conn, $checkLike);
            mysqli_stmt_bind_param($stmt, "is", $musicId, $email);
            mysqli_stmt_execute($stmt);
            
            if (mysqli_stmt_get_result($stmt)->num_rows > 0) {
                throw new Exception('El like ya existe');
            }
            
            // Insertar like
            $query = "INSERT INTO likeu (IdMusi, CorrUsu) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "is", $musicId, $email);
            
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = 'Like agregado correctamente';
            }
        } else if ($action === 'remove') {
            // Eliminar like
            $query = "DELETE FROM likeu WHERE IdMusi = ? AND CorrUsu = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "is", $musicId, $email);
            
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = 'Like eliminado correctamente';
            }
        }
        
        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    } finally {
        if (isset($conn)) {
            mysqli_close($conn);
        }
        echo json_encode($response);
    }
}
?>