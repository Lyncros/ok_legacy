<?php
include_once('clases/class.loginwebuserBSN.php');
include_once('clases/class.perfileswebuserBSN.php');
$usuario=$_POST['usuario'];
$clave=$_POST['password'];
$login=new LoginwebuserBSN();
$retorno=$login->controlLogin($usuario,$clave);
if($retorno > 0){
	$perBSN = new PerfileswebuserBSN();
	$perfil=$perBSN->coleccionPerfilesUsuario($retorno);
	$_SESSION['Userrole']=$perfil;
} else {
	$_SESSION['Userrole']='';
}

?>