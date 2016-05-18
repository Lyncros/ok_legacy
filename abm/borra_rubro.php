<?php
include_once("inc/encabezado.php");
include_once("clases/class.rubroBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['u'])){
	if($_GET['u'] !=''){
		$id= $_GET['u'];
		$propBSN= new RubroBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_rubros.php?u=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>