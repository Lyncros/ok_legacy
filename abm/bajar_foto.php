<?php
include_once("inc/encabezado.php");
include_once("clases/class.fotoBSN.php");
include_once("./inc/encabezado_html.php");

if(isset($_GET['i']) && is_numeric($_GET['i'])){
	$tema=$_GET['i'];
	$foto='';
	if(isset($_GET['f']) && is_numeric($_GET['f'])){
		$id= $_GET['f'];

		$fotoBSN= new FotoBSN($id);
		$fotoBSN->bajarFoto();
	}
}

	$origen="lista_fotos.php?f=$id&i=$tema";
	header('location:'.$origen);

	include_once("./inc/pie.php");
?>

