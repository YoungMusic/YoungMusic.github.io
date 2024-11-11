<?php
require("conexion.php");

// Iniciar sesión si no está iniciada
session_start();

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

$con = conectar_bd();

// Defino un array para devolver las respuestas JSON según cada caso
$respuesta_json = array();

if (isset($_POST["envio"])) {
    // Verificar si existe la sesión
    if (!isset($_SESSION["email"])) {
        $respuesta_json['status'] = -1;
        $respuesta_json['mensaje'] = "No hay sesión iniciada";
        echo json_encode($respuesta_json);
        exit;
    }

    $nombre = $_POST["Musica"];
    $email = $_SESSION["email"]; // Email del usuario actual

    // Llamada a la función de búsqueda
    $resultado_busqueda = buscar_musica($con, $nombre);

    // Devuelvo la respuesta en formato JSON
    echo json_encode($resultado_busqueda);
}

function buscar_musica($con, $nombre)
{
    // Array para almacenar la respuesta
    $respuesta_json = array();
    $musicas = array();

    // Preparar la consulta para buscar música
    $consulta_buscar_musica = "SELECT m.Album, m.NomMusi, m.Archivo, m.ImgMusi, a.NomAlbum 
                              FROM musica m 
                              LEFT JOIN albun a ON m.Album = a.IdAlbum 
                              WHERE m.NomMusi LIKE ?";

    // Preparar el statement
    if ($stmt = mysqli_prepare($con, $consulta_buscar_musica)) {
        // Añadir los % para el LIKE
        $parametro_busqueda = "%" . $nombre . "%";
        
        // Vincular parámetros
        if (!mysqli_stmt_bind_param($stmt, "s", $parametro_busqueda)) {
            $respuesta_json['status'] = -1;
            $respuesta_json['mensaje'] = "Error al vincular parámetros: " . mysqli_stmt_error($stmt);
            return $respuesta_json;
        }
        
        // Ejecutar la consulta
        if (!mysqli_stmt_execute($stmt)) {
            $respuesta_json['status'] = -1;
            $respuesta_json['mensaje'] = "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt);
            return $respuesta_json;
        }
        
        // Obtener resultados
        $resultado = mysqli_stmt_get_result($stmt);
        
        if ($resultado === false) {
            $respuesta_json['status'] = -1;
            $respuesta_json['mensaje'] = "Error al obtener resultados: " . mysqli_error($con);
            return $respuesta_json;
        }

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                // Agregar cada música encontrada al array
                $musicas[] = array(
                    "id" => $fila["Album"],
                    "nombre" => $fila["NomMusi"],
                    "archivo" => $fila["Archivo"],
                    "imagen" => $fila["ImgMusi"],
                    "album" => $fila["NomAlbum"]
                );
            }
            $respuesta_json['status'] = 1; // se encontraron músicas
            $respuesta_json['musicas'] = $musicas;
        } else {
            $respuesta_json['status'] = 0; // no se encontró
            $respuesta_json['mensaje'] = "No se encontraron canciones.";
        }
        
        // Cerrar el statement
        mysqli_stmt_close($stmt);
    } else {
        $respuesta_json['status'] = -1; // error en la consulta
        $respuesta_json['mensaje'] = "Error al preparar la consulta: " . mysqli_error($con);
    }

    return $respuesta_json;
}