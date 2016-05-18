<?php
include_once("inc/encabezado.php");
include_once("clases/class.tasacionBSN.php");
include_once("clases/class.tasacionVW.php");
include_once("generic_class/class.menu.php");
include_once("./inc/encabezado_html.php");

$ingreso=true;
$id="";

$origen="lista_tasaciones.php?i=";

if (isset($_GET['i']) && $_GET['i']!=0){
	$prop= $_GET['i'];
	if(isset($_GET['o']) && $_GET['o']==0){
		$tasacionVW= new TasacionVW();
		$tasacionVW->setIdPropiedad($prop);
	}
} else {
	$tasacionVW= new TasacionVW();
	if(isset($_POST['id_tasacion'])){
		$tasacionVW->leeDatosTasacionVW();
		$prop=$tasacionVW->getIdPropiedad();
		if ($_POST['id_tasacion']==0){
			$retorno=$tasacionVW->grabaTasacion();
		}
		if(!$retorno){
			echo "Fallo el registro de los datos";
		} else {
			$ingreso=false;
		}
		$menu = new Menu();
//		$ant=$menu->menuAnterior($_SESSION['opcionMenu']);
		$_SESSION['opcionMenu']=283;
		header('location:'.$origen.$prop);

	}
}
if ($ingreso){
//	print_r($tasacionVW);
	$_SESSION['id_prop']=$prop;
//	echo "<br>$prop<br>";
	$tasacionVW->cargaDatosTasacion();
}else{
	header('location:'.$origen.$prop);

}

include_once("./inc/pie.php");
?>

