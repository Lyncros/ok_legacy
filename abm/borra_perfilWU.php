<?php
include_once("inc/encabezado.php");
include_once("clases/class.perfileswebuserBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['l']) && isset($_GET['i'])){
	if($_GET['l'] !='' && $_GET['i']!=''){
		$pe= $_GET['l'];
		$id=$_GET['i'];
		$perf=new Perfileswebuser();
		$perf->setId_user($id);
		$perf->setPerfil($pe);
		$propBSN= new PerfileswebuserBSN($perf);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_perfilWU.php?l=";
	header('location:'.$origen.$pe);
	include_once("./inc/pie.php");
?>