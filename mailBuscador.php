<?php require_once('Connections/config.php'); ?>
<?php
session_start();
if(!isset($_SESSION['ingreso'])){
    $_SESSION['ingreso'] = $_SERVER['HTTP_REFERER'];
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

header('Content-type: text/html; charset=utf-8');
require_once('lib-nusoap/nusoap.php');

//$wsdl="http://localhost/okeefe/webservice/servicioweb.php?wsdl";
$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice

//¿ocurrio error al llamar al web service?
if ($client->fault) { // si
    echo 'No se pudo completar la operación';
    die();
}else { // no
    $error = $client->getError();
    if ($error) { // Hubo algun error
        echo 'Error:' . $error;
    }
}

$zona = $client->call('ListarZonaPrincipal',array(),'');
//print_r($zona);
//die();

$tipoProp = $client->call('ListarTipoProp',array(),'');
//print_r($tipoProp);
//die();

$de = $_POST['email'];
$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];


$mensaje = $deNombre . "<br />";
$mensaje .= $_POST['telefono'] . "<br />";
$mensaje .= $de . "<br />";
$mensaje .= $_POST['consulta'] . "<br /><br />";
$mensaje .= "Busqueda solicitada:<br />";
$busqueda = "Operacion: " . $_POST['opcTipoOper'] . "<br />";
foreach($tipoProp as $tipo){
	if($tipo['id_tipo_prop'] == $_POST['opcTipoProp']){
		$tipoProp = $tipo['tipo_prop'];
	}
}
$busqueda .= "Tipo de propiedad: " . $tipoProp . "<br />";
$localidades = $_POST['opcLocalidad'];
$loca = "";
for($i=0; $i < count($localidades); $i++){
	$loca .= $localidades[$i] . " - ";
}
$busqueda .= "Localidades: ". $loca . "<br />";
if($_POST['opcAmbientes'] != 0){
	$busqueda .= "Ambientes: ". $_POST['opcAmbientes'] . "<br />";
}
if($_POST['opcDespachos'] != 0){
	$busqueda .= "Despachos: ". $_POST['opcDespachos'] . "<br />";
}
if($_POST['opcSupTotal'] != 0){
	$busqueda .= "Cantidad de Ha: ". $_POST['opcSupTotal'] . "<br />";
}
$busqueda .= "Moneda: " .  $_POST['opcMonedaVenta'] . "<br />";
$busqueda .= "Desde: " .  $_POST['desde'] . "<br />";
$busqueda .= "Hasta: " .  $_POST['hasta'] . "<br />";

switch ($_POST['opcTipoProp']){
	case 6:
	case 7:
	case 16:
		$destino="dennis@okeefe.com.ar,felipe.atucha@okeefe.com.ar";
		break;
	case 1:
	case 9:
	case 3:
	case 17:
	case 18:
		$destino="paulo@okeefe.com.ar";
		break;
	case 2:
	case 11:
	case 15:
		$destino="tomas@okeefe.com.ar";
		break;
	default:
		$destino = "info@okeefe.com.ar";
}

$para = $destino;
//$para = "gustavo@zgroupsa.com.ar";
$Subject = 'Solicitud de Busqueda';


//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time()));
//define the headers we want passed. Note that they are separated with \r\n
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "To: " . $para . "\r\n";
$headers .= "From: ".$deNombre." <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
//add boundary string and mime type specification
//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";


$insertSQL = sprintf("INSERT INTO consultas (nombre, apellido, telefono, email, consulta, categoria, destino, acceso) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
				   GetSQLValueString($_POST['nombre'], "text"),
				   GetSQLValueString($_POST['apellido'], "text"),
				   GetSQLValueString($_POST['telefono'], "text"),
				   GetSQLValueString($_POST['email'], "text"),
				   GetSQLValueString($mensaje, "text"),
				   GetSQLValueString("BUSQUEDA ".str_replace('<br />',';',$busqueda), "text"),
				   GetSQLValueString($destino, "text"),
				   GetSQLValueString($_SESSION['ingreso'], "text"));

mysql_select_db($database_config, $config);
$Result1 = mysql_query($insertSQL, $config) or die(mysql_error());

if(mail( $para, $Subject, $mensaje.$busqueda, $headers )){
	//header("location: mailEnviado.html");
	echo "<script>window.location='mailEnviado.html';</script>";
}else{
	echo "Error al enviar";
}
?>
