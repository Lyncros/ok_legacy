<?php
// Define arreglos a devolver
$server->wsdl->addComplexType(
	'ArregloDatosPropiedad', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definici�n del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:DatosProp[]') // Atributos
	),
	'tns:DatosProp'
);

// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('DatosProp', 'complexType', 'struct', 'all', '',
    array(
		'id_prop_carac'		=> array('name' => 'id_prop_carac'	,'type' => 'xsd:int'),
    	'id_tipo'			=> array('name' => 'id_tipo'		,'type' => 'xsd:int'),
		'orden_tipo'		=> array('name' => 'orden_tipo'		,'type' => 'xsd:int'),
		'orden'				=> array('name' => 'orden'			,'type' => 'xsd:int'),
		'id_prop'			=> array('name' => 'id_prop'		,'type' => 'xsd:int'),
		'id_carac'			=> array('name' => 'id_carac'		,'type' => 'xsd:int'),
		'tipo_carac'		=> array('name' => 'tipo_carac'		,'type' => 'xsd:string'),
		'titulo'			=> array('name' => 'titulo'			,'type' => 'xsd:string'),
		'contenido'			=> array('name' => 'contenido'		,'type' => 'xsd:string'),
		'comentario'		=> array('name' => 'comentario'		,'type' => 'xsd:string')
    )
);


function ListarDatosPropiedad($id_prop){
	require_once("clases/class.datospropBSN.php");
	$prop = new DatospropBSN();
	$colec=$prop->coleccionCaracteristicasProp($id_prop,1);
	return $colec;
}

function DatosConjuntoPropiedades($inprop,$incarac){
	require_once("clases/class.datospropBSN.php");
	$prop = new DatospropBSN();
	$colec=$prop->coleccionCaracteristicasByGrupoProp($inprop,$incarac);
	return $colec;
}

$server->register(
	'DatosConjuntoPropiedades',   
    array('inprop'=> 'xsd:string','incarac'=> 'xsd:string'),           // Parametros de Entrada
    array('return' => 'tns:ArregloDatosPropiedad')   //Datos de Salida
);

$server->register(
	'ListarDatosPropiedad',   
    array('id_prop'=> 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:ArregloDatosPropiedad')   //Datos de Salida
);
?>
