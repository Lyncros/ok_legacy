<?php
include_once("inc/encabezado.php");
include_once("clases/class.contactoVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['c'])){
	$id= $_GET['c'];

	$postreVW= new ContactoVW();
	$postreVW->vistaTablaVW();
}
include_once("inc/pie.php");
?>

