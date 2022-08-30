// Funciones para enviar Email

<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'Exception.php';
	require 'PHPMailer.php';
	require 'SMTP.php';

    class clsMail{
          private 

           function __construct() {
               $this->mail = new PHPMailer();
			   $this->mail->isSMTP();
               $this->mail->SMTPAuth = true;
               $this->mail->SMTPSecure = 'tls';
               $this->mail->Host = 'smtp.gmail.com'; 
               $this->mail->Port = 587;
           
		   }

		}


?>
