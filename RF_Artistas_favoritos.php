<?php
require_once("conexion.php");
require_once("RF_Datos_Busqueda_YM.php"); // Aquí está verificarLikeExistente


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
function obtenerContenidoArtistasSeguidosPorUsuario($emailUsuario, $limitArtistas = 10, $limitAlbumes = 10, $limitCanciones = 20) {
    $conn = conectar_bd();
    $resultado = array(
        'artistas' => array(),
        'albumes' => array(),
        'canciones' => array()
    );
    
    try {
        // Obtener artistas seguidos
        $queryArtistas = "SELECT DISTINCT 
                art.CorrArti, art.NombArtis, art.Verificacion, art.FechaReg,
                u.FotoPerf,
                (SELECT COUNT(*) FROM musica m 
                 INNER JOIN albun al ON m.Album = al.IdAlbum 
                 WHERE al.NomCred = art.CorrArti) as NumCanciones
            FROM sigue s
            INNER JOIN artistas art ON s.CorrArti = art.CorrArti
            INNER JOIN usuarios u ON art.CorrArti = u.Correo
            WHERE s.CorrOyen = ?
            ORDER BY RAND()
            LIMIT ?";
            
        if ($stmt = mysqli_prepare($conn, $queryArtistas)) {
            mysqli_stmt_bind_param($stmt, "si", $emailUsuario, $limitArtistas);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultadoArtistas = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultadoArtistas)) {
                    $resultado['artistas'][] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }

        // Obtener álbumes aleatorios de artistas seguidos
        $queryAlbumes = "SELECT DISTINCT 
                a.IdAlbum, a.NomAlbum, a.Categoria, a.FechaLan, a.ImgAlbu,
                art.NombArtis, art.CorrArti, u.FotoPerf,
                (SELECT COUNT(*) FROM musica m WHERE m.Album = a.IdAlbum) as NumCanciones
            FROM sigue s
            INNER JOIN albun a ON s.CorrArti = a.NomCred
            INNER JOIN artistas art ON a.NomCred = art.CorrArti
            INNER JOIN usuarios u ON art.CorrArti = u.Correo
            WHERE s.CorrOyen = ?
            ORDER BY RAND()
            LIMIT ?";
            
        if ($stmt = mysqli_prepare($conn, $queryAlbumes)) {
            mysqli_stmt_bind_param($stmt, "si", $emailUsuario, $limitAlbumes);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultadoAlbumes = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultadoAlbumes)) {
                    $resultado['albumes'][] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
        
        // Obtener canciones aleatorias de artistas seguidos
        $queryCanciones = "SELECT DISTINCT
                m.IdMusi, m.NomMusi, m.ImgMusi, m.Archivo,
                a.NomAlbum, a.FechaLan,
                art.NombArtis, art.CorrArti, u.FotoPerf
            FROM sigue s
            INNER JOIN albun alb ON s.CorrArti = alb.NomCred
            INNER JOIN musica m ON alb.IdAlbum = m.Album
            INNER JOIN albun a ON m.Album = a.IdAlbum
            INNER JOIN artistas art ON a.NomCred = art.CorrArti
            INNER JOIN usuarios u ON art.CorrArti = u.Correo
            WHERE s.CorrOyen = ?
            ORDER BY RAND()
            LIMIT ?";
            
        if ($stmt = mysqli_prepare($conn, $queryCanciones)) {
            mysqli_stmt_bind_param($stmt, "si", $emailUsuario, $limitCanciones);
            
            if (mysqli_stmt_execute($stmt)) {
                $resultadoCanciones = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultadoCanciones)) {
                    // Verificar si la canción tiene like
                    $fila['hasLike'] = verificarLikeExistente($fila['IdMusi'], $emailUsuario);
                    $resultado['canciones'][] = $fila;
                }
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log("Error en obtenerContenidoArtistasSeguidosPorUsuario: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
    
    return $resultado;
}