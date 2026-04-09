<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'recursos/mailer/Exception.php';
require 'recursos/mailer/PHPMailer.php';
require 'recursos/mailer/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

$nombre = $_POST['name'];
$email = $_POST['email'];
$dni = $_POST['dni'];
$telefono = $_POST['celular'];
$servicio = $_POST['SERVICIOS'];
$subservicio = $_POST['subservicios'];
$mensaje = $_POST['message'];

try {
    //Server settings
    $mail->SMTPDebug = 0; //Enable verbose debug output
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = 'centropsicologicomagusa@gmail.com'; //SMTP username
    $mail->Password = 'KATALINA,1.'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
    $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('centropsicologicomagusa@gmail.com', 'Magusa Arcoiris');
    $mail->addAddress('centropsicologicomagusa@gmail.com', 'Magusa Arcoiris');
    //Content
    $mail->isHTML(true); //Set email format to HTML
    $mail->Subject = 'Contacto';
    $mail->Body .= "<h1 style='color:#3498db;'>Contacto</h1>";
    $mail->Body .= '<p>Nombre :' . $nombre . '</p>';
    $mail->Body .= '<p>DNI :' . $dni . '</p>';
    $mail->Body .= '<p>Servicio :' . $servicio . ' - ' . $subservicio . '</p>';
    $mail->Body .= '<p>Email :' . $email . '</p>';
    $mail->Body .= '<p>Teléfono :' . $telefono . '</p>';
    $mail->Body .= '<p>Mensaje :' . $mensaje . '</p>';
    $mail->send();
    echo 'Correo enviado';
} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
}
