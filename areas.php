<?php
ob_start();
require_once('Connections/config.php');
require_once('lib-nusoap/nusoap.php');

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

$area = $_GET['area'];

switch($area){
	case 1:
		$foto = "dennis.jpg";
		$tituloArea = "Director División Rural:";
		$nombre = "Dennis O'Keefe";
		$descrip = "La inmobiliaria O´Keefe se inicia con la sección rural en el año 1974. Desde entonces, y a través de la experiencia y capacitación, nos hemos perfeccionado en la <strong>compraventa de campos, chacras, quintas, hacienda y asesoramiento, tanto agropecuario como jurídico y legal</strong>.";
		$suc = 1;
		$tipoDest='6,7,16';
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",1);
		$descripcion = "La Inmobiliaria Rural O'Keefe se especializa en la compra, venta y arrendamiento de campos, chacras y quintas. También brinda asesoramiento agropecuario.";
		$titulo = "Compraventa de Campos, Chacras y Quintas. Asesoramiento Agropecuario | O'keefe";
		$keywords = "inmobiliaria rural, compra de chacras, venta de chacras, compra de campos, venta de campos, compra de quintas, venta de quintas, asesoramiento agropecuario";
		break;
	case 2:
		$foto = "tomas.jpg";
		$tituloArea = "Director División Residencial:";
		$nombre = "Tomás O'Keefe";
		$descrip = "Desde nuestros inicios, en la zona sur de Gran Buenos Aires, nos propusimos brindar un servicio personalizado para ayudarlo a tomar una de las más importantes decisiones de su vida: Elegir su hogar. En la actualidad contamos con una amplia oferta de inmuebles a su disposición.  No dude en consultarnos.";
		$suc = 2;
		$tipoDest='1,7,9,18';
		$descripcion = "Desde 1974 la Inmobiliaria Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales y quintas.";
		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts y lotes | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, Countries, barrios cerrados, alquileres zona sur, Locales, Agente de Bienes raices, agente inmobiliario, Comprar, Vender, Alquileres, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",2);
		break;
	case 3:
		$foto = "charly.jpg";
		$tituloArea = "Director División Comercial:";
		$nombre = "Carlos O'Keefe";
		$descrip = "Gracias a nuestro amplio espectro de cobertura, dado por las secciones Rural y Urbana, accedemos a una amplia red de propietarios de locales, galpones y oficinas como de empresas interesadas en acceder a buenas ubicaciones y mejores costos.";
		$suc = 3;
		$tipoDest='2,11,15,19';
		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, galpones, fábricas y cocheras.";
		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | compra, venta y alquileres de locales, oficinas, fabricas, galpones, lotes  y cocheras | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, Countries, barrios cerrados, alquileres zona sur, Locales, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquileres, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca, area60, fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",3);
		break;
	case 4:
		$foto = "ignacio.jpg";
		$tituloArea = "Director División Emprendimientos:";
		$nombre = "Ignacio O'Keefe";
		$descrip = "Poseemos un profundo conocimiento sobre todas las urbanizaciones de zona sur del Gran Buenos Aires y contamos con una amplia oferta de lotes y casas en Barrios cerrados y clubes de campo. No deje de consultarnos.";
		$suc = 4;
		$tipoDest=0;
		$descripcion = "Inmobiliaria O'Keefe, especializada en Emprendimientos y Desarrollo Inmobiliario, se dedica a la compra y desarrollo de tierras y a la venta de lotes, departamentos y condominios en Clubes de campo y Barrios Cerrados.";
		$titulo = "Inmobiliaria O'Keefe, especializada en Emprendimientos y Desarrollo Inmobiliario |Compra y desarrollo de tierras | Venta de lotes, departamentos y condominios en Clubes de campo y Barrios Cerrados.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, lotes, terrenos, Countries, barrios cerrados, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquileres, Berazategui, Berazategui Oeste, Ranelagh, La Plata, Brandsen y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, Club el Carmen, Country Club Abril, Abril Country Club, Nuevo Quilmes, El Mirador, El Paraíso, Fincas de Hudson, Fincas de Iraola, Fincas de Iraola II, Greenville, Haras del Sur II, Haras del Sur III, Hudson chico, La Arbolada, La Cándida, La Faustina, La Reserva, Las Acacias, Los Ombúes de Hudson, Los Troncos, Miralagos, New Field, Posada de los Lagos, Solares del Parque, Villa del Parque, Altos de Brandsen, Howard Johnson Chascomus, lerrenos, Campos de Roca, área 60, El Borgo, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, , Club de campo, Ombúes de Hudson, Altos de Hudson, Villalobos, Prados de la Vega, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228. ";
		break;
}


//echo $query_fotos;
$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",$suc);
mysql_select_db($database_config, $config);
$fotos = mysql_query($query_fotos, $config) or die(mysql_error());
//$totalRows_fotos = mysql_num_rows($fotos);

//$banner = rand(0, $totalRows_fotos);
//mysql_data_seek($fotos, $banner);
//$row_fotos = mysql_fetch_row($fotos);

$query_banners = sprintf("SELECT * FROM home WHERE id_area=%s AND activo = 1 ORDER BY orden ASC", GetSQLValueString($suc, "int"));
mysql_select_db($database_config, $config);
$banners = mysql_query($query_banners, $config) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $titulo; ?></title>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="DC.title" content="<?php echo $titulo; ?>" />
<meta name="description" content="<?php echo $descripcion; ?>" />
<meta http-equiv="expires" content="0" />
<meta name="RESOURCE-TYPE" content="DOCUMENT" />
<meta name="DISTRIBUTION" content="GLOBAL" />
<meta name="AUTHOR" content="Okeefe" />
<meta name="COPYRIGHT" content="Copyright (c) 2003 by Okeefe" />
<meta name="RATING" content="GENERAL" />
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="js/funciones.js" language="javascript"></script>
<script language="javascript" src="js/ajax.js" type="text/javascript" /></script>
<script language="javascript" src="js/ajax-dynamic-content.js" type="text/javascript" /></script>
<script language="javascript" src="js/jquery-1.6.2.min.js" type="text/javascript" /></script>
<script language="javascript" src="js/thickbox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/thickbox.css" />
<link href="css/okeefe.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="css/jquery.autocomplete.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/bannerRotator.js"></script>
<link href="css/banners.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/menu.js" language="javascript"></script>
<script type="text/javascript" src="js/destacados.php?d=<?php echo $tipoDest;?>" language="javascript"></script>
</head>

<body onload="MM_preloadImages('images/home_s2.gif','images/rural_s2.gif','images/residencial_s2.gif','images/comercial_s2.gif','images/country_s2.gif','images/obras_s2.gif','images/qs_s2.gif','images/tasaciones_s2.gif','images/contacto_s2.gif','images/oportunidades_s2.gif','images/novedades_s2.gif','images/revista_s2.gif','images/exito_s2.gif','images/cv_s2.gif');">
<form action="busqueda.php" name="fmenu" id="fmenu" method="get">
  <input type="hidden" name="opcTipoProp" id="opcTipoProp" />
</form>
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
    <div id="area">
      <div id="responsable">
        <div id="foto"><img src="images/<?php echo $foto;?>" width="59" height="62" /></div>
        <div id="titulo"><?php echo $tituloArea;?><br />
          <span class="respNombre"><?php echo $nombre;?></span></div>
	      <div  class="clearfix"></div>
      </div>
      <div id="descrip"><h2><?php echo $descrip;?></h2></div>
      <div  class="clearfix"></div>
    </div>
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
  <?php
  if(isset($_GET['utm_source'])){
  ?>
  <!-- Google Code for Landing Rural Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 973708058;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "itXICIbb2gkQmram0AM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/973708058/?label=itXICIbb2gkQmram0AM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<?php
}
  include_once('pie.php');
  ?>