<?php
include_once("inc/encabezado.php");
include_once("clases/class.caracteristicaBSN.php");
include_once("clases/class.caracteristicaVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_caracteristica.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiVW= new CaracteristicaVW($id);
} else {
	$notiVW= new CaracteristicaVW($id);
	if(isset($_POST['id_carac'])){
		$notiVW->leeDatosVW();
		$id=$notiVW->getId();
		if ($_POST['id_carac']==0){
			$retorno=$notiVW->grabaDatosVW();			
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
	$_SESSION['opcionMenu']=12;	
}  else {
	$_SESSION['opcionMenu']=12;	
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>

