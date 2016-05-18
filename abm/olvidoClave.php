<?php

include_once('clases/class.loginwebuserBSN.php');
$usuario=$_GET['usr'];
$login=new LoginwebuserBSN($usuario);

$clave=$login->solicitudcambioClave();

echo "CLAVE Automatica ".$clave."<br>";

?>