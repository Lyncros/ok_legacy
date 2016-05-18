<?php


// Define arreglos a devolver
$server->wsdl->addComplexType(
	'ArregloEmprendimiento', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definici�n del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Emprendimiento[]') // Atributos
	),
	'tns:Emprendimiento'
);


// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('Emprendimiento', 'complexType', 'struct', 'all', '',
    array(
		'id_emp'		=> array('name' => 'id_emp'			,'type' => 'xsd:int'),
		'nombre'		=> array('name' => 'nombre'			,'type' => 'xsd:string'),
		'id_ubica'		=> array('name' => 'id_ubica'		,'type' => 'xsd:int'),
		'ubicacion'		=> array('name' => 'ubicacion'		,'type' => 'xsd:string'),
		'descripcion'	=> array('name' => 'descripcion'	,'type' => 'xsd:string'),
		'id_tipo_emp'	=> array('name' => 'id_tipo_emp'	,'type' => 'xsd:int'),
		'estado'	=> array('name' => 'estado'	,'type' => 'xsd:string'),
		'logo'			=> array('name' => 'logo'			,'type' => 'xsd:string'),
		'comentario'	=> array('name' => 'comentario'		,'type' => 'xsd:string'),
		'foto'			=> array('name' => 'foto'			,'type' => 'xsd:string'),
		'goglat'		=> array('name' => 'goglat'			,'type' => 'xsd:double'),
		'goglong'		=> array('name' => 'goglong'		,'type' => 'xsd:double')
    )
);

// Define metodo del Webservice Para lista por Filtro

function ListarEmprendimientos($id_ubica,$id_tipo_emp,$estado,$pagina,$activas){
	require_once("clases/class.emprendimientoBSN.php");
	$prop = new EmprendimientoBSN();
	$colec=$prop->cargaColeccionFiltro($id_ubica,$id_tipo_emp,$estado,$activas,$pagina);
	return $colec;
}



$server->register(
	'ListarEmprendimientos',   
    array(
    	'id_ubica' 		=> 'xsd:int',
    	'id_tipo_emp'	=> 'xsd:string',
    	'estado'	=> 'xsd:string',
    	'pagina'		=> 'xsd:int',
    	'activas'		=> 'xsd:int'
    	),           // Parametros de Entrada
    array('return' => 'tns:ArregloEmprendimiento')   //Datos de Salida
);


// Define metodo del Webservice para ver los datos de una propiedad

function Emprendimiento($id_emp){
	require_once("clases/class.emprendimientoBSN.php");
	$prop = new EmprendimientoBSN();
	$prop->cargaById($id_emp);
	$colec=$prop->getObjetoView();
    $result = array(
		'id_emp'		=> $colec['id_emp'],
		'nombre'		=> $colec['nombre'],
		'id_ubica'		=> $colec['id_ubica'],
		'ubicacion'		=> $colec['ubicacion'],
		'descripcion'	=> $colec['descripcion'],
		'id_tipo_emp'	=> $colec['id_tipo_emp'],
		'estado'	=> $colec['estado'],
		'logo'			=> $colec['logo'],
		'comentario'	=> $colec['comentario'],
		'foto'			=> $colec['foto'],
		'goglat'		=> $colec['goglat'],
		'goglong'		=> $colec['goglong']
    );
	return $result;
}

$server->register(
	'Emprendimiento',   
    array('id_emp'=> 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:Emprendimiento')   //Datos de Salida
);

?>
