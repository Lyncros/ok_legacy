<?php
include ("comun/comun.php");

$validador = new validar ();
$validador->validarCampoForm ("zona");
$validador->validarCampoForm ("estadio");
$validador->validarCampoForm ("barrio");
$validador->validarCampoForm ("tipo");
$validador->validarCampoForm ("nombre");
$validador->validarCampoForm ("apellido");
$validador->validarCampoForm ("email");
$validador->validarCampoForm ("tel");
$validador->validarCampoForm ("consulta");
$validador->validarCampoForm ("zona");

if (! $validador->ocurrieronErrores ())
{
	// mensaje para mail
	$mensaje .= "<b>Zona:</b> " . $_POST['zona'] . " <br/>";
	$mensaje .= "<b>Estd&iacute;o:</b> " . $_POST['estadio'] . " <br/>";
	$mensaje .= "<b>Barrio:</b> " . $_POST['barrio'] . " <br/>";
	$mensaje .= "<b>Tipo:</b> " . $_POST['tipo'] . " <br/>";
	$mensaje .= "<b>Nombre:</b> " . $_POST['nombre'] . " <br/>";
	$mensaje .= "<b>Apellido:</b> " . $_POST['apellido'] . " <br/>";
	$mensaje .= "<b>Email:</b> " . $_POST['email'] . " <br/>";
	$mensaje .= "<b>Tel&eacute;fono:</b> " . $_POST['tel'] . " <br/>";
	$mensaje .= "<b>Consulta:</b> " . $_POST['consulta'] . " <br/>";

	$mensaje .= "<b>utm_source:</b> " . $_POST['utm_source'] . " <br/>";
	$mensaje .= "<b>utm_medium:</b> " . $_POST['utm_medium'] . " <br/>";
	$mensaje .= "<b>utm_campaign:</b> " . $_POST['utm_campaign'] . " <br/>";
	
	// guarda en bd
	$_POST = limpiarParaSql ($_POST);
	$db->query ("INSERT INTO contacto SET fechaAlta=CURDATE(), zona='" . $_POST['zona'] . "',estadio='" . $_POST['estadio'] . "',barrio='" . $_POST['barrio'] . "',tipo='" . $_POST['tipo'] . "',nombre='" . $_POST['nombre'] . "',apellido='" . $_POST['apellido'] . "', email='" . $_POST['email'] . "', tel='" . $_POST['tel'] . "', consulta='" . $_POST['consulta'] . "', utm_medium='" . $_POST['utm_medium'] . "', utm_campaign='" . $_POST['utm_campaign'] . "', utm_source='" . $_POST['utm_source'] . "'");
	
	
	// envia el mail
	enviar_mail ("juancruz@okeefe.com.ar,  matias.losada@okeefe.com.ar ", "Nuevo mensaje desde la landing Barrios cerrados ZONA SUR", $mensaje, $_POST['email'], $_POST['apellido'].", ".$_POST['nombre']);
	//enviar_mail ("juan@lyncros.com", "Nuevo mensaje desde la landing Barrios cerrados ZONA SUR", $mensaje, $_POST['email'], $_POST['apellido'].", ".$_POST['nombre']);
	//enviar_mail ("victoria@eldodo.com.ar", "Nuevo mensaje desde la landing Barrios cerrados ZONA SUR", $mensaje, $_POST['email'], $_POST['apellido'].", ".$_POST['nombre']);

	redirect ("gracias.html");
}
else
{
	redirect ("index.html");
}
?>