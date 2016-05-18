<?php
include_once("inc/encabezado.php");
include_once("clases/class.cartelBSN.php");
include_once("clases/class.cartelVW.php");
//include_once("clases/class.tema.php");
include_once("./inc/encabezado_html.php");

if((isset($_GET['i']) && is_numeric($_GET['i'])) || isset($_POST['id_prop'])){
	if(isset($_GET[i])){
		$tema=$_GET['i'];
	}elseif (isset($_POST['id_prop'])){
		$tema=$_POST['id_prop'];
	}
	$cartelVW= new CartelVW();
	$cartelVW->vistaTablaVW($tema);
	
}
include_once("./inc/pie.php");
?>

