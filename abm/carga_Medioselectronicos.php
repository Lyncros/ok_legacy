<?php
include_once("inc/encabezado.php");
include_once("clases/class.medioselectronicosVW.php");
include_once("generic_class/class.menu.php");

if (isset($_GET['t']) && isset($_GET['tc']) && isset($_GET['c'])){
	$id= $_GET['t'];
	$tipocont=$_GET['tc'];
	$cont=$_GET['c'];
        if(array_key_exists('div',$_GET)){
        	$div=$_GET['div'];
       }else{
           $div='';
       }
       
}else{
	$tipocont=$_POST['tipocont'];
	$cont=$_POST['id_cli'];
        if(array_key_exists('div',$_POST)){
        	$div=$_POST['div'];
       }else{
           $div='';
       }
}

include_once 'inc/encabezado_pop.php';
?>
	<div id="container">

<?php 
$ingreso=true;
$id="";



if (isset($_GET['t']) && isset($_GET['tc']) && isset($_GET['c'])){
	$id= $_GET['t'];
	$tipocont=$_GET['tc'];
	$cont=$_GET['c'];
        if(array_key_exists('div',$_GET)){
        	$div=$_GET['div'];
       }else{
           $div='';
       }

	if(isset($_GET['b']) && $_GET['b']=='b' && $id!=0){
		$objBSN= new MedioselectronicosBSN($id);
		$objBSN->borraDB();
			echo "<script type=\"text/javascript\">KillMe(); </script>\n";

	}
	if($id==0){
		$obj = new Medioselectronicos();
		$obj->setId_medio($id);
		$obj->setTipocont($tipocont);
		$obj->setId_cli($cont);
		$objVW= new MedioselectronicosVW($obj);
	}else{
		$objVW= new MedioselectronicosVW($id);
	}

} else {
	$objVW= new MedioselectronicosVW();
	if(isset($_POST['id_medio'])){
		$objVW->leeDatosVW();
		if($_POST['id_medio']==0){
			$retorno=$objVW->grabaDatosVW(false);
		}else{
			$retorno=$objVW->grabaModificacion();
		}

		if(!$retorno){
			echo "Fallo el registro de los datos";
			echo "<script type=\"text/javascript\">KillMe(); </script>\n";
		} else {
			$tipocont=$_POST['tipocont'];
			$cont=$_POST['id_cli'];
			$obj = new Medioselectronicos();
			$obj->setTipocont($tipocont);
			$obj->setId_cli($cont);
			$objVW= new MedioselectronicosVW($obj);
			echo "<script type=\"text/javascript\">KillMe(); </script>\n";

		}


	}
}
if ($ingreso){
	$objVW->vistaTablaVW($tipocont,$cont,'o');
	print "<br>";
	$objVW->cargaDatosVW($div);
	if($id!=0){
			echo "<script type=\"text/javascript\">muestraCargaData(); </script>\n";
	}
}else{
	echo "<script type=\"text/javascript\">KillMe(); </script>\n";

}

include_once("./inc/pie.php");
?>
