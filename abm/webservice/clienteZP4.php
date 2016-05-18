<?php
require_once('lib-nusoap/nusoap.php');

$wsdl="http://qa.zonaprop.com.ar/webservice/realState?wsdl";

//$wsdl="http://abm.achavalcornejo.com/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$header = "";
$header.="<password>4ch4v4lC0rn3j0</password>";
$header.="<proveedor>44</proveedor>";
$header.="<usuario>7077212</usuario>";
//$header.="<pais>AR</pais>";

$client=new nusoap_client($wsdl,true); //instanciando un nuevo objeto cliente para consumir el webservice  

$client->soap_defencoding = "UTF-8";
$client->decode_utf8 = true;

$client->setHeaders($header);

//$mynamespace = "http://schemas.xmlsoap.org/soap/envelope";
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


//consultarPublicaciones
$paramA=array();


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
     
//   ARMADO POR EL FLACO
$especifCantAmb="\n\t\t<especificaciones>\n\t\t\t<nombre>cantidad_ambientes</nombre>\n\t\t\t<valor>3</valor>\n\t\t</especificaciones>";
$especifSupTot="\n\t\t<especificaciones>\n\t\t\t<nombre>superficie_total</nombre>\n\t\t\t<valor>35</valor>\n\t\t</especificaciones>";
$especifSupCub="\n\t\t<especificaciones>\n\t\t\t<nombre>superficie_cubierta</nombre>\n\t\t\t<valor>30</valor>\n\t\t</especificaciones>";
$especifAP="\n\t\t<especificaciones>\n\t\t\t<nombre>apto_profesional</nombre>\n\t\t\t<valor>no</valor>\n\t\t</especificaciones>";
$especifCantDom="\n\t\t<especificaciones>\n\t\t\t<nombre>cant_dormitorios</nombre>\n\t\t\t<valor>no</valor>\n\t\t</especificaciones>";
$especifDeptos="\n\t\t<especificaciones>\n\t\t\t<nombre>deptos_x_piso</nombre>\n\t\t\t<valor>5</valor>\n\t\t</especificaciones>";
$especifAnt="\n\t\t<especificaciones>\n\t\t\t<nombre>antiguedad</nombre>\n\t\t\t<valor>10</valor>\n\t\t</especificaciones>";
$especifEstado="\n\t\t<especificaciones>\n\t\t\t<nombre>estado_gral_inmueble</nombre>\n\t\t\t<valor>excelente</valor>\n\t\t</especificaciones>";
$especifDisp="\n\t\t<especificaciones>\n\t\t\t<nombre>disposicion</nombre>\n\t\t\t<valor>contrafrente</valor>\n\t\t</especificaciones>";
$especifOferta="\n\t\t<especificaciones>\n\t\t\t<nombre>oferta_reservada</nombre>\n\t\t\t<valor>0</valor>\n\t\t</especificaciones>";

$especificaciones=$especifCantAmb.$especifSupTot.$especifSupCub.$especifAP.$especifCantDom.$especifDeptos.$especifAnt.$especifEstado.$especifDisp.$especifOferta;

$telefono1="\n\t\t\t\t<codigoArea>011</codigoArea>\n\t\t\t\t<numeroTelefono>12345678</numeroTelefono>\n\t\t\t\t<extension></extension>";
$llamarA = "\n\t\t\t<nombre>Pepe</nombre>\n\t\t\t<apellido>Picapiedras</apellido>\n\t\t\t<email>333@yahoo.com</email>\n\t\t\t<telefono1>$telefono1\n\t\t\t</telefono1>\n\t\t\t<horarioContacto>1 hs a 5 hs</horarioContacto>";

$domicilio="\t\t<nombreCalle>Rivadavia</nombreCalle>\n\t\t\t<alturaCalle>350</alturaCalle>\n\t\t\t<piso>3</piso>\n\t\t\t<entreCalle1>Piedras</entreCalle1>\n\t\t\t<entreCalle2>Peru</entreCalle2>\n\t\t\t<coordenadaLatitud>23.2</coordenadaLatitud>\n\t\t\t<coordenadaLongitud>-12.5</coordenadaLongitud>\n\t\t\t<idUbicacion>3667</idUbicacion>";
$ubicacion="\n\t\t<ubicacion>\n\t$domicilio\n\t\t</ubicacion>";
  
//$param=array(array('aviso'=>array('idAvisoProveedor'=>'ACH10','subtitulo'=>'Prueba1','tipoPropiedad'=>'Departamentos_Duplex','tipoOperacion'=>'Alquiler','tipoMoneda'=>'USD','precio'=>1500,'descripcion'=>'Esta es una prueba',array('ubicacion'=>$ubicacion),array('contacto'=>$llamarA),array('especificaciones'=>especif1))));
/*
$param=array(array('aviso'=>array('idAvisoProveedor'=>'ACH10','subtitulo'=>'Prueba1','tipoPropiedad'=>'Departamentos_Duplex','tipoOperacion'=>'Alquiler','tipoMoneda'=>'USD','precio'=>1500,'descripcion'=>'Esta es una prueba','ubicacion'=>$ubicacion)));
Error:    Array ( [WebServiceIntegerResponse] => Array ( [returnCode] => -1 ) )  No se envio algun campo obligatorio 
*/


$param="\t\t<idAvisoProveedor>ACH12</idAvisoProveedor>\n\t\t<subtitulo>Prueba1</subtitulo>\n\t\t<tipoPropiedad>Departamentos_Duplex</tipoPropiedad>";
$param.="\n\t\t<tipoOperacion>venta</tipoOperacion>\n\t\t<tipoMoneda>USD</tipoMoneda>\n\t\t<precio>1500</precio>\n\t\t<descripcion>Esta es una prueba</descripcion>";
$param.="\n\t\t<condicionesPago>Contado</condicionesPago>";
$param.="$ubicacion\n\t\t<contacto>$llamarA\n\t\t</contacto>$especificaciones";

// Error -1 falta parametro obligatorio
//$paramA=array('publicar'=>'<aviso>'.$param.'</aviso>');
//print_r($paramA);

//$paramA="<ser:publicar>\n\t<ser:aviso>\n".$param."\n\t<ser:/aviso>\n<ser:/publicar>";
$paramA=array('aviso'=>$param);

print_r($paramA);


$resultado=$client->call("publicar",$paramA);


$err = $client->getError();
if ($err) {
    echo '<p><b>Constructor error: ' . $err . '</b></p>';
}else{
//     $result = $client->call("publicar",$paramA);
     $result = $client->call("consultarPublicaciones",$paramA);
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
