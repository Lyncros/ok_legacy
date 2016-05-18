<?php
include_once ("clases/class.rubroBSN.php");
if(isset($_GET['u']) && $_GET['u']!=''){
	$rubro=$_GET['u'];
}else{
	$rubro=0;
}

$rubBSN= new RubroBSN();
$rubBSN->comboRubro($rubro);

?>