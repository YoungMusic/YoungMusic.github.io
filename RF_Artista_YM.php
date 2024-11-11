<?php
session_start();
require_once("conexion.php");
require("Funciones.php");
require_once("RF_Álbumes_YM.php");

if (!isset($_SESSION["email"])) {
    header("Location: Login_YM.php");
    exit();
}

$email = $_SESSION["email"];

$Consulta = "SELECT CorrArti FROM artistas WHERE CorrArti = ? AND Verificacion IS NOT NULL";
$Resultado = $con->prepare($Consulta);
$Resultado->bind_param("s", $email);
$Resultado->execute();
$Resultado->store_result();

if ($Resultado->num_rows == 0) {

    header("Location: Login_YM.php");
    exit();
}

$Resultado->close();

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
$albumes = obtenerAlbumes($email);

if (isset($_POST['editarPerfil'])) {
    $nuevoNombre = $_POST['nuevoNombre'];
    $nuevaBiografia = $_POST['nuevaBiografia'];
    $nuevaFoto = $_FILES['nuevaFoto'];
    $password = $_POST['password'];
    $redes = [$_POST['Red1'], $_POST['Red2'], $_POST['Red3'], $_POST['Red4']];

    if (verificarContrasena($email, $password)) {
        if (editarPerfilArtista($email, $nuevoNombre, $nuevaBiografia, $nuevaFoto, $redes)) {
            echo "Perfil actualizado correctamente.";
            header("Location: Artista_YM.php");
            exit();
        } else {
            echo "Error al actualizar el perfil.";
        }
    } else {
        echo "Contraseña incorrecta.";
    }
}

if (isset($_POST['eliminarPerfil'])) {
    $password = $_POST['password'];

    if (verificarContrasena($email, $password)) {
        if (eliminarPerfil($email)) {
            session_destroy();
            echo "Perfil eliminado correctamente.";
            header("Location: Registro_YM.php");
            exit();
        } else {
            echo "Error al eliminar el perfil.";
        }
    } else {
        echo "Contraseña incorrecta.";
    }
}

