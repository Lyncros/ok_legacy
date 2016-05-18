<?php
include_once("inc/encabezado.php");
include_once("clases/class.emprendimientoBSN.php");
include_once("clases/class.emprendimientoVW.php");

include_once("./inc/encabezado_html.php");
//include_once("./inc/encabezado_html_gmap.php");


$ingreso=true;
$id="";
$origen="lista_emprendimiento.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiVW= new EmprendimientoVW($id);
} else {
	$notiVW= new EmprendimientoVW($id);
	if(isset($_POST['id_emp'])){
		$notiVW->leeDatosEmprendimientoVW();
		$id=$notiVW->getId();
		if ($_POST['id_emp']==0){
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
	if(isset($_GET['c'])){
		$cli=$_GET['c'];
	} else {
		$cli=0;
	}
	$notiVW->cargaDatosVW($cli);
}  else {
	$id=$notiVW->getId();

	$_SESSION['opcionMenu']=34;
	$origen='carga_datosemp.php?i=';
	header('location:'.$origen.$id."&o=1");

	
}

include_once("./inc/pie.php");
?>

