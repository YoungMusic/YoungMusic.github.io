<?php
require_once("conexion.php");

function obtenerMusicaPorPreferencias($correoUsuario) {
    $conn = conectar_bd();
    $musicas = array();
    
    try {
        // Consulta preparada para obtener las preferencias del usuario
        $query = "SELECT DISTINCT m.IdMusi, m.NomMusi, m.ImgMusi, m.Archivo, 
                 a.NomAlbum, art.NombArtis, art.CorrArti, u.FotoPerf 
                 FROM musica m 
                 INNER JOIN albun a ON m.Album = a.IdAlbum 
                 INNER JOIN artistas art ON a.NomCred = art.CorrArti 
                 INNER JOIN usuarios u ON art.CorrArti = u.Correo 
                 INNER JOIN generos g ON m.IdMusi = g.IdMusi 
                 WHERE g.GeneMusi IN (
                     SELECT Genero 
                     FROM preferencias 
                     WHERE CorrUsu = ?
                 ) 
                 LIMIT 10";
                 
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $correoUsuario);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultado = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $musicas[] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        // Manejar el error apropiadamente
        error_log("Error en obtenerMusicaPorPreferencias: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $musicas;
}

function obtenerMusicaReciente() {
    $conn = conectar_bd();
    $musicas = array();
    
    try {
        $query = "SELECT m.IdMusi, m.NomMusi, m.ImgMusi, m.Archivo, 
                 a.NomAlbum, art.NombArtis, u.FotoPerf 
                 FROM musica m 
                 INNER JOIN albun a ON m.Album = a.IdAlbum 
                 INNER JOIN artistas art ON a.NomCred = art.CorrArti 
                 INNER JOIN usuarios u ON art.CorrArti = u.Correo 
                 ORDER BY m.IdMusi DESC 
                 LIMIT 10";
                 
        if ($stmt = mysqli_prepare($conn, $query)) {
            if (mysqli_stmt_execute($stmt)) {
                $resultado = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $musicas[] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log("Error en obtenerMusicaReciente: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $musicas;
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
function obtenerArtistasRandom($limit = 10) {
    $conn = conectar_bd();
    $artistas = array();
    
    try {
        $query = "SELECT a.CorrArti, a.NombArtis, a.Verificacion, u.FotoPerf 
                 FROM artistas a 
                 INNER JOIN usuarios u ON a.CorrArti = u.Correo AND Verificacion IS NOT NULL
                 ORDER BY RAND() 
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
        error_log("Error en obtenerArtistasRandom: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $artistas;
}

// New function to get random music
function obtenerMusicaRandom($limit = 10) {
    $conn = conectar_bd();
    $musicas = array();
    
    try {
        $query = "SELECT m.IdMusi, m.NomMusi, m.ImgMusi, m.Archivo, 
                 a.NomAlbum, art.NombArtis, art.CorrArti, u.FotoPerf 
                 FROM musica m 
                 INNER JOIN albun a ON m.Album = a.IdAlbum 
                 INNER JOIN artistas art ON a.NomCred = art.CorrArti 
                 INNER JOIN usuarios u ON art.CorrArti = u.Correo 
                 ORDER BY RAND() 
                 LIMIT ?";
                 
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "i", $limit);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultado = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $musicas[] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log("Error en obtenerMusicaRandom: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $musicas;
}

?>