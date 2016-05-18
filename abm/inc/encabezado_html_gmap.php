<?php
include_once("./generic_class/class.cargaConfiguracion.php");

$conf=CargaConfiguracion::getInstance();
$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey=$conf->leeParametro("gmkey");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>O'Keefe Propiedades</title>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
<script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
<link href="css/agenda.css" rel="stylesheet" type="text/css" />
<link href="jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<script src="jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
<script src="jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>  
<script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gmapkey?>" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" src="inc/funciones_gmap.js"></script>
<script type="text/javascript">    
	var map = null;
    var geocoder = null;
</script>
</head>

<body onload="initialize('map_canvas')" onunload="GUnload()">

<div id="container">
  <table width="<?php echo $anchoPagina; ?>" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" height="65"><img src="images/okeefe.png" alt="O'Keefe Propiedades" width="170" height="56" align="middle" /></td>
  </tr>
	</table>
<table width="780" bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
