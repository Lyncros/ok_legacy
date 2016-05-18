<?php
include_once("inc/encabezado.php");
include_once("clases/class.tipo_caracBSN.php");
include_once("clases/class.tipo_caracVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_tipo_carac.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiVW= new Tipo_caracVW($id);
} else {
	$notiVW= new Tipo_caracVW($id);
	if(isset($_POST['id_tipo_carac'])){
		$notiVW->leeDatosVW();
		$id=$notiVW->getId();
		if ($_POST['id_tipo_carac']==0){
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
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>

