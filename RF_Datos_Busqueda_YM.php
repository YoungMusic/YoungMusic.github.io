<?php
session_start();
require_once("conexion.php");
require_once("Funciones.php");

if (!isset($_SESSION["email"])) {
    header("Location: Login_YM.php");
    exit();
}

$email = $_SESSION["email"];

$Consulta = "SELECT CorrArti FROM artistas WHERE CorrArti = ? AND Verificacion IS NOT NULL";
$ResultadoA = $con->prepare($Consulta);
$ResultadoA->bind_param("s", $email);
$ResultadoA->execute();
$ResultadoA->store_result();

if ($ResultadoA->num_rows != 0) {
    $email = $_SESSION["email"];
$usuario = obtenerDatosArtista($email);

if ($usuario) {
    $nombre = $usuario["NombArtis"];
    $correo = $usuario["Correo"];
    $biografia = $usuario["Biografia"];
    $fotoPerfil = $usuario["FotoPerf"] ? $usuario["FotoPerf"] : 'Subida/';
} else {
    echo "Error al obtener los datos del usuario.";
    exit();
}
$ResultadoA->close();
}

$Consulta = "SELECT CorrDisc FROM discografica WHERE CorrDisc = ? AND Verificacion IS NOT NULL";
$ResultadoD = $con->prepare($Consulta);
$ResultadoD->bind_param("s", $email);
$ResultadoD->execute();
$ResultadoD->store_result();

if ($ResultadoD->num_rows != 0) {
    $email = $_SESSION["email"];
$usuario = obtenerDatosDisco($email);

if ($usuario) {
    $nombre = $usuario["CorrDisc"];
    $correo = $usuario["Correo"];
    $biografia = $usuario["Biografia"];
    $fotoPerfil = $usuario["FotoPerf"] ? $usuario["FotoPerf"] : 'Subida/';
} else {
    echo "Error al obtener los datos del usuario.";
    exit();
}
$ResultadoD->close();
}



$Consulta = "SELECT CorrOyen FROM oyente WHERE CorrOyen = ?";
$Resultado = $con->prepare($Consulta);
$Resultado->bind_param("s", $email);
$Resultado->execute();
$Resultado->store_result();

if ($Resultado->num_rows != 0) {

    $email = $_SESSION["email"];
    $usuario = obtenerDatosUsuario($email);
    
    if ($usuario) {
        $nombre = $usuario["NomrUsua"];
        $correo = $usuario["Correo"];
        $biografia = $usuario["Biografia"];
        $fotoPerfil = $usuario["FotoPerf"] ? $usuario["FotoPerf"] : 'Subida/';
    } else {
        echo "Error al obtener los datos del usuario.";
        exit();
    }
$Resultado->close();
} else {

    $email = $_SESSION["email"];
    $usuario = obtenerDatosUsuario($email);
    
    if ($usuario) {
        $nombre = $usuario["NomrUsua"];
        $correo = $usuario["Correo"];
        $biografia = $usuario["Biografia"];
        $fotoPerfil = $usuario["FotoPerf"] ? $usuario["FotoPerf"] : 'Subida/';
    } else {
        echo "Error al obtener los datos del usuario.";
        exit();
    }

    $Resultado->close();
}



function determinarTipoUsuario($email) {
    global $con;
    
    // Verificar si es artista verificado
    $consultaArtista = "SELECT CorrArti FROM artistas WHERE CorrArti = ? AND Verificacion IS NOT NULL";
    $resultadoA = $con->prepare($consultaArtista);
    $resultadoA->bind_param("s", $email);
    $resultadoA->execute();
    $resultadoA->store_result();
    
    if ($resultadoA->num_rows > 0) {
        $resultadoA->close();
        return "Artista_YM.php";
    }
    
    // Verificar si es discográfica verificada
    $consultaDisc = "SELECT CorrDisc FROM discografica WHERE CorrDisc = ? AND Verificacion IS NOT NULL";
    $resultadoD = $con->prepare($consultaDisc);
    $resultadoD->bind_param("s", $email);
    $resultadoD->execute();
    $resultadoD->store_result();
    
    if ($resultadoD->num_rows > 0) {
        $resultadoD->close();
        return "Discografica_YM.php";
    }
    
    // Si no es ninguno de los anteriores, se considera oyente
    return "Usuario_YM.php";
}