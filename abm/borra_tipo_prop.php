<?php
include_once("inc/encabezado.php");
include_once("clases/class.tipo_propBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['i'])){
	if($_GET['i'] !=''){
		$id= $_GET['i'];
		$propBSN= new Tipo_propBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_tipo_prop.php?i=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>