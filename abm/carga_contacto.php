<?php
include_once("inc/encabezado.php");
include_once("generic_class/class.menu.php");

include_once("clases/class.contactoBSN.php");
include_once("clases/class.contactoVW.php");

include_once("./generic_class/class.cargaConfiguracion.php");
header('Content-Type: text/html; charset=utf-8');
$conf=CargaConfiguracion::getInstance();
$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey=$conf->leeParametro("gmkey");

if(isset($_GET['c'])){
	if($_GET['c']==0 || $_GET['c']==''){
		$timestamp=date('YmdHis');
	}else{
		$timestamp=$_GET['c'];
	}
}else{
	if(isset($_POST['id_cont'])){
		$timestamp=$_POST['id_cont'];
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>O'Keefe Propiedades en ABM</title>
<script language="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
<script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
<script language="JavaScript" type="text/javascript" src="inc/menuPullDown.js"></script>
<link rel="stylesheet" type="text/css" href="css/agenda.css" />
<link rel="stylesheet" type="text/css" href="css/menuPullDown.css" />
<link rel="stylesheet" type="text/css" href="jquery.ui-1.5.2/themes/ui.datepicker.css"  />
<link rel="stylesheet" type="text/css" href="css/thickbox.css" 	media="screen" />
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" media="screen" />
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/thickbox.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/ui/ui.datepicker.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.autocomplete.js"></script>
<!--        <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gmapkey?>" type="text/javascript"></script>  -->
        <script language="javascript" type="text/javascript">
            function RepaintPagina(){
            	comboRubro( 'div_rubro');
            	listaTelefonos('O',<?php echo $timestamp; ?>, 'div_tel');
            	listaDomicilios('O',<?php echo $timestamp; ?>, 'div_dom');
            }

        </script>
        
    </head>

    <body onload="load();" onunload="GUnload();" onfocus="RepaintPagina();">

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
$ingreso=true;
$id="";

$notiVW= new ContactoVW($id);

$origen="lista_contactos.php?c=";

if (isset($_GET['c'])){
	$id= $_GET['c'];
//	$notiVW->cargaContacto($id);
	$notiVW->cargaVW($id);
} else {
	$notiVW= new ContactoVW($id);
	if(isset($_POST['id_cont'])){
		$notiVW->leeDatosVW();
//		$id=$notiVW->getIdContacto();
		$id=$notiVW->getId();
		if ($_POST['operacion']=='n'){
			$retorno=$notiVW->grabaDatosVW();			
		} else {
			$retorno=$notiVW->grabaModificacion();
//			header('location:'.$origen.$id);
		}
		if(!$retorno){
			echo "Fallo el registro de los datos";
		} else {	
			$ingreso=false;
		}
	} 
}
if ($ingreso){
	$notiVW->cargaDatosVW($timestamp);
	$_SESSION['opcionMenu']=5;	
}  else {
	$_SESSION['opcionMenu']=5;	
	header('location:'.$origen.$id);
}

?>
 </div>
<!-- end #container -->
</div>
</body>
</html>