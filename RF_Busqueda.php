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

    $nombre = $_POST["usuario"];
    $email = $_SESSION["email"]; // Email del usuario actual

    // Llamada a la función login
    $resultado_busqueda = buscar_user($con, $nombre, $email);

    // Devuelvo la respuesta en formato JSON
    echo json_encode($resultado_busqueda);
}

function buscar_user($con, $nombre, $email_actual)
{
    // Array para almacenar la respuesta
    $respuesta_json = array();
    $usuario = array(); // Cambiado de $usuarios a $usuario para mantener consistencia

    // Preparar la consulta excluyendo al usuario actual
    $consulta_buscar_user = "SELECT FotoPerf, NombArtis, CorrArti 
                            FROM usuarios 
                            INNER JOIN artistas ON Correo=CorrArti 
                            WHERE NombArtis LIKE ? 
                            AND CorrArti != ?";

    // Preparar el statement
    if ($stmt = mysqli_prepare($con, $consulta_buscar_user)) {
        // Añadir los % para el LIKE
        $parametro_busqueda = "%" . $nombre . "%";
        
        // Vincular parámetros
        if (!mysqli_stmt_bind_param($stmt, "ss", $parametro_busqueda, $email_actual)) {
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
                // Agregar cada usuario encontrado al array
                $usuario[] = array(
                    "nombre" => $fila["NombArtis"],
                    "perfil" => $fila["FotoPerf"],
                    "correo" => $fila["CorrArti"]
                );
            }
            $respuesta_json['status'] = 1; // se encontró el usuario
            $respuesta_json['usuarios'] = $usuario;
        } else {
            $respuesta_json['status'] = 0; // no se encontró
            $respuesta_json['mensaje'] = "No se encontraron usuarios.";
        }
        
        // Cerrar el statement
        mysqli_stmt_close($stmt);
    } else {
        $respuesta_json['status'] = -1; // error en la consulta
        $respuesta_json['mensaje'] = "Error al preparar la consulta: " . mysqli_error($con);
    }

    return $respuesta_json;
}