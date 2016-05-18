<?php
include_once("inc/encabezado.php");
include_once("clases/class.impuestoBSN.php");
include_once("clases/class.impuestoVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_impuesto.php?imp=";

if (isset($_GET['imp'])){
	$id= $_GET['imp'];
	$notiVW= new ImpuestoVW($id);
} else {
	$notiVW= new ImpuestoVW($id);
	if(isset($_POST['id_impuesto'])){
		$notiVW->leeDatosVW();
		$id=$notiVW->getId();
		if ($_POST['id_impuesto']==0){
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
	$notiVW->cargaDatosVW('n');
	$_SESSION['opcionMenu']=1000;	
}  else {
	$_SESSION['opcionMenu']=1000;	
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>
