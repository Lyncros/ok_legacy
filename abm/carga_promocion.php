<?php
include_once("inc/encabezado.php");
include_once("clases/class.promocionBSN.php");
include_once("clases/class.promocionVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_promocion.php?promo=";

if (isset($_GET['promo'])){
	$id= $_GET['promo'];
	$objVW= new PromocionVW($id);
} else {
	$objVW= new PromocionVW($id);
	if(isset($_POST['id_promo'])){
		$objVW->leeDatosVW();
		$id=$objVW->getId();
		if ($_POST['id_promo']==0){
			$retorno=$objVW->grabaDatosVW(false);			
		} else {
			$retorno=$objVW->grabaModificacion();
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
	$objVW->cargaDatosVW();
	$_SESSION['opcionMenu']=12;	
}  else {
	$_SESSION['opcionMenu']=12;	
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>
