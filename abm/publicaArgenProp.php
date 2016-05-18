<?php
include_once("inc/encabezado.php");
include_once("clases/class.argenpropBSN.php");
/*
Array
(
    [operador] => 'felipe.atucha@okeefe.com.ar',
    [clave] => 'Felipe01',
    [accion] => 1,
    [id_resp] => 6,
    [id] => 6194
)
*/
$id_prop=$_POST['id'];
$id_resp=$_POST['id_resp'];
$id_user=$_POST['operador'];
$accion=$_POST['accion'];
$clave=$_POST['clave'];

$zp= new ArgenpropBSN($id_prop,$id_resp);

include_once("./inc/encabezado_pop.php");

if($accion==1){
	$resp=$zp->publicoPropiedad($id_user,$clave);
}else{
	$zp->retiroPropiedad($id_user,$clave);
}
//echo json_encode(array("resp"=>$resp));
include_once("inc/pie.php");
?>
