<?php
include_once("inc/encabezado.php");
include_once("clases/class.propiedadVW.php");

include_once("inc/encabezado_html.php");

//if (isset($_GET['i'])){
//	$id= $_GET['i'];
	if(!isset($_POST['pagina'])){
		$pag=1;
	}else{
		$pag=$_POST['pagina'];
	}
	$postreVW= new PropiedadVW();
	$postreVW->vistaGoogleMaps($pag);
//}
include_once("inc/pie.php");
?>

