<?php
include_once("inc/encabezado.php");
include_once("clases/class.fotoempBSN.php");
include_once("./inc/encabezado_html.php");

if(isset($_GET['o']) && is_numeric($_GET['o'])){
	$tema=$_GET['o'];
	$foto='';
	if(isset($_GET['f']) && is_numeric($_GET['f'])){
		$id= $_GET['f'];

		$fotoBSN= new FotoempBSN($id);
		$fotoBSN->bajarFotoemp();
	}
}

	$origen="lista_fotosemp.php?f=$id&o=$tema";
	header('location:'.$origen);

	include_once("./inc/pie.php");
?>

