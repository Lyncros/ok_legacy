<?php
include_once("inc/encabezado.php");
include_once("clases/class.fotoempBSN.php");
include_once("clases/class.fotoempVW.php");
//include_once("clases/class.tema.php");
include_once("./inc/encabezado_html.php");

if((isset($_GET['o']) && is_numeric($_GET['o'])) || isset($_POST['id_emp_carac'])){
	if(isset($_GET['o'])){
		$tema=$_GET['o'];
	}elseif (isset($_POST['id_emp_carac'])){
		$tema=$_POST['id_emp_carac'];
	}
	$fotoVW= new FotoempVW();
	$fotoVW->vistaTablaVW($tema);
	
}
include_once("./inc/pie.php");
?>

