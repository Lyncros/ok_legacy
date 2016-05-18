<?php
include_once ("clases/class.contactoBSN.php");
include_once ("clases/class.contactoVW.php");

if(isset($_GET['c'])){
	$cliBSN=new ContactoBSN();
	$arrayDatos=$cliBSN->cargaColeccionFiltro($_GET['c'],$_GET['r']);
	$cliVW = new ContactoVW();
	$cliVW->despliegaTabla($arrayDatos);	
}

?>