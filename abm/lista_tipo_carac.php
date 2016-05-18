<?php
include_once("inc/encabezado.php");
include_once("clases/class.tipo_caracVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['i'])){
	$id= $_GET['i'];

	$postreVW= new Tipo_caracVW();
	$postreVW->vistaTablaVW();
}
include_once("inc/pie.php");
?>

