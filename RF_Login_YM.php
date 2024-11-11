<?php
require_once("conexion.php");
require("Funciones.php");

$con = conectar_bd();

if (isset($_POST["envio"])) {
    $email = $_POST["email"];
    $contrasenia = $_POST["pass"];

    // Consulta a la base de datos para verificar si el usuario existe
    $consulta_login = "SELECT * FROM usuarios WHERE Correo = ?";
    $stmt = $con->prepare($consulta_login);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado_login = $stmt->get_result();

    if ($resultado_login->num_rows > 0) {
        $fila = $resultado_login->fetch_assoc();
        $password_bd = $fila["Contra"];

        // Verificar si la contraseña es correcta
        if (password_verify($contrasenia, $password_bd)) {
            session_start();
            $_SESSION["email"] = $email;

            $tipo_usuario = '';  // Definir la variable que almacenará el tipo

            // Consultar si es un artista y si está verificado
            $consulta_artista = "SELECT Verificacion FROM artistas WHERE CorrArti = ?";
            $stmt_artista = $con->prepare($consulta_artista);
            $stmt_artista->bind_param("s", $email);
            $stmt_artista->execute();
            $resultado_artista = $stmt_artista->get_result();
            if ($resultado_artista->num_rows > 0) {
                $fila_artista = $resultado_artista->fetch_assoc();
                if (is_null($fila_artista['Verificacion'])) {
                    echo json_encode(['status' => 'error', 'message' => 'Su cuenta de artista no está verificada.']);
                    exit;
                }
                $tipo_usuario = 'artista';
            } else {
                // Consultar si es un oyente
                $consulta_oyente = "SELECT * FROM oyente WHERE CorrOyen = ?";
                $stmt_oyente = $con->prepare($consulta_oyente);
                $stmt_oyente->bind_param("s", $email);
                $stmt_oyente->execute();
                $resultado_oyente = $stmt_oyente->get_result();
                if ($resultado_oyente->num_rows > 0) {
                    $tipo_usuario = 'oyente';
                } else {
                    // Consultar si es una discográfica y si está verificada
                    $consulta_discografica = "SELECT Verificacion FROM discografica WHERE CorrDisc = ?";
                    $stmt_discografica = $con->prepare($consulta_discografica);
                    $stmt_discografica->bind_param("s", $email);
                    $stmt_discografica->execute();
                    $resultado_discografica = $stmt_discografica->get_result();
                    if ($resultado_discografica->num_rows > 0) {
                        $fila_discografica = $resultado_discografica->fetch_assoc();
                        if (is_null($fila_discografica['Verificacion'])) {
                            echo json_encode(['status' => 'error', 'message' => 'Su cuenta de discográfica no está verificada.']);
                            exit;
                        }
                        $tipo_usuario = 'discografica';
                    }
                }
            }

            // Devolver una respuesta JSON con el tipo de usuario si está verificado
            echo json_encode(['status' => 'success', 'tipo_usuario' => $tipo_usuario]);
        } else {
            // Devolver una respuesta JSON si la contraseña es incorrecta
            echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
        }
    } else {
        // Devolver una respuesta JSON si no se encuentra el usuario
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
    }

    $stmt->close();
    $con->close();
}
