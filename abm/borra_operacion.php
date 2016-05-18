<?php
include_once("inc/encabezado.php");
include_once("clases/class.operacionBSN.php");
include_once("./inc/encabezado_html.php");
if(isset($_GET['i']) && is_numeric($_GET['i'])){
	$tema=$_GET['i'];

	if (isset($_GET['o'])){
		$id= $_GET['o'];
		$postreVW= new OperacionBSN($id);
		$postreVW->borraDB();
//		$postreVW->borraDB();
	}
	$id=0;
	$origen="lista_operaciones.php?i=";
	header('location:'.$origen.$tema);
}else {
	die("Fallo la aplicacion, llame a Soporte");
}
	include_once("./inc/pie.php");
?>

