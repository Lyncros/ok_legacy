<?php
include_once("inc/encabezado.php");
include_once("clases/class.rubroVW.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['u'])){
	$id= $_GET['u'];

	$postreVW= new RubroVW();
	$postreVW->vistaTablaVW();
}
include_once("inc/pie.php");
?>

