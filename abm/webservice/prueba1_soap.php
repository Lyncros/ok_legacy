<?php

$par=array();

$client = new SoapClient("http://abm.achavalcornejo.com/webservice/servicioweb.php?wsdl", $par);

$result = $client->ListarZona($par);//llamamos al métdo que nos interesa con los parámetros

print_r($result);
?>
