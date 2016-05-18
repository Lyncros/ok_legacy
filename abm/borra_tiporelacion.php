<?php
include_once("inc/encabezado.php");
include_once("clases/class.tiporelacionBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['r'])){
	if($_GET['r'] !=0){
		$id= $_GET['r'];
		$trelBSN= new TiporelacionBSN($id);
		$trelBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_tiporelacion.php?r=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>