<?php
include_once("inc/encabezado.php");
include_once("clases/class.perfileswebuserVW.php");
include_once("clases/class.perfileswebuser.php");

include_once("inc/encabezado_html.php");

if (isset($_GET['l'])){
	$id= $_GET['l'];
	$perf= new Perfileswebuser($id);
	
	$postreVW= new PerfileswebuserVW($perf);
	$postreVW->vistaTablaVW();
}
include_once("inc/pie.php");
?>

