<?php

$server->wsdl->addComplexType(
	'ArregloZona', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definición del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Zona[]') // Atributos
	),
	'tns:Zona'
);


$server->wsdl->addComplexType('Zona', 'complexType', 'struct', 'all', '',
    array(
		'id_zona'=> array('name' => 'id_zona','type' => 'xsd:int'),
		'nombre_zona'    => array('name' => 'nombre_zona',    'type' => 'xsd:string')
    )
);


function ListarZona(){
	require_once("clases/class.zonaBSN.php");
	$tpBSN = new ZonaBSN();
	$colec=$tpBSN->cargaColeccionForm();
	return $colec;
}
$server->register(
	'ListarZona',   						// Nombre del Metodo
    array(),           // Parametros de Entrada
    array('return' => 'tns:ArregloZona')   //Datos de Salida
);

?>
