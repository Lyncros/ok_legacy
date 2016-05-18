<?php
include_once("inc/encabezado.php");
include_once("clases/class.clienteBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['c'])){
	if($_GET['c'] !=''){
		$id= $_GET['c'];
		$propBSN= new ClienteBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_clientes.php?c=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>