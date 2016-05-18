<?php require_once('Connections/config.php'); ?>
<?php
//print_r($_POST);
//die();
if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$insertSQL = sprintf("INSERT INTO consultas (nombre, apellido, email, categoria) VALUES (%s, %s, %s, %s)",
				   GetSQLValueString($_POST['nombre'], "text"),
				   GetSQLValueString($_POST['apellido'], "text"),
				   GetSQLValueString($_POST['email'], "text"),
				   GetSQLValueString('RECOMENDAR', "text"));

mysql_select_db($database_config, $config);
$Result1 = mysql_query($insertSQL, $config) or die(mysql_error());

header('Content-type: text/html; charset=utf-8');

$mensaje = $_POST['nombre'] . ' ' .$_POST['apellido'] . "<br />";
if($_POST['tipo'] == "empre"){
	$mensaje .= "Te recomienda visitar este emprendimiento!!!<br /><br />";
	$mensaje .= "Para visitarla haga click <a href=\"http://www.okeefe.com.ar/detalleEmprendimiento.php?i=".intval($_POST['id'])."\">aqu&iacute;</a><br />";	
	$Subject = 'Te recominedo visitar este Emprendimiento';
}else{
	$mensaje .= "Te recomienda visitar esta propiedad!!!<br /><br />";
	$mensaje .= "Para visitarla haga click <a href=\"http://www.okeefe.com.ar/detalleProp.php?codigo=".intval($_POST['id'])."\">aqu&iacute;</a><br />";	
	$Subject = 'Te recominedo visitar esta Propiedad';
}

$de = $_POST['email'];
$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];

$paramail = explode(';', $_POST['para']);    

if(count($paramail) > 1){
	for($i=0; $i <= count($paramail); $i++){
		$para = $paramail[$i].";";
	}
}else{
	$para = $_POST['para'];
}

//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time()));
//define the headers we want passed. Note that they are separated with \r\n
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "To: " . $para . "\r\n";
$headers .= "From: ".$deNombre." <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
//add boundary string and mime type specification
//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";


if(mail( $para, $Subject, $mensaje, $headers )){
	header("location:mailEnviado.html");
}else{
	echo "Erroro al enviar";
}
?>
