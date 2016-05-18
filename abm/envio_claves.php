<?php
include_once("generic_class/class.cargaConfiguracion.php");

$conf = CargaConfiguracion::getInstance();

$db_host = $conf->leeParametro('host');
$db_user = $conf->leeParametro('user');
$db_pass = $conf->leeParametro('dbpass');
$db_db = $conf->leeParametro('name');

// Esstablish connect to MySQL database
$con = mysql_connect( $db_host, $db_user, $db_pass);
if(!$con)
    die('Could not connect: ' . mysql_error() );

mysql_select_db($db_db, $con);
/*
if($_GET["arg"] == 1) {
    $campo = "id_prop";
}else {
    $campo = "calle";
}
*/


//$query = "SELECT * FROM usuarios WHERE email='gustavo@zgroupsa.com.ar';";
$query = "SELECT * FROM usuarios;";
$result = mysql_query($query);
//echo $query;
while($row = mysql_fetch_array($result)){
	if($row['email'] != ""){
		echo $row['email']."<br />";
		$de = 'info@okeefe.com.ar';
		$deNombre = 'Okeefe Propiedades';


		$mensaje = "Usuario y password para ingresar al ABM: <br />";
		$mensaje .= "Usuario: " . $row['usuario'] . "<br />";
		$mensaje .= "Password: " . $row['clave'] . "<br />";

		$Subject = 'Usuario y password para ingresar al ABM';

		$para = $row['email'];

		//so we use the MD5 algorithm to generate a random hash
		$random_hash = md5(date('r', time()));
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "To: " . $para . "\r\n";
		$headers .= "From: ".$deNombre." <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
		//add boundary string and mime type specification
		//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
		mail( $para, $Subject, $mensaje, $headers );
	}
}
echo "Listo!!";

mysql_close($con);

?>
