<?php

$server->wsdl->addComplexType(
	'ArregloLocalidad', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definición del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Localidad[]') // Atributos
	),
	'tns:Localidad'
);

$server->wsdl->addComplexType('Localidad', 'complexType', 'struct', 'all', '',
    array(
		'id_loca'=> array('name' => 'id_loca','type' => 'xsd:int'),
		'id_zona'=> array('name' => 'id_zona','type' => 'xsd:int'),
		'nombre_loca'    => array('name' => 'nombre_loca',    'type' => 'xsd:string')
    )
);


function ListarLocalidad($id_zona){
	require_once("clases/class.localidad.php");
	require_once("clases/class.localidadBSN.php");
	$loc = new Localidad();
	$loc->setId_zona($id_zona);
	$tpBSN = new LocalidadBSN($loc);
	$colec=$tpBSN->cargaColeccionByZona($id_zona);
	return $colec;
}



$server->register(
	'ListarLocalidad',   
    array('id_zona' => 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:ArregloLocalidad')   //Datos de Salida
);

?>
