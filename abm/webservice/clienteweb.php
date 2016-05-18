<?php
require_once('lib-nusoap/nusoap.php');

$wsdl="http://www.zgroupsa.com.ar/achaval/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
//$client=new soapclient($wsdl,false); //instanciando un nuevo objeto cliente para consumir el webservice  
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice  


$param=array('estado'=>'1'); //pasando parametros de entrada que seran pasados hacia el metodo

$productos = $client->call('ListarProductos', array(),''); //llamando al metodo y recuperando el array de productos en una variable

//¿ocurrio error al llamar al web service? 
if ($client->fault) { // si
      echo 'No se pudo completar la operación'; 
      die(); 
}else{ // no
	$error = $client->getError(); 
	if ($error) { // Hubo algun error 
		echo 'Error:' . $error; 
	} 
} 
print_r($productos);
if(is_array($productos)){ //si hay valores en el array
	for($i=0;$i<count($productos);$i++){
		echo $productos[$i]['ProductoID'].'  '.$productos[$i]['Nombre'].' su precio es : '.$productos[$i]['Precio'].'<br>';
	}
}else{
	echo 'No hay productos';
}
?>
