<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        // CREAR EL OBJETO DE MAIL
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('from@APPSalon.com', 'APPSalon');
        $mail->addAddress('admin@APPSalon.com', 'ADMIN');
        $mail->Subject = 'Confirmación de registro en APPsalon';

        // SET HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>, Has creado una cuenta en APPSalon, confirma presionando el siguiente enlace: </p>";
        $contenido .= "<a href='" . $_ENV['PROJECT_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar cuenta</a>";
        $contenido .= "<p>Si no solitaste nada, ignora este correo</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        // ENVIAR EL CORREO
        $mail->send();
    }

    public function enviarInstrucciones() {
          // CREAR EL OBJETO DE MAIL
          $mail = new PHPMailer();
          $mail->isSMTP();
          $mail->Host = $_ENV['EMAIL_HOST'];
          $mail->SMTPAuth = true;
          $mail->Port = $_ENV['EMAIL_PORT'];
          $mail->Username = $_ENV['EMAIL_USER'];
          $mail->Password = $_ENV['EMAIL_PASS'];
  
          $mail->setFrom('from@APPSalon.com', 'APPSalon');
          $mail->addAddress('admin@APPSalon.com', 'ADMIN');
          $mail->Subject = 'Recuperación de cuenta';
  
          // SET HTML
          $mail->isHTML(true);
          $mail->CharSet = 'UTF-8';
  
          $contenido = "<html>";
          $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>, Has solicitado una recuperación de cuenta, dirijase al siguiente enlace para poder crear una nueva contraseña. </p>";
          $contenido .= "<a href='" . $_ENV['PROJECT_URL'] . "/recuperar?token=" . $this->token . "'>Reestablecer Contraseña</a>";
          $contenido .= "<p>Si no solitaste nada, ignora este correo</p>";
          $contenido .= "</html>";
          $mail->Body = $contenido;
  
          // ENVIAR EL CORREO
          $mail->send();
    }
}