<?php

$server->wsdl->addComplexType(
	'ArregloTipoEmp', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definición del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:TipoEmp[]') // Atributos
	),
	'tns:TipoEmp'
);


$server->wsdl->addComplexType('TipoEmp', 'complexType', 'struct', 'all', '',
    array(
		'id_tipo_emp'=> array('name' => 'id_tipo_emp','type' => 'xsd:int'),
		'tipo_emp'    => array('name' => 'tipo_emp',    'type' => 'xsd:string')
    )
);


function ListarTipoEmp(){
	require_once("clases/class.tipo_empBSN.php");
	$tpBSN = new Tipo_empBSN();
	$colec=$tpBSN->cargaColeccionForm();
	return $colec;
}

$server->register(
	'ListarTipoEmp',   						// Nombre del Metodo
    array(),           // Parametros de Entrada
    array('return' => 'tns:ArregloTipoEmp')   //Datos de Salida
);
?>
