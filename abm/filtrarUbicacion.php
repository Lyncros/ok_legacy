<?php
include_once ("clases/class.ubicacionpropiedadBSN.php");
include_once ("clases/class.ubicacionpropiedadVW.php");

if(isset($_GET['n'])){
	$nombre=$_GET['n'];
	$ubiBSN = new UbicacionpropiedadBSN();
	$arrayDatos=$ubiBSN->cargaColeccionFiltro($nombre);
	$ubiVW=new UbicacionpropiedadVW();
	$ubiVW->despliegaTabla($arrayDatos);
}

?>