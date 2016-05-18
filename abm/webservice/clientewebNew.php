<?php
require_once('lib-nusoap/nusoap.php');

//$wsdl="http://okeefe.com/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos

$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice  

/*
 // Metodos de UBICACIONPROPIEDAD
$param2=array();
  $prop2 = $client->call('ListarZonaPrincipal',$param2,'');
  print_r($prop2);

  $param3=array('id_padre'=>6134);
  $prop3 = $client->call('ListarZonasDependientes',$param3,'');
  print_r($prop3);
*/
/*
$param5 = array('id_ubica'=>0,'id_tipo_emp'=>'16,18','estado'=>'','activas'=>0,'pagina'=>0);
$prop5=$client->call('ListarEmprendimientos',$param5);
print_r( $prop5);
*/
/*
 // Metodos ListaPropiedades
$param4 = array('id_ubica'=>0,'id_tipo_prop'=>0,'operacion'=>'','id_emp'=>0);
$prop4=$client->call('cantidadPropiedades',$param4);
echo $prop4;

$param5 = array('id_ubica'=>0,'id_tipo_prop'=>0,'operacion'=>'','id_emp'=>0,'pagina'=>0);
$prop5=$client->call('ListarPropiedades',$param5);
print_r( $prop5);

*/
/*    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
    $colec = $prop->cargaColeccionOportunidad($id_tipo_prop, $operacion);
    print_r($colec);
*/

/*
$param6 = array('id_tipo_prop'=>0,'operacion'=>'');
$prop6=$client->call('listarOportunidades',$param6);
print_r( $prop6);
*/
/*
	require_once("clases/class.ubicacionesActivas.php");
	$ubBSN = UbicacionesActivas::getInstance();
	$colec=$ubBSN->cargaColeccionPrincipalActivas('Venta',9);
	$arrayRet=array();
	foreach ($colec as $registro) {
		$arrayRet[]=array('id_ubica'=>$registro['id_ubica'],'id_padre'=>$registro['id_padre'],'nombre_ubicacion'=>$registro['nombre_ubicacion']);
	}
	print_r($arrayRet);
 * 
 */
$paramP = array('operacion'=>"'Venta'");
$prop = $client->call('listarNovedades', $paramP, '');


/*
$param6 = array('operacion'=>'Venta','tipo_prop'=>9);
$prop6=$client->call('ListarZonaPrincipalActiva',$param6);
print_r( $prop6);
*/
/*
$id_padre=3979;
$operacion='Venta';
$tipo_prop=9;
$colec=array();
	if(is_int($id_padre)  || is_numeric($id_padre)){
		$colec=  ZonasActivasDependientes($id_padre,$operacion,$tipo_prop,$colec);
	}else{
		$colec=array('id_ubica'=>0,'id_padre'=>0,'nombre_ubicacion'=>'');
	}
	print_r($colec); 
*/
/*
$param7 = array('id_padre'=>3979,'operacion'=>'Venta','tipo_prop'=>9);
$prop7=$client->call('ListarZonasDependientesActivas',$param7);
print_r( $prop7);

$param8 = array('id_padre'=>588,'operacion'=>'Venta','tipo_prop'=>9);
$prop8=$client->call('ListarZonasDependientesActivas',$param8);
print_r( $prop8);

$param9 = array('id_padre'=>5971,'operacion'=>'Venta','tipo_prop'=>9);
$prop9=$client->call('ListarZonasDependientesActivas',$param9);
print_r( $prop9);
*/
/*
$param6=array();
$prop6=$client->call('ListarTipoProp',$param6);
print_r( $prop6);
*/

if ($client->fault)
{
  echo '<h2>Error: La peticion contiene un contenido SOAP invalido</h2>'.
  '<pre>'; print_r($resultado); echo '</pre>';
}else{
  $err = $client->getError();
  if ($err){
    echo '<h2>Error Revento aca</h2><pre>' . $err . '</pre>';
  } else  {
  	
	print_r($response);
  }
}


function ZonasActivasDependientes($id_padre,$operacion,$tipo_prop,$arrayRet){
	require_once("clases/class.ubicacionesActivas.php");
	$ubBSN = UbicacionesActivas::getInstance();
	if(is_int($id_padre)  || is_numeric($id_padre)){
		$colecHijos=$tpBSN->cargaColeccionHijosActivas($id_padre,$operacion,$tipo_prop);
		for($ind=0; $ind < sizeof($colecHijos);$ind++){
			$arrayRet[]=array('id_ubica'=>$colecHijos[$ind]['id_ubica'],'id_padre'=>$colecHijos[$ind]['id_padre'],'nombre_ubicacion'=>$colecHijos[$ind]['nombre_ubicacion']);
			$arrayRet=ZonasActivasDependientes($colecHijos[$ind]['id_ubica'],$operacion,$tipo_prop,$arrayRet);
		}
	}
	return $arrayRet;
}

?>
