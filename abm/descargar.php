<?php
include_once("inc/encabezado.php");

$archivo=$_GET['arc'];
$path=$_GET['path'];
header("Content-type:aplicationoctet-stream");
header("Content-Disposition: attachment; filename=".$archivo."\n\n");
readfile($path."/".$archivo);

?>