<?php
include_once("inc/encabezado.php");
include_once("clases/class.eventoVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['i'])){
	$id= $_GET['i'];

	$postreVW= new EventoVW();
	$postreVW->vistaTablaEvento($anio,$mes,$dia);
}
include_once("inc/pie.php");
?>

