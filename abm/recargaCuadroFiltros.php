<?php
//include_once("inc/encabezado.php");

include_once("inc/class.cuadroBuscador.php");

$ingreso=true;
$div='';
$pos='';
$opcion=0;
$valor='';
if (isset($_GET['div']) && isset($_GET['texto'])){
	$div= $_GET['div'];
	$texto=$_GET['texto'];
	$filtro=$_GET['filtro'];
	
	$cuadro=new cuadrosBuscador();
	$cuadro->cargaCuadroFiltros($div,$texto,$filtro);
}

?>
