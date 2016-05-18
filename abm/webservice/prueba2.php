<?php


$par=array('trace'=>1,'exceptions' => 0);
$client = new SoapClient("http://qa.zonaprop.com.ar/webservice/realState?wsdl", $par);

/*
$headerBody=array('usuario'=> '7077212',
                  'proveedor'=> '44',
                  'password'=>'4ch4v4lc0rn3j0',
                  'pais'=> 'ar'                  
                  );

$header = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'consultarUbicaciones', $headerBody);

$client->__setSoapHeaders($header);
(faultcode: soap:Server, faultstring: Invalid provider.)
*/

/*
$headers[] = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'proveedor', array('proveedor'=>'44'));
$headers[] = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'pais', array('pais'=>'ar'));
$headers[] = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'password', array('password'=>'4ch4v4lc0rn3j0'));
$headers[] = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'usuario', array('usuario'=>'7077212'));

$client->__setSoapHeaders($headers);
(faultcode: soap:Server, faultstring: Invalid provider)
*/

/*
$headers[] = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'proveedor', '44');
$headers[] = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'pais', ar'));
$headers[] = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'password', '4ch4v4lc0rn3j0');
$headers[] = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'usuario', '7077212');

$client->__setSoapHeaders($headers);
faultcode: soap:Server, faultstring: Invalid authentication.
*/



$param=array();
$result=$client->consultarUbicaciones($param);
print $result;

if (is_soap_fault($result)) {
    trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
}



echo "REQUEST HEADERS:\n" . $client->__getLastRequestHeaders() . "\n";

echo '<pre>';

//var_dump($client->__getFunctions());
echo '</pre>';


class 


?>
