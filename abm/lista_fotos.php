<?php
include_once("inc/encabezado.php");
include_once("clases/class.fotoBSN.php");
include_once("clases/class.fotoVW.php");
//include_once("clases/class.tema.php");
include_once("./inc/encabezado_html.php");

if((isset($_GET['i']) && is_numeric($_GET['i'])) || isset($_POST['id_prop'])){
	if(isset($_GET[i])){
		$tema=$_GET['i'];
	}elseif (isset($_POST['id_prop'])){
		$tema=$_POST['id_prop'];
	}
	$fotoVW= new FotoVW();
	$fotoVW->vistaTablaVW($tema);
	
}
include_once("./inc/pie.php");
?>

