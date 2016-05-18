<?php
include_once("inc/encabezado.php");
include_once("clases/class.fotoBSN.php");
include_once("./inc/encabezado_html.php");
if(isset($_GET['i']) && is_numeric($_GET['i'])){
	$tema=$_GET['i'];

	if (isset($_GET['f'])){
		$id= $_GET['f'];
		$postreVW= new FotoBSN($id);
		$postreVW->borraFoto();
//		$postreVW->borraDB();
	}
	$id=0;
	$origen="lista_fotos.php?i=";
	header('location:'.$origen.$tema);
}else {
	die("Fallo la aplicacion, llame a Soporte");
}
	include_once("./inc/pie.php");
?>

