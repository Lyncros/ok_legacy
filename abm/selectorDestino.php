<?php
include_once("inc/encabezado.php");

include_once("generic_class/class.fileManager.php");
include_once("generic_class/class.cargaConfiguracion.php");

if(!isset($_POST['path']) && !isset($_POST['accion']) && !isset($_POST['archivo'])  && !isset($_POST['newpath']) && isset($_GET['arc'])  && isset($_GET['path'])){
	$conf = CargaConfiguracion::getInstance('');
	$path=$_SERVER['DOCUMENT_ROOT']."/".$conf->leeParametro("basepath");
	$fm = new FileManager($path);
	if(isset($_GET['arc']) && file_exists($_GET['path'].'/'.$_GET['arc'])){
		$fm->showFileManager(1,$_GET['arc'],$_GET['path']);
	}
}else{
	$fm = new FileManager($_POST['curpath']);
	$fm->actuar($_POST['accion'],$_POST['path'],$_POST['newpath'],$_POST['archivo']);
	$fm->showFileManager(1,$_POST['newpath'],$_POST['pathorig']);
}

?>