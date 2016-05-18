<?php
include_once("inc/encabezado.php");
include_once("clases/class.fotoempBSN.php");
include_once("clases/class.fotoempVW.php");
include_once("generic_class/class.menu.php");
include_once("./inc/encabezado_html.php");

$ingreso=true;
$id="";

$origen="lista_fotosemp.php?o=";

if (isset($_GET['o']) && $_GET['o']!=0){
	$prop= $_GET['o'];
	if(isset($_GET['f']) && $_GET['f']==0){
		$fotoVW= new FotoempVW();
		$fotoVW->setId_emp_carac($prop);
	}
} else {
	$fotoVW= new FotoempVW();
	if(isset($_POST['id_foto'])){
		$prop=$_SESSION['id_emp_carac'];
		if($fotoVW->leeDatosFotoempVW()){
			$prop=$_POST['id_emp_carac'];
			if ($_POST['id_foto']==0){
				$retorno=$fotoVW->grabaFotoemp();
			}
			if(!$retorno){
				echo "Fallo el registro de los datos";
			} else {
				$ingreso=false;
			}
		}else{
			echo "Debe ingresar archivos de tipo GIF, JPG o PNG";
		}
		$menu = new Menu();
//		$ant=$menu->menuAnterior($_SESSION['opcionMenu']);
		$_SESSION['opcionMenu']=344;
		header('location:'.$origen.$prop);

	}
}
if ($ingreso){
//	print_r($fotoVW);
	$_SESSION['id_emp_carac']=$prop;
//	echo "<br>$prop<br>";
	$fotoVW->cargaDatosFotoemp();
}else{
	header('location:'.$origen.$prop);

}

include_once("./inc/pie.php");
?>

