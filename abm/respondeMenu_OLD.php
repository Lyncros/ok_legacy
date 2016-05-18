<?php
session_start();
ob_start();

include_once("generic_class/class.cargaConfiguracion.php");
include_once("generic_class/class.menu.php");

//echo $_POST['opcion'] . " - " . $_POST['dia'] . " - " . $_POST['mes'] . " - " . $_POST['anio'];

$conf=new CargaConfiguracion();
$menu=new Menu();

$URL=$conf->leeParametro("particular"); // lee URL del Sitio

//print_r($_POST);
//die();

// lee opcion del menu donde registro la accion seleccionada del menu
if (isset($_POST['opcion']) && is_numeric($_POST['opcion'])){
	$_SESSION['opcionMenu']=$_POST['opcion'];
}
$seteo=0;
if (isset($_POST['id'])){
	$op='i=' . $_POST['id'];
	$seteo=1;
}
if (isset($_POST['id_tipo_prop'])){
	$op='i=' . $_POST['id_tipo_prop'];
	$seteo=1;
}
if (isset($_POST['id_prop'])){
	$op='i=' . $_POST['id_prop'];
	$seteo=1;
}
if (isset($_POST['id_tipo_carac'])){
	$op='i=' . $_POST['id_tipo_carac'];
	$seteo=1;
}
if (isset($_POST['id_carac'])){
	$op='i=' . $_POST['id_carac'];
	$seteo=1;
}
if (isset($_POST['id_zona'])){
	$op='i=' . $_POST['id_zona'];
	$seteo=1;
}
if (isset($_POST['id_loca'])){
	$op='i=' . $_POST['id_loca'];
	$seteo=1;
}
if($seteo==0){
	$op='i=';
}


/*
if(isset($_POST['id_propiedad']) ){
	$_SESSION['id_propiedad']=$_POST['id_propiedad'];
}
*/

tomarAccion($_SESSION['opcionMenu'],$op);


function tomarAccion($accion, $op){
	$menu=new Menu();
//	$id_prop=$_SESSION['id_propiedad'];
	
	switch ($accion){
		case 0:
			die();
		case 1:			// Clientes	Administracion	1
			$menu->redireccionURL("index.php?$op");
			break;
		case 2:			// Clientes	Administracion	1
			$menu->redireccionURL("lista_propiedad.php?$op");
			break;
		case 10:			// Clientes	Administracion	1
			$menu->redireccionURL("lista_tipo_prop.php?$op");
			break;
		case 11:			// Propiedades Administracion 2
			$menu->redireccionURL("lista_tipo_carac.php?$op");
			break;
		case 12:			// Propiedades Administracion 2
			$menu->redireccionURL("lista_caracteristica.php?$op");
			break;
		case 21:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_propiedad.php?i=");
			break;
		case 22:			// Edita Propiedad	2
			$menu->redireccionURL("carga_propiedad.php?$op");
			break;
		case 23:			// Borra Propiedades  2
			$menu->redireccionURL("borra_propiedad.php?$op");
			break;
		case 24:			// Borra Propiedades  2
			$menu->redireccionURL("carga_datosprop.php?$op");
			break;
		case 101:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_tipo_prop.php?i=");
			break;
		case 102:			// Edita Propiedad	2
			$menu->redireccionURL("carga_tipo_prop.php?$op");
			break;
		case 103:			// Borra Propiedades  2
			$menu->redireccionURL("borra_tipo_prop.php?$op");
			break;
		case 29:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 109:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 1019:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 1029:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 111:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_tipo_carac.php?i=");
			break;
		case 112:			// Edita Propiedad	2
			$menu->redireccionURL("carga_tipo_carac.php?$op");
			break;
		case 113:			// Borra Propiedades  2
			$menu->redireccionURL("borra_tipo_carac.php?$op");
			break;
		case 114:			// Edita Propiedad	2
			$menu->redireccionURL("subir_tipocarac.php?$op");
			break;
		case 115:			// Borra Propiedades  2
			$menu->redireccionURL("bajar_tipocarac.php?$op");
			break;
		case 119:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 1119:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 1129:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 121:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_caracteristica.php?i=");
			break;
		case 122:			// Edita Propiedad	2
			$menu->redireccionURL("carga_caracteristica.php?$op");
			break;
		case 123:			// Borra Propiedades  2
			$menu->redireccionURL("borra_caracteristica.php?$op");
			break;
		case 124:			// Edita Propiedad	2
			$menu->redireccionURL("subir_carac.php?$op");
			break;
		case 125:			// Borra Propiedades  2
			$menu->redireccionURL("bajar_carac.php?$op");
			break;
		case 129:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 249:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 1219:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 1229:			// Salir Evento  3
			irMenuAnterior();
			break;
		case 13:		//Zona
			$menu->redireccionURL("lista_zona.php?i=");
			break;
		case 131:		// nueva Zona
			$menu->redireccionURL("carga_zona.php?$op");
			break;
		case 132:		//editar Zona
			$menu->redireccionURL("carga_zona.php?$op");
			break;
		case 133:		//borrar Zona
			$menu->redireccionURL("borra_zona.php?$op");
			break;
		case 139:		// salir zona
			irMenuAnterior();
			break;
		case 14:		//localidad
			$menu->redireccionURL("lista_localidad.php?i=");
			break;
		case 141:		// nueva localidad
			$menu->redireccionURL("carga_localidad.php?$op");
			break;
		case 142:		//editar localidad
			$menu->redireccionURL("carga_localidad.php?$op");
			break;
		case 143:		//borrar localidad
			$menu->redireccionURL("borra_localidad.php?$op");
			break;
		case 149:		// salir localidad
			irMenuAnterior();
			break;
		case 9:		// Salir	Salir de la Aplicacion	0
			session_unset();
			session_destroy();
			die("Gracias por utilizar esta aplicación.");

		default:
			if($_SESSION['opcionMenu']!=0){
				irMenuAnterior();
				break;
			} else {
				echo "Sale por default";
				$_SESSION['opcionMenu']=0;
				$menu->redireccionURL("index.php");
			}
			break;
	}
}

function controlSeleccion($_id){
	$retorno=true;
	if($_id == ""){
		$retorno=false;
	}
	return $retorno;
}

function irMenuAnterior(){
	$menu=new Menu();
	$opcion=$menu->menuAnterior($_SESSION['opcionMenu']);
	$_SESSION['opcionMenu']=$opcion;
	if($opcion!=0){
		$menu->redireccionURL("respondeMenu.php");
	} else {
		$menu->redireccionURL("index.php");
	}
	
}

?>