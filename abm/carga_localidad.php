<?php
include_once("inc/encabezado.php");
include_once("clases/class.localidadBSN.php");
include_once("clases/class.localidadVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_localidad.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiVW= new LocalidadVW($id);
} else {
	$notiVW= new LocalidadVW($id);
	if(isset($_POST['id_loca'])){
		$notiVW->leeDatosLocalidadVW();
		$id=$notiVW->getId_loca();
		if ($_POST['id_loca']==0){
			$retorno=$notiVW->grabaLocalidad();			
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
	$notiVW->cargaDatosLocalidad();
}  else {
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>

