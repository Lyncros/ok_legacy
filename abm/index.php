<?php
session_start();
ob_start();

include_once("inc/encabezado.php");

//include_once("clases/class.eventoVW.php");

//include_once("inc/funciones.inc");

setlocale(LC_ALL, 'es_ES'); 

//$_SESSION['Userrole']="admin";
//$_SESSION['opcionMenu']=0;

include_once("./inc/encabezado_html.php");
/*
 print "<form name='lista' method='POST' action='respondeMenu.php'>\n";	
$menu=new Menu();
$menu->dibujaMenu('lista','opcion');
print "<input type='hidden' name='opcion' id='opcion' value=''>";
print "</form>\n";
*/
include_once("./inc/pie.php");
?>
