<?php
include_once("inc/encabezado.php");
include_once("clases/class.caracteristicaBSN.php");
include_once("./inc/encabezado_html.php");

if(isset($_GET['i']) && is_numeric($_GET['i'])){
	$id=$_GET['i'];

		$caracBSN= new CaracteristicaBSN($id);
		$caracBSN->insertaBuscador($id,$_GET['comp']);
}

	$origen="lista_caracteristica.php?i=$id";
	header('location:'.$origen);

	include_once("./inc/pie.php");
?>

