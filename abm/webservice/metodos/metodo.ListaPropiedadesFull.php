<?php


// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('PropiedadFull', 'complexType', 'struct', 'all', '',
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
		'caracteristica'=> array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:DatosPropiedad[]')
    )
);


// Define metodo del Webservice para ver los datos de una propiedad

function PropiedadFull($id_prop){
	require_once("clases/class.propiedadBSN.php");
	$prop = new PropiedadBSN();
	$prop->cargaById($id_prop);
	$colec=$prop->getObjetoView();
	$arraycarac=ListarDatosPropiedad($id_prop);
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
		'nomedif'		=> $colec['nomedif'],
		'caracteristica'=> $arraycarac
    );
	return $result;
}


$server->register(
	'PropiedadFull',   
    array('id_prop'=> 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:PropiedadFull')   //Datos de Salida
);

?>
