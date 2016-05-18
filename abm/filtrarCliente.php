<?php

session_start();
//include_once ("clases/class.clienteBSN.php");
include_once("clases/class.clienteCache.php");
include_once ("clases/class.clienteVW.php");

if (isset($_GET['c']) || isset($_GET['id'])) {
    if (isset($_GET['pos'])) {
        $pos = $_GET['pos'];
    } else {
        $pos = 1;
    }
    $cliC = ClienteCache::getInstance();
    if ($_GET['id'] != 0 && isset($_GET['id'])) {
            $arrayDatos = $cliC->getClientesById($_GET['id'], 'a');
    } else {
        if ($pos == 0) {
//	$cliBSN=new ClienteBSN();
//	$arrayDatos=$cliBSN->coleccionClientesFiltrados($_GET['c'],$pos);
            $arrayDatos = $cliC->getClientesByFiltroApellido($_GET['c'], 'a');
        } else {
            $arrayDatos = $cliC->getClientesByFiltro($_GET['c'], 'a');
        }
    }
    $cliVW = new ClienteVW();
    $cliVW->despliegaTablaCache($arrayDatos);
//	$cliVW->despliegaTabla($arrayDatos);	
}
?>