<?php
include_once("inc/encabezado.php");
include_once ("./inc/encabezado_pop.php");
include_once("clases/class.zonapropBSN.php");

$zp= new ZonapropBSN();

$zp->muestraColeccion();
include_once("inc/pie.php");
?>
