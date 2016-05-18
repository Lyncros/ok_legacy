<?php
include_once("inc/encabezado.php");
include_once("clases/class.loginwebuserBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['i'])){
	if($_GET['i'] !=''){
		$id= $_GET['i'];
		$propBSN= new LoginwebuserBSN($id);
		$propBSN->activarUsuario();
	}
}
	$id=0;
	$origen="lista_usuarios.php?i=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>