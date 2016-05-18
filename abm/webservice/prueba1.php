<?php

 /*
 
 <soap:Header> 
    <RequestorCredentials xmlns="http://namespace.example.com/"> 
      <Token>string</Token> 
      <Version>string</Version> 
      <MerchantID>string</MerchantID> 
      <UserCredentials> 
        <UserID>string</UserID> 
        <Password>string</Password> 
      </UserCredentials> 
    </RequestorCredentials> 
</soap:Header> 

Corresponding PHP code: 

<?php 

$ns = 'http://namespace.example.com/'; //Namespace of the WS. 

//Body of the Soap Header. 
$headerbody = array('Token' => $someToken, 
                    'Version' => $someVersion, 
                    'MerchantID'=>$someMerchantId, 
                      'UserCredentials'=>array('UserID'=>$UserID, 
                                             'Password'=>$Pwd)); 

//Create Soap Header.        
$header = new SOAPHeader($ns, 'RequestorCredentials', $headerbody);        
        
//set the Headers of Soap Client. 
$soap_client->__setSoapHeaders($header); 

 
 */
 
 
 
 
 
//instanciamos el cliente soap
$par=array();
$par['idUbicacion']=3667;

$client = new SoapClient("http://qa.zonaprop.com.ar/webservice/realState?wsdl", $par);
 
//añadimos las cabeceras a las peticiones
$headerBody=array('proveedor'=> '44',
                  'pais', 'ar',
                  'clave', '4ch4v4lc0rn3j0',
                  'usuario', '7077212'
                  );
$header = new SoapHeader("http://qa.zonaprop.com.ar/webservice/realState?wsdl", 'header', $headerBody);
/*
$headers[] = new SoapHeader("http://service.g7.ws.inmuebles.dridco.com", 'proveedor', '44');
$headers[] = new SoapHeader("http://service.g7.ws.inmuebles.dridco.com/", 'pais', 'ar');
$headers[] = new SoapHeader("http://service.g7.ws.inmuebles.dridco.com/", 'clave', '4ch4v4lc0rn3j0');
$headers[] = new SoapHeader("http://service.g7.ws.inmuebles.dridco.com/", 'usuario', '7077212');
*/

$client->__setSoapHeaders($headers);


//$header.='<password>4ch4v4lC0rn3j0</password>';
//$header.='<proveedor>44</proveedor>';
//$header.='<usuario>7077212</usuario>';
//$header.='<pais>ar</pais>';
 
//    $servicio="http://qa.zonaprop.com.ar/webservice/consultarUbicaciones?wsdl"; //url del servicio
//    $parametros=array(); //parametros de la llamada
//    $client = new SoapClient($servicio, $parametros);


    $result = $client->consultarTiposInmueblesPorUbicacion($par);//llamamos al métdo que nos interesa con los parámetros

    print($result);

?>
