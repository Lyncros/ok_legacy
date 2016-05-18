<?php
if(isset($_GET['i']) && isset($_GET['c']) && isset($_GET['t'])){// && isset($_GET['a'])){
	require_once('webservice/lib-nusoap/nusoap.php');

//	$wsdl="http://www.zgroupsa.com.ar/achaval_test/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
	$wsdl="http://abm.achavalcornejo.com/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos

	$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice  
	
	$param=array('idcrm'=>$_GET['i'],'crmpar'=>$_GET['c'],'crmtxt'=>$_GET['t'],'adjuntos'=>$_GET['a']);
	
	$loca = $client->call('registraBusqueda',$param,'');
//echo $_GET['t'];
//	echo $loca;
	
/*	if ($client->fault)	{
  		echo '<h2>Error: La peticion contiene un contenido SOAP invalido</h2><pre>'; print_r($resultado); echo '</pre>';
	}else{
  		$err = $client->getError();
  		if ($err){
    		echo '<h2>Error Revento aca</h2><pre>' . $err . '</pre>';
  		} else  {
			print_r($response);
  		}
	}
*/	
}
?>