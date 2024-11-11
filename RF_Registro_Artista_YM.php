
<?php
session_start();
require_once("conexion.php");
require("Funciones.php");

$con = conectar_bd();


    $nombre = $_POST["nombre_a"];
    $fecha = $_POST["fecha"];
    $email = $_SESSION["email"];
    $usuario = obtenerDatosUsuario($email);

    if ($usuario) {
        $correo = $usuario["Correo"];

        insertar_usr($con, $Tabla = 'artistas', $columnas = 'CorrArti, NombArtis, FechNacA', $Valores = [$email, $nombre, $fecha], consultar_existe_usr($con, $tabla = 'artistas', $columna = 'CorrArti', $email));
        header("Location: Redes_Arti_YM.php");
    } else {
        echo "Error al obtener los datos del usuario.";
        exit();
    }

