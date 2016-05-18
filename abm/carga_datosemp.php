<?php
include_once("inc/encabezado.php");
include_once("clases/class.datosempBSN.php");
include_once("clases/class.datosempVW.php");
include_once("./inc/encabezado_html.php");
include_once("generic_class/class.menu.php");

$ingreso=true;
$id="";

$origen="lista_emprendimiento.php?i=";

$viene="";
if(isset($_POST['pagorig']) && is_numeric($_POST['pagorig'])){
	$viene=$_POST['pagorig'];
}
if(isset($_GET['o']) && is_numeric($_GET['o'])){
	$viene=$_GET['o'];
}

if (isset($_GET['i']) && $_GET['i']!=0){
	$prop= $_GET['i'];
	$datosemp=new Datosemp();
	$datosemp->setId_emp($prop);
	$datosempVW= new DatosempVW($datosemp);
} else {
	$datosempVW= new DatosempVW();
	if(isset($_POST['id_emp'])){
		$datosempVW->leeDatosDatosempVW();
		$retorno=$datosempVW->grabaDatosemp($_POST['id_emp']);
		if(!$retorno){
			echo "Fallo el registro de los datos";
		} else {
			$ingreso=false;
		}
	}
}
if ($ingreso){
	$_SESSION['id_emp']=$prop;
	$datosempVW->cargaDatosVW($prop,$viene);
}else{
	if($viene==1){
		$_SESSION['opcionMenu']=34;
		$origen='lista_datosemp.php?i=';
		$prop=$_POST['id_emp'];
	}
	header('location:'.$origen.$prop);

}

include_once("./inc/pie.php");
?>

