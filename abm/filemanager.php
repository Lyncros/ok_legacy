<?php
include_once("inc/encabezado.php");

include_once("generic_class/class.fileManager.php");
include_once("generic_class/class.cargaConfiguracion.php");

if(!isset($_POST['path']) && !isset($_POST['accion']) && !isset($_FILES['archivo'])  && !isset($_POST['newpath']) && !isset($_POST['vista'])){
	$conf = CargaConfiguracion::getInstance();
	$path=$_SERVER['DOCUMENT_ROOT']."/".$conf->leeParametro("basepath");
	$fm = new FileManager($path);
	$vista=0;
}else{
	$fm = new FileManager($_POST['curpath']);
	$fm->actuar($_POST['accion'],$_POST['path'],$_POST['newpath'],$_FILES['archivo']);
	$vista=$_POST['vista'];
}
$fm->showFileManager($vista);

?>