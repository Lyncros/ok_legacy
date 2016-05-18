<?php
include_once("inc/encabezado.php");
include_once("clases/class.zonapropBSN.php");

$id_prop=$_POST['id'];
$id_resp=$_POST['id_resp'];
$id_user=$_POST['operador'];
$accion=$_POST['accion'];
$clave=$_POST['clave'];

$zp= new ZonapropBSN($id_prop,$id_resp);

include_once("./inc/encabezado_pop.php");

if($accion==1){
	$zp->publicoPropiedad($id_user,$clave);
}else{
	if($accion==0){
		$zp->retiroPropiedad($id_user,$clave);
	}else{
		$zp->consultoUbicaciones($id_user,$clave);
	}
}
include_once("inc/pie.php");
?>
