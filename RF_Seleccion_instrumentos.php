<?php
require_once("conexion.php");
require("Funciones.php");

session_start();


if (!isset($_SESSION['email'])) {
    header("Location: Login_YM.php");
    exit();
}


$correoUsuario = $_SESSION['email'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['instrumentos'])) {
    $InstrumentosSeleccionados = $_POST['instrumentos'];


    $con = conectar_bd();


    foreach ($InstrumentosSeleccionados as $instrumento) {
        $instrumentoEscapado = mysqli_real_escape_string($con, $instrumento);
        $consultaInsertarinstrumento = "INSERT INTO instrumento (CorrArti, NomInst) VALUES ('$correoUsuario', '$instrumentoEscapado')";
        mysqli_query($con, $consultaInsertarinstrumento);
    }

    mysqli_close($con);


    header("Location: Login_YM.php");
    exit();
} else {
    echo "No se seleccionaron géneros.";
}
