<?php
include_once("inc/encabezado.php");
include_once("clases/class.localidadVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['i'])){
	$id= $_GET['i'];

	$postreVW= new LocalidadVW();
	$postreVW->vistaTablaLocalidad();
}
include_once("inc/pie.php");
?>

