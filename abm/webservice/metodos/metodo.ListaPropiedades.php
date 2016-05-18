<?php

// Define arreglos a devolver
$server->wsdl->addComplexType(
        'ArregloPropiedad', // Nombre
        'complexType', // Tipo de Clase
        'array', // Tipo de PHP
        '', // definici�n del tipo secuencia(all|sequence|choice)
        'SOAP-ENC:Array', // Restricted Base
        array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Propiedad[]') // Atributos
        ), 'tns:Propiedad'
);

// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('Propiedad', 'complexType', 'struct', 'all', '', array(
    'id_prop' => array('name' => 'id_prop', 'type' => 'xsd:string'),
    'id_ubica' => array('name' => 'id_ubica', 'type' => 'xsd:string'),
//    'id_zona' => array('name' => 'id_zona', 'type' => 'xsd:string'),
//    'id_loca' => array('name' => 'id_loca', 'type' => 'xsd:string'),
    'calle' => array('name' => 'calle', 'type' => 'xsd:string'),
    'nro' => array('name' => 'nro', 'type' => 'xsd:string'),
    'id_tipo_prop' => array('name' => 'id_tipo_prop', 'type' => 'xsd:string'),
    'subtipo_prop' => array('name' => 'subtipo_prop', 'type' => 'xsd:string'),
    'intermediacion' => array('name' => 'intermediacion', 'type' => 'xsd:string'),
    'id_inmo' => array('name' => 'id_inmo', 'type' => 'xsd:string'),
    'operacion' => array('name' => 'operacion', 'type' => 'xsd:string'),
    'piso' => array('name' => 'piso', 'type' => 'xsd:string'),
    'dpto' => array('name' => 'dpto', 'type' => 'xsd:string'),
    'id_cliente' => array('name' => 'id_cliente', 'type' => 'xsd:string'),
    'activa' => array('name' => 'activa', 'type' => 'xsd:string'),
    'id_sucursal' => array('name' => 'id_sucursal', 'type' => 'xsd:string'),
    'id_emp' => array('name' => 'id_emp', 'type' => 'xsd:string'),
    'compartir' => array('name' => 'compartir', 'type' => 'xsd:string'),
    'publicaprecio' => array('name' => 'publicaprecio', 'type' => 'xsd:string'),
    'destacado' => array('name' => 'destacado', 'type' => 'xsd:string'),
    'oportunidad' => array('name' => 'oportunidad', 'type' => 'xsd:string'),
    'publicadir' => array('name' => 'publicadir', 'type' => 'xsd:string'),
    'goglat' => array('name' => 'goglat', 'type' => 'xsd:string'),
    'goglong' => array('name' => 'goglong', 'type' => 'xsd:string'),
    'nombre_zona' => array('name' => 'nombre_zona', 'type' => 'xsd:string'),
    'nombre_loca' => array('name' => 'nombre_loca', 'type' => 'xsd:string'),
    'video' => array('name' => 'video', 'type' => 'xsd:string'),
    'plano1' => array('name' => 'plano1', 'type' => 'xsd:string'),
    'plano2' => array('name' => 'plano2', 'type' => 'xsd:string'),
    'plano3' => array('name' => 'plano3', 'type' => 'xsd:string'),
    'suptot' => array('name' => 'suptot', 'type' => 'xsd:string'),
    'cantamb' => array('name' => 'cantamb', 'type' => 'xsd:string'),
    'monalq' => array('name' => 'monalq', 'type' => 'xsd:string'),
    'valalq' => array('name' => 'valalq', 'type' => 'xsd:string'),
    'monven' => array('name' => 'monven', 'type' => 'xsd:string'),
    'valven' => array('name' => 'valven', 'type' => 'xsd:string')
        )
);

// Define metodo del Webservice Para lista por Filtro

function ListarPropiedades($id_ubica, $id_tipo_prop, $operacion, $id_emp, $pagina, $campo, $orden) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
    $colec = $prop->cargaColeccionFiltroBuscadorMapa(0, '', $id_ubica, $id_tipo_prop, $operacion, $id_emp, '', $pagina, $campo, $orden);

    return $colec;
}

$server->register(
        'ListarPropiedades', array(
    'id_ubica' => 'xsd:int',
    'id_tipo_prop' => 'xsd:int',
    'operacion' => 'xsd:string',
    'id_emp' => 'xsd:int',
    'pagina' => 'xsd:int',
    'campo' => 'xsd:string',
    'orden' => 'xsd:int'
        ), // Parametros de Entrada
        array('return' => 'tns:ArregloPropiedad')   //Datos de Salida
        //    array('return' => 'xsd:string')   //Datos de Salida
);

function listarOportunidades($id_tipo_prop, $operacion) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
    $colec = $prop->cargaColeccionOportunidad($id_tipo_prop, $operacion);

    return $colec;
}

$server->register(
    'listarOportunidades', array(
    'id_tipo_prop' => 'xsd:string',
    'operacion' => 'xsd:string',
        ), // Parametros de Entrada
        array('return' => 'tns:ArregloPropiedad')   //Datos de Salida
        //    array('return' => 'xsd:string')   //Datos de Salida
);

function listarDestacados($id_tipo_prop, $operacion) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
    $colec = $prop->cargaColeccionDestacado($id_tipo_prop, $operacion);

    return $colec;
}

$server->register(
        'listarDestacados', array(
    'id_tipo_prop' => 'xsd:string',
    'operacion' => 'xsd:string',
        ), // Parametros de Entrada
        array('return' => 'tns:ArregloPropiedad')   //Datos de Salida
        //    array('return' => 'xsd:string')   //Datos de Salida
);

function listarNovedades($operacion) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
    $colec = $prop->cargaColeccionNovedad($operacion,1);

    return $colec;
}

$server->register(
        'listarNovedades', array(
    'operacion' => 'xsd:string',
        ), // Parametros de Entrada
        array('return' => 'tns:ArregloPropiedad')   //Datos de Salida
        //    array('return' => 'xsd:string')   //Datos de Salida
);


function ListarPropiedadesFiltro($txtFiltro, $pagina, $campo, $orden) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();

    $txtFiltro = ereg_replace('\\\\\'', '', $txtFiltro);
    $filtro = array();
    $arrayFilt = explode('|', $txtFiltro);
    foreach ($arrayFilt as $dato) {
        $filtro[] = ereg_replace('\'', '', $dato);
    }
    $arrayFiltro = array();
    foreach ($filtro as $valor) {
        $arrayFiltro[] = explode('-', $valor);
    }
    $colec = $prop->cargaColeccionFiltroBuscadorAvanzado($arrayFiltro, $pagina,$campo, $orden);
    //	  $colec=  $txtFiltro;
    return $colec;
}

$server->register(
        'ListarPropiedadesFiltro', array(
    'txtFiltro' => 'xsd:string',
    'pagina' => 'xsd:int',
    'campo' => 'xsd:string',
    'orden' => 'xsd:int'            
        ), // Parametros de Entrada
        array('return' => 'tns:ArregloPropiedad')   //Datos de Salida
        //    array('return' => 'tns:ArregloPropiedad')   //Datos de Salida
        //    array('return' => 'xsd:string')   //Datos de Salida
);

// Define metodo del Webservice para ver los datos de una propiedad

function Propiedad($id_prop) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
    $prop->cargaById($id_prop);
    $colec = $prop->getObjetoView();
    $result = array(
        'id_prop' => $colec['id_prop'],
        'id_ubica' => $colec['id_ubica'],
//        'id_zona' => $colec['id_zona'],
//        'id_loca' => $colec['id_loca'],
        'calle' => $colec['calle'],
        'entre1' => $colec['entre1'],
        'entre2' => $colec['entre2'],
        'nro' => $colec['nro'],
        'descripcion' => $colec['descripcion'],
        'id_tipo_prop' => $colec['id_tipo_prop'],
        'subtipo_prop' => $colec['subtipo_prop'],
        'intermediacion' => $colec['intermediacion'],
        'id_inmo' => $colec['id_inmo'],
        'operacion' => $colec['operacion'],
        'comentario' => $colec['comentario'],
        'video' => $colec['video'],
        'piso' => $colec['piso'],
        'dpto' => $colec['dpto'],
        'id_cliente' => $colec['id_cliente'],
        'goglat' => $colec['goglat'],
        'goglong' => $colec['goglong'],
        'activa' => $colec['activa'],
        'id_sucursal' => $colec['id_sucursal'],
        'id_emp' => $colec['id_emp'],
        'nomedif' => $colec['nomedif'],
        'plano1' => $colec['plano1'],
        'plano2' => $colec['plano2'],
        'plano3' => $colec['plano3'],
        'publicaprecio' => $colec['publicaprecio'],
        'destacado' => $colec['destacado'],
        'oportunidad' => $colec['oportunidad'],
        'publicadir' => $colec['publicadir'],
    	'suptot' => array('name' => 'suptot', 'type' => 'xsd:string'),
        'cantamb' => array('name' => 'cantamb', 'type' => 'xsd:string'),
        'monalq' => array('name' => 'monalq', 'type' => 'xsd:string'),
        'valalq' => array('name' => 'valalq', 'type' => 'xsd:string'),
        'monven' => array('name' => 'monven', 'type' => 'xsd:string'),
        'valven' => array('name' => 'valven', 'type' => 'xsd:string')
    );
    return $result;
}

$server->register(
        'Propiedad', array('id_prop' => 'xsd:int'), // Parametros de Entrada
        array('return' => 'tns:Propiedad')   //Datos de Salida
);

//function cantidadPropiedades($id_zona, $id_loca, $id_tipo_prop, $operacion, $id_emp) {
function cantidadPropiedades($id_ubica, $id_tipo_prop, $operacion, $id_emp) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
//    $colec = $prop->cantidadRegistrosFiltroBuscador(0, '', $id_zona, $id_loca, $id_tipo_prop, $operacion, $id_emp, '');
    $colec = $prop->cantidadRegistrosFiltroBuscador(0, '', $id_ubica, $id_tipo_prop, $operacion, $id_emp, '');
    return $colec;
}

$server->register(
        'cantidadPropiedades', array(
    'id_ubica' => 'xsd:int',
//    'id_zona' => 'xsd:int',
//    'id_loca' => 'xsd:int',
    'id_tipo_prop' => 'xsd:int',
    'operacion' => 'xsd:string',
    'id_emp' => 'xsd:int'
        ), // Parametros de Entrada
        array('return' => 'xsd:int')   //Datos de Salida
);

function cantidadPropiedadesFiltro($txtFiltro) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();

    $txtFiltro = ereg_replace('\\\\\'', '', $txtFiltro);
    $filtro = array();
    $arrayFilt = explode('|', $txtFiltro);
    foreach ($arrayFilt as $dato) {
        $filtro[] = ereg_replace('\'', '', $dato);
    }
    $arrayFiltro = array();
    foreach ($filtro as $valor) {
        $arrayFiltro[] = explode('-', $valor);
    }

    $colec = $prop->cantRegistrosColeccionFiltroBuscadorAvanzado($arrayFiltro);

    return $colec;
}

$server->register(
        'cantidadPropiedadesFiltro', array('txtFiltro' => 'xsd:string'), // Parametros de Entrada
        array('return' => 'xsd:int')   //Datos de Salida
);



//---------
//Listar zona,localidad,calle,nro,piso,depto,id de propiedades
// Define arreglos a devolver
$server->wsdl->addComplexType(
        'CRMDatosUbicacion', // Nombre
        'complexType', // Tipo de Clase
        'array', // Tipo de PHP
        '', // definici�n del tipo secuencia(all|sequence|choice)
        'SOAP-ENC:Array', // Restricted Base
        array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:DatosUbicacion[]') // Atributos
        ), 'tns:DatosUbicacion'
);

// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('DatosUbicacion', 'complexType', 'struct', 'all', '', array(
    'id_prop' => array('name' => 'id_prop', 'type' => 'xsd:string'),
    'calle' => array('name' => 'calle', 'type' => 'xsd:string'),
    'nro' => array('name' => 'nro', 'type' => 'xsd:string'),
    'piso' => array('name' => 'piso', 'type' => 'xsd:string'),
    'dpto' => array('name' => 'dpto', 'type' => 'xsd:string'),
    'nombre_zona' => array('name' => 'nombre_zona', 'type' => 'xsd:string'),
    'nombre_loca' => array('name' => 'nombre_loca', 'type' => 'xsd:string'),
        )
);

// Define metodo del Webservice Para lista Datos
function ListarUbicacionPropiedades() {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
    $colec = $prop->cargaColeccionFiltroBuscadorMapa(0, '', 0, 0, 0, '', 0, '', 0);
    return $colec;
}

$server->register(
        'ListarUbicacionPropiedades', array(), // Parametros de Entrada
        array('return' => 'tns:CRMDatosUbicacion')   //Datos de Salida
);
?>
