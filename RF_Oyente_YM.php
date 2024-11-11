<?php
session_start();
require_once("conexion.php");
require("Funciones.php");

$con = conectar_bd();


$email = $_SESSION["email"];
$usuario = obtenerDatosUsuario($email);

if ($usuario) {
    $correo = $usuario["Correo"];

    insertar_usr($con, $Tabla = 'oyente', $columnas = 'CorrOyen', $Valores = [$email], consultar_existe_usr($con, $tabla = 'oyente', $columna = 'CorrOyen', $email));
    header("Location: Usuario_YM.php");
} else {
    echo "Error al obtener los datos del usuario.";
    exit();
}
