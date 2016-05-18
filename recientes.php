<?php
session_start();

//date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once('lib-nusoap/nusoap.php');

//$wsdl="http://localhost/okeefe/webservice/servicioweb.php?wsdl";
$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice  

//¿ocurrio error al llamar al web service? 
if ($client->fault) { // si
    echo 'No se pudo completar la operación';
    die();
}else { // no
    $error = $client->getError();
    if ($error) { // Hubo algun error
        echo 'Error:' . $error;
    }
}


?>
<table width="215" border="0" cellspacing="0" cellpadding="2">
<?php
if(count($_SESSION['reciente']) > 1){
	 foreach ($_SESSION['reciente'] as $valor){ 
		$param=array('id_prop'=>$valor);
		$prop = $client->call('Propiedad',$param,'');
		//print_r($prop);

		$param=array('id_prop'=>$valor);
		$fotos = $client->call('ListarFotosPropiedad',$param,'');
		if(count($fotos) == 0){
			$foto = "images/noDisponible.gif";
		}else{
			$foto = "http://abm.okeefe.com.ar/fotos_th/" . $fotos[0]['foto'];
		}
?>
  <tr>
    <td width="50" height="33"><img src="<?php echo $foto; ?>" width="50" height="33" alt="" border="0" onclick="javascript: txt(<?php echo $valor; ?>);" style="cursor:pointer;"></td>
    <td style="font-size:.82em;"><?php echo $txtZona; ?></td>
  </tr>
  <?php } 
}else{
	?>
<tr><td>No existen datos para mostrar</td></tr>
<?php } ?>
</table>
