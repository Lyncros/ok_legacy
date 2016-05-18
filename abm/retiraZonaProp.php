<?php
include_once("inc/encabezado.php");

include_once("clases/class.zonapropBSN.php");
$id_prop=$_GET['i'];
$id_user=$_GET['u'];

$zp= new ZonapropBSN($id_prop,0,$id_user);

$zp->retiroPropiedad();
include_once("inc/pie.php");

?>
