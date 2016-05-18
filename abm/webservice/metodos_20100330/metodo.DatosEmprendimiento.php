<?php


// Define arreglos a devolver
$server->wsdl->addComplexType(
	'ArregloDatosEmprendimiento', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definición del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:DatosEmprendimiento[]') // Atributos
	),
	'tns:DatosEmprendimiento'
);


// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('DatosEmprendimiento', 'complexType', 'struct', 'all', '',
    array(
		'id_emp_carac'		=> array('name' => 'id_emp_carac'	,'type' => 'xsd:int'),
		'orden'				=> array('name' => 'orden'			,'type' => 'xsd:int'),
		'id_emp'			=> array('name' => 'id_emp'			,'type' => 'xsd:int'),
		'id_carac'			=> array('name' => 'id_carac'		,'type' => 'xsd:int'),
		'titulo'			=> array('name' => 'titulo'			,'type' => 'xsd:string'),
		'contenido'			=> array('name' => 'contenido'		,'type' => 'xsd:string'),
		'comentario'		=> array('name' => 'comentario'		,'type' => 'xsd:string')
    )
);

// Define metodo del Webservice Para lista por Filtro

function ListarDatosEmprendimiento($id_emp){
	require_once("clases/class.datosempBSN.php");
	$emp = new DatosempBSN();
	$colec=$emp->coleccionCaracteristicasEmp($id_emp,1);
	return $colec;
}

$server->register(
	'ListarDatosEmprendimiento',   
    array('id_emp'=> 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:ArregloDatosEmprendimiento')   //Datos de Salida
);

?>
