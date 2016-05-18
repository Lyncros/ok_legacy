<?php
include_once("inc/encabezado.php");
include_once("clases/class.rubroBSN.php");
include_once("clases/class.rubroVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="lista_rubros.php?u=";

if (isset($_GET['u'])){
	$id= $_GET['u'];
	$notiVW= new RubroVW($id);
} else {
	$notiVW= new RubroVW($id);
	if(isset($_POST['id_rubro'])){
		$notiVW->leeDatosVW();
		$id=$notiVW->getId();
		if ($_POST['id_rubro']==0){
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
	$_SESSION['opcionMenu']=18;	
}  else {
	$_SESSION['opcionMenu']=18;	
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>
