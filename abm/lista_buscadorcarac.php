<?php
include_once("inc/encabezado.php");
include_once("clases/class.caracteristicaVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['i'])){
	$id= $_GET['i'];

	$postreVW= new CaracteristicaVW();
	$postreVW->tablaBuscadorCaracteristica();
}
include_once("inc/pie.php");
?>

