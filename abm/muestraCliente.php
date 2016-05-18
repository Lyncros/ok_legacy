<?php
include_once ("clases/class.clienteBSN.php");
include_once ("clases/class.clienteVW.php");

if(isset($_GET['c'])){
	$cliVW = new ClienteVW($_GET['c']);
	$cliVW->vistaDatos();	
}

?>