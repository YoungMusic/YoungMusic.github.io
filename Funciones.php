<?php
require_once("conexion.php");
require("Conexion_Cloud.php");
require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Admin\AdminApi;

function logear($con, $email, $pass, $redirect = "Usuario_YM.php")
{
    session_start();
    $consulta_login = "SELECT * FROM usuarios WHERE Correo = '$email'";
    $resultado_login = mysqli_query($con, $consulta_login);

    if (mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $password_bd = $fila["Contra"];

        if (password_verify($pass, $password_bd)) {
            $_SESSION["email"] = $email;
            header("Location: $redirect");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}


function consultar_existe_usr($con, $tabla, $columna, $email)
{

    $email = mysqli_real_escape_string($con, $email);


    $consulta_existe_dato = "SELECT $columna FROM $tabla WHERE $columna = '$email'";


    $resultado_existe_dato = mysqli_query($con, $consulta_existe_dato);


    return mysqli_num_rows($resultado_existe_dato) > 0;
}


function insertar_datos($con, $nombre, $email, $contrasenia, $ubicacion, $biografia, $fotoPerfil, $existe_usr)
{
    if (!$existe_usr) {
        $email = mysqli_real_escape_string($con, $email);
        $contraseniaH = password_hash($contrasenia, PASSWORD_DEFAULT);

        $consulta_insertar = "INSERT INTO usuarios (NomrUsua, Correo, Contra, Locacion, Biografia, FotoPerf) 
                                VALUES ('$nombre', '$email', '$contraseniaH', '$ubicacion', '$biografia', '$fotoPerfil')";

        if (mysqli_query($con, $consulta_insertar)) {
            echo "Usuario registrado con éxito.";
            logear($con, $email, $contrasenia, "Preferencias_Usr.php");
            exit();
        } else {
            echo "Error: " . $consulta_insertar . "<br>" . mysqli_error($con);
        }
    } else {
        echo "El usuario ya existe.";
    }
}

function insertar_usr($con, $tabla, $columnas, $valores, $existe_usr)
{
    if (!$existe_usr) {

        foreach ($valores as &$valor) {
            $valor = mysqli_real_escape_string($con, $valor);
        }


        $placeholders = implode(', ', array_fill(0, count($valores), '?'));


        $consulta_insertar = "INSERT INTO $tabla ($columnas) VALUES ($placeholders)";


        $stmt = $con->prepare($consulta_insertar);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $con->error);
        }


        $types = str_repeat('s', count($valores));


        $stmt->bind_param($types, ...$valores);


        if ($stmt->execute()) {
            echo "Datos insertados con éxito.";
        } else {
            echo "Error: " . $stmt->error;
        }


        $stmt->close();
    } else {
        echo "El usuario ya existe.";
    }
}

function obtenerDatosUsuario($email)
{
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $email);
    $consulta = "SELECT * FROM usuarios WHERE Correo = '$email'";
    $resultado = mysqli_query($con, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        mysqli_close($con);
        return $usuario;
    } else {
        mysqli_close($con);
        return false;
    }
}
function obtenerDatosDisco($email)
{
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $email);

    // Consulta para obtener datos de la discográfica
    $consulta = "SELECT * FROM usuarios 
                 INNER JOIN discografica ON discografica.CorrDisc = usuarios.Correo 
                 WHERE Correo = '$email'";
    $resultado = mysqli_query($con, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);

        // Inicializar las variables de redes sociales
        $Instagram = $Youtube = $TikTok = $Spotify = '';

        // Consulta para obtener redes sociales de la discográfica
        $consulta = "SELECT * FROM redesd WHERE CorrDisc = '$email'";
        $resultado_Redes = mysqli_query($con, $consulta);

        if ($resultado_Redes && mysqli_num_rows($resultado_Redes) > 0) {
            $Redes = mysqli_fetch_assoc($resultado_Redes);
            $Instagram = $Redes['Instagram'];
            $Youtube = $Redes['Youtube'];
            $TikTok = $Redes['TikTok'];
            $Spotify = $Redes['Spotify'];
        }

        $usuario['Instagram'] = $Instagram;
        $usuario['Youtube'] = $Youtube;
        $usuario['TikTok'] = $TikTok;
        $usuario['Spotify'] = $Spotify;

        mysqli_close($con);
        return $usuario;
    } else {
        mysqli_close($con);
        return false;
    }
}
function obtenerDatosArtista($email)
{
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $email);

    // Consulta para obtener datos del artista
    $consulta = "SELECT * FROM usuarios 
                     INNER JOIN artistas ON artistas.CorrArti = usuarios.Correo 
                     WHERE Correo = '$email'";
    $resultado = mysqli_query($con, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);

        // Consulta para obtener instrumentos del artista
        $consulta_instrumentos = "SELECT NomInst FROM instrumento WHERE CorrArti = '$email'";
        $resultado_instrumentos = mysqli_query($con, $consulta_instrumentos);

        $instrumentos = [];
        if ($resultado_instrumentos && mysqli_num_rows($resultado_instrumentos) > 0) {
            while ($row = mysqli_fetch_assoc($resultado_instrumentos)) {
                $instrumentos[] = $row['NomInst'];
            }
        }

        // Añadir instrumentos al array de usuario
        $usuario['instrumentos'] = $instrumentos;

        // Consulta para obtener redes sociales del artista

        $consulta = "SELECT * FROM redesa WHERE CorrArti = '$email'";
        $resultado_Redes = mysqli_query($con, $consulta);

        if ($resultado_Redes && mysqli_num_rows($resultado_Redes) > 0) {
            $Redes = mysqli_fetch_assoc($resultado_Redes);
            $Instagram = $Redes['Instagram'];
            $Youtube = $Redes['Youtube'];
            $TikTok  = $Redes['TikTok'];
            $Spotify = $Redes['Spotify'];
        }
        $usuario['Instagram'] = $Instagram;
        $usuario['Youtube'] = $Youtube;
        $usuario['TikTok'] = $TikTok;
        $usuario['Spotify'] = $Spotify;

        mysqli_close($con);
        return $usuario;
    } else {
        mysqli_close($con);
        return false;
    }
}

function verificarContrasena($email, $password)
{
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $email);
    $consulta = "SELECT Contra FROM usuarios WHERE Correo = '$email'";
    $resultado = mysqli_query($con, $consulta);
    $usuario = mysqli_fetch_assoc($resultado);
    mysqli_close($con);
    return password_verify($password, $usuario['Contra']);
}

function editarPerfil($email, $nuevoNombre, $nuevaBiografia, $nuevaFoto)
{
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $email);
    $nuevoNombre = mysqli_real_escape_string($con, $nuevoNombre);
    $nuevaBiografia = mysqli_real_escape_string($con, $nuevaBiografia);

    $fotoGuardada = "";
    if (!empty($nuevaFoto['name'])) {
        try {
            // Primero, obtener la foto actual del usuario
            $consultaFoto = "SELECT FotoPerf FROM usuarios WHERE Correo = '$email'";
            $resultadoFoto = mysqli_query($con, $consultaFoto);
            $fotoActual = mysqli_fetch_assoc($resultadoFoto);

            // Si existe una foto anterior, eliminarla de Cloudinary
            if (!empty($fotoActual['FotoPerf']) && strpos($fotoActual['FotoPerf'], 'cloudinary') !== false) {
                try {
                    // Extraer el public_id de la URL
                    $urlPartes = explode('/', $fotoActual['FotoPerf']);
                    $nombreArchivo = end($urlPartes);
                    $publicId = 'Subida/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);

                    // Eliminar la imagen anterior
                    $admin = new AdminApi();
                    $admin->deleteAssets($publicId);
                } catch (Exception $e) {
                    error_log("Advertencia: No se pudo eliminar la imagen anterior: " . $e->getMessage());
                    // Continuamos con la ejecución aunque falle la eliminación
                }
            }

            // Subir la nueva imagen a Cloudinary
            $resultado = (new UploadApi())->upload($nuevaFoto['tmp_name'], [
                "folder" => "Subida/",  // Carpeta en Cloudinary
                "public_id" => "imagen_" . uniqid(),  // Nombre único
                "resource_type" => "image"
            ]);

            // Obtener la URL de la imagen subida
            $fotoGuardada = ", FotoPerf = '" . $resultado['secure_url'] . "'";
            
            // Verificar si existe una foto local antigua y eliminarla
            if (!empty($fotoActual['FotoPerf']) && file_exists($fotoActual['FotoPerf']) && strpos($fotoActual['FotoPerf'], 'Subida/') === 0) {
                unlink($fotoActual['FotoPerf']);
            }

        } catch (Exception $e) {
            error_log("Error al subir la foto a Cloudinary: " . $e->getMessage());
            return false;
        }
    }

    $consulta = "UPDATE usuarios SET NomrUsua = '$nuevoNombre', Biografia = '$nuevaBiografia' $fotoGuardada WHERE Correo = '$email'";
    $resultado = mysqli_query($con, $consulta);
    
    mysqli_close($con);
    return $resultado;
}


function editarPerfilArtista($email, $nuevoNombre, $nuevaBiografia, $nuevaFoto, $redes)
{
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $email);
    $nuevoNombre = mysqli_real_escape_string($con, $nuevoNombre);
    $nuevaBiografia = mysqli_real_escape_string($con, $nuevaBiografia);

    $fotoGuardada = "";
    if (!empty($nuevaFoto['name'])) {
        try {
            // Primero, obtener la foto actual del usuario
            $consultaFoto = "SELECT FotoPerf FROM usuarios WHERE Correo = '$email'";
            $resultadoFoto = mysqli_query($con, $consultaFoto);
            $fotoActual = mysqli_fetch_assoc($resultadoFoto);

            // Si existe una foto anterior, eliminarla de Cloudinary
            if (!empty($fotoActual['FotoPerf'])) {
                try {
                    // Extraer el public_id de la URL
                    $urlPartes = explode('/', $fotoActual['FotoPerf']);
                    $nombreArchivo = end($urlPartes);
                    $publicId = 'Subida/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);

                    // Eliminar la imagen anterior
                    $admin = new AdminApi();
                    $admin->deleteAssets($publicId);
                } catch (Exception $e) {
                    echo "Advertencia: No se pudo eliminar la imagen anterior: " . $e->getMessage();
                    // Continuamos con la ejecución aunque falle la eliminación
                }
            }

            // Subir la nueva imagen a Cloudinary
            $resultado = (new UploadApi())->upload($nuevaFoto['tmp_name'], [
                "folder" => "Subida/",  // Carpeta en Cloudinary
                "public_id" => "imagen_" . uniqid(),  // Nombre único
                "resource_type" => "image"
            ]);

            // Obtener la URL de la imagen subida
            $fotoGuardada = ", FotoPerf = '" . $resultado['secure_url'] . "'";
            echo "Foto de perfil actualizada exitosamente en Cloudinary.";
        } catch (Exception $e) {
            echo "Error al subir la foto a Cloudinary: " . $e->getMessage();
            return false;
        }
    }

    // Actualizar los datos del usuario
    $consulta = "UPDATE usuarios SET NomrUsua = '$nuevoNombre', Biografia = '$nuevaBiografia' $fotoGuardada WHERE Correo = '$email'";
    $resultado = mysqli_query($con, $consulta);

    if ($resultado) {
        // Verificar si las redes ya existen o se deben insertar
        $consultaRedes = "SELECT * FROM redesa WHERE CorrArti = '$email'";
        $resultadoRedes = mysqli_query($con, $consultaRedes);

        if (mysqli_num_rows($resultadoRedes) == 0) {
            // Si no existen registros de redes sociales, las insertamos
            $insertRedes = "INSERT INTO `redesa`(`CorrArti`, `Instagram`, `Youtube`, `Spotify`, `TikTok`) 
                            VALUES ('$email', '{$redes[0]}', '{$redes[1]}', '{$redes[2]}', '{$redes[3]}')";
            mysqli_query($con, $insertRedes);
        } else {
            // Si ya existen registros de redes sociales, las actualizamos
            $updateRedes = "UPDATE `redesa` SET 
                            `Instagram` = '{$redes[0]}', 
                            `Youtube` = '{$redes[1]}', 
                            `Spotify` = '{$redes[2]}', 
                            `TikTok` = '{$redes[3]}'
                            WHERE `CorrArti` = '$email'";
            mysqli_query($con, $updateRedes);
        }
    } else {
        echo "Error al actualizar el perfil del artista.";
        return false;
    }

    mysqli_close($con);
    return $resultado;
}

function editarPerfilDisco($email, $nuevoNombre, $nuevaBiografia, $nuevaFoto, $redes)
{
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $email);
    $nuevoNombre = mysqli_real_escape_string($con, $nuevoNombre);
    $nuevaBiografia = mysqli_real_escape_string($con, $nuevaBiografia);

    $fotoGuardada = "";
    if (!empty($nuevaFoto['name'])) {
        try {
            // Primero, obtener la foto actual del usuario
            $consultaFoto = "SELECT FotoPerf FROM usuarios WHERE Correo = '$email'";
            $resultadoFoto = mysqli_query($con, $consultaFoto);
            $fotoActual = mysqli_fetch_assoc($resultadoFoto);

            // Si existe una foto anterior, eliminarla de Cloudinary
            if (!empty($fotoActual['FotoPerf'])) {
                try {
                    // Extraer el public_id de la URL
                    $urlPartes = explode('/', $fotoActual['FotoPerf']);
                    $nombreArchivo = end($urlPartes);
                    $publicId = 'Subida/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);

                    // Eliminar la imagen anterior
                    $admin = new AdminApi();
                    $admin->deleteAssets($publicId);
                } catch (Exception $e) {
                    echo "Advertencia: No se pudo eliminar la imagen anterior: " . $e->getMessage();
                    // Continuamos con la ejecución aunque falle la eliminación
                }
            }

            // Subir la nueva imagen a Cloudinary
            $resultado = (new UploadApi())->upload($nuevaFoto['tmp_name'], [
                "folder" => "Subida/",  // Carpeta en Cloudinary
                "public_id" => "imagen_" . uniqid(),  // Nombre único
                "resource_type" => "image"
            ]);

            // Obtener la URL de la imagen subida
            $fotoGuardada = ", FotoPerf = '" . $resultado['secure_url'] . "'";
            echo "Foto de perfil actualizada exitosamente en Cloudinary.";
        } catch (Exception $e) {
            echo "Error al subir la foto a Cloudinary: " . $e->getMessage();
            return false;
        }
    }

    // Actualizar los datos del usuario
    $consulta = "UPDATE usuarios SET NomrUsua = '$nuevoNombre', Biografia = '$nuevaBiografia' $fotoGuardada WHERE Correo = '$email'";
    $resultado = mysqli_query($con, $consulta);

    if ($resultado) {
        // Verificar si las redes ya existen o se deben insertar
        $consultaRedes = "SELECT * FROM redesd WHERE CorrDisc = '$email'";
        $resultadoRedes = mysqli_query($con, $consultaRedes);

        if (mysqli_num_rows($resultadoRedes) == 0) {
            // Si no existen registros de redes sociales, las insertamos
            $insertRedes = "INSERT INTO `redesd`(`CorrDisc`, `Instagram`, `Youtube`, `Spotify`, `TikTok`) 
                            VALUES ('$email', '{$redes[0]}', '{$redes[1]}', '{$redes[2]}', '{$redes[3]}')";
            mysqli_query($con, $insertRedes);
        } else {
            // Si ya existen registros de redes sociales, las actualizamos
            $updateRedes = "UPDATE `redesd` SET 
                            `Instagram` = '{$redes[0]}', 
                            `Youtube` = '{$redes[1]}', 
                            `Spotify` = '{$redes[2]}', 
                            `TikTok` = '{$redes[3]}'
                            WHERE `CorrDisc` = '$email'";
            mysqli_query($con, $updateRedes);
        }
    } else {
        echo "Error al actualizar el perfil del artista.";
        return false;
    }

    mysqli_close($con);
    return $resultado;
}


function eliminarPerfil($email)
{
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $email);
    
    // Verificar si el usuario es un artista (tiene álbumes)
    $consultaArtista = "SELECT COUNT(*) as count FROM albun WHERE NomCred = '$email'";
    $resultadoArtista = mysqli_query($con, $consultaArtista);
    $esArtista = mysqli_fetch_assoc($resultadoArtista)['count'] > 0;

    if ($esArtista) {
        // Obtener todos los álbumes del artista
        $consultaAlbumes = "SELECT IdAlbum, ImgAlbu FROM albun WHERE NomCred = '$email'";
        $resultadoAlbumes = mysqli_query($con, $consultaAlbumes);
        
        $admin = new AdminApi();

        while ($album = mysqli_fetch_assoc($resultadoAlbumes)) {
            // Eliminar imagen del álbum de Cloudinary
            if (!empty($album['ImgAlbu'])) {
                try {
                    $urlPartes = explode('/', $album['ImgAlbu']);
                    $nombreArchivo = end($urlPartes);
                    $publicId = 'Albumes/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
                    $admin->deleteAssets($publicId);
                } catch (Exception $e) {
                    error_log("Error al eliminar imagen del álbum: " . $e->getMessage());
                }
            }

            // Obtener y eliminar todas las canciones del álbum
            $consultaCanciones = "SELECT Archivo, ImgMusi FROM musica WHERE Album = " . $album['IdAlbum'];
            $resultadoCanciones = mysqli_query($con, $consultaCanciones);

            while ($cancion = mysqli_fetch_assoc($resultadoCanciones)) {
                // Eliminar archivo MP3
                if (!empty($cancion['Archivo'])) {
                    try {
                        $urlPartes = explode('/', $cancion['Archivo']);
                        $nombreArchivo = end($urlPartes);
                        $publicId = 'Musica/Canciones/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
                        $admin->deleteAssets($publicId);
                    } catch (Exception $e) {
                        error_log("Error al eliminar archivo MP3: " . $e->getMessage());
                    }
                }

                // Eliminar imagen de la canción
                if (!empty($cancion['ImgMusi'])) {
                    try {
                        $urlPartes = explode('/', $cancion['ImgMusi']);
                        $nombreArchivo = end($urlPartes);
                        $publicId = 'Musica/portadas/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
                        $admin->deleteAssets($publicId);
                    } catch (Exception $e) {
                        error_log("Error al eliminar imagen de la canción: " . $e->getMessage());
                    }
                }
            }

            // Eliminar todas las canciones del álbum de la base de datos
            $eliminarCanciones = "DELETE FROM musica WHERE Album = " . $album['IdAlbum'];
            mysqli_query($con, $eliminarCanciones);
        }

        // Eliminar todos los álbumes del artista de la base de datos
        $eliminarAlbumes = "DELETE FROM albun WHERE NomCred = '$email'";
        mysqli_query($con, $eliminarAlbumes);
    }

    // Eliminar foto de perfil del usuario
    $consultaFoto = "SELECT FotoPerf FROM usuarios WHERE Correo = '$email'";
    $resultadoFoto = mysqli_query($con, $consultaFoto);
    $fotoActual = mysqli_fetch_assoc($resultadoFoto);

    if (!empty($fotoActual['FotoPerf'])) {
        try {
            $urlPartes = explode('/', $fotoActual['FotoPerf']);
            $nombreArchivo = end($urlPartes);
            $publicId = 'Subida/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
            $admin = new AdminApi();
            $admin->deleteAssets($publicId);
        } catch (Exception $e) {
            error_log("Error al eliminar foto de perfil: " . $e->getMessage());
        }
    }

    // Finalmente, eliminar el usuario de la base de datos
    $consulta = "DELETE FROM usuarios WHERE Correo = '$email'";
    $resultado = mysqli_query($con, $consulta);
    
    mysqli_close($con);
    return $resultado;
}
function esAdmin($correo) {
    global $con;
    $query = "SELECT PermO FROM oyente WHERE CorrOyen = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        return $row['PermO'] != NULL;
    }
    return false;
}
function EliminarAlbum($idAlbum, $con){


    $consultaAlbumes = "SELECT ImgAlbu FROM albun WHERE IdAlbum = '$idAlbum'";
    $resultadoAlbumes = mysqli_query($con, $consultaAlbumes);
    $album = mysqli_fetch_assoc($resultadoAlbumes);
    $admin = new AdminApi();

    
        // Eliminar imagen del álbum de Cloudinary
        if (!empty($album['ImgAlbu'])) {
            try {
                $urlPartes = explode('/', $album['ImgAlbu']);
                $nombreArchivo = end($urlPartes);
                $publicId = 'Albumes/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
                $admin->deleteAssets($publicId);
            } catch (Exception $e) {
                error_log("Error al eliminar imagen del álbum: " . $e->getMessage());
            }
        }

        // Obtener y eliminar todas las canciones del álbum
        $consultaCanciones = "SELECT Archivo, ImgMusi FROM musica WHERE Album = " . $album['IdAlbum'];
        $resultadoCanciones = mysqli_query($con, $consultaCanciones);
        $cancion = mysqli_fetch_assoc($resultadoCanciones);


            // Eliminar archivo MP3
            if (!empty($cancion['Archivo'])) {
                try {
                    $urlPartes = explode('/', $cancion['Archivo']);
                    $nombreArchivo = end($urlPartes);
                    $publicId = 'Musica/Canciones/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
                    $admin->deleteAssets($publicId);
                } catch (Exception $e) {
                    error_log("Error al eliminar archivo MP3: " . $e->getMessage());
                }
            }

            // Eliminar imagen de la canción
            if (!empty($cancion['ImgMusi'])) {
                try {
                    $urlPartes = explode('/', $cancion['ImgMusi']);
                    $nombreArchivo = end($urlPartes);
                    $publicId = 'Musica/portadas/' . pathinfo($nombreArchivo, PATHINFO_FILENAME);
                    $admin->deleteAssets($publicId);
                } catch (Exception $e) {
                    error_log("Error al eliminar imagen de la canción: " . $e->getMessage());
                }
            }
        

        // Eliminar todas las canciones del álbum de la base de datos
        $eliminarCanciones = "DELETE FROM musica WHERE Album = " . $album['IdAlbum'];
        mysqli_query($con, $eliminarCanciones);
    

    // Eliminar todos los álbumes del artista de la base de datos
    $eliminarAlbumes = "DELETE FROM albun WHERE IdAlbum  = '$idAlbum'";
    mysqli_query($con, $eliminarAlbumes);
}
function obtenerDetallesAlbum($idAlbum)
{
    $conexion = conectar_bd();

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $consultaAlbum = "SELECT a.*, u.*, ar.*
                      FROM albun a 
                      JOIN usuarios u ON a.NomCred = u.Correo
                      JOIN artistas ar ON ar.CorrArti = u.Correo 
                      WHERE a.IdAlbum = ?";

    $stmtAlbum = $conexion->prepare($consultaAlbum);

    if (!$stmtAlbum) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmtAlbum->bind_param("i", $idAlbum);
    $stmtAlbum->execute();
    $resultado = $stmtAlbum->get_result();
    $album = $resultado->fetch_assoc();

    $stmtAlbum->close();
    $conexion->close();

    return $album;
}

function obtenerCancionesAlbum($albumId)
{
    $conexion = conectar_bd();

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $queryCanciones = "SELECT m.*, GROUP_CONCAT(g.GeneMusi SEPARATOR ', ') AS Generos 
                       FROM musica m 
                       LEFT JOIN generos g ON m.IdMusi = g.IdMusi 
                       WHERE m.Album = ? 
                       GROUP BY m.IdMusi 
                       ORDER BY m.IdMusi";
    $stmtCanciones = $conexion->prepare($queryCanciones);

    if (!$stmtCanciones) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmtCanciones->bind_param("i", $albumId);
    $stmtCanciones->execute();
    $resultado = $stmtCanciones->get_result();

    $canciones = array();
    while ($cancion = $resultado->fetch_assoc()) {
        $canciones[] = $cancion;
    }

    $stmtCanciones->close();
    $conexion->close();

    return $canciones;
}

// Función auxiliar para verificar si un álbum existe
function albumExiste($idAlbum)
{
    $conexion = conectar_bd();

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $consulta = "SELECT IdAlbum FROM albun WHERE IdAlbum = ?";
    $stmt = $conexion->prepare($consulta);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $idAlbum);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $existe = $resultado->num_rows > 0;

    $stmt->close();
    $conexion->close();

    return $existe;
}
function agregarComentario($idAlbum, $correoUsuario, $comentario) {
    $conexion = conectar_bd();
    
    if (!$conexion) {
        throw new Exception("Error de conexión a la base de datos");
    }
    
    try {
        // Preparar la consulta
        $consulta = "INSERT INTO comentarios (IdAlbum, CorrUsu, Comentario) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($consulta);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
        }
        
        // Vincular parámetros
        $stmt->bind_param("iss", $idAlbum, $correoUsuario, $comentario);
        
        // Ejecutar la consulta
        $resultado = $stmt->execute();
        
        if (!$resultado) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $stmt->close();
        $conexion->close();
        
        return true;
        
    } catch (Exception $e) {
        error_log("Error en agregarComentario: " . $e->getMessage());
        if (isset($stmt)) $stmt->close();
        if (isset($conexion)) $conexion->close();
        throw $e;
    }
}
function puedeEliminarComentario($comentarioId, $correoUsuario) {
    $conexion = conectar_bd();
    
    if (!$conexion) {
        throw new Exception("Error de conexión a la base de datos");
    }
    
    try {
        // Verificar si el usuario es admin (oyente con PermO no nulo)
        $consultaAdmin = "SELECT PermO FROM oyente WHERE CorrOyen = ?";
        $stmtAdmin = $conexion->prepare($consultaAdmin);
        $stmtAdmin->bind_param("s", $correoUsuario);
        $stmtAdmin->execute();
        $resultadoAdmin = $stmtAdmin->get_result();
        $esAdmin = false;
        
        if ($row = $resultadoAdmin->fetch_assoc()) {
            $esAdmin = $row['PermO'] !== null;
        }
        $stmtAdmin->close();

        // Verificar si el usuario es el creador del álbum
        $consultaCreador = "SELECT a.NomCred 
                           FROM comentarios c 
                           JOIN albun a ON c.IdAlbum = a.IdAlbum 
                           WHERE c.IdComentario = ?";
        $stmtCreador = $conexion->prepare($consultaCreador);
        $stmtCreador->bind_param("i", $comentarioId);
        $stmtCreador->execute();
        $resultadoCreador = $stmtCreador->get_result();
        $esCreador = false;
        
        if ($row = $resultadoCreador->fetch_assoc()) {
            $esCreador = ($row['NomCred'] === $correoUsuario);
        }
        $stmtCreador->close();

        // Verificar si el usuario es el autor del comentario
        $consultaAutor = "SELECT CorrUsu FROM comentarios WHERE IdComentario = ?";
        $stmtAutor = $conexion->prepare($consultaAutor);
        $stmtAutor->bind_param("i", $comentarioId);
        $stmtAutor->execute();
        $resultadoAutor = $stmtAutor->get_result();
        $esAutor = false;
        
        if ($row = $resultadoAutor->fetch_assoc()) {
            $esAutor = ($row['CorrUsu'] === $correoUsuario);
        }
        $stmtAutor->close();

        $conexion->close();
        
        return $esAdmin || $esCreador || $esAutor;
        
    } catch (Exception $e) {
        if (isset($conexion)) $conexion->close();
        throw $e;
    }
}

function eliminarComentario($comentarioId, $correoUsuario) {
    $conexion = conectar_bd();
    
    if (!$conexion) {
        throw new Exception("Error de conexión a la base de datos");
    }
    
    try {
        // Primero verificar si el usuario tiene permiso para eliminar
        if (!puedeEliminarComentario($comentarioId, $correoUsuario)) {
            throw new Exception("No tienes permiso para eliminar este comentario");
        }
        
        // Si tiene permiso, proceder con la eliminación
        $consulta = "DELETE FROM comentarios WHERE IdComentario = ?";
        $stmt = $conexion->prepare($consulta);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta");
        }
        
        $stmt->bind_param("i", $comentarioId);
        $resultado = $stmt->execute();
        
        if (!$resultado) {
            throw new Exception("Error al eliminar el comentario");
        }
        
        $stmt->close();
        $conexion->close();
        
        return true;
        
    } catch (Exception $e) {
        if (isset($stmt)) $stmt->close();
        if (isset($conexion)) $conexion->close();
        throw $e;
    }
}

function obtenerComentarios($idAlbum, $correoUsuarioActual = null) {
    $conexion = conectar_bd();
    
    if (!$conexion) {
        throw new Exception("Error de conexión a la base de datos");
    }
    
    try {
        // Obtener información de los comentarios
        $consulta = "SELECT c.*, u.NomrUsua, u.FotoPerf, a.NomCred as CreadorAlbum
                     FROM comentarios c 
                     JOIN usuarios u ON c.CorrUsu = u.Correo 
                     JOIN albun a ON c.IdAlbum = a.IdAlbum
                     WHERE c.IdAlbum = ? 
                     ORDER BY c.FechaCom DESC";
        
        $stmt = $conexion->prepare($consulta);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
        }
        
        $stmt->bind_param("i", $idAlbum);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $comentarios = array();
        
        // Si hay un usuario logueado, verificar sus permisos
        $esAdmin = false;
        if ($correoUsuarioActual) {
            $consultaAdmin = "SELECT PermO FROM oyente WHERE CorrOyen = ?";
            $stmtAdmin = $conexion->prepare($consultaAdmin);
            $stmtAdmin->bind_param("s", $correoUsuarioActual);
            $stmtAdmin->execute();
            $resultadoAdmin = $stmtAdmin->get_result();
            if ($row = $resultadoAdmin->fetch_assoc()) {
                $esAdmin = ($row['PermO'] !== null);
            }
            $stmtAdmin->close();
        }
        
        while ($comentario = $resultado->fetch_assoc()) {
            // Determinar si el usuario actual puede eliminar este comentario
            $puedeEliminar = false;
            if ($correoUsuarioActual) {
                $puedeEliminar = $esAdmin || 
                                $correoUsuarioActual === $comentario['CorrUsu'] ||
                                $correoUsuarioActual === $comentario['CreadorAlbum'];
            }
            
            $comentario['puedeEliminar'] = $puedeEliminar;
            $comentarios[] = $comentario;
        }
        
        $stmt->close();
        $conexion->close();
        
        return $comentarios;
        
    } catch (Exception $e) {
        error_log("Error en obtenerComentarios: " . $e->getMessage());
        if (isset($stmt)) $stmt->close();
        if (isset($conexion)) $conexion->close();
        return array();
    }
}

function shouldShowFooter() {
    // Verificar si el usuario tiene sesión iniciada
    if (!isset( $_SESSION["email"])) {
        return false;
    }
    
    $correo =  $_SESSION["email"];
    $conn = conectar_bd();
    
    // Verificar si es artista verificado
    $queryArtista = "SELECT Verificacion FROM artistas WHERE CorrArti = ? AND Verificacion IS NOT NULL";
    $stmtArtista = $conn->prepare($queryArtista);
    $stmtArtista->bind_param("s", $correo);
    $stmtArtista->execute();
    $resultArtista = $stmtArtista->get_result();
    
    if ($resultArtista->num_rows > 0) {
        return true;
    }
    
    // Verificar si es discográfica verificada
    $queryDisc = "SELECT Verificacion FROM discografica WHERE CorrDisc = ? AND Verificacion IS NOT NULL";
    $stmtDisc = $conn->prepare($queryDisc);
    $stmtDisc->bind_param("s", $correo);
    $stmtDisc->execute();
    $resultDisc = $stmtDisc->get_result();
    
    if ($resultDisc->num_rows > 0) {
        return true;
    }
    
    // Verificar si es oyente
    $queryOyente = "SELECT CorrOyen FROM oyente WHERE CorrOyen = ?";
    $stmtOyente = $conn->prepare($queryOyente);
    $stmtOyente->bind_param("s", $correo);
    $stmtOyente->execute();
    $resultOyente = $stmtOyente->get_result();
    
    if ($resultOyente->num_rows > 0) {
        return true;
    }
    
    return false;
}
