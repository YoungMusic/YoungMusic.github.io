<?php
include('conexion.php');

$conexion = conectar_bd();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Función para validar la contraseña
    function validarPassword($password) {
        $errores = [];
        
        // Validar longitud mínima
        if (strlen($password) < 7) {
            $errores[] = "La contraseña debe tener al menos 7 caracteres";
        }
        
        // Validar mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            $errores[] = "La contraseña debe contener al menos una mayúscula";
        }
        
        // Validar número
        if (!preg_match('/[0-9]/', $password)) {
            $errores[] = "La contraseña debe contener al menos un número";
        }
        
        return $errores;
    }

    // Validar la nueva contraseña
    $errores = validarPassword($newPassword);

    // Verificar que las contraseñas coincidan
    if ($newPassword !== $confirmPassword) {
        $errores[] = "Las contraseñas no coinciden";
    }

    // Si no hay errores, proceder con el cambio
    if (empty($errores)) {
        // Hash de la nueva contraseña
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $stmt = $conexion->prepare("UPDATE usuarios SET Contra = ? WHERE Correo = ?");
        
        if ($stmt === false) {
            echo "Error en la preparación de la consulta: " . $conexion->error;
            exit;
        }

        // Vincular parámetros
        $stmt->bind_param("ss", $hashedPassword, $email);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Eliminar el código de restablecimiento de la base de datos
            $stmt = $conexion->prepare("DELETE FROM codigos WHERE Correo = ?");
            if ($stmt === false) {
                echo "Error en la preparación de la consulta: " . $conexion->error;
                exit;
            }

            // Vincular parámetros
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                // Si todo sale bien, redirigir al login
                header("Location: Login_YM.php");
                exit;
            } else {
                header("Location: Recuperacion_YM.php?error=delete");
                exit;
            }
        } else {
            header("Location: Recuperacion_YM.php?error=update");
            exit;
        }

        $stmt->close();
    } else {
        // Si hay errores, redirigir con mensaje de error
        $errorString = implode(", ", $errores);
        header("Location: Recuperacion_YM.php?error=" . urlencode($errorString));
        exit;
    }
} else {
    // Si no es POST, redirigir
    header("Location: Recuperacion_YM.php");
    exit;
}
?>