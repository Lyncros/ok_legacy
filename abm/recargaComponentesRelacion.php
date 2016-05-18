<?php
include_once ("clases/class.relacion.php");
// t='+tipo+'&pc='+id_pc+'&sc='+id_sc+'&r='+id_rel
if(isset($_GET['t']) && isset($_GET['pc']) && isset($_GET['sc']) && isset($_GET['r'])){
	
	$tipo=$_GET['t'];
	$pc=$_GET['pc'];
	$sc=$_GET['sc'];
	$rel=$_GET['r'];
	
	$relVW = new RelacionVW();
	$relVW->cargaCamposRelacion($tipo,$pc,$sc,$rel);
	
}


?>