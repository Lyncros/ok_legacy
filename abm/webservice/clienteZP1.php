<?php
require_once('lib-nusoap/nusoap.php');

$wsdl="http://qa.zonaprop.com.ar/webservice/realState?wsdl";

//$wsdl="http://abm.achavalcornejo.com/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$header = "";
$header.="<password>4ch4v4lC0rn3j0</password>";
$header.="<proveedor>44</proveedor>";
$header.="<usuario>7077212</usuario>";
//$header.="<pais>AR</pais>";

//<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.g7.ws.inmuebles.dridco.com/">

$mynamespace = "http://schemas.xmlsoap.org/soap/envelope";

$client=new nusoap_client($wsdl,true); //instanciando un nuevo objeto cliente para consumir el webservice  

$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = true;

$client->setHeaders($header);
$client->useHTTPPersistentConnection();

$param1 = array('idAvisoProveedor'=>'BELGR23',
'subtitulo'=>'LA PAMPA 2050',
'tipoPropiedad'=>'Departamentos_Duplex',
'tipoOperacion'=>'venta',
'tipoMoneda'=>'USD',
'precio'=>90000,
'descripcion'=>'Edificio de muy buena categoria en ubicacion estrategica');

$ubicacion = array(
    'nombreCalle'=>'La Pampa',
    'alturaCalle'=>'2050',
    'piso'=>'13',
    'entreCalle1'=>'11 de Septiembre',
    'entreCalle2'=>'3 de Febrero',
    'idUbicacion'=>'3652'
);
$telefono1 = array('codigoArea'=>011,
      'numeroTelefono'=>'41141000'
      );
$contacto = array(
    'nombre'=>'Juan',
    'apellido'=>'Perez',
    'email'=>'jperez@hotmail.com',
    $telefono1,
    'horarioContacto'=>'9 hs a 18 hs'
);
$img1 = array('urlImagenes'=>'http://miraloqueveo.files.wordpress.com/2009/03/arte-urbano1.jpg?w=400&amp;h=300');
$img2 = array('urlImagenes'=>'http://3.bp.blogspot.com/_G5xE-kNy7eU/SCey3lhjgPI/AAAAAAAAB9E/IxeWm7CFLlo/s1600/arte%2Burbano-argentina-palermo.png');
$img3 = array('urlImagenes'=>'http://www.inforo.com.ar/files/film-and-arts-arte-urbano-berlin.jpg');

$imagenes = array($img1, $img2, $img3);

$apto_profesional = array('nombre'=>'apto_profesional',
    'valor'=>'no');
$superficie_total = array('nombre'=>'superficie_total',
    'valor'=>100);
$superficie_cubierta = array('nombre'=>'superficie_cubierta',
    'valor'=>100);    
$cantidad_ambientes = array('nombre'=>'cantidad_ambientes',
    'valor'=>'3');
$cant_dormitorios = array('nombre'=>'cant_dormitorios',
    'valor'=>'3');
$deptos_x_piso= array('nombre'=>'deptos_x_piso',
    'valor'=>3);
$antiguedad = array('nombre'=>'antiguedad',
    'valor'=>10);
$disposicion = array('nombre'=>'disposicion',
    'valor'=>'contrafrente');
$estado_gral_inmueble = array('nombre'=>'estado_gral_inmueble',
    'valor'=>'excelente');
$oferta_reservada = array('nombre'=>'oferta_reservada',
    'valor'=>0);

$especificaciones = array($apto_profesional, $superficie_total, $superficie_cubierta, $cantidad_ambientes, $cant_dormitorios, $deptos_x_piso, $antiguedad, $disposicion, $estado_gral_inmueble, $oferta_reservada);

$amb1 = array('ambiente'=>'Dormitorio principal',
               'valor'=>'4x5'
);
$amb2 = array('ambiente'=>'Dormitorio 1',
               'valor'=>'4x5'
               );
$medidas = array($amb1, $amb2);

$aviso = array($param1, $ubicacion, $contacto, $imagenes, $especificaciones, $medidas);

$paramA = array($aviso));
//$paramA='<aviso>'.$param.'</aviso>';
print_r($paramA);

//$resultado=$client->call("publicarAviso",$paramA);




  
//  echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//  echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';

$err = $client->getError();
if ($err) {
    echo '<p><b>Constructor error: ' . $err . '</b></p>';
}
else{
     $result = $resultado=$client->call("publicar",$paramA, $mynamespace);
     if ($client->fault) {
          echo '<p><b>Fault: ';
          print_r($result);
          echo '</b></p>';
          echo '<p><b>Request: <br>';
          echo htmlspecialchars($client->request, ENT_QUOTES) . '</b></p><br />';
          echo '<p><b>Response: <br>';
          echo htmlspecialchars($client->response, ENT_QUOTES) . '</b></p><br />';
          echo '<p><b>Debug: <br>';
          echo htmlspecialchars($client->debug_str, ENT_QUOTES) . '</b></p><br />';
     } else {
          $err = $client->getError();
          if ($err) {
               echo '<p><b>Error: ' . $err . '</b></p>';
               echo '<p><b>Request: <br>';
               echo htmlspecialchars($client->request, ENT_QUOTES) . '</b></p><br />';
               echo '<p><b>Response: <br>';
               echo htmlspecialchars($client->response, ENT_QUOTES) . '</b></p><br />';
               echo '<p><b>Debug: <br>';
               echo htmlspecialchars($client->debug_str, ENT_QUOTES) . '</b></p><br />';
          } else {
               print_r($result);
          }
     }
}
?>
