<?php
include_once("inc/encabezado.php");
include_once("clases/class.comentarioBSN.php");
include_once("clases/class.comentarioVW.php");
include_once ("./inc/encabezado_pop.php");

if((isset($_GET['i']) && is_numeric($_GET['i'])) || isset($_POST['id_prop'])){
	if(isset($_GET['i'])){
		$tema=$_GET['i'];
	}elseif (isset($_POST['id_prop'])){
		$tema=$_POST['id_prop'];
	}
	$comVW= new ComentarioVW();
	$comVW->vistaTablaVW($tema);
	
}
include_once("./inc/pie.php");
?>

