<?php
include_once("inc/encabezado.php");
include_once('clases/class.loginwebuserBSN.php');
include_once('clases/class.loginwebuserVW.php');
include_once('clases/class.perfileswebuserVW.php');

include_once("./inc/encabezado_html.php");

$logon=new PerfileswebuserVW();

$ingreso=true;
$id="";
$origen="lista_perfilWU.php?l=";

if (isset($_GET['i']) && isset($_GET['l'])){
	$id= $_GET['i'];
	$pe=$_GET['l'];
	$perf=new Perfileswebuser($pe,$id);
	$logon->cargaVW($perf);
} else {
	if(isset($_POST['id_user'])){
		$perf=new Perfileswebuser();
		$perfBSN=new PerfileswebuserBSN();
		if(isset($_POST['auxperfil']) && isset($_POST['auxuser']) && $_POST['auxperfil']<>'' &&  $_POST['auxuser']<>''){
			$pe=$_POST['auxperfil'];
			$perf->setPerfil($_POST['auxperfil']);
			$perf->setId_user($_POST['auxuser']);
			$perfBSN->seteaBSN($perf);
			$perfBSN->borraDB();
		}
		$pe=$_POST['perfil'];
		$perf->setPerfil($_POST['perfil']);
		$perf->setId_user($_POST['id_user']);
		$perfBSN->seteaBSN($perf);
		$retorno=$perfBSN->insertaDB();		
		if(!$retorno){
			echo "Fallo el registro de los datos";
		} else {	
			$ingreso=false;
		}
	} 
}

if ($ingreso){
	$logon->cargaDatosVW();
} else {
	$_SESSION['opcionMenu']=824;
	header('location:'.$origen.$pe);
}

include_once("./inc/pie.php");
?>

