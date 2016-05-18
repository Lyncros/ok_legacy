<?php
include_once("inc/encabezado.php");

include_once("clases/class.datospropBSN.php");
include_once("clases/class.datospropVW.php");

$ingreso=true;
$id="";
$modi=0;
$origen="lista_propiedad.php?i=";

if (isset($_GET['i']) && is_numeric($_GET['i'])){
	$id= $_GET['i'];
	if(isset($_GET['v']) && is_numeric($_GET['v'])){
		$vista=$_GET['v'];
	}else{
		$vista=-1;
	}
        
        $carVW= new DatospropVW();
        $carVW->vistaDatosPropSh($id,$vista);

}

?>
