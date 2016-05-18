<?php
include_once("inc/encabezado.php");
include_once("clases/class.impuestoBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['imp'])){
	if($_GET['imp'] !=''){
		$id= $_GET['imp'];
		$propBSN= new ImpuestoBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_impuesto.php?imp=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>