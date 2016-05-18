<?php
header('Content-type: text/html; charset=utf-8');
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

$param=array('id_padre' => $_GET['zona']);
$loca = $client->call('ListarZonasDependientesActivas',$param,'');
//print_r($loca);
//die();
	?>

<select name="opcLocalidad[]" id="opcLocalidad" onclick="//abreSelector();" multiple="multiple" size="6">
  <option value='0' selected="selected">Localidad</option>
  <?php for($i=0; $i < count($loca); $i++) { 
  			if($loca[$i]['id_padre'] == $_GET['zona']){
  ?>
  			
  <option value="<?php echo $loca[$i]['nombre_ubicacion']; ?>"><?php echo $loca[$i]['nombre_ubicacion']; ?></option>
  <?php		
  			$id=$loca[$i]['id_ubica'];
			foreach($loca as $hijos){
	  			if($hijos['id_padre'] == $id && $loca[$i]['nombre_ubicacion'] != $hijos['nombre_ubicacion']){
  ?>
  <option value="<?php echo $hijos['nombre_ubicacion']; ?>">--<?php echo $hijos['nombre_ubicacion']; ?></option>
  <?php  
				}
  			}
			}
		}
	?>
</select>
<span style="font-size:.8em;">Selección Múltiple con tecla CONTROL.</span>
