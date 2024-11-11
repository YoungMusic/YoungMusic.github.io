<?php
// Cargar Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('conexion.php');

$conn = conectar_bd(); // Asegúrate de que esta función esté definida correctamente

// Database connection (puedes usar la conexión que ya tienes en conectar_bd)
$pdo = new PDO('mysql:host=localhost;dbname=bd_ym_proyect', 'root', '');

function generateNumericCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Genera un código de 6 dígitos
}

function sendResetEmail($email, $code) {
    $subject = "Restablecimiento de contraseña";
    $message = "Tu código de restablecimiento de contraseña es: " . $code;
    header("Location: verificacion.php");

    // Configuración de PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Cambia esto por tu servidor SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'youngmusic063@gmail.com'; // Tu correo
        $mail->Password   = 'd n d x k e t o p j v k s v x u'; // Tu contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Configuración de caracteres
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->setFrom('youngmusic063@gmail.com', 'YoungMusic Official'); // Cambia por tu correo y nombre
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
        header("Location: Recuperacion_YM.php");
    }
}

// Solicitud de restablecimiento de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['txtcorreo'];

    // Verificar si el correo existe en la base de datos
    $stmt = $pdo->prepare("SELECT Correo FROM usuarios WHERE Correo = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $code = generateNumericCode();
        $tiempo = date('Y-m-d H:i:s', time() + 300); // Código válido por 5 minutos

        // Almacenar el código en la base de datos
        $stmt = $pdo->prepare("INSERT INTO codigos (Correo, Codigo, Tiempo) VALUES (?, ?, ?)");
        $stmt->execute([$email, $code, $tiempo]);

        // Enviar el código por correo
        sendResetEmail($email, $code);
    } else {
        header("Location: Recuperacion_YM.php");
    }
}
?>