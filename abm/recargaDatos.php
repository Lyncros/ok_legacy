<?php
//include_once("inc/encabezado.php");

include_once("inc/class.cuadroBuscador.php");
include_once("clases/class.propiedadBSN.php");

$ingreso=true;
$div='';
$pos='';
$opcion=0;
$valor='';
if (isset($_GET['div']) && isset($_GET['filtro'])){
	$div= $_GET['div'];
	$texto=$_GET['filtro'];
	
	$cuadro=new cuadrosBuscador();
	$arrayFiltro=$cuadro->string2arrayFiltro($texto);
	print_r($arrayFiltro);
	
	$propBSN = new PropiedadBSN();
	$arrayProps=$propBSN->cargaColeccionFiltroBuscadorAvanzado($arrayFiltro);
	echo "cant: ".sizeof($arrayProps)."<br>";
	print_r($arrayProps);
}

?>
