<?php

$par=array();

$client = new SoapClient("http://abm.achavalcornejo.com/webservice/servicioweb.php?wsdl", $par);

$result = $client->ListarZona($par);//llamamos al m�tdo que nos interesa con los par�metros

print_r($result);
?>
