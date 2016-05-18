<?php
include_once("inc/encabezado.php");
include_once("clases/class.caracteristicaVW.php");

include_once("inc/encabezado_html.php");

if(isset($_POST['id_tipo_carac'])){
    if($_POST['id_tipo_carac']!=0){
	$id_tipo_carac = $_POST['id_tipo_carac'];
        $filtroCar=array('id_tipo_carac'=>$id_tipo_carac);
        $_SESSION['filtroCar']=$filtroCar;
    }else{
        unset($_SESSION['filtroCar']);
    }
}

if (isset($_GET['i'])){
	$id= $_GET['i'];

	$postreVW= new CaracteristicaVW();
	$postreVW->vistaTablaVW();
}

include_once("inc/pie.php");
?>

