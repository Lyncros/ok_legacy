<?php

// Define arreglos a devolver
$server->wsdl->addComplexType(
	'ArregloCasoExito', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definici�n del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:CasoExito[]') // Atributos
	),
	'tns:CasoExito'
);


// Define estructura interna de los array a devolver
//id_caso,id_elemento,tipo,comentario,publicar,orden
$server->wsdl->addComplexType('CasoExito', 'complexType', 'struct', 'all', '',
    array(
		'id_caso'=> array('name' => 'id_caso','type' => 'xsd:int'),
		'id_elemento'=> array('name' => 'id_elemento','type' => 'xsd:int'),
		'tipo'    => array('name' => 'tipo',    'type' => 'xsd:string'),
        	'comentario'    => array('name' => 'comentario',    'type' => 'xsd:string'),
		'publicar'    => array('name' => 'publicar',    'type' => 'xsd:int'),
		'orden'    => array('name' => 'tiordenpo',    'type' => 'xsd:int')
    )
);

// Define metodos del Webservice
function listarCasosExito($tipo){
	require_once("clases/class.casoexito.php");
	$casoBSN = new CasoexitoBSN();
	$colec=$casoBSN->coleccionCasosexitos($tipo);
	return $colec;
}


// Registra los metodos del Webservice
$server->register(
	'listarCasosExito',   						// Nombre del Metodo
    array('tipo' => 'xsd:string'),           // Parametros de Entrada
    array('return' => 'tns:ArregloCasoExito')   //Datos de Salida
);

?>
