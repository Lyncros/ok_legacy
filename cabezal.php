<?php 
ob_start();
session_start();
if(!isset($_SESSION['ingreso'])){
	if(isset($_GET['envio'])){
    	$_SESSION['ingreso'] = $_GET['envio'];
	}else{
    	$_SESSION['ingreso'] = $_SERVER['HTTP_REFERER'];
    }
}
 ?>
<?php require_once('Connections/config.php'); ?>
<?php
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
/*
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
*/
if(!isset($suc)){
	$suc=0;
}

switch (intval($suc)){
	default:
		$suc = 0;
		$keywords = "inmobiliaria okeefe, propiedades en venta, propiedades en alquiler, casas en venta, casas en alquiler, departamentos en venta, departamentos en alquiler, venta de campos, alquiler de campos, venta de chacras, alquiler de chacras, asesoramiento agropecuario, tasaciones inmobiliarias, comercializacion de emprendimientos inmobiliarios, consultoria en desarrollos inmobiliarios, departamentos en zona sur, propiedades";
		break;
	case 1:
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",1);
		$descripcion = "O´Keefe Inmobiliaria Rural y Urbana – Compra Venta de campos, Chacras  y Estancias en Argentina, administracion, venta de hacienda, Farms for sale in Argentina.";
		$titulo = "O´Keefe Inmobiliaria Rural – Venta de Campos, Chacras y Quintas";
		$keywords = "Campo en venta, Inmobiliaria argentina, Provincia de Buenos Aires, O´Keefe, okeefe, Emprendimientos, Campo, Campos, Chacra, Chacras, quinta, quintas, lote, lotes, terreno, terrenos, en venta, en alquiler, venta, alquiler, alquileres, zona sur, Sucursal Quilmes, sucursal Hudson, Cuenca del salado, Administración de campos, renta, producción, venta de hacienda, campo, campo de cría, campo agrícola, negocios, inmobiliario, inmobiliaria, rural, arrendamientos, Estancias, Patagonia, Brandsen, Chascomús, Berazategui, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación, trigo, cría, buenos aires, provincia, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Ferme in vendita, Azienda agricola in vendita, venta de ranchos, compra de estancias, campos en Uruguay, venta de granjas, ranchos en Argentina, tierras para turismo, Inmobiliaria rural, coto de caza, bienes, agrícola, campos en Alquiler, Fincas, Agente de Bienes, agente inmobiliario, Comprar, Vender, Argentina Inmobiliaria, los campos para la venta, ranches for sale, Farms, Patagonia, tambo, Real State, Cámara Argentina de Inmobiliarias Rurales, CAIR, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez, junto al hombre de campo";
		break;
	case 2:
		$descripcion = "O'Keefe Inmobiliaria Urbana - Compra, venta y alquiler de casas, departamentos, lofts y lotes - Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios.";
		$titulo = "O´Keefe Inmobiliaria Urbana – Compra y venta de propiedades en zona sur.";
		$keywords = "Inmobiliaria en Quilmes, inmobiliaria en Hudson, emprendimiento, emprendimientos, Casa, Casas, departamento, Departamentos, oficina, Oficinas, Lote, Lotes, chalet, loft, PH, Propiedad horizontal, piso, semipiso, vistas, vista, garaje, Countries, barrios cerrados, alquileres zona sur, alquileres, contratos, Locales, Agente de Bienes raices, agente inmobiliario, Comprar, Vender, Alquileres, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda,   Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
		break;
	case 3:
		$descripcion = "O'Keefe Inmobiliaria Rural y Urbana - Compra, venta y alquileres de locales, oficinas, fabricas, galpones, lotes  y cocheras – En zona sur de Gran Buenos Aires.";
		$titulo = "O'Keefe Inmobiliaria Rural y Urbana - Compra, venta y alquileres de comercios.";
		$keywords = "Comercio en Quilmes, Comercios en Quilmes, Inmobiliaria Quilmes, inmobiliaria en Hudson, Comercios en zona sur, gran Buenos Aires, emprendimientos, Casas, Departamentos, Oficinas, Countries, barrios cerrados, alquileres zona sur, Locales, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquileres, contratos, garantias, garantia, garantia propietaria, rentabilidad, rentas, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca, area60, fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
		break;
	case 4:
		$descripcion = "Compra, venta y alquiler de casas, lotes y departamentos en barrios privados o cerrados. Especializados en el desarrollo de emprendimientos Inmobiliarios en zona Sur.";
		$titulo = "O’Keefe Inmobiliaria - Emprendimientos y desarrollos en zona sur.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, agente de bienes raíces, agente inmobiliario, asesor inmobiliario, emprendimientos, desarrollos, avance, progreso,  en pozo, en obra, lanzamiento, casas, viviendas, hogar, morada, departamentos, unidades funcionales,  lotes, fracciones, terrenos, condominios, al costo, pesos, cuotas fijas, dólares, countries, country, barrios cerrados, barrio privado, club de campo, loteo, tierra, comprar, adquirir, ganar, vender, ceder, transferir, ofrecer, venta, negocio, inversión, alquiler, arrendamiento, renta, ganancia, dividendo, ingreso, lucro,  permuta, cambio, mudanza, trueque, laguna, agua, pileta, tenis, futbol, arboleda, forestación, club house, SUM, zona sur, Berazategui, Quilmes, Bernal, Barrio Parque, Hudson, Platanos, Berazategui Oeste, Ranelagh, La Plata, Brandsen, Ruta 2, Ruta 215, Villa Elisa, City Bell, Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Aquavento, Aquaterra, Hudson Park, Country Club Abril, Abril Country Club, Nuevo Quilmes, El Mirador, El Paraíso, Fincas de Hudson, Fincas de Iraola, Fincas de Iraola II, Greenville, Haras del Sur II, Haras del Sur III, Hudson chico, La Arbolada, La Cándida, La Faustina, La Reserva, Las Acacias, Los Ombúes de Hudson, Los Troncos, Miralagos, New Field, Posada de los Lagos, Solares del Parque, Village del Parque, Altos de Brandsen, Howard Johnson Chascomus, Campos de Roca, área 60, El Borgo, Barrio Parque el Carmen, El Carmencito, Country Club el Carmen, Altos de Hudson, Villalobos, Pueblos del Plata, Prados de la Vega, Malteria Hudson, Lagoon Hudson, Grupo Monarca, Pampas Pueblo Hudson, Altos de Tinogasta, Arqsteel,  Néstor Rojo, Otamendi, Fernando Fogola, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Adipa, Juan Di Paolantonio, Memmo, Savasta, J. Yacoub, Goldoni, Alicia Duna, Hudson Propiedades, fideicomisos, quincho, garage, lavadero, galeria, comedor, dormitorio, parque, ventanal, riego, cocina, chalet, categoría, pileta, locales, oficinas, industrial, vivienda,  servicios, gas, luz, agua corriente";
		break;
	case 5:
		$descripcion = "Desarrollo y comercialización de obras en pozo – Inversiones en Emprendimientos y Desarrollos Inmobiliarios. Condominios, Edificios, loteos abiertos y cerrados.";
		$titulo = "O’Keefe Inmobiliaria Urbana - Realización y comercialización de obras en pozo.";
		$keywords = "Departamento en Pozo, Inversión en fideicomiso, Invierta en pesos, Invertir en pesos, Fideicomiso inmobiliario, Inmobiliaria Quilmes, inmobiliaria Hudson, emprendimientos, Casas, Departamentos, lotes, terrenos, lofts, locales comerciales, cocheras, Countries, barrios cerrados, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquilerar, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, La Plata, Brandsen, Las Golondrinas, vivilasgolondrinas, Hudson Park, Club el Carmen, Country Club Abril, Abril Country Club, Nuevo Quilmes, El Mirador, El Paraíso, Fincas de Hudson, Fincas de Iraola, Fincas de Iraola II, Greenville, Haras del Sur II, Haras del Sur III, Hudson chico, La Arbolada, La Cándida, La Faustina, La Reserva, Las Acacias, Los Ombúes de Hudson, Los Troncos, Miralagos, New Field, Posada de los Lagos, Solares del Parque, Villa del Parque, Altos de Brandsen, Howard Johnson Chascomus, Campos de Roca, área 60, El Borgo, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, , Club de campo, Ombúes de Hudson, Altos de Hudson, Villalobos, Prados de la Vega, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Mansion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228.";
		$suc=4;
		break;
	case 6:
		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, quintas, chacras y campos y brinda el servicio de arrendamiento, administración rural  y venta de hacienda.";
		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts, lotes, quintas, chacras y campos | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios | Arrendamiento, administración rural  y venta de hacienda.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
		$suc=4;
		break;
	case 7:
		$descripcion = "Inmobiliaria O’Keefe presenta alternativas de inversión en proyectos en pozo y fideicomisos inmobiliarios.";
		$titulo = "Inmobiliaria O’Keefe presenta alternativas de inversión en proyectos en pozo y fideicomisos inmobiliarios.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
		$suc=0;
		break;
	case 8:
		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, quintas, chacras y campos y brinda el servicio de arrendamiento, administración rural  y venta de hacienda.";
		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts, lotes, quintas, chacras y campos | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios | Arrendamiento, administración rural  y venta de hacienda.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
		$suc=0;
		break;
	case 9:
		$descripcion = "Inmobiliaria O’Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
		$titulo = "Inmobiliaria O’Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez.";
		$suc=0;
		break;
	case 10:
		$descripcion = "Conocé todas las notas y artículos de la inmobiliaria O'Keefe en los medios más
importantes del país sobre temas relacionados al rubro inmobiliario.";
		$titulo = "Inmobiliaria O'Keefe en los medios | O'Keefe Inmobiliaria";
		$keywords = "inmobiliaria okeefe, okeefe en los medios";
		$suc=0;
		break;
	case 11:
	$descripcion = "Inmobiliaria O’Keefe: novedades de compra, venta y alquiler de departamentos, casas, oficinas, locales, lotes, quintas y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
		$titulo = "Novedades en compra y venta de inmuebles en zona sur | O'keefe Inmobiliaria";
		$keywords = "novedades inmobiliarias, nuevos emprendimientos inmobiliarios, okeefe inmobiliaria";
		$suc=0;
		break;
	case 12: /*--------CV------------*/
		$descripcion = "Inmobiliaria Rural y Urbana O’Keefe busca martilleros, corredores inmobiliarios y profesionales de comercialización y ventas para sumarse a nuestro equipo de trabajo.";
		$titulo = "Inmobiliaria Rural y Urbana O’Keefe busca martilleros, corredores inmobiliarios y profesionales de comercialización y ventas para sumarse a nuestro equipo de trabajo.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez.";
		$suc=0;
		break;
	case 15: /*--------REVISTA------------*/
		$descripcion = "Conozca la revista digital o en papel de la Inmobiliaria O'Keefe. Suscríbase y entérese de las últimas novedades inmobiliarias.";
		$titulo = "Conocé la revista inmobiliaria digital o en papel de la Inmobiliaria O'Keefe";
		$keywords = "revista inmobiliaria, novedades inmobiliarias, revista sobre inmuebles, revista sobre propiedades, revista okeefe";
		$suc=0;
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $titulo; ?></title>
<META NAME="TITLE" CONTENT="<?php echo $titulo; ?>">
<META NAME="DESCRIPTION" CONTENT="<?php echo $descripcion; ?>">
<META NAME="KEYWORDS" CONTENT="<?php echo $keywords; ?>">
<META HTTP-EQUIV="CHARSET" CONTENT="ISO-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
<!--<script language="javascript" src="js/swfobject.js" type="text/javascript" /></script>
 -->
<script language="javascript" src="js/jquery-1.6.2.min.js" type="text/javascript" /></script>
<script language="javascript" src="js/thickbox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/thickbox.css" />
<link href="css/okeefe.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="css/jquery.autocomplete.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/menu.js" language="javascript"></script>
<script type="text/javascript" src="js/bannerRotator.js"></script>
<script type="text/javascript" src="js/destacados.php" language="javascript"></script>
</head>

<body onload="MM_preloadImages('images/home_s2.gif','images/rural_s2.gif','images/residencial_s2.gif','images/comercial_s2.gif','images/country_s2.gif','images/obras_s2.gif','images/qs_s2.gif','images/tasaciones_s2.gif','images/contacto_s2.gif','images/oportunidades_s2.gif','images/novedades_s2.gif','images/revista_s2.gif','images/exito_s2.gif','images/cv_s2.gif');">
<!--<form action="busqueda.php" name="fmenu" id="fmenu" method="get">
  <input type="hidden" name="opcTipoProp" id="opcTipoProp" />
</form>
 --><div id="cabeza">
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
