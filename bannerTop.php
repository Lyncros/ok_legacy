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
switch (intval($_GET['suc'])){
	default:
		$query_fotos = sprintf("SELECT * FROM bannertop WHERE activo = 1 ORDER BY orden ASC",0);
		break;
	case 0:
		$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",0);
		break;
	case 1:
		$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",1);
		break;
	case 2:
		$query_fotos = sprintf("SELECT * FROM bannertop WHERE area=%s AND activo = 1 ORDER BY orden ASC",2);
		break;
	case 4:
		$query_fotos = "SELECT * FROM home_vlo WHERE activo = 1 ORDER BY orden ASC";
		break;
	case 3:
		$query_fotos = "SELECT * FROM home_mad WHERE activo = 1 ORDER BY orden ASC";
		break;
	case 5:
		$query_fotos = "SELECT * FROM home_pat WHERE activo = 1 ORDER BY orden ASC";
		break;
	case 10:
		$query_fotos = "SELECT * FROM home_rural WHERE activo = 1 ORDER BY orden ASC";
		break;
	case 11:
		$query_fotos = "SELECT * FROM home_residencial WHERE activo = 1 ORDER BY orden ASC";
		break;
	case 12:
		$query_fotos = "SELECT * FROM home_comercial WHERE activo = 1 ORDER BY orden ASC";
		break;
}
//echo $query_fotos;
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
