<?php
include_once("inc/encabezado.php");
include_once("clases/class.tipo_propBSN.php");
include_once("clases/class.tipo_propVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_tipo_prop.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiVW= new Tipo_propVW($id);
} else {
	$notiVW= new Tipo_propVW($id);
	if(isset($_POST['id_tipo_prop'])){
		$notiVW->leeDatosVW();
		$id=$notiVW->getId();
		if ($_POST['id_tipo_prop']==0){
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

