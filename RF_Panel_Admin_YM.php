<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("conexion.php");
require("Funciones.php");
// Verificar si es admin
if(!isset($_SESSION["email"]) || !esAdmin($_SESSION["email"])) {
    header("Location: Login_YM.php");
    exit();
}

// Procesar confirmación
if(isset($_POST['confirmar'])) {
    $tipo = $_POST['tipo'];
    $correo = $_POST['correo'];
    
    if($tipo === 'artista') {
        $query = "UPDATE artistas SET Verificacion = 1 WHERE CorrArti = ?";
    } elseif($tipo === 'discografica') {
        $query = "UPDATE discografica SET Verificacion = 1 WHERE CorrDisc = ?";
    } elseif($tipo === 'oyente') {
        $query = "UPDATE oyente SET PermO = 1 WHERE CorrOyen = ?";
    }
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
}

// Obtener artistas sin verificar
$query_artistas = "SELECT CorrArti, NombArtis, FechNacA FROM artistas WHERE Verificacion = 0 OR Verificacion IS NULL";
$artistas = $con->query($query_artistas);

// Obtener discográficas sin verificar
$query_discograficas = "SELECT CorrDisc, NombDisc FROM discografica WHERE Verificacion = 0 OR Verificacion IS NULL";
$discograficas = $con->query($query_discograficas);

// Obtener oyentes sin permisos
$query_oyentes = "SELECT CorrOyen, NomrUsua FROM oyente INNER JOIN usuarios ON Correo=CorrOyen WHERE PermO IS NULL";
$oyentes = $con->query($query_oyentes);



?>
