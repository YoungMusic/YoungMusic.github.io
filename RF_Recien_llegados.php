<?php
require_once("conexion.php");

function obtenerTemasRecientes($limit = 20) {
    $conn = conectar_bd();
    $temas = array();
    
    try {
        $query = "SELECT m.IdMusi, m.NomMusi, m.ImgMusi, m.Archivo,
                 a.NomAlbum, a.FechaLan, art.NombArtis, art.CorrArti, u.FotoPerf 
                 FROM musica m 
                 INNER JOIN albun a ON m.Album = a.IdAlbum 
                 INNER JOIN artistas art ON a.NomCred = art.CorrArti 
                 INNER JOIN usuarios u ON art.CorrArti = u.Correo 
                 ORDER BY a.FechaLan DESC 
                 LIMIT ?";
                 
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "i", $limit);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultado = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $temas[] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log("Error en obtenerTemasRecientes: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $temas;
}

function obtenerArtistasRecientes($limit = 20) {
    $conn = conectar_bd();
    $artistas = array();
    
    try {
        // Actualizado para usar FechaReg de la tabla artistas
        $query = "SELECT a.CorrArti, a.NombArtis, a.Verificacion, a.FechaReg, u.FotoPerf,
                 (SELECT COUNT(*) FROM musica m 
                  INNER JOIN albun al ON m.Album = al.IdAlbum 
                  WHERE al.NomCred = a.CorrArti) as NumCanciones
                 FROM artistas a 
                 INNER JOIN usuarios u ON a.CorrArti = u.Correo
                 WHERE a.Verificacion IS NOT NULL
                 ORDER BY a.FechaReg DESC 
                 LIMIT ?";
                 
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "i", $limit);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultado = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $artistas[] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log("Error en obtenerArtistasRecientes: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $artistas;
}

function obtenerAlbumesRecientes($limit = 20) {
    $conn = conectar_bd();
    $albumes = array();
    
    try {
        $query = "SELECT a.IdAlbum, a.NomAlbum, a.Categoria, a.FechaLan, 
                 a.ImgAlbu, art.NombArtis, art.CorrArti, u.FotoPerf,
                 (SELECT COUNT(*) FROM musica m WHERE m.Album = a.IdAlbum) as NumCanciones
                 FROM albun a 
                 INNER JOIN artistas art ON a.NomCred = art.CorrArti
                 INNER JOIN usuarios u ON art.CorrArti = u.Correo
                 ORDER BY a.FechaLan DESC 
                 LIMIT ?";
                 
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "i", $limit);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultado = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $albumes[] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log("Error en obtenerAlbumesRecientes: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $albumes;
}

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

?>