<?php
session_start();
require_once("conexion.php");
require_once("Funciones.php");

$con = conectar_bd();

if (isset($_POST["envio"])) {
    $nombre = $_POST["nombre_d"];
    $email = $_SESSION["email"];
    $usuario = obtenerDatosUsuario($email);

    if ($usuario) {
        $correo = $usuario["Correo"];

        insertar_usr($con, $Tabla = 'discografica', $columnas = 'CorrDisc, NombDisc', $Valores = [$email, $nombre], consultar_existe_usr($con, $tabla = 'discografica', $columna = 'CorrDisc', $email));
        header("Location: Redes_Disc_YM.php");
    } else {
        echo "Error al obtener los datos del usuario.";
        exit();
    }
}
