<?php
include_once("inc/encabezado.php");
include_once("clases/class.propiedadBSN.php");
include_once("./inc/encabezado_html.php");

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiBSN= new PropiedadBSN($id);
	$notiBSN->publicarPropiedad();

}

	$origen="lista_propiedad.php?i=";
	header('location:'.$origen.$id);

	include_once("./inc/pie.php");

?>

