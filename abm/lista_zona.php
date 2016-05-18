<?php
include_once("inc/encabezado.php");
include_once("clases/class.zonaVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['i'])){
	$id= $_GET['i'];

	$postreVW= new ZonaVW();
	$postreVW->vistaTablaZona();
}
include_once("inc/pie.php");
?>

