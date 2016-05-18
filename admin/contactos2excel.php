<?php require_once('../Connections/config.php'); ?>
<?php
$filename = "contactos_".date("d-m-Y");
$file_ending = "xls";

mysql_select_db($database_config, $config);
$result = mysql_query($_GET['q'], $config) or die(mysql_error());

//header info for browser
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache");
header("Expires: 0");
 
/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
 
//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {
	echo mysql_field_name($result,$i) . "\t";
}
print("\n");
//end of printing column names
 
//start while loop to get data
while($row = mysql_fetch_row($result)){
	$schema_insert = "";
	for($j=0; $j<mysql_num_fields($result);$j++)
	{
		if(!isset($row[$j]))
			$schema_insert .= "NULL".$sep;
		elseif ($row[$j] != "")
			$schema_insert .= "$row[$j]".$sep;
		else
			$schema_insert .= "".$sep;
	}
	$schema_insert = str_replace($sep."$", "", $schema_insert);
	$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
	$schema_insert .= "\t";
	print(trim($schema_insert));
	print "\n";
}
?>