<?php
require_once('lib-nusoap/nusoap.php');

//$wsdl="http://www.achavalcornejonordelta.com/propiedades/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos

$wsdl="http://abm.achavalcornejo.com/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos

//$wsdl="http://www.zgroupsa.com.ar/achaval/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice  

//echo $wsdl."<br>";
//$param=array('estado'=>'1'); //pasando parametros de entrada que seran pasados hacia el metodo

/*
$productos = $client->call('ListarTipoProp', array(),''); //llamando al metodo y recuperando el array de productos en una variable

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

if(is_array($productos)){ //si hay valores en el array
	for($i=0;$i<count($productos);$i++){
		echo $productos[$i]['id_tipo_prop'].'  '.$productos[$i]['tipo_prop'].'<br>';
	}
}else{
	echo 'No hay productos';
}

echo "<br>";
$tpemp = $client->call('ListarTipoEmp',array(),'');
	for($i=0;$i<count($tpemp);$i++){
		echo $tpemp[$i]['id_tipo_emp'].' - '.$tpemp[$i]['tipo_emp'].'<br>';
	}

	
echo "<br>";
$zona = $client->call('ListarZona',array(),'');
	for($i=0;$i<count($zona);$i++){
		echo $zona[$i]['id_zona'].' - '.$zona[$i]['nombre_zona'].'<br>';
	}
                              
*/
/*
echo "<br>";
$param=array('id_zona'=>3);
$loca = $client->call('ListarLocalidad',$param,'');
print_r($loca);
	for($i=0;$i<count($loca);$i++){
		echo $loca[$i]['id_zona'].' - '.$loca[$i]['id_loca'].' - '.$loca[$i]['nombre_loca'].'<br>';
	}
 */
	
/*
echo "<br>";
$param=array('id_prop'=>1826);
$loca = $client->call('Propiedad',$param,'');
print_r($loca);
	*/
	

/*
echo "<br>";
$param=array('id_prop'=>4);
$loca = $client->call('PropiedadFull',$param,'');
print_r($loca);
	*/
	
/*	
	require_once("clases/class.datospropBSN.php");
	$prop = new DatospropBSN();
	$colec=$prop->coleccionCaracteristicasProp(16);
//	$colec=$prop->getObjetoView();
	print_r($colec);
*/

/*
	require_once("clases/class.fotoBSN.php");
	$foto = new FotoBSN();
	$colec=$foto->cargaColeccionFormByPropiedad(10);
	print_r($colec);
	

echo "<br>";
$param=array('id_prop'=>10);
$loca = $client->call('ListarFotosPropiedad',$param,'');
print_r($loca);
*/	
/*
require_once("clases/class.emprendimientoBSN.php");
$emp=new EmprendimientoBSN();
$emp->cargaById(21);
print_r($emp->getObjetoView());
*/

/*
echo "<br>";
$param=array('id_zona'=>0,'id_loca'=>0,'id_tipo_emp'=>0);
$loca = $client->call('ListarEmprendimientos',$param,'');
print_r($loca);
*/

/*
echo "Previo <br>";
	require_once("clases/class.propiedadBSN.php");
	$prop = new PropiedadBSN();
	//$codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina=1,$campo='',$orden=0
	$colec=$prop->cargaColeccionFiltroBuscadorMapa(0,'',30,0,0,'',0,'',1)	;
	print_r($colec);
echo "<br>";
 */
 
 /*
echo "Cant <br>";//$id_zona,$id_loca,$id_tipo_prop,$operacion,$id_emp
$param=array('id_zona'=>30,'id_loca'=>53,'id_tipo_prop'=>0,'operacion'=>'','id_emp'=>0);
$loca = $client->call('cantidadPropiedades',$param,'');
print_r($loca);
*/

/*
echo "<br>";//$id_zona,$id_loca,$id_tipo_prop,$operacion,$id_emp
echo "Listar Propiedades <br />";
//$param=array('id_zona'=>30,'id_loca'=>53,'id_tipo_prop'=>0,'operacion'=>'Venta','id_emp'=>0,'pagina'=>1); 
$param=array('id_zona'=>3,'id_loca'=>0,'id_tipo_prop'=>9,'operacion'=>"'Venta'",'id_emp'=>0,'pagina'=>1);
$loca = $client->call('ListarPropiedades',$param,'');
print_r($loca);
*/

/*
echo "<br>";//$id_zona,$id_loca,$id_tipo_prop,$operacion,$id_emp
echo "Ubicacion Propiedades <br />";
$param=array();
$loca = $client->call('ListarUbicacionPropiedades',$param,'');
print_r($loca);
*/

/*
echo "<br>";//$id_zona,$id_loca,$id_tipo_prop,$operacion,$id_emp
echo "Datos Propiedades CRM <br />";
$param=array('idcrm'=>'1298398973184');
$loca = $client->call('datosPropiedadesCRM',$param,'');
print_r($loca);
 */
 
 
 
/*
$param=array('id_emp'=>22);
$loca = $client->call('Emprendimiento',$param,'');
print_r($loca);
*/

                                                      /*
$param=array('id_emp'=>34);
$loca = $client->call('ListarDatosEmprendimiento',$param,'');
print_r($loca);
                  
                                                        */
/*
echo "Datos prop <br>";
$param=array('id_prop'=>190);
$loca = $client->call('ListarDatosPropiedad',$param,'');
print_r($loca);
*/  
  
/*
//$param=array('id_emp_carac'=>106);
$param=array('id_emp_carac'=>106);
$loca = $client->call('ListarFotosCaracteristicaEmp',$param,'');
print_r($loca);
echo "<br>";
*/
/*
echo "<br>";
echo "Conjunto fotos emprendimiento<br>";
$param=array('id_emp'=>83);
$loca = $client->call('ListarFotosEmprendimiento',$param,'');
print_r($loca);
echo "<br>";


echo "<br>";
echo "Conjunto propiedades<br>";                             
$param=array('inprop'=>'177,190','incarac'=>'42,161,164,165,166,198,208,255,257');
$loca = $client->call('DatosConjuntoPropiedades',$param,'');
print_r($loca);
*/             


                    
//$txtFiltro0="'opcZona-30|opcTipoOper-Venta'";
$txtFiltro="'opcTipoProp-9'";
echo $txtFiltro."<br>";
                                                   /*
	require_once("clases/class.propiedadBSN.php");
	$prop = new PropiedadBSN();
 
  $txtFiltro=ereg_replace('\\\\\'','',$txtFiltro);
  $filtro=array();
  $arrayFilt=explode('|',$txtFiltro);
  foreach($arrayFilt as $dato){
    $filtro[]=ereg_replace('\'','',$dato);
  }
  $arrayFiltro=array();
  foreach($filtro as $valor){
    $arrayFiltro[]=explode('-',$valor);
  }  
  $colec=$prop->cantRegistrosColeccionFiltroBuscadorAvanzado($arrayFiltro)	;
	
	echo $colec."<br>";
*/

//  $txtFiltro=ereg_replace('\'','',$txtFiltro0);
	$param1=array('txtFiltro'=>$txtFiltro);
  $prop1 = $client->call('cantidadPropiedadesFiltro',$param1,'');
  print_r($prop1);
	//	echo "cant: $cant <br>";


echo "<br>";
		$param2=array('txtFiltro'=>$txtFiltro,'pagina'=>1);
		$prop = $client->call('ListarPropiedadesFiltro',$param2,'');
            print_r($prop);

echo "<br>";
  $param3=array('id_prop'=>1820);
		$prop3 = $client->call('Propiedad',$param3,'');
            print_r($prop3);

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
?>
