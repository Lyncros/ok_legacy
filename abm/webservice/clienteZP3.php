<?php
require_once('lib-nusoap/nusoap.php');

$wsdl="http://qa.zonaprop.com.ar/webservice/realState?wsdl";

$client=new nusoap_client($wsdl,true); //instanciando un nuevo objeto cliente para consumir el webservice  

//$wsdl="http://abm.achavalcornejo.com/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$header = "";
$header.="<password>4ch4v4lC0rn3j0</password>";
$header.="<proveedor>44</proveedor>";
$header.="<usuario>7077212</usuario>";
$header.="<pais>ar</pais>";

//<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.g7.ws.inmuebles.dridco.com/">

$mynamespace = "http://schemas.xmlsoap.org/soap/envelope";

$client=new nusoap_client($wsdl,true); //instanciando un nuevo objeto cliente para consumir el webservice  

$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = true;

$client->setHeaders($header);
$client->useHTTPPersistentConnection();

$param = array();


print($param);

$err = $client->getError();
if ($err) {
    echo '<p><b>Constructor error: ' . $err . '</b></p>';
}
else{
     $result = $client->call("consultarUbicaciones",$param, $mynamespace);
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
