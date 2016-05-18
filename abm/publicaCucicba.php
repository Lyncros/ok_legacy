<?php

include_once("inc/encabezado.php");
require_once("clases/class.cucicbaBSN.php");

$id_prop = $_POST['id'];
$id_resp = $_POST['id_resp'];
$id_user = $_POST['operador'];
$accion = $_POST['accion'];
$clave = $_POST['clave'];


$zp = new CucicbaBSN();

include_once("./inc/encabezado_pop.php");

if ($accion == 1) {
    $item = $zp->publicaPropiedad($id_user, $clave,$id_prop,$id_resp);

} else {
    $zp->retiraPropiedad($id_user, $clave,$id_prop);
}
include_once("inc/pie.php");
?>
