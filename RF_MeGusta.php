<?php
require_once("conexion.php");
function verificarLikeExistente($idMusica, $correoUsuario) {
    $conn = conectar_bd();
    $hasLike = false;
    
    try {
        $query = "SELECT 1 FROM likeu WHERE IdMusi = ? AND CorrUsu = ? LIMIT 1";
        
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "is", $idMusica, $correoUsuario);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultado = mysqli_stmt_get_result($stmt);
                $hasLike = mysqli_num_rows($resultado) > 0;
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log("Error en verificarLikeExistente: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $hasLike;
}
function obtenerTemasConLike($correoUsuario) {
    $conn = conectar_bd();
    $temas = array();
    
    try {
        $query = "SELECT m.IdMusi, m.NomMusi, m.ImgMusi, m.Archivo,
                 a.NomAlbum, a.FechaLan, art.NombArtis, art.CorrArti, u.FotoPerf
                 FROM likeu l
                 INNER JOIN musica m ON l.IdMusi = m.IdMusi
                 INNER JOIN albun a ON m.Album = a.IdAlbum 
                 INNER JOIN artistas art ON a.NomCred = art.CorrArti 
                 INNER JOIN usuarios u ON art.CorrArti = u.Correo 
                 WHERE l.CorrUsu = ?
                 ORDER BY m.IdMusi DESC";
                 
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $correoUsuario);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultado = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $temas[] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log("Error en obtenerTemasConLike: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $temas;
}

// Reemplaza el contenido del carousel por:
?>