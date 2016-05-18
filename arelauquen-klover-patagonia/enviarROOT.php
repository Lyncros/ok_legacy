<?php
include("comun/comun.php");

$validador = new validar();
$validador->validarCampoForm("nombre");
$validador->validarCampoForm("email");
$validador->validarCampoForm("tel", "", "", "Tel&eacute;fono");
$validador->validarCampoForm("consulta");

if (!$validador->ocurrieronErrores()) {
	//mensaje para mail
	$mensaje .= "<b>Nombre:</b> $_POST[nombre] <br/>";
	$mensaje .= "<b>Email:</b> $_POST[email] <br/>";
	$mensaje .= "<b>Tel&eacute;fono:</b> $_POST[tel] <br/>";
	$mensaje .= "<b>Consulta:</b> $_POST[consulta] <br/>";

	//guarda en bd
	$_POST = limpiarParaSql($_POST);
	$db->query("INSERT INTO contacto SET fechaAlta=CURDATE(), nombre='$_POST[nombre]', email='$_POST[email]', tel='$_POST[tel]', consulta='$_POST[consulta]'");

	//envia el mail
	enviar_mail("info@okeefe.com.ar", "Contacto desde la Landing de Arelauquen Klover Paragonia", $mensaje, $_POST[email], $_POST[nombre]);

	redirect("gracias.html");
}else{
	redirect("index.html");
}
?>