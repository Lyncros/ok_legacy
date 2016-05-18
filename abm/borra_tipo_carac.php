<?php
include_once("inc/encabezado.php");
include_once("clases/class.tipo_caracBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['i'])){
	if($_GET['i'] !=''){
		$id= $_GET['i'];
		$propBSN= new Tipo_caracBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_tipo_carac.php?i=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>