<?php
include_once("clases/class.caracteristicaBSN.php");
include_once("clases/class.caracteristica.php");
$tipo_carac=$_GET['p'];
$campoloc=$_GET['c'];

$objaux=new Caracteristica();
$objaux->setId_tipo_carac($tipo_carac);
$objBSN= new CaracteristicaBSN($objaux);
$orden=$objBSN->proximaPosicion();

print "<input class='campos' type='text' name='orden' id='orden' value='" . $orden . "' maxlength='2' size='10'>";


?>