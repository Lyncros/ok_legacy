<?php

include_once("clases/class.propiedadBSN.php");
include_once("clases/class.loginwebuserBSN.php");
include_once("clases/class.clienteBSN.php");


$tipo = $_GET['t'];
$valor = $_GET['v'];

$titulo = '';
$clase = '';
$metodo = '';

switch ($tipo) {
    case 'P':
        $titulo = 'Indique la Propiedad';
        $clase = 'PropiedadBSN';
        $metodo = 'comboColeccion';
        break;
    case 'U':
        $titulo = 'Indique el Empleado';
        $clase = 'LoginwebuserBSN';
        $metodo = 'comboUsuarios';
        break;
    case 'C':
        echo "";
        break;
    default:
        $titulo = '';
        print "<input type='hidden' name='id' id='id' value='0'>\n";
}

if ($titulo != '' && $tipo != 'C') {
    print "<br>$titulo<br>";
    $objBSN = new $clase();
    $objBSN->{$metodo}($valor, 'id');
    print "\n";
}
?>