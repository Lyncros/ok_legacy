<?php require_once('Connections/config.php'); ?>
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

$empreBuscador = $client->call('ListarEmprendimientos',array(),'');
//print_r($empreBuscador);
//die();

if(!isset($suc)){
	$suc=0;
}

switch (intval($suc)){
// 	default:
// 		$suc = 0;
// 		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE activo = 1 ORDER BY orden ASC",0);
// 		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, quintas, chacras y campos y brinda el servicio de arrendamiento, administración rural  y venta de hacienda.";
// 		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts, lotes, quintas, chacras y campos | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios | Arrendamiento, administración rural  y venta de hacienda.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez.";
// 		//$suc = 0;
// 		break;
// 	case 0:
// 		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",0);
// 		//$suc = 0;
// 		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, quintas, chacras y campos y brinda el servicio de arrendamiento, administración rural  y venta de hacienda.";
// 		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts, lotes, quintas, chacras y campos | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios | Arrendamiento, administración rural  y venta de hacienda.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez.";
// 		break;
// 	case 1:
// 		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",1);
// 		$descripcion = "Desde 1974 la Inmobiliaria Rural O'Keefe se especializa en la compra, venta y arrendamiento de campos, chacras y quintas y brinda el servicio de administración rural  y venta de hacienda.";
// 		$titulo = "Inmobiliaria Rural O'Keefe | compra, venta y alquiler de campos, chacras y quintas | Arrendamiento, administración rural  y venta de hacienda.";
// 		$keywords = "Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y cia, Dasilva Enriquez, venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms";
// 		break;
// 	case 2:
// 		$descripcion = "Desde 1974 la Inmobiliaria Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales y quintas.";
// 		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts y lotes | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, Countries, barrios cerrados, alquileres zona sur, Locales, Agente de Bienes raices, agente inmobiliario, Comprar, Vender, Alquileres, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
// 		break;
// 	case 3:
// 		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, galpones, fábricas y cocheras.";
// 		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | compra, venta y alquileres de locales, oficinas, fabricas, galpones, lotes  y cocheras | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, Countries, barrios cerrados, alquileres zona sur, Locales, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquileres, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca, area60, fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
// 		break;
// 	case 4:
// 		$descripcion = "Inmobiliaria O'Keefe, especializada en Emprendimientos y Desarrollo Inmobiliario, se dedica a la compra y desarrollo de tierras y a la venta de lotes, departamentos y condominios en Clubes de campo y Barrios Cerrados.";
// 		$titulo = "Inmobiliaria O'Keefe, especializada en Emprendimientos y Desarrollo Inmobiliario |Compra y desarrollo de tierras | Venta de lotes, departamentos y condominios en Clubes de campo y Barrios Cerrados.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, lotes, terrenos, Countries, barrios cerrados, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquileres, Berazategui, Berazategui Oeste, Ranelagh, La Plata, Brandsen y  Guillermo Hudson, Las Golondrinas, vivilasgolondrinas, Hudson Park, Club el Carmen, Country Club Abril, Abril Country Club, Nuevo Quilmes, El Mirador, El Paraíso, Fincas de Hudson, Fincas de Iraola, Fincas de Iraola II, Greenville, Haras del Sur II, Haras del Sur III, Hudson chico, La Arbolada, La Cándida, La Faustina, La Reserva, Las Acacias, Los Ombúes de Hudson, Los Troncos, Miralagos, New Field, Posada de los Lagos, Solares del Parque, Villa del Parque, Altos de Brandsen, Howard Johnson Chascomus, lerrenos, Campos de Roca, área 60, El Borgo, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, , Club de campo, Ombúes de Hudson, Altos de Hudson, Villalobos, Prados de la Vega, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Masion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228. ";
// 		break;
// 	case 5:
// 		$descripcion = "Inmobiliaria O'Keefe, especializada en Emprendimientos y Desarrollo Inmobiliario, se dedica a la compra y desarrollo de tierras y a la venta de lotes, departamentos y condominios. ";
// 		$titulo = "Inmobiliaria O'Keefe, especializada en Emprendimientos y Desarrollo Inmobiliario |Compra y desarrollo de tierras.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria Hudson, emprendimientos, Casas, Departamentos, lotes, terrenos, lofts, locales comerciales, cocheras, Countries, barrios cerrados, Agente de Bienes, agente inmobiliario, Comprar, Vender, Alquileres, Quilmes, Quilmes Oeste, Bernal, Bernal Oeste, Ezpeleta, Ezpeleta Oeste, Don Bosco, Wilde, San Francisco Solano, Berazategui, Berazategui Oeste, Ranelagh, Avellaneda y  Guillermo Hudson, La Plata, Brandsen, Las Golondrinas, vivilasgolondrinas, Hudson Park, Club el Carmen, Country Club Abril, Abril Country Club, Nuevo Quilmes, El Mirador, El Paraíso, Fincas de Hudson, Fincas de Iraola, Fincas de Iraola II, Greenville, Haras del Sur II, Haras del Sur III, Hudson chico, La Arbolada, La Cándida, La Faustina, La Reserva, Las Acacias, Los Ombúes de Hudson, Los Troncos, Miralagos, New Field, Posada de los Lagos, Solares del Parque, Villa del Parque, Altos de Brandsen, Howard Johnson Chascomus, Campos de Roca, área 60, El Borgo, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, , Club de campo, Ombúes de Hudson, Altos de Hudson, Villalobos, Prados de la Vega, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Altos del Parque I, Altos del Parque II, Alvear 333, Ayacucho 719, Ayres de Moreno, Bauhaus II, Bauhaus III, Don Bosco 93, Klover I, Klover II, Klover III, Klover IV, Klover V, Mansion Towers, Mitre Trescientos 22, Sarmiento 325, Urquiza 228";
// 		$suc=4;
// 		break;
// 	case 6:
// 		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, quintas, chacras y campos y brinda el servicio de arrendamiento, administración rural  y venta de hacienda.";
// 		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts, lotes, quintas, chacras y campos | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios | Arrendamiento, administración rural  y venta de hacienda.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
// 		$suc=4;
// 		break;
// 	case 7:
// 		$descripcion = ": Inmobiliaria O'Keefe presenta alternativas de inversión en proyectos en pozo y fideicomisos inmobiliarios.";
// 		$titulo = ": Inmobiliaria O'Keefe presenta alternativas de inversión en proyectos en pozo y fideicomisos inmobiliarios.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
// 		$suc=0;
// 		break;
// 	case 8:
// 		$descripcion = "Desde 1974 la Inmobiliaria Rural y Urbana O'Keefe se especializa en la compra, venta y alquiler de casas, departamentos, lofts, lotes, locales comerciales, quintas, chacras y campos y brinda el servicio de arrendamiento, administración rural  y venta de hacienda.";
// 		$titulo = "Inmobiliaria Rural y Urbana O'Keefe | Compra, venta y alquiler de casas, departamentos, lofts, lotes, quintas, chacras y campos | Desarrollo de proyectos en pozo y Fideicomisos Inmobiliarios | Arrendamiento, administración rural  y venta de hacienda.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
// 		$suc=0;
// 		break;
// 	case 9:
// 		$descripcion = "Inmobiliaria O'Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
// 		$titulo = "Inmobiliaria O'Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
// 		$suc=0;
// 		break;
// 	case 10:
// 		$descripcion = "Inmobiliaria O'Keefe comparte todas las notas de prensa de medios de comunicacion como La Nación, Clarín, Ámbito Financiero, Revista Apertura, Revista Fortuna, Diario El Sol de Quilmes y BAE.";
// 		$titulo = "Inmobiliaria O'Keefe comparte todas las notas de prensa de medios de comunicacion como La Nación, Clarín, Ámbito Financiero, Revista Apertura, Revista Fortuna, Diario El Sol de Quilmes y BAE.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
// 		$suc=0;
// 		break;
// 	case 11:
// 		$descripcion = "Inmobiliaria O'Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
// 		$titulo = "Inmobiliaria O'Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
// 		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
// 		$suc=0;
// 		break;
	default:
		$suc = 0;
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE activo = 1 ORDER BY orden ASC",0);
		$descripcion = "O´Keefe Inmobiliaria Rural y Urbana – Venta de campos en Argentina, Emprendimientos en zona sur de Buenos Aires, Casa, obras, departamento y local en Quilmes.";
		$titulo = "O´Keefe Inmobiliaria Rural y Urbana – departamentos, Campos, chacras, casas";
		$keywords = "Inmobiliaria argentina, Provincia de Buenos Aires, O´Keefe, okeefe, Emprendimientos, Campo, Campos, Chacra, Chacras, quinta, quintas, casa, Casas, departamento, Departamentos, oficina, Oficinas, lote, lotes, Locales, local, countries, barrio cerrado, barrios cerrados, terrenos, en venta, en alquiler, alquileres, zona sur, Quilmes, Hudson, Chascomus  Berazategui, Bernal, fideicomisos inmobiliarios, Country Abril, Haras del Sur, Campos de Roca, area60, fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Hudson Park, Altos de Hudson, Villalobos, Cuenca del salado, Administración de campos, venta de hacienda, campo, campo de cría, campo agrícola, negocios, inmobiliario, inmobiliaria, rural, arrendamientos, Estancias, Patagonia, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación, trigo, cría, buenos aires, provincia, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, campos en Uruguay, venta de granjas, ranchos en Argentina, tierras para turismo, Inmobiliaria rural, coto de caza, bienes, agrícola, campos en Alquiler, Fincas,  Agente de Bienes, agente inmobiliario, Comprar, Vender, Argentina Inmobiliaria, los campos para la venta, ranches for sale, Farms, Patagonia, tambo, Real State, Cámara Argentina de Inmobiliarias Rurales, CAIR, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez, Ida Goldoni, Néstor Rojo, Otamendi, Fogola, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Diseñarq, Remax, renta ganancia inversion, dividendo, lucro";
		//$suc = 0;
		break;
	case 0:
		//$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",0);
		//$suc = 0;
		$descripcion = "O´Keefe Inmobiliaria Rural y Urbana – Venta de campos en Argentina, Emprendimientos en zona sur de Buenos Aires, Casa, obras, departamento y local en Quilmes.";
		$titulo = "O´Keefe Inmobiliaria Rural y Urbana – departamentos, Campos, chacras, casas";
		$keywords = "Inmobiliaria argentina, Provincia de Buenos Aires, O´Keefe, okeefe, Emprendimientos, Campo, Campos, Chacra, Chacras, quinta, quintas, casa, Casas, departamento, Departamentos, oficina, Oficinas, lote, lotes, Locales, local, countries, barrio cerrado, barrios cerrados, terrenos, en venta, en alquiler, alquileres, zona sur, Quilmes, Hudson, Chascomus  Berazategui, Bernal, fideicomisos inmobiliarios, Country Abril, Haras del Sur, Campos de Roca, area60, fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Hudson Park, Altos de Hudson, Villalobos, Cuenca del salado, Administración de campos, venta de hacienda, campo, campo de cría, campo agrícola, negocios, inmobiliario, inmobiliaria, rural, arrendamientos, Estancias, Patagonia, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación, trigo, cría, buenos aires, provincia, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, campos en Uruguay, venta de granjas, ranchos en Argentina, tierras para turismo, Inmobiliaria rural, coto de caza, bienes, agrícola, campos en Alquiler, Fincas,  Agente de Bienes, agente inmobiliario, Comprar, Vender, Argentina Inmobiliaria, los campos para la venta, ranches for sale, Farms, Patagonia, tambo, Real State, Cámara Argentina de Inmobiliarias Rurales, CAIR, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez, Ida Goldoni, Néstor Rojo, Otamendi, Fogola, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Diseñarq, Remax, renta ganancia inversion, dividendo, lucro";
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
		$descripcion = "Inmobiliaria O’Keefe comparte todas las notas de prensa de medios de comunicacion como La Nación, Clarín, Ámbito Financiero, Revista Apertura, Revista Fortuna, Diario El Sol de Quilmes y BAE.";
		$titulo = "Inmobiliaria O’Keefe comparte todas las notas de prensa de medios de comunicacion como La Nación, Clarín, Ámbito Financiero, Revista Apertura, Revista Fortuna, Diario El Sol de Quilmes y BAE.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez";
		$suc=0;
		break;
	case 11:
		$descripcion = "Inmobiliaria O’Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
		$titulo = "Inmobiliaria O’Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez.";
		$suc=0;
		break;
	case 12: /*--------CV------------*/
		$descripcion = "Inmobiliaria Rural y Urbana O’Keefe busca martilleros, corredores inmobiliarios y profesionales de comercialización y ventas para sumarse a nuestro equipo de trabajo.";
		$titulo = "Inmobiliaria Rural y Urbana O’Keefe busca martilleros, corredores inmobiliarios y profesionales de comercialización y ventas para sumarse a nuestro equipo de trabajo.";
		$keywords = "Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez.";
		$suc=0;
		break;
}
$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",$suc);
//echo $query_fotos;
mysql_select_db($database_config, $config);
$fotos = mysql_query($query_fotos, $config) or die(mysql_error());
$totalRows_fotos = mysql_num_rows($fotos);

$banner = rand(0, $totalRows_fotos);
mysql_data_seek($fotos, $banner);
$row_fotos = mysql_fetch_row($fotos);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $titulo; ?></title>
<META NAME="TITLE" CONTENT="<?php echo $titulo; ?>">
<META NAME="DESCRIPTION" CONTENT="<?php echo $descripcion; ?>">
<META NAME="KEYWORDS" CONTENT="<?php echo $keywords; ?>">
<META HTTP-EQUIV="CHARSET" CONTENT="ISO-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
<script type="text/javascript" src="js/jquery.tinyscrollbar.min.js"></script>
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
	//ajax_loadContent('contFavoritos','actualizaFavoritos.php');
	contador('id');
	contador('res');
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
  <div id="logo"><a href="index.php"><img src="images/logoOke.gif" alt="Okeefe Propiedades" width="220" height="67" border="0" /></a></div>
  <div id="trabajando"><img src="bannerTop/<?php echo $row_fotos[2];?>" border="0" /></div>
</div>
<?php include_once("menu.php");?>
<div id="contenido">
<div style="border-bottom:thin solid #CCC; padding-bottom:3px;">
  <div id="izq">
    <div id="buscadorHoriz">
          <!--<div style="float:left; width:90px;"><img src="images/buscarprop.gif" width="80" height="30" /></div>-->
          <form action="busqueda.php" name="buscador" id="buscador" method="get" onsubmit="actualizaDesdeHasta(); actualizaUbica();">
            <div style="width:215px; float:left;">
              <div>
                <select name="opcTipoOper">
                  <option value="" selected="selected">Operación</option>
                  <option value="Venta">Venta</option>
                  <option value="Alquiler">Alquiler</option>
                  <option value="Alquiler Temporario">Alquiler temporario</option>
                </select>
              </div>
              <div>
                <select name="opcTipoProp" id="opcTipoProp" onchange="actualizaAmbientes(this.value);">
                  <option value='0' selected="selected">Inmueble</option>
                  <?php foreach($tipoProp as $tipo){ ?>
                  <option value='<?php echo $tipo['tipo_prop'];?>'><?php echo $tipo['tipo_prop'];?></option>
                  <?php } ?>
                </select>
              </div>
              <div>
                <select name="opcZona" id="opcZona">
                  <option value='0' selected="selected">Zona</option>
                  <?php foreach($zona as $ubi){ ?>
                  <option value='<?php echo $ubi['id_ubica'];?>'><?php echo $ubi['nombre_ubicacion'];?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div style="width:215px; float:left; padding-left:8px;">
              <div id="Localidad">
                <select name="localidad" onclick="abreSelector();">
                  <option value='0' selected="selected">Localidad</option>
                </select>
              </div>
              <div style="display:block;" id="ambientes">
                <select name="opcAmbientes" id="opcAmbientes">
                  <option value="" selected="selected">Ambientes</option>
                  <option value=" =1">1</option>
                  <option value=" =2">2</option>
                  <option value=" =3">3</option>
                  <option value=" =4">4</option>
                  <option value=" >=5">5 o más</option>
                </select>
                </div>
              <div style="display:none;" id="despachos">
                <select name="opcDespachos" id="opcDespachos">
                  <option value="" selected="selected">Cant. Despachos</option>
                  <option value=" =1">1</option>
                  <option value=" =2">2</option>
                  <option value=" =3">3</option>
                  <option value=" =4">4</option>
                  <option value=" >=5">5 o más</option>
                </select>
                </div>
              <div style="display:none;" id="supTotal">
                <select name="opcSupTotal" id="opcSupTotal">
                  <option value="0" selected="selected">Cantidad de Ha</option>
                  <option value=" 50 AND 100 ">Entre 50 y 100Ha</option>
                  <option value=" 100 AND 200">Entre 100 y 200Ha</option>
                  <option value=" 200 AND 300">Entre 200 y 300Ha</option>
                  <option value=" 300 AND 500">Entre 300 y 500Ha</option>
                  <option value=" >=500">Más de 500Ha</option>
                </select>
              </div>
              <div>
                <select name="opcMonedaVenta" id="opcMonedaVenta">
                  <option value=''  selected="selected">Moneda</option>
                  <option value='U$S'>u$s</option>
                  <option value='$'>$</option>
                </select>
              </div>
            </div>
            <div style="width:265px; float:left; padding-left:8px;">
                <div style="float:left; width:130px;">
                <select name="desde" id="desde" onblur="actualizaDesdeHasta();">
                  <option value=''  selected="selected">Desde</option>
                  <option value='0'>0</option>
                  <option value='100000'>100.000</option>
                  <option value='150000'>150.000</option>
                  <option value='200000'>200.000</option>
                  <option value='250000'>250.000</option>
                  <option value='300000'>300.000</option>
                  <option value='400000'>400.000</option>
                  <option value='500000'>500.000</option>
                </select>
                </div>
                <div style="width:130px; float:right;">
                <select name="hasta" id="hasta" onblur="actualizaDesdeHasta();">
                  <option value=''  selected="selected">Hasta</option>
                  <option value='100000'>100.000</option>
                  <option value='150000'>150.000</option>
                  <option value='200000'>200.000</option>
                  <option value='250000'>250.000</option>
                  <option value='300000'>300.000</option>
                  <option value='400000'>400.000</option>
                  <option value='500000'>500.000 o más</option>
                </select>
                  <input type="hidden" name="opcPrecioVenta" id="opcPrecioVenta" value="" />
                </div>
                <div class="clearfix"></div>
      <div id="emprendimientos" style="float:left; width:185px;">
        <select name="opcEmprendimiento" id="opcEmprendimiento">
          <option value='0' selected="selected">Emprendimientos</option>
          <?php foreach($empreBuscador as $emp){ ?>
          <option value='<?php echo $emp['id_emp'];?>'><?php echo $emp['nombre'];?></option>
          <?php } ?>
        </select>
      </div>
                <div style="width:68px; float:right;">
                  <input name="enviar" type="image" src="images/buscarHome.gif" style="border:none; padding:0px;" />
                </div>
                <div class="clearfix"></div>
              </div>
            <input type="hidden" name="opcLocalidad" id="opcLocalidad" />
            <input type="hidden" name="opcUbica" id="opcUbica" />
            <input type="hidden" name="opcPrecioVenta" id="opcPrecioVenta" value="" />
          </form>
            <form action="detalleProp.php" method="get">
    <div style="width:265px; float:left; padding-left:8px;">
      <div style="float:left; width:235px;">Código
        <input type="text" value="" name="codigo" id="codigo" max="6" style="color:#939393; border:#939598 solid thin; width:180px; height:16px; padding-left:4px; border-radius: 5px; -ms-border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;	-khtml-border-radius: 5px;" onChange="javascript:this.value=this.value.toUpperCase();" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="if(this.value == '') this.value = this.defaultValue" />
      </div>
      <div style="float:right; width:25px;">
        <input name="enviar" type="image" src="images/lupa.gif" style="border:none; width:20px; height:20px;" />
      </div>
      <div class="clearfix"></div>
    </div>
  </form>
                <div class="clearfix"></div>
        </div>
          </div>
  <div id="derecha">
    <table width="235" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center"><img src="images/sobre.gif" width="17" height="13" alt="mail" /></td>
    <td><a href="mailto:inmobiliaria@okeefe.com.ar" class="mailHome">inmobiliaria@okeefe.com.ar</a></td>
    <td class="telHome" align="right"><img src="images/redes.gif" alt="redes sociales" width="56" height="18" border="0" usemap="#Map" /></td>
  </tr>
  <tr>
    <td align="center" width="19"><img src="images/telefono.gif" width="19" height="13" alt="Teléfono" /></td>
    <td colspan="2" class="telHome">[5411] &shy;4253&shy;-3961 / 02229&shy;-45&shy;-5003</td>
  </tr>
</table>
    <div id="idFavoritos">
      <div id="tituFavoritos" onclick="javascript: mi_busqueda('id');"><img src="images/trebolFavoritos.gif" width="15" height="13" style="vertical-align:middle" /> Favoritos <span id="cant"></span></div>
    </div>
    <div id="idVistos">
      <a href="busqueda.php?mb=2"><div id="tituVistos"><img src="images/vista.gif" width="18" height="11" style="vertical-align:baseline;" /> Recientemente vistos <span id="cantRes">(<?php echo count($_SESSION['reciente']); ?>)</span></div></a>
    </div>
  </div>
<map name="Map" id="Map">
  <area shape="rect" coords="0,-1,17,17" href="http://www.facebook.com/profile.php?id=100001722647049" target="_blank" />
  <area shape="rect" coords="40,1,58,16" href="callto://inmobiliariaokeefe/" target="_blank" />
</map>
  <div class="clearfix"></div>
</div>

