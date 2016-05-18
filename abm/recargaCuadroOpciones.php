<?php
//include_once("inc/encabezado.php");

include_once("inc/class.cuadroBuscador.php");

$ingreso=true;
$div='';
$pos='';
$opcion=0;
$valor='';
if (isset($_GET['div']) ){
	$div= $_GET['div'];
	$tam=$_GET['tam'];

	if(isset($_GET['opcion'])){
		$opcion=$_GET['opcion'];
	}
	$cuadro=new cuadrosBuscador();
	$cuadro->cargaCuadroOpciones($div,$tam,0,1,$opcion);
}

?>
