<?php
include ("comun/comun.php");

$validador = new validar ();
$validador->validarCampoForm ("nombre");
$validador->validarCampoForm ("apellido");
$validador->validarCampoForm ("email");
$validador->validarCampoForm ("tel");
$validador->validarCampoForm ("calle");
$validador->validarCampoForm ("numero");
$validador->validarCampoForm ("piso");
$validador->validarCampoForm ("localidad");
$validador->validarCampoForm ("cp");

if (! $validador->ocurrieronErrores ())
{
	// mensaje para mail
	$mensaje .= "<b>Nombre:</b> " . $_POST['nombre'] . " <br/>";
	$mensaje .= "<b>Apellido:</b> " . $_POST['apellido'] . " <br/>";
	$mensaje .= "<b>Email:</b> " . $_POST['email'] . " <br/>";
	$mensaje .= "<b>Tel&eacute;fono:</b> " . $_POST['tel'] . " <br/>";
	$mensaje .= "<b>Calle:</b> " . $_POST['calle'] . " <br/>";
	$mensaje .= "<b>N&uacute;mero:</b> " . $_POST['numero'] . " <br/>";
	$mensaje .= "<b>Piso:</b> " . $_POST['piso'] . " <br/>";
	$mensaje .= "<b>Localidad:</b> " . $_POST['localidad'] . " <br/>";
	$mensaje .= "<b>C&oacute;digo postal:</b> " . $_POST['cp'] . " <br/>";

	$mensaje .= "<b>utm_source:</b> " . $_POST['utm_source'] . " <br/>";
	$mensaje .= "<b>utm_medium:</b> " . $_POST['utm_medium'] . " <br/>";
	$mensaje .= "<b>utm_campaign:</b> " . $_POST['utm_campaign'] . " <br/>";

	$sqlUTM = ", utm_medium='" . $_POST['utm_medium'] . "', utm_campaign='" . $_POST['utm_campaign'] . "', utm_source='" . $_POST['utm_source'] . "'";
	
	// guarda en bd
	$_POST = limpiarParaSql ($_POST);
	$db->query ("INSERT INTO contacto SET fechaAlta=CURDATE(), nombre='" . $_POST['nombre'] . "',apellido='" . $_POST['apellido'] . "', email='" . $_POST['email'] . "', tel='" . $_POST['tel'] . "', calle='" . $_POST['calle'] .  "',numero='" . $_POST['numero'] . "',piso='" . $_POST['piso'] . "',localidad='" . $_POST['localidad'] . "',cp='" . $_POST['cp'] . "'" . $sqlUTM);
	
	// envia el mail
	enviar_mail ("julian.laferrera@okeefe.com.ar, silvina@se-mas.com.ar", "Nuevo mensaje desde la landing REVISTA", $mensaje, $_POST['email'], $_POST['apellido'].", ".$_POST['nombre']);
	//enviar_mail ("juancruz@okeefe.com.ar", "Nuevo mensaje desde la landing REVISTA", $mensaje, $_POST['email'], $_POST['apellido'].", ".$_POST['nombre']);
	//enviar_mail ("ivanberlot@gmail.com", "Nuevo mensaje desde la landing REVISTA", $mensaje, $_POST['email'], $_POST['apellido'].", ".$_POST['nombre']);
	//enviar_mail ("victoria@eldodo.com.ar, mariadelmar@eldodo.com.ar", "Nuevo mensaje desde la landing REVISTA", $mensaje, $_POST['email'], $_POST['apellido'].", ".$_POST['nombre']);
	redirect ("gracias.html");
}
else
{
	redirect ("index.html");
}
?>