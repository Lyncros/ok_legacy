<?php
include_once("inc/encabezado.php");
include_once("clases/class.tipo_caracBSN.php");
include_once("./inc/encabezado_html.php");

if(isset($_GET['i']) && is_numeric($_GET['i'])){
		$id= $_GET['i'];
		$caracBSN= new Tipo_caracBSN($id);
		$caracBSN->subirTipoCarac();
}

	$origen="lista_tipo_carac.php?i=$id";
	header('location:'.$origen);

	include_once("./inc/pie.php");
?>

