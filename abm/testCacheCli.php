<?php
include_once( "clases/class.clienteCache.php");

$cliC=  ClienteCache::getInstance();
//echo "Entra en cache<br>";
//print_r($cliC->getClientes());
print_r($cliC->getClientesByFiltro('abalos','t'));
//print_r($cliC->getClientesByFiltroApellido('AB', 'a'));
?>
