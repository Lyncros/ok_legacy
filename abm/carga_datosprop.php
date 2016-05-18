<?php
include_once("inc/encabezado.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.datospropVW.php");
include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_propiedad.php?i=";
$viene="";
if(isset($_POST['pagorig']) && is_numeric($_POST['pagorig'])){
	$viene=$_POST['pagorig'];
}
if(isset($_GET['o']) && is_numeric($_GET['o'])){
	$viene=$_GET['o'];
}


if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiVW= new DatospropVW();
} else {
	$notiVW= new DatospropVW();
	if(isset($_POST['id_prop'])){
		$notiVW->leeDatosDatospropVW();
		$retorno=$notiVW->grabaDatosprop($_POST['id_prop']);			
		if(!$retorno){
			echo "Fallo el registro de los datos";
		} else {	
			$ingreso=false;
		}
	} 
}
if ($ingreso){
	$notiVW->cargaDatosDatosprop($id,$viene);
}  else {
	if($viene==1){
		$_SESSION['opcionMenu']=25;
		$origen='lista_fotos.php?i=';
		$id=$_POST['id_prop'];
	}
	header('location:'.$origen.$id);

}

include_once("./inc/pie.php");
?>

