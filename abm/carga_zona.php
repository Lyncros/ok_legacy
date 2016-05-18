<?php
include_once("inc/encabezado.php");
include_once("clases/class.zonaBSN.php");
include_once("clases/class.zonaVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_zona.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiVW= new ZonaVW($id);
} else {
	$notiVW= new ZonaVW($id);
	if(isset($_POST['id_zona'])){
		$notiVW->leeDatosZonaVW();
		$id=$notiVW->getId_zona();
		if ($_POST['id_zona']==0){
			$retorno=$notiVW->grabaZona();			
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
	$notiVW->cargaDatosZona();
	
}  else {
	$_SESSION['opcionMenu']=13;
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>

