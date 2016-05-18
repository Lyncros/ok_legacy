<?php
include_once("inc/encabezado.php");
include_once("clases/class.perfilesVW.php");

include_once("inc/encabezado_html.php");

//if (isset($_GET['i'])){
//	$id= $_GET['i'];

	$postreVW= new PerfilesVW();
	$postreVW->vistaTablaVW();
//}
include_once("inc/pie.php");
?>

