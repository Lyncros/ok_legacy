<?php
include_once("inc/encabezado.php");
include_once("clases/class.caracteristicaempBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['i'])){
	if($_GET['i'] !=''){
		$id= $_GET['i'];
		$propBSN= new CaracteristicaempBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_caracteristicaemp.php?i=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>