<?php
require_once('lib-nusoap/nusoap.php');

$id_prop = $_GET['id'];

//$wsdl="http://localhost/okeefe/webservice/servicioweb.php?wsdl";
$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice  

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

$param=array('id_prop'=>$id_prop);
$fotos = $client->call('ListarFotosPropiedad',$param,'');

setlocale(LC_ALL, "es_ES");
echo "<?xml version='1.0' encoding='UTF-8'?>"."\n";
echo "<galeria>\n";

for($i=0; $i <count($fotos); $i++){
	$data = getimagesize("http://abm.okeefe.com.ar/fotos_th/" . str_replace(" ", "%20", $fotos[$i]['foto']));
	echo "\t<imagen img='" . str_replace(" ", "%20", $fotos[$i]['foto']) . "' ancho='$data[0]' alto='$data[1]' />\n";
}

echo "</galeria>";

?>