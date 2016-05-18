<?php
set_time_limit(0); 
require_once('lib-nusoap/nusoap.php');

$wsdl="http://qa.zonaprop.com.ar/webservice/realState?wsdl";

$header = "";
//$header.='<password>4ch4v4lC0rn3j0</password>';
//$header.='<proveedor>44</proveedor>';
//$header.='<usuario>7077212</usuario>';
$header.='<password>0K33f3</password>';
$header.='<proveedor>80</proveedor>';
$header.='<usuario>7077386</usuario>';
$header.='<pais>ar</pais>';

$client=new nusoap_client($wsdl,true); //instanciando un nuevo objeto cliente para consumir el webservice  

$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = true;

$client->setHeaders($header);


$param1=array('idUbicacion'=>3667);
$resultado1=$client->call('consultarTiposInmueblesPorUbicacion',$param1);
print_r($resultado1);

/*
$param=array();
$resultado=$client->call('consultarUbicaciones',$param);
print_r($resultado);
*/

$err = $client->getError();
if ($err) {
    echo '<p><b>Constructor error: ' . $err . '</b></p>';
}else{
//     $result = $client->call("publicar",$paramA);
     $result = $client->call("consultarUbicaciones",$param);
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
