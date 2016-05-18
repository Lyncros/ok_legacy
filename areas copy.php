<?php
ob_start();
require_once('Connections/config.php');
require_once('lib-nusoap/nusoap.php');

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

$area = $_GET['area'];

switch($area){
	case 1:
		$foto = "dennis.jpg";
		$titulo = "Director División Rural:";
		$nombre = "Dennis O'Keefe";
		$descrip = "La inmobiliaria O´Keefe se inicia con la sección rural en el año 1974. Desde entonces, y a través de la experiencia y capacitación, nos hemos perfeccionado en la compraventa de campos, chacras, quintas, hacienda y asesoramiento, tanto agropecuario como jurídico y legal.";
		$suc = 1;
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",1);
		$descripcion = "Desde 1974 la Inmobiliaria Rural O'Keefe se especializa en la compra, venta y arrendamiento de campos, chacras y quintas y brinda el servicio de administración rural  y venta de hacienda.";
		$titulo = "Inmobiliaria Rural O'Keefe | compra, venta y alquiler de campos, chacras y quintas | Arrendamiento, administración rural  y venta de hacienda.";
		$keywords = "Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y cia, Dasilva Enriquez, venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms";
		break;
	case 2:
		$foto = "tomas.jpg";
		$titulo = "Director División Residencial:";
		$nombre = "Tomás O'Keefe";
		$descrip = "Desde nuestros inicios, en la zona sur de Gran Buenos Aires, nos propusimos brindar un servicio personalizado para ayudarlo a tomar una de las más importantes decisiones de su vida: Elegir su hogar. En la actualidad contamos con una amplia oferta de inmuebles a su disposición.  No dude en consultarnos.";
		$suc = 2;
		$descripcion = "Desde 1974 la Inmobiliaria Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales y quintas.";
		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts y lotes | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, Countries, barrios cerrados, alquileres zona sur, Locales, Agente de Bienes raices, agente inmobiliario, Comprar, Vender, Alquileres, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",2);
		break;
	case 3:
		$foto = "charly.jpg";
		$titulo = "Director División Comercial:";
		$nombre = "Carlos O'Keefe";
		$descrip = "Gracias a nuestro amplio espectro de cobertura, dado por las secciones Rural y Urbana, accedemos a una amplia red de propietarios de locales, galpones y oficinas como de empresas interesadas en acceder a buenas ubicaciones y mejores costos.";
		$suc = 3;
		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, galpones, fábricas y cocheras.";
		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | compra, venta y alquileres de locales, oficinas, fabricas, galpones, lotes  y cocheras | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, Countries, barrios cerrados, alquileres zona sur, Locales, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquileres, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca, area60, fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",3);
		break;
	case 4:
		$foto = "ignacio.jpg";
		$titulo = "Director División Emprendimientos:";
		$nombre = "Ignacio O'Keefe";
		$descrip = "Poseemos un profundo conocimiento sobre todas las urbanizaciones de zona sur del Gran Buenos Aires y contamos con una amplia oferta de lotes y casas en Barrios cerrados y clubes de campo. No deje de consultarnos.";
		$suc = 4;
		$descripcion = "Inmobiliaria O'Keefe, especializada en Emprendimientos y Desarrollo Inmobiliario, se dedica a la compra y desarrollo de tierras y a la venta de lotes, departamentos y condominios en Clubes de campo y Barrios Cerrados.";
		$titulo = "Inmobiliaria O'Keefe, especializada en Emprendimientos y Desarrollo Inmobiliario |Compra y desarrollo de tierras | Venta de lotes, departamentos y condominios en Clubes de campo y Barrios Cerrados.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, lotes, terrenos, Countries, barrios cerrados, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquileres, Berazategui, Berazategui Oeste, Ranelagh, La Plata, Brandsen y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, Club el Carmen, Country Club Abril, Abril Country Club, Nuevo Quilmes, El Mirador, El Paraíso, Fincas de Hudson, Fincas de Iraola, Fincas de Iraola II, Greenville, Haras del Sur II, Haras del Sur III, Hudson chico, La Arbolada, La Cándida, La Faustina, La Reserva, Las Acacias, Los Ombúes de Hudson, Los Troncos, Miralagos, New Field, Posada de los Lagos, Solares del Parque, Villa del Parque, Altos de Brandsen, Howard Johnson Chascomus, lerrenos, Campos de Roca, área 60, El Borgo, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, , Club de campo, Ombúes de Hudson, Altos de Hudson, Villalobos, Prados de la Vega, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228. ";
		break;
}
//echo $query_fotos;
$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",$suc);
mysql_select_db($database_config, $config);
$fotos = mysql_query($query_fotos, $config) or die(mysql_error());
$totalRows_fotos = mysql_num_rows($fotos);

$banner = rand(0, $totalRows_fotos);
mysql_data_seek($fotos, $banner);
$row_fotos = mysql_fetch_row($fotos);

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
<meta name="ROBOTS" content="INDEX, FOLLOW" />
<meta name="REVISIT-AFTER" content="1 DAYS" />
<meta name="RATING" content="GENERAL" />
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="js/funciones.js" language="javascript"></script>
<script language="javascript" src="js/ajax.js" type="text/javascript" /></script>
<script language="javascript" src="js/ajax-dynamic-content.js" type="text/javascript" /></script>
<script language="javascript" src="js/swfobject.js" type="text/javascript" /></script>
<script language="javascript" src="js/jquery-1.6.2.min.js" type="text/javascript" /></script>
<script language="javascript" src="js/thickbox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/thickbox.css" />
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
	mostrarDestacado();
});

document.onclick = jsddm_close;

</script>
<script type="text/javascript">
var id_dest = new Array();
var titu_dest = new Array();
var foto_dest = new Array();
var desc_dest = new Array();
<?php
for($i=0; $i < count($destacados); $i++) {
	$id_prop = $destacados[$i]['id_prop'];
	// BUSCA FOTOS ----------
	$param=array('id_prop'=>$id_prop);
	$fotos = $client->call('ListarFotosPropiedad',$param,'');
	//print_r($fotos);
	//-----------------------
	$paramU=array('id_ubica' => $destacados[$i]['id_ubica'],'modo' => 'c');
	$loca = $client->call('detallaNombreZona',$paramU,'');
	foreach($tipoProp as $tipo){
		if($tipo['id_tipo_prop'] == $destacados[$i]['id_tipo_prop']){
			$txtTipo = $tipo['tipo_prop'];
			break;
		}
	}

	echo "id_dest[".$i."]=".$id_prop.";\n";
	echo "titu_dest[".$i."]='".$loca." - ".$txtTipo."';\n";
	echo "foto_dest[".$i."]='".$fotos[0]['foto']."'; \n";
	$desc = nl2br(busca_valor($destacados[$i]['id_prop'], 255, $carac));
	$MaxLENGTH = 80;
	if (strlen($desc) > $MaxLENGTH) {
		$desc = substr(strip_tags($desc), 0, strrpos(substr(strip_tags($desc), 0, $MaxLENGTH), " "));
		$desc .= '...';
	}

	echo "desc_dest[".$i."]='". trim(preg_replace( '/\s+/', ' ', $desc))."';\n";
}
?>			  
var contador = 0;
setInterval('mostrarDestacado()',10000);

function mostrarDestacado(){
	if(contador == <?php echo count($destacados) ?>){
		contador = 0;
	}

	document.getElementById('tituDestacado').innerHTML=titu_dest[contador];
	document.getElementById('txtDestacado').innerHTML=desc_dest[contador];
	document.getElementById('fotoDestacado').innerHTML='<a href="detalleProp.php?iddest='+id_dest[contador]+'"><img src="http://abm.okeefe.com.ar/fotos_th/'+foto_dest[contador]+'" width="235" /></a>';
	document.getElementById('vermasDestacado').innerHTML='<a href="detalleProp.php?iddest='+id_dest[contador]+'"><img src="images/vermashome.gif" width="53" height="11" border="0" />';
	contador++;
}
function sigDestacado(){
	//contador++;
	if(contador >= <?php echo count($destacados) ?>){
		contador = 0;
	}
	mostrarDestacado();
}
function antDestacado(){
	contador-=2;
	if(contador < 0){
		contador = <?php echo count($destacados) ?>;
	}
	//alert(contador);
	mostrarDestacado();
}
</script>
<script type="text/javascript">
var flashvars = {
	suc: <?php echo $area; ?>,
	carp: 'imgHome'
	};
var params = {
  quality: "high",
  align: "top",
  play: "true",
  loop: "true",
  scale: "showall",
  wmode: "opaque",
  devicefont: "false",
  bgcolor: "#FFFFFF",
  menu: "true",
  allowFullScreen: "false",
  allowScriptAccess:"sameDomain"
};
var attributes = {};
 
swfobject.embedSWF("images/fotos_centro.swf", "fotos", "755", "445", "9.0.0","Scripts/expressInstall.swf", flashvars, params, attributes);
</script>
</head>

<body onload="MM_preloadImages('images/home_s2.gif','images/rural_s2.gif','images/residencial_s2.gif','images/comercial_s2.gif','images/country_s2.gif','images/obras_s2.gif','images/qs_s2.gif','images/tasaciones_s2.gif','images/contacto_s2.gif','images/oportunidades_s2.gif','images/novedades_s2.gif','images/revista_s2.gif','images/exito_s2.gif','images/cv_s2.gif');">
<form action="busqueda.php" name="fmenu" id="fmenu" method="get">
  <input type="hidden" name="opcTipoProp" id="opcTipoProp" />
</form>
<div id="cabeza">
  <div id="logo"><a href="index.php"><img src="images/logoOke.gif" alt="Okeefe Propiedades" width="220" height="67" border="0" /></a></div>
  <div id="trabajando"><img src="bannerTop/<?php echo $row_fotos[2];?>" border="0" /></div>
</div>
<?php include_once("menu.php");?>
<div id="contenido">
  <div id="izq">
    <div id="area">
      <div id="responsable">
        <div id="foto"><img src="images/<?php echo $foto;?>" width="59" height="62" /></div>
        <div id="titulo"><?php echo $titulo;?><br />
          <span class="respNombre"><?php echo $nombre;?></span></div>
      </div>
      <div id="descrip"><?php echo $descrip;?></div>
      <div  class="clearfix"></div>
    </div>
    <div id="fotos"></div>
<?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
        <?php include_once("buscadorVertical.php"); ?>
  </div>
  <?php include_once('pie.php'); ?>