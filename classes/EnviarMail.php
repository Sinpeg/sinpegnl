<?php
require '../../vendors/phpmailer_new/PHPMailer.php';
require_once '../../vendors/phpmailer_new/SMTP.php';
require_once '../../vendors/phpmailer_new/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require '../../vendors/phpmailer_new/autoload.php';


Class EnviarMail{
	
    private $destino;
    private $assunto;
    private $mensagem;
    
    public function criaEnviarMail($assunto,$mensagem,$destino){
    	$this->destino=$destino;
    	$this->assunto=$assunto;
    	$this->mensagem=$mensagem;
    }
    
	
	public function enviarEmail(){
		
	    $mail = new PHPMailer(true);
	    
	    try {
	        //Server settings
	        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
	        $mail->isSMTP();                                            //Send using SMTP
	        $mail->Host       = 'cupijo.ufpa.br';                     //Set the SMTP server to send through
	        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	        $mail->Username   = 'sinpeg@ufpa.br';                     //SMTP username
	        $mail->Password   = 'sinpegproplan';                               //SMTP password
	        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
	        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
	        $mail->SMTPSecure = "tls";
	        
	        //Recipients
	        $mail->setFrom('sinpeg@ufpa.br', 'SInPeG');
	        $mail->addAddress($this->destino, 'SInPeG');     //Add a recipient
	        //$mail->addAddress('ellen@example.com');               //Name is optional
	        //$mail->addReplyTo('info@example.com', 'Information');
	        //$mail->addCC('cc@example.com');
	        //$mail->addBCC('bcc@example.com');
	        
	        //Attachments
	        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
	        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
	        
	        //Content
	        $mail->isHTML(true);                                  //Set email format to HTML
	        $mail->Subject = utf8_decode($this->assunto);
	        $mail->Body    = utf8_decode($this->mensagem);
	        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	        
	        $mail->send();
	        return 1;
	    } catch (Exception $e) {
	        return 0;
	    }
	}
	
}