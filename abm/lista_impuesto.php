<?php
include_once("inc/encabezado.php");
include_once("clases/class.impuestoVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['imp'])){
	$id= $_GET['imp'];

	$postreVW= new ImpuestoVW();
	$postreVW->vistaTablaVW();
}
include_once("inc/pie.php");
?>

