<?php

require_once('lib-nusoap/nusoap.php');

$server = new soap_server();

$ns="http://okeefe.com/okeefe/webservice";
//$ns="http://www.zgroupsa.com.ar/achaval/webservice"; // espacio de nombres; Sitio donde estara alojado el web service
$server->configurewsdl('CRMPropWebService'); //nombre del web service
$server->wsdl->schematargetnamespace=$ns;

include_once("metodos/metodo.Ubicacionpropiedad.php");
//include_once("metodos/metodo.Localidad.php");
//include_once("metodos/metodo.Zona.php");
include_once("metodos/metodo.TipoProp.php");
include_once("metodos/metodo.TipoEmp.php");
include_once("metodos/metodo.ListaPropiedades.php");
//include_once("metodos/metodo.ListaPropiedadesFull.php");
include_once("metodos/metodo.DatosPropiedad.php");
include_once("metodos/metodo.FotosPropiedad.php");
include_once("metodos/metodo.ListaEmprendimientos.php");
include_once("metodos/metodo.FotosEmprendimiento.php");
include_once("metodos/metodo.DatosEmprendimiento.php");
include_once("metodos/metodo.Crmbuscador.php");
include_once("metodos/metodo.Casoexito.php");

/******PROCESA LA SOLICITUD Y DEVUELVE LA RESPUESTA*******/
$input = (isset($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : implode("\r\n", file('php://input'));
$server->service($input);
exit;
?>
