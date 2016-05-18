<?php
include_once("inc/encabezado.php");
include_once('clases/class.loginwebuserBSN.php');
include_once('clases/class.loginwebuserVW.php');

include_once("./generic_class/class.cargaConfiguracion.php");
header('Content-Type: text/html; charset=utf-8');
$conf=CargaConfiguracion::getInstance();
$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey=$conf->leeParametro("gmkey");

$timezone = $conf->leeParametro('timezone');
date_default_timezone_set($timezone);

if(isset($_GET['i'])){
	if($_GET['i']==0 || $_GET['i']==''){
		$timestamp=date('YmdHis');
	}else{
		$timestamp=$_GET['i'];
	}
}else{
	if(isset($_POST['id_user'])){
		$timestamp=$_POST['id_user'];
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>O'Keefe Propiedades en ABM</title>
        <script LANGUAGE="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
        <script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
        <link href="css/agenda.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="jquery.ui-1.5.2/jquery.js"></script>
        <script type="text/javascript" src="jquery.ui-1.5.2/thickbox.js"></script>
        <link href="jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
        <script src="jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
        <script type="text/javascript" src="jquery.ui-1.5.2/jquery.bgiframe.min.js"></script>
        <script type="text/javascript" src="jquery.ui-1.5.2/jquery.autocomplete.js"></script>
        <link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/jquery.autocomplete.css" type="text/css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/menuPullDown.css" />
        <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gmapkey?>" type="text/javascript"></script>
    </head>

    <body onload="load();" onunload="GUnload();" onfocus="listaTelefonos('U',<?php echo $timestamp; ?>, 'div_tel');">

        <div id="container">
		<div style='width:1000px; height:70px;'>
		<div  id='Encabezado' style='border: 0px; position: relative; left: 0px; right: 0px;'><img src="images/okeefe.png" alt="O'Keefe Propiedades" width="170" height="56" align="middle" /></div>
		<div id="menuEncabezado"><a href="javascript:ventana('rssNovedades.php', 'Novedades', 400, 400);">Novedades</a></div>
		<div style="clear: both;"></div>
		</div>
		<div id="divMenu" width="<?php echo $anchoPagina; ?>px">
		<?php 
	  		$menu=new Menu();
	  		$menu->dibujaMenu();
	  	?>
	  	</div>
        <div id="divCuerpo">

<?php

//require_once('generic_class/LiteVerifyCode.class.php');

$logon=new LoginwebuserVW();

$ingreso=true;
$id="";
$origen="lista_usuarios.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$logon->cargaVW($id);
} else {
	if(isset($_POST['id_user'])){
		$logon->leeDatosLoginView();

//		LiteVerifyCode::checkSessionState();

//		if(isset($_POST['code']) && (strtoupper($_POST['code']) === $_SESSION['VERIFY_CODE'])){
//		if(isset($_POST['code'])){
			if ($_POST['operacion']=='n'){
				$retorno=$logon->grabaLogon();			
			} else {
				$retorno=$logon->grabaModificacion();
			}
			if(!$retorno){
				echo "Fallo el registro de los datos";
			} else {	
				$ingreso=false;
			}
//		}
//		}else {
//			echo "Fallo la carga del codigo de seguridad. Por favor reintente.";
//		}
	} 
}

//$logon=new LoginwebuserVW($id);
$_SESSION['opcionMenu']=81;

if ($ingreso){
	
//	LiteVerifyCode::Code();
//	$codseg=$ROOT_URL."generic_class/LiteVerifyCode.class.php?code.gif";

//	$logon->cargaDatosVW($codseg);
	$logon->cargaDatosVW($timestamp);
} else {
	$_SESSION['opcionMenu']=81;
	header('location:'.$origen.$id);
}

//include_once("./inc/pie.php");
?>
</div>
<!-- end #container --></div>
</body>
</html>
