<?php
include_once("inc/encabezado.php");
include_once("clases/class.caracteristicaempBSN.php");
include_once("./inc/encabezado_html.php");

if(isset($_GET['i']) && is_numeric($_GET['i'])){
	$id=$_GET['i'];

		$caracBSN= new CaracteristicaempBSN($id);
		$caracBSN->bajarCarac();
}

	$origen="lista_caracteristicaemp.php?i=$id";
	header('location:'.$origen);

	include_once("./inc/pie.php");
?>

