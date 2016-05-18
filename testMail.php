<?php
header('Content-type: text/html; charset=utf-8');

$mail_usuario = "info@okeefe.com.ar";
$destino="info@okeefe.com.ar,";
$destino .= $mail_usuario . ',';
$destino = rtrim($destino, ',');

var_dump($destino);die;

require("inc/class.phpmailer.php");

	$mensaje .= "Hasta: <br />";
	
	$mail = new PHPMailer();
	//$mail->IsSMTP();   						// set mailer to use SMTP
	$mail->Mailer = "smtp";
	$mail->Host = "ssl://smtp.gmail.com";			// specify main and backup server
	 
	//Puerto de gmail 465
	$mail->Port="465";
	$mail->SMTPAuth = true; 					// turn on SMTP authentication
	
	$mail->Username = "abm@okeefe.com.ar"; 		// SMTP username
	$mail->Password = "&SVF6&n8";                                   // SMTP password
	
	
	$mail->From = "edgardo@zgroupsa.com.ar";
	
$mail->AddAddress("edgardo@zgroupsa.com.ar");
		
	$mail->IsHTML(true);                                 	 // set email format to HTML
	$mail->Subject = 'Solicitud de Busqueda';
	
	
	$mail->Body = $mensaje;
	//	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
	
	if(!$mail->Send()){
	   echo "Message could not be sent.";
	   echo "Mailer Error: " . $mail->ErrorInfo;
	   exit;
	}
	header("location:salio.html");
?>
