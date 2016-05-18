<?php
include_once ("./generic_class/class.menu.php");
include_once("./generic_class/class.cargaConfiguracion.php");
header('Content-Type: text/html; charset=utf-8');
$conf=CargaConfiguracion::getInstance();
$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey=$conf->leeParametro("gmkey");

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
<link rel="stylesheet" type="text/css" href="css/vistaTablas.css" />
<link rel="stylesheet" type="text/css" href="css/menuPullDown.css" />
<link rel="stylesheet" type="text/css" href="jquery.ui-1.5.2/themes/ui.datepicker.css"  />
<link rel="stylesheet" type="text/css" href="css/thickbox.css" 	media="screen" />
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" media="screen" />
<link rel="stylesheet" type="text/css"  href="jquery1.8/themes/base/jquery.ui.all.css" />

<script type="text/javascript" src="jquery.ui-1.5.2/jquery.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/thickbox.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/ui/ui.datepicker.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.autocomplete.js"></script>
<script type="text/javascript" src="jquery1.8/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="jquery1.8/ui/jquery.ui.tabs.js"></script>

	<script>
	$(function() {
		$( "#tabs" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible. " +
						"If this wouldn't be a demo." );
				}
			}
		});
	});
	</script>

<!--       <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gmapkey?>" type="text/javascript"></script>  -->
</head>

<body onload="load();" onunload="GUnload();">

	<div id="container">
		<div id="divCuerpo">
