<?php
include_once("inc/encabezado.php");
include_once("clases/class.cartelBSN.php");
include_once("./inc/encabezado_html.php");
if(isset($_GET['i']) && is_numeric($_GET['i'])){
	$tema=$_GET['i'];

	if (isset($_GET['o'])){
		$id= $_GET['o'];
		$postreVW= new CartelBSN($id);
		$postreVW->borraDB();
//		$postreVW->borraDB();
	}
	$id=0;
	$origen="lista_carteles.php?i=";
	header('location:'.$origen.$tema);
}else {
	die("Fallo la aplicacion, llame a Soporte");
}
	include_once("./inc/pie.php");
?>

