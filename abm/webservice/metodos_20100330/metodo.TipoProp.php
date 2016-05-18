<?php

// Define arreglos a devolver
$server->wsdl->addComplexType(
	'ArregloTipoProp', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definición del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:TipoProp[]') // Atributos
	),
	'tns:TipoProp'
);


// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('TipoProp', 'complexType', 'struct', 'all', '',
    array(
		'id_tipo_prop'=> array('name' => 'id_tipo_prop','type' => 'xsd:int'),
		'tipo_prop'    => array('name' => 'tipo_prop',    'type' => 'xsd:string')
    )
);

// Define metodos del Webservice
function ListarTipoProp(){
	require_once("clases/class.tipo_propBSN.php");
	$tpBSN = new Tipo_propBSN();
	$colec=$tpBSN->cargaColeccionForm();
	return $colec;
}


// Registra los metodos del Webservice
$server->register(
	'ListarTipoProp',   						// Nombre del Metodo
    array(),           // Parametros de Entrada
    array('return' => 'tns:ArregloTipoProp')   //Datos de Salida
);

?>
