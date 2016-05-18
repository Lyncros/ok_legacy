<?php
include_once ("clases/class.relacion.php");

if(isset($_GET['c'])){
	$relVW = new RelacionVW();
	$relVW->vistaRelacionesCliente($_GET['c']);	
}

?>