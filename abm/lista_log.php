<?php
include_once("inc/encabezado.php");
include_once("clases/class.tiporelacionVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['r'])){
	$id= $_GET['r'];

	$postreVW= new TiporelacionVW();
	$postreVW->vistaTablaVW();
}
include_once("inc/pie.php");
?>

