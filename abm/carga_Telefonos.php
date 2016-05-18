<?php
include_once("inc/encabezado.php");
include_once("clases/class.telefonosVW.php");
include_once("generic_class/class.menu.php");
//include_once("./inc/encabezado_html.php");
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
	$cont=$_POST['id_cont'];
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
		$telBSN= new TelefonosBSN($id);
		$telBSN->borraDB();
			echo "<script type=\"text/javascript\">KillMe(); </script>\n";

	}
	if($id==0){
		$tel = new Telefonos();
		$tel->setId_telefono($id);
		$tel->setTipocont($tipocont);
		$tel->setId_cont($cont);
		$telVW= new TelefonosVW($tel);
	}else{
		$telVW= new TelefonosVW($id);
	}

} else {
	$telVW= new TelefonosVW();
	if(isset($_POST['id_telefono'])){
		$telVW->leeDatosVW();

		if($_POST['id_telefono']==0){
			$retorno=$telVW->grabaDatosVW(false);
		}else{
			$retorno=$telVW->grabaModificacion();
		}

		if(!$retorno){
			echo "Fallo el registro de los datos";
			echo "<script type=\"text/javascript\">KillMe(); </script>\n";
		} else {
			$tipocont=$_POST['tipocont'];
			$cont=$_POST['id_cont'];
			$tel = new Telefonos();
			$tel->setTipocont($tipocont);
			$tel->setId_cont($cont);
			$telVW= new TelefonosVW($tel);
			echo "<script type=\"text/javascript\">KillMe(); </script>\n";

		}


	}
}
if ($ingreso){
	$telVW->vistaTablaVW($tipocont,$cont,'o');
	print "<br>";
	$telVW->cargaDatosVW($div);
	if($id!=0){
			echo "<script type=\"text/javascript\">muestraCargaData(); </script>\n";
	}
}else{
	echo "<script type=\"text/javascript\">KillMe(); </script>\n";

}

include_once("./inc/pie.php");
?>
