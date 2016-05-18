<?php
include_once 'inc/encabezado.php';
include_once 'generic_class/rssGen.php';
include_once 'clases/class.operacionBSN.php';
include_once 'clases/class.loginwebuserBSN.php';
include_once 'inc/encabezado_pop.php';

function get_date_spanish($time, $part = false, $formatDate = '') {
	#Declare n compatible arrays
	$month = array ("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiempre", "diciembre" ); #n
	$month_execute = "n"; #format for array month
	

	$month_mini = array ("", "ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "DIC" ); #n
	$month_mini_execute = "n"; #format for array month
	

	$day = array ("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado" ); #w
	$day_execute = "w";
	
	$day_mini = array ("DOM", "LUN", "MAR", "MIE", "JUE", "VIE", "SAB" ); #w
	$day_mini_execute = "w";
	
	/*
Other examples:
  Whether it's a leap year
  $leapyear = array("Este año febrero tendrá 28 días"."Si, estamos en un año bisiesto, un día más para trabajar!"); #l
  $leapyear_execute = "L";
*/
	
	#Content array exception print "HOY", position content the name array. Duplicate value and key for optimization in comparative
	$print_hoy = array ("month" => "month", "month_mini" => "month_mini" );
	
	if ($part === false) {
		return date ( "d", $time ) . " de " . $month [date ( "n", $time )] . ", " . date ( "H:i", $time ) . " hs";
	} elseif ($part === true) {
		if (! empty ( $print_hoy [$formatDate] ) && date ( "d-m-Y", $time ) == date ( "d-m-Y" ))
			return "HOY"; #Exception HOY
		if (! empty ( ${$formatDate} ) && ! empty ( ${$formatDate} [date ( ${$formatDate . '_execute'}, $time )] ))
			return ${$formatDate} [date ( ${$formatDate . '_execute'}, $time )];
		else
			return date ( $formatDate, $time );
	} else {
		return date ( "d-m-Y H:i", $time );
	}
}
/*
$rss_channel = new rssGenerator_channel();
$rss_channel->atomLinkHref = '';
$rss_channel->title = 'Novedades';
$rss_channel->link = 'http://localhost/okeefe/news.php';
$rss_channel->description = 'Novedades de operaciones.';
$rss_channel->language = 'es';
$rss_channel->generator = 'O\'keefe Propiedades';
$rss_channel->managingEditor = 'soporte@zgroupsa.com.ar';
$rss_channel->webMaster = 'soporte@zgroupsa.com.ar';

$operBSN = new OperacionBSN();
$arrayDatos = $operBSN-> cargaNovedadesOperacion();

//print_r($arrayDatos);
//die();
          [0] => Array
                (
                    [0] => 2656
                    [id_oper] => 2656
                    [1] => 2983
                    [id_prop] => 2983
                    [2] => Alquiler
                    [operacion] => Alquiler
                    [3] => 02-12-2012
                    [cfecha] => 02-12-2012
                    [4] => 0
                    [intervino] => 0
                    [5] => 
                    [comentario] => 
                )
                
foreach ($arrayDatos as $datos){
	$item = new rssGenerator_item();
	$item->title = 'ID '.$datos['id_prop'];
	$item->description = 'Ultimo estado: '.$datos['operacion'];
	$item->link = 'http://newsite.com';
	$item->guid = 'http://newsite.com';
	$item->pubDate = date ( "r" , strtotime($datos['cfecha']) );
	$rss_channel->items[] = $item;
}

$rss_feed = new rssGenerator_rss();
$rss_feed->encoding = 'UTF-8';
$rss_feed->version = '2.0';
header('Content-Type: text/xml');
echo $rss_feed->createFeed($rss_channel);
*/
$usrBSN = new LoginwebuserBSN ();

$operBSN = new OperacionBSN ();
$arrayDatos = $operBSN->cargaNovedadesOperacion ();
	print "<div style='padding:10px;'>\n";
	print "<div>\n";
	
foreach ( $arrayDatos as $datos ) {
	$usrBSN->cargaById ( $datos ['intervino'] );
	$usrNombre = $usrBSN->getObjeto ()->getNombre ();
	$usrApellido = $usrBSN->getObjeto ()->getApellido ();
	print "<div style=\"border-bottom: thin solid #F16221; padding:5px 0px;\">\n";
	print "<div style=\"float:left; width:53px;font-weight:bold;\">ID " . $datos ['id_prop'] . "</div>\n";
	print "<div style=\"float: left;\">Ultimo estado: <b>" . $datos ['operacion'] . "</b></div>\n";
	print "<div style=\"float: left;padding-left:5px;\">el " . get_date_spanish(strtotime($datos ['cfecha']), 'false','d-m-Y') . "</div>\n";
	print "<div style=\"clear:both;\"></div>\n";
	print "<div style=\"float: left;\">Intervino: " . $usrNombre . " " . $usrApellido . "</div>\n";
	print "<div style=\"clear:both;\"></div>\n";
	print "</div>\n";
}
include_once 'inc/pie.php';
?>