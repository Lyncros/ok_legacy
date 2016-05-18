<?php
header('Content-type: text/html; charset=utf-8');
require_once('lib-nusoap/nusoap.php');

//$wsdl="http://localhost/okeefe/webservice/servicioweb.php?wsdl";
$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice

//¿ocurrio error al llamar al web service?
if ($client->fault) { // si
    echo 'No se pudo completar la operación';
    die();
}else { // no
    $error = $client->getError();
    if ($error) { // Hubo algun error
        echo 'Error:' . $error;
    }
}

$zona = $client->call('ListarZonaPrincipal',array(),'');
//print_r($zona);
//die();

$tipoProp = $client->call('ListarTipoProp',array(),'');
//print_r($tipoProp);
//die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>O'Keefe Propiedades</title>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="js/funciones.js" language="javascript"></script>
<script language="javascript" src="js/ajax.js" type="text/javascript" /></script>
<script language="javascript" src="js/ajax-dynamic-content.js" type="text/javascript" /></script>
<script language="javascript" src="js/swfobject.js" type="text/javascript" /></script>
<script language="javascript" src="js/jquery-1.6.2.min.js" type="text/javascript" /></script>
<link href="css/okeefe.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="css/jquery.autocomplete.css" type="text/css" media="screen" />
<script type="text/javascript">
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;

function jsddm_open()
{	jsddm_canceltimer();
	jsddm_close();
	ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');}

function jsddm_close()
{	if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}

function jsddm_timer()
{	closetimer = window.setTimeout(jsddm_close, timeout);}

function jsddm_canceltimer()
{	if(closetimer)
	{	window.clearTimeout(closetimer);
		closetimer = null;}}

$(document).ready(function()
{	$('#jsddm > li').bind('mouseover', jsddm_open);
	$('#jsddm > li').bind('mouseout',  jsddm_timer);
	$("#codigo").autocomplete("auto_codigo.php");
});

document.onclick = jsddm_close;
</script>
</head>

<body onload="MM_preloadImages('images/home_s2.gif','images/rural_s2.gif','images/residencial_s2.gif','images/comercial_s2.gif','images/country_s2.gif','images/obras_s2.gif','images/qs_s2.gif','images/tasaciones_s2.gif','images/contacto_s2.gif','images/oportunidades_s2.gif','images/novedades_s2.gif','images/revista_s2.gif','images/exito_s2.gif','images/cv_s2.gif');">
<form action="busqueda.php" name="fmenu" id="fmenu" method="get">
  <input type="hidden" name="opcTipoProp" id="opcTipoProp" />
</form>
<div id="cabeza">
  <div id="logo"><img src="images/logoOke.gif" width="220" height="67" alt="Okeefe Propiedades" /></div>
  <div id="trabajando"></div>
</div>
<?php include_once("menu.php");?>
<div id="contenido">
  <div id="izq">
    <div id="qsTitulo"><img src="images/exitotitu.gif" width="140" height="24" /></div>
    <div id="casos">
      <div id="casosI">
      <p>Mitre 322 24 unidades</p>
<p>Las Golondrinas 296 lotes</p>
<p>Torres Conjunto Parque Bernal 60 unidades</p>
<p>Prados de la Vega 129 lotes</p>
<p>Dúplex Libertad 2 unidades</p>
<p>Hudson Park 407 lotes</p>
<p>Horizonte I 13 unidades</p>
<p>Solares del Parque 35 lotes</p>
<p>Mitre 320 18 unidades</p>
<p>Urquiza 228 4 unidades</p>
<p>Mar del Plata – Alberti esq. B. de Yrigoyen 37 unidades</p>
</div>
      <div id="casosC"><p>El Borgo (Área 60) 450 unidades</p>
<p>Altos del Parque I y II 40 unidades</p>
<p>Chacras de Hudson 341 unidades</p>
<p>Bauhaus I, II y III 30 unidades</p>
<p>Área 60 2900 lotes</p>
<p>Palcos de Solís 10 unidades</p>
<p>La Arbolada 63 lotes</p>
<p>Ayres de Moreno 9 unidades</p>
<p>New Field 26 lotes</p>
<p>Guido I 8 unidades</p>
<p>Estancia Las Malvinas 290 lotes</p>
</div>      
      <div id="casosD">
<p>Village del Parque 300 lotes</p>
<p>Condominio Islas Malvinas 10 unidades</p>
<p>Maipú 12 unidades</p>
<p>Alvear 333 14 unidades</p>
<p>Klover I, II, III y IV 104 unidades</p>
<p>Sarmiento 325 24 unidades</p>
<p>Don Bosco 18 unidades</p>
<p>Rondeau 64 30 unidades</p>
<p>Pisos de Brown 4 unidades</p>
<p>Garage Privado Quilmes 160 unidades</p>
<p>Matheu 10 unidades</p></div>
    </div>
    <div class="clearfix"></div>
<?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <?php include_once("buscadorVertical.php"); ?>
  </div>
  <?php include_once("pie.php"); ?>
</body>
</html>