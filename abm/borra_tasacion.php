<?php
include_once("inc/encabezado.php");
include_once("clases/class.tasacionBSN.php");
include_once("./inc/encabezado_html.php");
if(isset($_GET['i']) && is_numeric($_GET['i'])){
	$tema=$_GET['i'];

	if (isset($_GET['o'])){
		$id= $_GET['o'];
		$postreVW= new TasacionBSN($id);
		$postreVW->borraDB();
//		$postreVW->borraDB();
	}
	$id=0;
	$origen="lista_tasaciones.php?i=";
	header('location:'.$origen.$tema);
}else {
	die("Fallo la aplicacion, llame a Soporte");
}
	include_once("./inc/pie.php");
?>

