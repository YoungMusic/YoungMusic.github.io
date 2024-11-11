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
function obtenerTemasPopulares($limit = 20) {
    $conn = conectar_bd();
    $temas = array();
    
    try {
        $query = "SELECT m.IdMusi, m.NomMusi, m.ImgMusi, m.Archivo,
                 a.NomAlbum, a.FechaLan, art.NombArtis, art.CorrArti, u.FotoPerf,
                 COUNT(l.IdMusi) as NumLikes
                 FROM musica m 
                 INNER JOIN albun a ON m.Album = a.IdAlbum 
                 INNER JOIN artistas art ON a.NomCred = art.CorrArti 
                 INNER JOIN usuarios u ON art.CorrArti = u.Correo 
                 LEFT JOIN likeu l ON m.IdMusi = l.IdMusi
                 GROUP BY m.IdMusi, m.NomMusi, m.ImgMusi, m.Archivo,
                          a.NomAlbum, a.FechaLan, art.NombArtis, art.CorrArti, u.FotoPerf
                 ORDER BY NumLikes DESC 
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
        error_log("Error en obtenerTemasPopulares: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $temas;
}

function obtenerArtistasPopulares($limit = 20) {
    $conn = conectar_bd();
    $artistas = array();
    
    try {
        $query = "SELECT a.CorrArti, a.NombArtis, a.Verificacion, a.FechaReg, u.FotoPerf,
                 COUNT(s.CorrArti) as NumSeguidores,
                 (SELECT COUNT(*) FROM musica m 
                  INNER JOIN albun al ON m.Album = al.IdAlbum 
                  WHERE al.NomCred = a.CorrArti) as NumCanciones
                 FROM artistas a 
                 INNER JOIN usuarios u ON a.CorrArti = u.Correo
                 LEFT JOIN sigue s ON a.CorrArti = s.CorrArti
                 WHERE a.Verificacion IS NOT NULL
                 GROUP BY a.CorrArti, a.NombArtis, a.Verificacion, a.FechaReg, u.FotoPerf
                 ORDER BY NumSeguidores DESC 
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
        error_log("Error en obtenerArtistasPopulares: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $artistas;
}

function obtenerAlbumesPopulares($limit = 20) {
    $conn = conectar_bd();
    $albumes = array();
    
    try {
        $query = "SELECT a.IdAlbum, a.NomAlbum, a.Categoria, a.FechaLan, 
                 a.ImgAlbu, art.NombArtis, art.CorrArti, u.FotoPerf,
                 COUNT(m.IdMusi) as NumCanciones,
                 COUNT(l.IdMusi) as NumLikesTotal
                 FROM albun a 
                 INNER JOIN artistas art ON a.NomCred = art.CorrArti
                 INNER JOIN usuarios u ON art.CorrArti = u.Correo
                 LEFT JOIN musica m ON m.Album = a.IdAlbum
                 LEFT JOIN likeu l ON m.IdMusi = l.IdMusi
                 GROUP BY a.IdAlbum, a.NomAlbum, a.Categoria, a.FechaLan,
                          a.ImgAlbu, art.NombArtis, art.CorrArti, u.FotoPerf
                 ORDER BY NumLikesTotal DESC 
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
        error_log("Error en obtenerAlbumesPopulares: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $albumes;
}