<?php
require_once('lib-nusoap/nusoap.php');

$wsdl="http://qa.zonaprop.com.ar/webservice/realState?wsdl";

$client=new nusoap_client($wsdl,true); //instanciando un nuevo objeto cliente para consumir el webservice  

$param = "<?xml version=\"1.0\" encoding=\"UTF-8\"\?\>
<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ser=\"http://service.g7.ws.inmuebles.dridco.com/\">
    <soapenv:Header>
        <ser:password>4ch4v4lC0rn3j0</ser:password>
        <ser:proveedor>44</ser:proveedor>
        <ser:usuario>7077212</ser:usuario>
        <pais>ar</pais>
    </soapenv:Header>
    <soapenv:Body>
        <ser:publicar>
            <ser:aviso>
                <idAvisoProveedor>BELGR23</idAvisoProveedor>
                <subtitulo>LA PAMPA 2050</subtitulo>
                <tipoPropiedad>Departamentos_Duplex</tipoPropiedad>
                <tipoOperacion>Alquiler</tipoOperacion>                                                     
                <tipoMoneda>USD</tipoMoneda>                                                  
                <precio>900</precio>
                <descripcion>Edificio de muy buena categoria en ubicacion estrategica</descripcion>
                 
                <ubicacion>
                    <nombreCalle>La Pampa</nombreCalle>
                    <alturaCalle>2050</alturaCalle>
                    <piso>13</piso>
                    <entreCalle1>11 de Septiembre</entreCalle1>
                    <entreCalle2>3 de Febrero</entreCalle2>
                    <coordenadaLatitud></coordenadaLatitud>
                    <coordenadaLongitud></coordenadaLongitud>
                    <idUbicacion>3652</idUbicacion>                                                                 
                </ubicacion>
                <contacto>
                    <nombre>Juan</nombre>
                    <apellido>Perez</apellido>
                    <email>jperez@hotmail.com</email>
                    <telefono1>
                        <codigoArea>011</codigoArea>
                        <numeroTelefono>41141000</numeroTelefono>
                    </telefono1>
                    <horarioContacto>9 hs a 18 hs</horarioContacto>
                </contacto>

                <urlImagenes>http://miraloqueveo.files.wordpress.com/2009/03/arte-urbano1.jpg?w=400&amp;h=300</urlImagenes>
                <urlImagenes>http://3.bp.blogspot.com/_G5xE-kNy7eU/SCey3lhjgPI/AAAAAAAAB9E/IxeWm7CFLlo/s1600/arte%2Burbano-argentina-palermo.png</urlImagenes>
                <urlImagenes>http://www.inforo.com.ar/files/film-and-arts-arte-urbano-berlin.jpg</urlImagenes>

                <especificaciones>
                    <nombre>cantidad_ambientes</nombre>
                    <valor>3</valor>
                </especificaciones>

                <especificaciones>
                    <nombre>superficie_total</nombre>
                    <valor>100</valor>
                </especificaciones>

                <especificaciones>
                    <nombre>superficie_cubierta</nombre>
                    <valor>100</valor>
                </especificaciones>

                <especificaciones>
                    <nombre>oferta_reservada</nombre>
                    <valor>0</valor>
                </especificaciones>

                <medidas>
                    <ambiente>Dormitorio principal</ambiente>
                    <valor>4x5</valor>
                </medidas>

                <medidas>
                    <ambiente>Dormitorio 1</ambiente>
                    <valor>3.50x3</valor>
                </medidas>

            </ser:aviso>
        </ser:publicar>
    </soapenv:Body>
</soapenv:Envelope>";


print($param);

$err = $client->getError();
if ($err) {
    echo '<p><b>Constructor error: ' . $err . '</b></p>';
}
else{
     $result = $resultado=$client->call("publicar",$param);
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
