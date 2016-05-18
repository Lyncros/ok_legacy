<?php
include_once("clases/class.clienteCache.php");

if(isset($_GET['term']) && $_GET['term']!=''){
    $cliCh=  ClienteCache::getInstance();
    
    $output_items=$cliCh->getClientesByFiltro($_GET['term'],'j');
    
    echo json_encode($output_items);
}

?>
