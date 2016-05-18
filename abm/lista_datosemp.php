<?php
include_once("inc/encabezado.php");
include_once("clases/class.datosempBSN.php");
include_once("clases/class.datosempVW.php");
//include_once("clases/class.tema.php");
include_once("./inc/encabezado_html.php");

if((isset($_GET['i']) && is_numeric($_GET['i'])) || isset($_POST['id_emp'])){
	if(isset($_GET[i])){
		$tema=$_GET['i'];
	}elseif (isset($_POST['id_emp'])){
		$tema=$_POST['id_emp'];
	}
	$operacionVW= new DatosempVW();
	$operacionVW->vistaTablaVW($tema);
	
}
include_once("./inc/pie.php");
?>

