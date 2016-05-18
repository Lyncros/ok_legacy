<?php
session_start();
ob_start();
include_once("clases/class.comentarioBSN.php");
include_once("clases/class.comentarioVW.php");

if(isset($_GET['prop']) && is_numeric($_GET['prop']) && isset($_GET['tipo'])){
	$tipo=$_GET['tipo'];
	$prop=$_GET['prop'];
        $comBSN= new ComentarioBSN();
        $arrayDatos=$comBSN->cargaColeccionHistorial($prop, $tipo);
        $comVW= new ComentarioVW();
	$comVW->despliegaTabla($arrayDatos);
	
}
?>

