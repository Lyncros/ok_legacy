<?php
include_once("inc/encabezado.php");
include_once("clases/class.datosempBSN.php");
include_once("./inc/encabezado_html.php");

if (isset($_GET['o']) && isset($_GET['i'])){
	$id= $_GET['o'];
	$idemp=$_GET['i'];
	$notiBSN= new DatosempBSN($id);
	$notiBSN->quitarCaracEmprendimiento();
	$origen="lista_datosemp.php?i=";
}else{

	$origen="lista_emprendimiento.php?i=";
}
	header('location:'.$origen.$idemp);

	include_once("./inc/pie.php");

?>

