<?php
session_start();
require_once("conexion.php");
require("Funciones.php");

$con = conectar_bd();

if (isset($_POST["envio"])) {
    $email = $_SESSION["email"];
    $usuario = obtenerDatosUsuario($email);




    $Datos = [$email, $_POST["Red1"], $_POST["Red2"], $_POST["Red3"], $_POST["Red4"]];


    $existe_usr = consultar_existe_usr($con, 'redesa', 'CorrArti', $email);


    $tabla = 'redesa';
    $columnas = 'CorrArti, Instagram, Youtube, Spotify, TikTok';



    insertar_usr($con, $tabla, $columnas, $Datos, $existe_usr);
}

header("Location: Seleccion_instrumentos.php");
