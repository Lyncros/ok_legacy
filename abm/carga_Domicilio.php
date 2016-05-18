<?php
include_once("inc/encabezado.php");
include_once("clases/class.domicilioVW.php");
include_once("generic_class/class.menu.php");
//include_once("./inc/encabezado_html.php");
if (isset($_GET['t']) && isset($_GET['tc']) && isset($_GET['c'])){
	$id= $_GET['t'];
	$tipocont=$_GET['tc'];
	$cont=$_GET['c'];
	$div=$_GET['div'];
}else{
	$tipocont=$_POST['tipocont'];
	$cont=$_POST['id_cont'];
	$div=$_POST['div'];
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
		$div=$_GET['div'];

		if(isset($_GET['b']) && $_GET['b']=='b' && $id!=0){
			$domBSN= new DomicilioBSN($id);
			$domBSN->borraDB();
			echo "<script type=\"text/javascript\">KillMe(); </script>\n";

		}
		if($id==0){
			$dom = new Domicilio();
			$dom->setId_dom($id);
			$dom->setTipocont($tipocont);
			$dom->setId_cont($cont);
			$domVW= new DomicilioVW($dom);
		}else{
			$domVW= new DomicilioVW($id);
		}

	} else {
		$domVW= new DomicilioVW();
		if(isset($_POST['id_dom'])){
			$domVW->leeDatosVW();

			if($_POST['id_dom']==0){
				$retorno=$domVW->grabaDatosVW(false);
			}else{
				$retorno=$domVW->grabaModificacion();
			}

			if(!$retorno){
				echo "Fallo el registro de los datos";
				echo "<script type=\"text/javascript\">KillMe(); </script>\n";
			} else {
				$tipocont=$_POST['tipocont'];
				$cont=$_POST['id_cont'];
				$dom = new Domicilio();
				$dom->setTipocont($tipocont);
				$dom->setId_cont($cont);
				$domVW= new DomicilioVW($dom);
				echo "<script type=\"text/javascript\">KillMe(); </script>\n";

			}


		}
	}
	if ($ingreso){
		$domVW->vistaTablaVW($tipocont,$cont,'o');
		print "<br>";
		$domVW->cargaDatosVW($div);
		if($id!=0){
			echo "<script type=\"text/javascript\">muestraCargaData(); </script>\n";
		}

	}else{
		echo "<script type=\"text/javascript\">KillMe(); </script>\n";

	}
include_once("./inc/pie.php");
	?>
