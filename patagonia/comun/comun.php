<?
//Base de datos
//Parametros para editar <<<<<
DEFINE(DB_HOST, "localhost");
DEFINE(DB_USER, "okeefe_pat");
DEFINE(DB_PASS, "pat2016");
DEFINE(DB_BASE, "okeefe_pat");

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

//includes
require("class_abm.php");
require("class_db.php");
require("class_paginado.php");
require("class_orderby.php");
require("class_validar.php");
require("funciones_comunes.php");

//conexi�n
$db = new class_db(DB_HOST, DB_USER, DB_PASS, DB_BASE, "utf8");
$db->mostrarErrores = true;
$db->connect();
?>