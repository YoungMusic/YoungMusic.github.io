<?php
require_once("conexion.php");

$email = $_SESSION["email"];

function obtenerAlbumes($correo) {
    $conexion = conectar_bd(); // Usando la función de conexión existente
    
    if (!$conexion) {
        return array(); // Retorna array vacío si hay error de conexión
    }
    
    $query = "SELECT * FROM albun WHERE NomCred = ? ORDER BY FechaLan DESC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $albumes = array();
    while($fila = $resultado->fetch_assoc()) {
        $albumes[] = $fila;
    }
    
    $stmt->close();
    $conexion->close();
    
    return $albumes;
}
?>