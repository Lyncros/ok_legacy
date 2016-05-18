<?php


// Define arreglos a devolver
$server->wsdl->addComplexType(
	'ArregloFotosPropiedad', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definición del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:FotosPropiedad[]') // Atributos
	),
	'tns:FotosPropiedad'
);


// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('FotosPropiedad', 'complexType', 'struct', 'all', '',
    array(
		'id_foto'			=> array('name' => 'id_foto'		,'type' => 'xsd:int'),
		'id_prop'			=> array('name' => 'id_prop'		,'type' => 'xsd:int'),
		'foto'				=> array('name' => 'foto'			,'type' => 'xsd:string'),
		'posicion'			=> array('name' => 'posicion'		,'type' => 'xsd:int')
    )
);

// Define metodo del Webservice Para lista por Filtro

function ListarFotosPropiedad($id_prop){
	require_once("clases/class.fotoBSN.php");
	require_once("generic_class/class.cargaConfiguracion.php");
	$conf=new CargaConfiguracion();
	$path=$conf->leeParametro('path_fotos');
	$foto = new FotoBSN();
	$colec=$foto->cargaColeccionPublicasFormByPropiedad($id_prop);
	$retorno=array();
	foreach ($colec as $reg){
		$retorno[]=array(
						'id_foto'	=> $reg['id_foto'],
						'id_prop'	=> $reg['id_prop'],
						'foto'		=> $reg['hfoto'],
						'posicion'	=> $reg['posicion']
						);
	}
	return $retorno;
}

$server->register(
	'ListarFotosPropiedad',   
    array('id_prop'=> 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:ArregloFotosPropiedad')   //Datos de Salida
);

?>
