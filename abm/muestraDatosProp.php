<?php
include_once("inc/encabezado.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedadVW.php");
include_once("clases/class.operacionBSN.php");
include_once("clases/class.tasacionBSN.php");

$ingreso=true;
$id="";
$modi=0;
$origen="lista_propiedad.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
        
	$notiVW= new PropiedadVW($id);
        $notiVW->vistaDatosPropiedad();
}

?>