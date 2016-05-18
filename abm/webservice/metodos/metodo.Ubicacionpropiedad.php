<?php

$server->wsdl->addComplexType(
	'ArregloUbicacion', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definici�n del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
array(),
array(
array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Ubicacion[]') // Atributos
),
	'tns:Ubicacion'
);


$server->wsdl->addComplexType('Ubicacion', 'complexType', 'struct', 'all', '',
array(
		'id_ubica'			=> array('name' => 'id_ubica'			,'type' => 'xsd:string'),
		'id_padre'			=> array('name' => 'id_padre'			,'type' => 'xsd:string'),
	    'nombre_ubicacion'  => array('name' => 'nombre_ubicacion'	,'type' => 'xsd:string')
)
);


function ListarZonaPrincipal(){
	require_once("clases/class.ubicacionpropiedadBSN.php");
	$tpBSN = new UbicacionpropiedadBSN();
	$colec=$tpBSN->cargaColeccionPrincipal();
	$arrayRet=array();
	foreach ($colec as $registro) {
		$arrayRet[]=array('id_ubica'=>$registro['id_ubica'],'id_padre'=>$registro['id_padre'],'nombre_ubicacion'=>$registro['nombre_ubicacion']);
	}
	return $arrayRet;
}


function ListarZonasDependientes($id_padre){
	$colec=array();
	if(is_int($id_padre)  || is_numeric($id_padre)){
		$colec=ZonasDependientes($id_padre,$colec);
	}else{
		$colec=array('id_ubica'=>0,'id_padre'=>0,'nombre_ubicacion'=>'');
	}
	return $colec;
}

function ListarZonaPrincipalActiva($operacion,$tipo_prop){
	require_once("clases/class.ubicacionesActivas.php");
	$ubBSN = UbicacionesActivas::getInstance();
	$colec=$ubBSN->cargaColeccionPrincipalActivas($operacion,$tipo_prop);
	$arrayRet=array();
	foreach ($colec as $registro) {
		$arrayRet[]=array('id_ubica'=>$registro['id_ubica'],'id_padre'=>$registro['id_padre'],'nombre_ubicacion'=>$registro['nombre_ubicacion']);
	}
	return $arrayRet;
}

function ListarZonasDependientesActivas($id_padre,$operacion,$tipo_prop){
	$colec=array();
	if(is_int($id_padre)  || is_numeric($id_padre)){
		$colec=  ZonasActivasDependientes($id_padre,$operacion,$tipo_prop,$colec);
	}else{
		$colec=array('id_ubica'=>0,'id_padre'=>0,'nombre_ubicacion'=>'');
	}
	return $colec;
}





function detallaNombreZona($id_ubica,$modo){
    require_once("clases/class.ubicacionpropiedadBSN.php");
    $nombre='';
    $ubiBSN = new UbicacionpropiedadBSN();
    switch ($modo){
        case 'c':
            $nombre=$ubiBSN->armaNombreZonaAbr($id_ubica);
            break;
        case 'l':
            $nombre=$ubiBSN->armaNombreZona($id_ubica);
            break;
        case 'i':
            $nombre=$ubiBSN->armaNombreZonaGMap($id_ubica);
            break;
    }
    return $nombre;
}

function ZonasDependientes($id_padre,$arrayRet){
	require_once("clases/class.ubicacionpropiedadBSN.php");
	$tpBSN = new UbicacionpropiedadBSN();
	if(is_int($id_padre)  || is_numeric($id_padre)){
		$colecHijos=$tpBSN->cargaColeccionHijos($id_padre);
		for($ind=0; $ind < sizeof($colecHijos);$ind++){
			$arrayRet[]=array('id_ubica'=>$colecHijos[$ind]['id_ubica'],'id_padre'=>$colecHijos[$ind]['id_padre'],'nombre_ubicacion'=>$colecHijos[$ind]['nombre_ubicacion']);
			$arrayRet=ZonasDependientes($colecHijos[$ind]['id_ubica'],$arrayRet);
		}
	}
	return $arrayRet;
}

function ZonasActivasDependientes($id_padre,$operacion,$tipo_prop,$arrayRet){
	require_once("clases/class.ubicacionesActivas.php");
	$ubBSN = UbicacionesActivas::getInstance($id_padre);
	if(is_int($id_padre)  || is_numeric($id_padre)){
		$colecHijos=$ubBSN->cargaColeccionHijosActivas($id_padre,$operacion,$tipo_prop);
		for($ind=0; $ind < sizeof($colecHijos);$ind++){
			$arrayRet[]=array('id_ubica'=>$colecHijos[$ind]['id_ubica'],'id_padre'=>$colecHijos[$ind]['id_padre'],'nombre_ubicacion'=>$colecHijos[$ind]['nombre_ubicacion']);
			$arrayRet=ZonasActivasDependientes($colecHijos[$ind]['id_ubica'],$operacion,$tipo_prop,$arrayRet);
		}
	}
	return $arrayRet;
}

$server->register(
	'ListarZonaPrincipal',   						// Nombre del Metodo
array(),           // Parametros de Entrada
array('return' => 'tns:ArregloUbicacion')   //Datos de Salida
);



$server->register(
	'ListarZonasDependientes',   						// Nombre del Metodo
array('id_padre'=> 'xsd:int'),           // Parametros de Entrada
array('return' => 'tns:ArregloUbicacion')   //Datos de Salida
);

$server->register(
	'ListarZonaPrincipalActiva',   						// Nombre del Metodo
array('operacion'=>'xsd:string','tipo_prop'=> 'xsd:int'),           // Parametros de Entrada
array('return' => 'tns:ArregloUbicacion')   //Datos de Salida
);



$server->register(
	'ListarZonasDependientesActivas',   						// Nombre del Metodo
array('id_padre'=> 'xsd:int','operacion'=>'xsd:string','tipo_prop'=> 'xsd:int'),           // Parametros de Entrada
array('return' => 'tns:ArregloUbicacion')   //Datos de Salida
);


$server->register(
	'detallaNombreZona',   						// Nombre del Metodo
array('id_ubica'=> 'xsd:int','modo'=>'xsd:string'),           // Parametros de Entrada
array('return' => 'xsd:string')   //Datos de Salida
);


?>