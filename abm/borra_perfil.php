<?php
include_once("inc/encabezado.php");
include_once("clases/class.perfilesBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['l'])){
	if($_GET['l'] !=''){
		$id= $_GET['l'];
		$propBSN= new PerfilesBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_perfil.php?i=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>