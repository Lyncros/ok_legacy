<?php require_once('Connections/config.php'); ?>
<?php
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

$query_fotos = sprintf("SELECT * FROM home WHERE id_area=%s AND activo = 1 ORDER BY orden ASC", GetSQLValueString($_GET['suc'], "int"));
mysql_select_db($database_config, $config);
$fotos = mysql_query($query_fotos, $config) or die(mysql_error());
//$row_fotos = mysql_fetch_assoc($fotos);
//$totalRows_fotos = mysql_num_rows($fotos);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo utf8_encode("<productos>\n");

while ($row_fotos = mysql_fetch_assoc($fotos)) {
		echo utf8_encode("\t<datos imagen=\"".$row_fotos['foto']."\" link_foto=\"".$row_fotos['link']."\" />\n");
}
echo utf8_encode("</productos>");


mysql_free_result($fotos);
?>
