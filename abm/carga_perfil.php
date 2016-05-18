<?php

include_once("inc/encabezado.php");
include_once("clases/class.perfilesBSN.php");
include_once("clases/class.perfilesVW.php");

include_once("./inc/encabezado_html.php");

$ingreso=true;
$id="";
$origen="lista_perfil.php?i=";

if (isset($_GET['l'])){
	$id= $_GET['l'];
	$notiVW= new PerfilesVW($id);
} else {
	$notiVW= new PerfilesVW($id);
	if(isset($_POST['perfil'])){
		$notiVW->leeDatosVW();
		$id=$notiVW->getId();
		if ($_POST['auxperf']==''){
			$retorno=$notiVW->grabaDatosVW(false);
		} else {
			$retorno=$notiVW->grabaModificacion();
			header('location:'.$origen.$id);
		}
		if(!$retorno){
			echo "Fallo el registro de los datos";
		} else {	
			$ingreso=false;
		}
	} 
}
if ($ingreso){
	$notiVW->cargaDatosVW();
}  else {
	$_SESSION['opcionMenu']=82;
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>

