<?php
include_once("inc/encabezado.php");
include_once("clases/class.eventoBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['i'])){
	if($_GET['i'] !=''){
		$id= $_GET['i'];
		$propBSN= new EventoBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="calendario.php?i=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>