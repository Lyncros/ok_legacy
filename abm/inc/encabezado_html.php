<?php
include_once ("./generic_class/class.menu.php");
include_once("./generic_class/class.cargaConfiguracion.php");
header('Content-Type: text/html; charset=utf-8');
/*
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE');
header("Access-Control-Allow-Headers: X-Requested-With");
*/
$conf=CargaConfiguracion::getInstance();
$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey=$conf->leeParametro("gmkey");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>O'Keefe Propiedades en ABM</title>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <!-- Font Awesome Icons -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
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




<!--
<script type="text/javascript" src="jquery1.9/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/thickbox.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/ui/ui.datepicker.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.autocomplete.js"></script>
<script type="text/javascript" src="jquery1.8/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="jquery1.8/ui/jquery.ui.tabs.js"></script>-->

<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	
<script type="text/javascript">
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
<script type="text/javascript">
document.oncontextmenu = function(){return false;}
</script>
<!--       <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gmapkey?>" type="text/javascript"></script>  -->

<script type="text/javascript" src="js/apiokeefe.js"></script>
</head>

<body onload="load()" onunload="GUnload();">

	<div id="container">
		<div id="popups_portales">
			<!-- EAJ-->
			<div id='dialog-form-zp' title='Publicar en ZonaProp' style='display:none;'>
			    <div id='content-publicacion-zp'>
			      <div style='clear:both;'></div>
			      <div id='div_tipodestacado_zp' style='width: 300px; padding: 10px;'>
			      		Seleccione el Tipo de Destaque en ZonaProp
			          <select name='zp_destacado' id='zp_destacado' class='campos_btn' style="height:32px;">
			              <option value='0' SELECTED >Seleccione una opción</option>
			              <option value='9'>SIN DESTAQUE</option>
			              <option value='1'>DESTAQUE NORMAL</option>
			              <option value='3'>DESTAQUE PREMIUM</option>
			          </select>
			      </div>
			      <div style='clear:both;'></div>
			   		<div id='div_responsable' style='width: 350px; padding: 10px;'>
			   			Responsable de la Publicación
			   		    <select name='zp_responsable' id='zp_responsable' class='campos_btn' style="height:32px;">
			   		    	<option value="0" selected="">Seleccione una opción</option>
			   		    	<option value="20160328200650">Oficina Hudson </option>
			   		    	<option value="20160427132842">Oficina Quilmes </option>
			   		    </select>
			   		</div>  			      
			    </div>
			    <label id='mensaje-zp' style="color:blueviolet;font-weight: 600;"></label>  
			</div>			
			<div id='dialog-form-ap' title='Publicar en ArgenProp' style='display:none;'>
			    <div id='content-publicacion-ap'>			      		
					<div style='clear:both;'></div>
					<div id='div_tipodestacado_ap' style='width: 300px; padding: 10px;'>
							Seleccione el Tipo de Destaque en Argenprop
					    <select name='ap_destacado' id='ap_destacado' class='campos_btn' style="height:32px;">
					        <option value='0' SELECTED >Seleccione una opción</option>
					        <option value='3'>SIMPLE</option>
					        <option value='4'>DESTACADO</option>
					        <option value='5'>PREMIUM</option>
					    </select>
					</div>			      
					<div style='clear:both;'></div>
		   			<div id='div_responsable_ap' style='width: 350px; padding: 10px;'>
			   			Responsable de la Publicación
			   		    <select name='ap_responsable' id='ap_responsable' class='campos_btn' style="height:32px;">
			   		    	<option value="0" selected="">Seleccione una opción</option>
			   		    	<option value="20160328200650">Oficina Hudson </option>
			   		    	<option value="20160427132842">Oficina Quilmes </option>
			   		    </select>
			   		</div>			      
			    </div>
			    <label id='mensaje-ap' style="color:blueviolet;font-weight: 600;"></label> 
			</div>	
			<div id='dialog-form-sp' title='Publicar en SumaProp' style='display:none;'>
			    <label id='mensaje-sp' style="color:blueviolet;font-weight: 600;"></label>  
			    <div id='content-publicacion-sp'>
			      <div style='clear:both;'></div>
			      <div id='div_tipodestacado_sp' style='width: 300px; padding: 10px;'>
			      		Al publicar incluirá la propiedad  en el feed <br/>
			      		de propiedades disponibles para SumaProp. La publicación no es 
			      		directa.<br/>
			      		<!--  y la lectura del mismo corresponde a SumaProp -->
			      </div>
					<div style='clear:both;'></div>
		   			<div id='div_responsable_sp' style='width: 350px; padding: 10px;'>
			   			Responsable de la Publicación
			   		    <select name='sp_responsable' id='sp_responsable' class='campos_btn' style="height:32px;">
			   		    </select>
			   		</div>	
			    </div>
			    <label id='mensaje-sp' style="color:blueviolet;font-weight: 600;"></label> 
			</div>								
		</div>
		<div style='width:<?php echo $anchoPagina; ?>px; height:70px;'>
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
