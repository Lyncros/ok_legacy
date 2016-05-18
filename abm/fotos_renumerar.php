<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "ACHabm10";

// Esstablish connect to MySQL database
$con = mysql_connect( $db_host, $db_user, $db_pass);
if(!$con)
    die('Could not connect: ' . mysql_error() );

mysql_select_db("ip045603_ach0", $con);


$query = "SELECT id_prop FROM propiedad ORDER BY id_prop";
$result = mysql_query($query);

while($row = mysql_fetch_array($result)){
	$query_foto = sprintf("SELECT id_foto, posicion FROM fotos WHERE id_prop=%s", $row['id_prop']);
	$result_foto = mysql_query($query_foto);
	if(mysql_num_rows($result_foto) > 0){
		$i=1;
		while($row_foto = mysql_fetch_assoc($result_foto)){
			$query_ufoto = sprintf("UPDATE fotos SET posicion=%s WHERE id_foto=%s", $i, $row_foto['id_foto']);
			$result_ufoto = mysql_query($query_ufoto);
			mysql_free_result($result_ufoto);
			echo $query_ufoto ."<br />";
			//echo "id prop: ".$row['id_prop'] . " posi: ".$i ."<br />";
			$i++;
		}
	}
}
mysql_close($con);

?>