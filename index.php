<?php require_once('Connections/config.php'); ?>
<?php
session_start();
if(!isset($_SESSION['ingreso'])){
    $_SESSION['ingreso'] = $_SERVER['HTTP_REFERER'];
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
header('Content-type: text/html; charset=utf-8');
require_once('lib-nusoap/nusoap.php');

function busca_valor($id_prop, $id_carac, $arreglo) {
    for ($j = 0; $j < count($arreglo); $j++) {
        if ($arreglo[$j]['id_prop'] == $id_prop && $arreglo[$j]['id_carac'] == $id_carac) {
            if (isset ($arreglo[$j]['contenido']) && $arreglo[$j]['contenido'] != "") {
                $valor = $arreglo[$j]['contenido'];
            } else {
                $valor = "-";
            }
            return $valor;
            break;
        }
    }
}

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

$paramD = array('id_tipo_prop'=>0, 'operacion'=>'');
$destacados = $client->call('listarDestacados',$paramD,'');
//print_r($destacados);
//die();

$lista_id_prop="";
for($i=0; $i < count($destacados); $i++) {
	$lista_id_prop .= $destacados[$i]['id_prop'] . ",";
}
$lista_id_prop = substr($lista_id_prop, 0, -1);
$lista_id_carac = "198,255,257,303";
//$lista_id_carac = "42,255,257";
$param=array('inprop'=>$lista_id_prop,'incarac'=>$lista_id_carac);
$carac = $client->call('DatosConjuntoPropiedades',$param,'');

switch (intval($suc)){
	default:
		$query_fotos = sprintf("SELECT * FROM bannertop WHERE activo = 1 ORDER BY orden ASC",0);
		break;
	case 0:
		$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",0);
		break;
	case 1:
		$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",1);
		break;
	case 2:
		$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",2);
		break;
}
//echo $query_fotos;
mysql_select_db($database_config, $config);
$fotos = mysql_query($query_fotos, $config) or die(mysql_error());
//$totalRows_fotos = mysql_num_rows($fotos);

//$bannerTop = rand(0, $totalRows_fotos);
//mysql_data_seek($fotos, $bannerTop);
//$row_fotos = mysql_fetch_row($fotos);

$query_banners = sprintf("SELECT * FROM home WHERE id_area=%s AND activo = 1 ORDER BY orden ASC", GetSQLValueString(0, "int"));
mysql_select_db($database_config, $config);
$banners = mysql_query($query_banners, $config) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Inmobiliaria rural y urbana O'Keefe. Alquiler y venta de inmuebles</title>
<META NAME="TITLE" CONTENT="Inmobiliaria rural y urbana O'Keefe. Alquiler y venta de inmuebles">
<META NAME="DESCRIPTION" CONTENT="La inmobiliaria O'Keefe lo ayuda a resolver las variables del negocio inmobiliario. Obtenga asesoramiento en la compra-venta y alquiler de inmuebles urbanos y rurales.">
<META NAME="KEYWORDS" CONTENT= "inmobiliaria okeefe, propiedades en venta, propiedades en alquiler, casas en venta, casas en alquiler, departamentos en venta, departamentos en alquiler, venta de campos, alquiler de campos, venta de chacras, alquiler de chacras, asesoramiento agropecuario, tasaciones inmobiliarias, comercializacion de emprendimientos inmobiliarios, consultoria en desarrollos inmobiliarios, departamentos en zona sur, propiedades
en zona sur">
<META HTTP-EQUIV="CHARSET" CONTENT="ISO-8859-1">
<meta http-equiv="expires" content="0" />
<meta name="RESOURCE-TYPE" content="DOCUMENT" />
<meta name="DISTRIBUTION" content="GLOBAL" />
<meta name="AUTHOR" content="Inmobiliaria Okeefe" />
<meta name="COPYRIGHT" content="Copyright (c) 2003 by Okeefe" />
<meta name="RATING" content="GENERAL" />
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="js/funciones.js" language="javascript"></script>
<script language="javascript" src="js/ajax.js" type="text/javascript" /></script>
<script language="javascript" src="js/ajax-dynamic-content.js" type="text/javascript" /></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="css/okeefe.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="css/jquery.autocomplete.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/bannerRotator.js"></script>
<link href="css/banners.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/menu.js" language="javascript"></script>
<script type="text/javascript" src="js/destacados.php" language="javascript"></script>
<script>
$(function () {
	$("#dialog").dialog({
		autoOpen: false,
		modal: true
	});
	$("#abrir")
		.button()
		.click(function () {
		$("#dialog").dialog("open");
	});
});
</script>
</head>
	
<body onload="MM_preloadImages('images/home_s2.gif','images/rural_s2.gif','images/residencial_s2.gif','images/comercial_s2.gif','images/country_s2.gif','images/obras_s2.gif','images/qs_s2.gif','images/tasaciones_s2.gif','images/contacto_s2.gif','images/oportunidades_s2.gif','images/novedades_s2.gif','images/revista_s2.gif','images/exito_s2.gif','images/cv_s2.gif');">
<!--<form action="busqueda.php" name="fmenu" id="fmenu" method="get">
  <input type="hidden" name="opcTipoProp" id="opcTipoProp" />
</form>-->
<div id="cabeza">
  <div id="logo"><a href="index.php"><img src="images/logoOke.gif" alt="Okeefe Propiedades" width="220" height="67" border="0" /></a></div>
  <div id="trabajando">
    <div id="loop_top">
      <ul>
        <?php while ($row_fotos = mysql_fetch_assoc($fotos)) { 
				if(is_null($row_fotos['link'])){
		?>
        <li><img src="bannerTop/<?php echo $row_fotos['foto'];?>" /></li>
        <?php }else{ ?>
        <li><a href="<?php echo $row_fotos['link'];?>" target="_blank"><img src="bannerTop/<?php echo $row_fotos['foto'];?>" /></a></li>
        <?php 
				}
			}
		?>
      </ul>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<?php include_once("menu.php");?>
<div id="contenido">
  <div id="izq">
    <div id="fotos">
      <div id="loop">
        <ul>
          <?php while ($row_banners = mysql_fetch_assoc($banners)) {
			if($row_banners['link'] != "" || !is_null($row_banners['link'])){
				?>
          <li> <a href="<?php echo $row_banners['link']; ?>" target="_blank"> <img src="imgHome/<?php echo $row_banners['foto']; ?>" alt="<?php echo $row_banners['alt']; ?>"> </a> </li>
          <?php
			}else{
				?>
          <li><img src="imgHome/<?php echo $row_banners['foto']; ?>" alt="<?php echo $row_banners['alt']; ?>"></li>
          <?php 
			}
		 }?>
        </ul>
      </div>
    </div>
    <?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <?php include_once("buscadorVertical.php"); ?>
  </div>
  <?php include_once("pie.php"); ?>
