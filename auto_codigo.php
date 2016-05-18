<?php
include("Connections/config.php");

// Esstablish connect to MySQL database
$con = mysql_connect($hostname_config, $username_config, $password_config);
if(!$con)
    die('Could not connect: ' . mysql_error() );

mysql_select_db('okeefe_abm', $con);

$query = "SELECT id_sucursal, id_prop FROM propiedad WHERE id_prop LIKE '%".$_GET['q']."%' AND activa=1 LIMIT 10;";
$result = mysql_query($query);
//echo $query;
$output_items = array();
while($row = mysql_fetch_array($result)){
    $output_items[] = $row['id_prop'];
}

print(implode("\n", $output_items));

mysql_close($con);

?>
