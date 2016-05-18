<?php
include_once ("clases/class.relacion.php");
if(isset($_GET['t']) && isset($_GET['c'])){
	
	$tipo=$_GET['t'];
	$cli=$_GET['c'];
        $rel=0;
        switch($tipo){
            case 'UC':
                $pc=0;
                $sc=$cli;
                break;
            case 'CC':
            case 'CP':
                $pc=$cli;
                $sc=0;
                break;
        }
	$relVW = new RelacionVW();
	$relVW->cargaCamposRelacion($tipo,$pc,$sc,$rel);
	
}


?>