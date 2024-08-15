<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function mandarEmail($nomeDestinatario,$To,$Subject,$Message) {

	require('../lib/src/PHPMailer.php');
	require('../lib/src/Exception.php');
	require('../lib/src/SMTP.php');

	$mail = new PHPMailer;
	//$mail->SMTPDebug = 2;									// Enable verbose debug output
	$mail->isSMTP();										// Set mailer to use SMTP
	$mail->Charset = 'UTF-8';
	$mail->Host = 'smtp.gmail.com';  		  				// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;									// Enable SMTP authentication
	//$mail->SMTPSecure = 'tls';							// Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;										// TCP port to connect to
	$mail->Username = 'gabrielmuzaranho22092@gmail.com';         	// SMTP username
	$mail->Password = 'yiwlniujjhwyqibx';				// SMTP password
	$mail->From = 'gabrielmuzaranho22092@gmail.com';
	$mail->FromName = utf8_decode('Equipe de contas');
	if (gettype($To)=="array") {
		foreach ($To as $key => $value) {
			$mail->addAddress($value);  // Add a recipient
		}
	} else {
		$mail->addAddress($To);  // Add a recipient
	}
	$mail->isHTML(true);									// Set email format to HTML
	//$mail->addAttachment('/var/tmp/file.tar.gz');			// Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');	// Optional name

	$mail->Subject = utf8_decode($Subject);
	$mail->Body    = utf8_decode($Message);
	$mail->AltBody = 'Seu email precisa ser capaz de usar HTML para mostrar essa mensagem! Verifique!';

	if(!$mail->send()) {
	    return false;
	} else {
	    return true;
	}	
	//print_r($mail);
	//die();
} // termina aqui o mandarEmail

?>