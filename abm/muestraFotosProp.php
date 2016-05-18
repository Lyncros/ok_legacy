<?php
include_once("inc/encabezado.php");
include_once("clases/class.fotoBSN.php");
include_once("clases/class.fotoVW.php");

$ingreso=true;
$id="";
$modi=0;

if (isset($_GET['i'])){
	$id= $_GET['i'];
        
    $fotoVW= new FotoVW();
	$fotoVW->muestraFotosProp($id);
        
}

?>
