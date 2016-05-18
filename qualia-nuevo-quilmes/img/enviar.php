<?php
include ("comun/comun.php");

$validador = new validar ();
$validador->validarCampoForm ("nombre");
$validador->validarCampoForm ("apellido");
$validador->validarCampoForm ("email");
$validador->validarCampoForm ("tel");
$validador->validarCampoForm ("consulta");

if (! $validador->ocurrieronErrores ())
{
	// mensaje para mail
	$mensaje .= "<b>Nombre:</b> $_POST[nombre] <br/>";
	$mensaje .= "<b>Apellido:</b> $_POST[apellido] <br/>";
	$mensaje .= "<b>Email:</b> $_POST[email] <br/>";
	$mensaje .= "<b>Tel&eacute;fono:</b> $_POST[tel] <br/>";
	$mensaje .= "<b>Consulta:</b> $_POST[consulta] <br/>";
	
	$mensaje .= "<b>utm_source:</b> " . $_POST['utm_source'] . " <br/>";
	$mensaje .= "<b>utm_medium:</b> " . $_POST['utm_medium'] . " <br/>";
	$mensaje .= "<b>utm_campaign:</b> " . $_POST['utm_campaign'] . " <br/>";
	
	$sqlUTM = ", utm_medium='" . $_POST['utm_medium'] . "', utm_campaign='" . $_POST['utm_campaign'] . "', utm_source='" . $_POST['utm_source'] . "'";
	
	// guarda en bd
	$_POST = limpiarParaSql ($_POST);
	$db->query ("INSERT INTO contacto SET fechaAlta=CURDATE(), nombre='$_POST[nombre]',apellido='$_POST[apellido]', email='$_POST[email]', tel='$_POST[tel]', consulta='$_POST[consulta]'" . $sqlUTM);
	
	// envia el mail
	enviar_mail ("juancruz@okeefe.com.ar", "Nuevo mensaje desde landing de Qualia", $mensaje, $_POST[email], $_POST[nombre] . " " . $_POST[apellido]);
	
	redirect ("gracias.html");
}
else
{
	redirect ("index.html");
}
?>