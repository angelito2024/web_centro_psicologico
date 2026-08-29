<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'recursos/mailer/Exception.php';
require 'recursos/mailer/PHPMailer.php';
require 'recursos/mailer/SMTP.php';

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Método no permitido.';
    exit;
}

$configFile = __DIR__ . '/mail-config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    echo 'Error de configuración del servidor.';
    exit;
}
$config = require $configFile;

function campo(string $key): string
{
    return trim($_POST[$key] ?? '');
}

$nombre = campo('name');
$email = campo('email');
$dni = campo('dni');
$telefono = campo('celular');
$servicio = campo('SERVICIOS');
$subservicio = campo('subservicios');
$mensaje = campo('message');

if ($nombre === '' || $email === '' || $mensaje === '') {
    echo 'Por favor completa nombre, email y mensaje.';
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'El correo electrónico ingresado no es válido.';
    exit;
}

// Escapamos todo antes de insertarlo en el cuerpo HTML del correo
$nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$dni = htmlspecialchars($dni, ENT_QUOTES, 'UTF-8');
$telefono = htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8');
$servicio = htmlspecialchars($servicio, ENT_QUOTES, 'UTF-8');
$subservicio = htmlspecialchars($subservicio, ENT_QUOTES, 'UTF-8');
$mensaje = nl2br(htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'));

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0; //Enable verbose debug output
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = $config['smtp_host']; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = $config['smtp_user']; //SMTP username
    $mail->Password = $config['smtp_pass']; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
    $mail->Port = $config['smtp_port']; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['to_email'], $config['to_name']);
    $mail->addReplyTo($email, $nombre);

    //Content
    $mail->isHTML(true); //Set email format to HTML
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Contacto - ' . $nombre;
    $mail->Body .= "<h1 style='color:#3498db;'>Contacto</h1>";
    $mail->Body .= '<p>Nombre: ' . $nombre . '</p>';
    $mail->Body .= '<p>DNI: ' . $dni . '</p>';
    $mail->Body .= '<p>Servicio: ' . $servicio . ' - ' . $subservicio . '</p>';
    $mail->Body .= '<p>Email: ' . $email . '</p>';
    $mail->Body .= '<p>Teléfono: ' . $telefono . '</p>';
    $mail->Body .= '<p>Mensaje: ' . $mensaje . '</p>';
    $mail->send();
    echo 'Correo enviado';
} catch (Exception $e) {
    error_log('contacto.php mail error: ' . $mail->ErrorInfo);
    echo 'No se pudo enviar el mensaje. Intenta nuevamente más tarde.';
}
