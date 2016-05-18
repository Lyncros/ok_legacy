<?php
include_once("inc/encabezado.php");
include_once("clases/class.loginwebuserVW.php");

include_once("inc/encabezado_html.php");

//if (isset($_GET['i'])){
//	$id= $_GET['i'];

	$postreVW= new LoginwebuserVW();
	$postreVW->vistaTablaUsuarios();
//}
include_once("inc/pie.php");
?>

