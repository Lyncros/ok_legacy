<?php
include_once("inc/encabezado.php");
include_once("clases/class.tiporelacionBSN.php");
include_once("clases/class.tiporelacionVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_tiporelacion.php?r=";

if (isset($_GET['r'])){
	$id= $_GET['r'];
	$trelVW= new TiporelacionVW($id);
} else {
	$trelVW= new TiporelacionVW($id);
	if(isset($_POST['id_tiporel'])){
		$trelVW->leeDatosVW();
		$id=$trelVW->getId();
		if ($_POST['id_tiporel']==0){
			$retorno=$trelVW->grabaDatosVW(false);			
		} else {
			$retorno=$trelVW->grabaModificacion();
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
	$trelVW->cargaDatosVW();
}  else {
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>

