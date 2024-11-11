<?php
require_once("conexion.php");
require("Funciones.php");

session_start();


if (!isset($_SESSION['email'])) {
    header("Location: Login_YM.php");
    exit();
}


$correoUsuario = $_SESSION['email'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generos'])) {
    $generosSeleccionados = $_POST['generos'];
    $destinoFinal = isset($_POST['destinoFinal']) ? $_POST['destinoFinal'] : 'Usuario_YM.php';


    $con = conectar_bd();


    foreach ($generosSeleccionados as $genero) {
        $generoEscapado = mysqli_real_escape_string($con, $genero);
        $consultaInsertarGenero = "INSERT INTO preferencias (CorrUsu, Genero) VALUES ('$correoUsuario', '$generoEscapado')";
        mysqli_query($con, $consultaInsertarGenero);
    }

    mysqli_close($con);


    header("Location: " . $destinoFinal);
    exit();
} else {
    echo "No se seleccionaron géneros.";
}
