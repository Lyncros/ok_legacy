<?php


// Define arreglos a devolver
$server->wsdl->addComplexType(
	'ArregloPropiedad', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definición del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Propiedad[]') // Atributos
	),
	'tns:Propiedad'
);


// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('Propiedad', 'complexType', 'struct', 'all', '',
    array(
		'id_prop'		=> array('name' => 'id_prop'		,'type' => 'xsd:int'),
		'id_zona'		=> array('name' => 'id_zona'		,'type' => 'xsd:int'),
		'id_loca'		=> array('name' => 'id_loca'		,'type' => 'xsd:int'),
		'calle'			=> array('name' => 'calle'			,'type' => 'xsd:string'),
		'entre1'		=> array('name' => 'entre1'			,'type' => 'xsd:string'),
		'entre2'		=> array('name' => 'entre2'			,'type' => 'xsd:string'),
		'nro'			=> array('name' => 'nro'			,'type' => 'xsd:string'),
		'descripcion'	=> array('name' => 'descripcion'	,'type' => 'xsd:string'),
		'id_tipo_prop'	=> array('name' => 'id_tipo_prop'	,'type' => 'xsd:int'),
		'subtipo_prop'	=> array('name' => 'subtipo_prop'	,'type' => 'xsd:string'),
		'intermediacion'=> array('name' => 'intermediacion'	,'type' => 'xsd:string'),
		'id_inmo'		=> array('name' => 'id_inmo'		,'type' => 'xsd:int'),
		'operacion'		=> array('name' => 'operacion'		,'type' => 'xsd:string'),
		'comentario'	=> array('name' => 'comentario'		,'type' => 'xsd:string'),
		'video'			=> array('name' => 'video'			,'type' => 'xsd:string'),
		'piso'			=> array('name' => 'piso'			,'type' => 'xsd:string'),
		'dpto'			=> array('name' => 'dpto'			,'type' => 'xsd:string'),
		'id_cliente'	=> array('name' => 'id_cliente'		,'type' => 'xsd:int'),
		'goglat'		=> array('name' => 'goglat'			,'type' => 'xsd:double'),
		'goglong'		=> array('name' => 'goglong'		,'type' => 'xsd:double'),
		'activa'		=> array('name' => 'activa'			,'type' => 'xsd:int'),
		'id_sucursal'	=> array('name' => 'id_sucursal'	,'type' => 'xsd:string'),
		'id_emp'		=> array('name' => 'id_emp'			,'type' => 'xsd:int'),
		'nomedif'		=> array('name' => 'nomedif'		,'type' => 'xsd:string')
    )
);

// Define metodo del Webservice Para lista por Filtro

function ListarPropiedades($id_zona,$id_loca,$id_tipo_prop,$operacion,$id_emp,$pagina){
	require_once("clases/class.propiedadBSN.php");
	$prop = new PropiedadBSN();
	$colec=$prop->cargaColeccionFiltroBuscador($id_zona,$id_loca,$id_tipo_prop,$operacion,$id_emp,'',$pagina)	;
	return $colec;
}



$server->register(
	'ListarPropiedades',   
    array(
    	'id_zona' 		=> 'xsd:int',
    	'id_loca'		=> 'xsd:int',
    	'id_tipo_prop'	=> 'xsd:int',
    	'operacion'		=> 'xsd:string',
    	'id_emp'		=> 'xsd:int',
		'pagina'		=> 'xsd:int'
    	),           // Parametros de Entrada
    array('return' => 'tns:ArregloPropiedad')   //Datos de Salida
);


// Define metodo del Webservice para ver los datos de una propiedad

function Propiedad($id_prop){
	require_once("clases/class.propiedadBSN.php");
	$prop = new PropiedadBSN();
	$prop->cargaById($id_prop);
	$colec=$prop->getObjetoView();
    $result = array(
		'id_prop'		=> $colec['id_prop'],
		'id_zona'		=> $colec['id_zona'],
		'id_loca'		=> $colec['id_loca'],
		'calle'			=> $colec['calle'],
		'entre1'		=> $colec['entre1'],
		'entre2'		=> $colec['entre2'],
		'nro'			=> $colec['nro'],
		'descripcion'	=> $colec['descripcion'],
		'id_tipo_prop'	=> $colec['id_tipo_prop'],
		'subtipo_prop'	=> $colec['subtipo_prop'],
		'intermediacion'=> $colec['intermediacion'],
		'id_inmo'		=> $colec['id_inmo'],
		'operacion'		=> $colec['operacion'],
		'comentario'	=> $colec['comentario'],
		'video'			=> $colec['video'],
		'piso'			=> $colec['piso'],
		'dpto'			=> $colec['dpto'],
		'id_cliente'	=> $colec['id_cliente'],
		'goglat'		=> $colec['goglat'],
		'goglong'		=> $colec['goglong'],
		'activa'		=> $colec['activa'],
		'id_sucursal'	=> $colec['id_sucursal'],
		'id_emp'		=> $colec['id_emp'],
		'nomedif'		=> $colec['nomedif']
    );
	return $result;
}


$server->register(
	'Propiedad',   
    array('id_prop'=> 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:Propiedad')   //Datos de Salida
);


function cantidadPropiedades($id_zona,$id_loca,$id_tipo_prop,$operacion,$id_emp){
	require_once("clases/class.propiedadBSN.php");
	$prop = new PropiedadBSN();
	$colec=$prop->cantidadRegistrosFiltroBuscador($id_zona,$id_loca,$id_tipo_prop,$operacion,$id_emp,'')	;
	return $colec;
}



$server->register(
	'cantidadPropiedades',   
    array(
    	'id_zona' 		=> 'xsd:int',
    	'id_loca'		=> 'xsd:int',
    	'id_tipo_prop'	=> 'xsd:int',
    	'operacion'		=> 'xsd:string',
    	'id_emp'		=> 'xsd:int'
    	),           // Parametros de Entrada
    array('return' => 'xsd:int')   //Datos de Salida
);


?>
