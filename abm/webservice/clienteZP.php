<?php
require_once('lib-nusoap/nusoap.php');

$wsdl="http://qa.zonaprop.com.ar/webservice/realState?wsdl";

//$wsdl="http://abm.achavalcornejo.com/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$header = "";
$header.='<password>4ch4v4lC0rn3j0</password>';
$header.='<proveedor>44</proveedor>';
$header.='<usuario>7077212</usuario>';
$header.='<pais>ar</pais>';

$client=new nusoap_client($wsdl,true); //instanciando un nuevo objeto cliente para consumir el webservice  

$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = true;

$client->setHeaders($header);

// Consulta de ubicacion   OK
/*
$param=array();
$resultado=$client->call('consultarUbicaciones',$param);
print_r($resultado);
*/

// Consulta de tipo de inmueble por ubicacion
/*
$param=array(array('idUbicacion'=>3667));
$resultado=$client->call('consultarTiposInmueblesPorUbicacion',$param);
*/


/*    FALLA PORQUE MANDA SOLO EL ULTIMO ESPECIFICACIONES
$especifCantAmb=array('nombre'=>'cantidad_ambientes','valor'=>'3');
$especifSupTot=array('nombre'=>'superficie_total','valor'=>'30');
$especifSupCub=array('nombre'=>'superficie_cubierta','valor'=>'20');

$especificaciones="'especificaciones'=>$especifCantAmb,";
$especificaciones.="'especificaciones'=>$especifSupTot,";
$especificaciones.="'especificaciones'=>$especifSupCub";

$telefono1=array('codigoArea'=>'011','numeroTelefono'=>'12345678','extension'=>'');
$telefono2=array('codigoArea'=>'','numeroTelefono'=>'','extension'=>'');
$llamarA = array('nombre'=>'Pepe','apellido'=>'Picapiedras','email'=>'333@yahoo.com','telefono1'=>$telefono1,'horarioContacto'=>'1 hs a 5 hs');

$domicilio=array('nombreCalle'=>'Rivadavia','alturaCalle'=>'350','piso'=>'3','entreCalle1'=>'Piedras','entreCalle2'=>'Peru','coordenadaLatitud'=>'23.2','coordenadaLongitud'=>'-12.5','idUbicacion'=>3667);
$ubicacion=array('ubicacion'=>$domicilio);


$param=array('aviso'=>array('idAvisoProveedor'=>'ACH10','subtitulo'=>'Prueba1','tipoPropiedad'=>'Departamentos_Duplex','tipoOperacion'=>'Alquiler','tipoMoneda'=>'USD','precio'=>1500,'descripcion'=>'Esta es una prueba','urlVideo'=>'','condicionesPago'=>'Contado','requisitosPago'=>'','financiacion'=>'','ubicacion'=>$domicilio,'contacto'=>$llamarA,'especificaciones'=>$especifCantAmb,'especificaciones'=>$especifSupTot,'especificaciones'=>$especifSupCub));
print_r($param);
*/

$especifCantAmb="<especificaciones><nombre>cantidad_ambientes</nombre><valor>3</valor></especificaciones>";
$especifSupTot="<especificaciones><nombre>superficie_total</nombre><valor>35</valor></especificaciones>";
$especifSupCub="<especificaciones><nombre>superficie_cubierta</nombre><valor>30</valor></especificaciones>";

$especificaciones=$especifCantAmb.$especifSupTot.$especifSupCub;

$telefono1="<codigoArea>011</codigoArea><numeroTelefono>12345678</numeroTelefono><extension></extension>";
$llamarA = "<nombre>Pepe</nombre><apellido>Picapiedras</apellido><email>333@yahoo.com</email><telefono1>$telefono1</telefono1><horarioContacto>1 hs a 5 hs</horarioContacto>";

$domicilio="<nombreCalle>Rivadavia</nombreCalle><alturaCalle>350</alturaCalle><piso>3</piso><entreCalle1>Piedras</entreCalle1><entreCalle2>Peru</entreCalle2><coordenadaLatitud>23.2</coordenadaLatitud><coordenadaLongitud>-12.5<coordenadaLongitud><idUbicacion>3667</idUbicacion>";
$ubicacion="<ubicacion>$domicilio</ubicacion>";

//$param=array(array('aviso'=>array('idAvisoProveedor'=>'ACH10','subtitulo'=>'Prueba1','tipoPropiedad'=>'Departamentos_Duplex','tipoOperacion'=>'Alquiler','tipoMoneda'=>'USD','precio'=>1500,'descripcion'=>'Esta es una prueba',array('ubicacion'=>$ubicacion),array('contacto'=>$llamarA),array('especificaciones'=>especif1))));
/*
$param=array(array('aviso'=>array('idAvisoProveedor'=>'ACH10','subtitulo'=>'Prueba1','tipoPropiedad'=>'Departamentos_Duplex','tipoOperacion'=>'Alquiler','tipoMoneda'=>'USD','precio'=>1500,'descripcion'=>'Esta es una prueba','ubicacion'=>$ubicacion)));
Error:    Array ( [WebServiceIntegerResponse] => Array ( [returnCode] => -1 ) )  No se envio algun campo obligatorio 
*/

$param="<idAvisoProveedor>ACH10</idAvisoProveedor><subtitulo>Prueba1</subtitulo><tipoPropiedad>Departamentos_Duplex</tipoPropiedad>";
$param.="<tipoOperacion>alquiler</tipoOperacion><tipoMoneda>USD</tipoMoneda><precio>1500</precio><descripcion>Esta es una prueba</descripcion>";
$param.="<condicionesPago>Contado</condicionesPago>";
$param.="$ubicacion<contacto>$llamarA</contacto>$especificaciones";

// Error -1 falta parametro obligatorio
//$paramA=array('publicar'=>'<aviso>'.$param.'</aviso>');
//print_r($paramA);

//$paramA='<publicar><aviso>'.$param.'</aviso></publicar>';
//$paramA='<aviso>'.$param.'</aviso>';

$paramA=array('aviso'=>"'".$param."'");

print_r($paramA);

$resultado=$client->call('publicar',$paramA);





//  echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//  echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';

if ($client->fault)
{
  echo '<h2>Error: La peticion contiene un contenido SOAP invalido</h2>'.
  '<pre>'; print_r($resultado); echo '</pre>';
}else{
  $err = $client->getError();
  if ($err){
    echo '<h2>Error Revento aca</h2><pre>' . $err . '</pre>';
  } else  {
  	
	print_r($resultado);
  }
}
?>
