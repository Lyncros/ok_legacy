<?php
include_once("inc/encabezado.php");
include_once("clases/class.fotoempBSN.php");
include_once("./inc/encabezado_html.php");

if(isset($_GET['o']) && is_numeric($_GET['o'])){
	$tema="o=".$_GET['o'];

	if (isset($_GET['f'])){
		$id= $_GET['f'];
		$postreVW= new FotoempBSN($id);
		$postreVW->borraFotoemp();
//		$postreVW->borraDB();
	}
	$id=0;
	$origen="lista_fotosemp.php?";
	header('location:'.$origen.$tema);
}else {
	die("Fallo la aplicacion, llame a Soporte");
}
	include_once("./inc/pie.php");
?>

