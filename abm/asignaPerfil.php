<?php
include_once("inc/encabezado.php");
include_once('clases/class.loginwebuserBSN.php');
include_once('clases/class.loginwebuserVW.php');
include_once('clases/class.perfileswebuserVW.php');

include_once("./inc/encabezado_html.php");

$logon=new LoginwebuserVW();

$ingreso=true;
$id="";
$origen="lista_usuarios.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$logon->cargaVW($id);
} else {
	if(isset($_POST['id_user'])){
		$perf=new Perfileswebuser($_POST['auxperfil'],$_POST['id_user']);
		$perfBSN=new PerfileswebuserBSN($perf);
		$perfBSN->borraDB();
		$perf->setPerfil($_POST['perfil']);
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
	$logon->cargaAsignacionUsuario();
} else {
	$_SESSION['opcionMenu']=81;
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>

