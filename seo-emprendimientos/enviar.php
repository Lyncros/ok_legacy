<?php
include("comun/comun.php");

$validador = new validar();
$validador->validarCampoForm("nombre");
$validador->validarCampoForm("apellido");
$validador->validarCampoForm("email");
$validador->validarCampoForm("tel");
$validador->validarCampoForm("consulta");

if (!$validador->ocurrieronErrores()) {
	//mensaje para mail
	$mensaje .= "<b>Nombre:</b> $_POST[nombre] <br/>";
	$mensaje .= "<b>Apellido:</b> $_POST[apellido] <br/>";
	$mensaje .= "<b>Email:</b> $_POST[email] <br/>";
	$mensaje .= "<b>Tel&eacute;fono:</b> $_POST[tel] <br/>";
	$mensaje .= "<b>Consulta:</b> $_POST[consulta] <br/>";

	//guarda en bd
	$_POST = limpiarParaSql($_POST);
	$db->query("INSERT INTO contacto SET fechaAlta=CURDATE(), nombre='$_POST[nombre]', apellido='$_POST[apellido]', email='$_POST[email]', tel='$_POST[tel]', consulta='$_POST[consulta]'");

	//envia el mail
	enviar_mail("inmobiliaria@okeefe.com.ar", "Nuevo mensaje desde la web", $mensaje, "inmobiliaria@okeefe.com.ar", "Nuevo mensaje desde la web");

	redirect("gracias.html");
}else{
	redirect("index.html");
}
?>