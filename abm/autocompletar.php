<?php
include_once("generic_class/class.cargaConfiguracion.php");

$conf = CargaConfiguracion::getInstance();

$db_host = $conf->leeParametro('host');
$db_user = $conf->leeParametro('user');
$db_pass = $conf->leeParametro('dbpass');
$db_db = $conf->leeParametro('name');

// Datebase varibles
//$db_host = "localhost";
//$db_user = "root";
//$db_pass = "ACHabm10";

// Esstablish connect to MySQL database
$con = mysql_connect( $db_host, $db_user, $db_pass);
if(!$con)
    die('Could not connect: ' . mysql_error() );

mysql_select_db($db_db, $con);

if($_GET["arg"] == 1) {
    $campo = "id_prop";
}else {
    $campo = "calle";
}

$query = "SELECT ".$campo." FROM propiedad WHERE ".$campo." LIKE '%".$_GET['q']."%' GROUP BY " . $campo . " LIMIT 10;";
$result = mysql_query($query);
//echo $query;
$output_items = array();
while($row = mysql_fetch_array($result)){
    $output_items[] = $row[$campo];
}

print(implode("\n", $output_items));

mysql_close($con);



?>
