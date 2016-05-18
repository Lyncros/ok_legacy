<?php

include_once("clases/class.promocionBSN.php");
include_once("clases/class.mediosweb.php");
include_once("clases/class.loginwebuserBSN.php");
include_once("clases/class.clienteBSN.php");


$tipo = $_GET['t'];
$valor = $_GET['v'];

$titulo = '';
$clase = '';
$metodo = '';

switch ($tipo) {
    case 1:
        $titulo = 'Indique la Promocion';
        $clase = 'PromocionBSN';
        $metodo = 'comboColeccion';
        break;
    case 5:
        $titulo = 'Indique el medio Web';
        $clase = 'MedioswebBSN';
        $metodo = 'comboParametros';
        break;
    case 2:
        $titulo = 'Indique el Empleado';
        $clase = 'LoginwebuserBSN';
        $metodo = 'comboUsuarios';
        break;
    case 3:
        echo "";
        break;
    default:
        $titulo = '';
        print "<input type='hidden' name='id_promo' id='id_promo' value='0'>\n";
}

if ($titulo != '' && $tipo != 3) {
    print "<br>$titulo<br>";
    $objBSN = new $clase();
    $objBSN->{$metodo}($valor, 'id_promo');
    print "\n";
}
?>