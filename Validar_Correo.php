<?php
require_once("conexion.php");
require_once("Funciones.php");

header('Content-Type: application/json');

if (isset($_POST['email'])) {
    $con = conectar_bd();
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    $exists = consultar_existe_usr($con, 'usuarios', 'Correo', $email);
    
    echo json_encode(['exists' => $exists]);
    mysqli_close($con);
} else {
    echo json_encode(['exists' => false]);
}