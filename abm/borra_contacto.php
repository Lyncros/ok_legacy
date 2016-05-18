<?php
include_once("inc/encabezado.php");
include_once("clases/class.contactoBSN.php");
include_once("./inc/encabezado_html.php");
if(isset($_GET['c']) && is_numeric($_GET['c'])){
	$contacto=$_GET['c'];
	$contBSN= new ContactoBSN($contacto);
	$contBSN->borraDB();
	$id=0;
	$origen="lista_contactos.php?c=";
	header('location:'.$origen.$tema);
}else {
	die("Fallo la aplicacion, llame a Soporte");
}
include_once("./inc/pie.php");
?>

