<?php


// Define arreglos a devolver
$server->wsdl->addComplexType(
	'ArregloFotosEmp', 				// Nombre
	'complexType', 					// Tipo de Clase
	'array', 						// Tipo de PHP
	'', 							// definición del tipo secuencia(all|sequence|choice)
	'SOAP-ENC:Array', 				// Restricted Base
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:FotosEmprendimiento[]') // Atributos
	),
	'tns:FotosEmprendimiento'
);


// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('FotosEmprendimiento', 'complexType', 'struct', 'all', '',
    array(
		'id_foto'			=> array('name' => 'id_foto'		,'type' => 'xsd:int'),
		'id_emp_carac'		=> array('name' => 'id_emp_carac'	,'type' => 'xsd:int'),
		'foto'				=> array('name' => 'foto'			,'type' => 'xsd:string'),
		'posicion'			=> array('name' => 'posicion'		,'type' => 'xsd:int')
    )
);


// Define metodo del Webservice Para lista por Filtro

function ListarFotosCaracteristicaEmp($id_emp_carac){
	require_once("generic_class/class.PGDAO.php");
	$dao = new PGDAO();
	$resultado=$dao->execSql("SELECT * FROM ip045603_ach0.fotosemp WHERE id_emp_carac=$id_emp_carac ORDER BY posicion");
	$fetch="mysql_fetch_array";
	while ($row = $fetch($resultado)){
		$retorno[]=array(
						'id_foto'		=> $row['id_foto'],
						'id_emp_carac'	=> $row['id_emp_carac'],
						'foto'			=> $row['foto'],
						'posicion'		=> $row['posicion']
						);
	}
	if(sizeof($retorno)==0){
		$retorno[]=array(
						'id_foto'		=> 0,
						'id_emp_carac'	=> 0,
						'foto'			=> '',
						'posicion'		=> 0
						);
		
	}
	return $retorno;
}

$server->register(
	'ListarFotosCaracteristicaEmp',   
    array('id_emp_carac'=> 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:ArregloFotosEmp')   //Datos de Salida
);

function ListarFotosEmprendimiento($id_emp){
	require_once("generic_class/class.PGDAO.php");
	$dao = new PGDAO();
	$sql = "SELECT f.id_foto,e.id_emp,f.foto,f.posicion,f.id_emp_carac FROM ip045603_ach0.fotosemp as f ";
	$sql.= " INNER JOIN (SELECT id_emp,id_emp_carac FROM ip045603_ach0.emprendimiento_caracteristicas WHERE id_emp=$id_emp) as e ";
	$sql.= " ON f.id_emp_carac=e.id_emp_carac";
	$resultado=$dao->execSql($sql);
	$fetch="mysql_fetch_array";
	while ($row = $fetch($resultado)){
		$retorno[]=array(
						'id_foto'		=> $row['id_foto'],
						'id_emp_carac'	=> $row['id_emp_carac'],
						'foto'			=> $row['foto'],
						'posicion'		=> $row['posicion']
						);
	}
	if(sizeof($retorno)==0){
		$retorno[]=array(
						'id_foto'		=> 0,
						'id_emp_carac'	=> 0,
						'foto'			=> '',
						'posicion'		=> 0
						);
		
	}
	return $retorno;
}

$server->register(
	'ListarFotosEmprendimiento',   
    array('id_emp'=> 'xsd:int'),           // Parametros de Entrada
    array('return' => 'tns:ArregloFotosEmp')   //Datos de Salida
);


?>
