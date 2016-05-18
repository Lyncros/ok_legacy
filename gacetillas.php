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

mysql_select_db($database_config, $config);
$query_prensa = "SELECT * FROM gacetillas ORDER BY orden";
$prensa = mysql_query($query_prensa, $config) or die(mysql_error());
//$row_prensa = mysql_fetch_assoc($prensa);
//$totalRows_prensa = mysql_num_rows($prensa);
 
$suc = 10;
?>
<?php include_once('cabezal.php'); ?>
<script language="javascript" src="js/thickbox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/thickbox.css" />
<div id="izq">
  <div id="qsTitulo"><a href="prensa.php" style="color:#007b3c;">Prensa</a> - Gacetillas</div>
 <?php while($row_prensa = mysql_fetch_assoc($prensa)){ ?>
  <table width="755" id="noticia" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td id="logoMedio"><?php if($row_prensa['img_gacetilla'] != ""){ ?><img src="gacetillas/<?php echo $row_prensa['img_gacetilla'];?>" border="0" alt="<?php echo $row_prensa['titu_gacetilla'];?>" /><?php }?></td>
    <td id="fechaNota"><?php echo date('d-m-Y', strtotime($row_prensa['fecha_gacetilla']));?></td>
    <td id="tituloNota"><?php echo htmlentities(utf8_decode($row_prensa['titu_gacetilla']));?></td>
	<td><a href="gacetillasDesc.php?id=<?php echo $row_prensa['id_gacetilla']; ?>"><div id="vermasNota">&gt; Ver m&aacute;s</div></a></td>
  </tr>
</table>
  <?php } ?>
</div>
<div id="derecha">
  <?php include_once("buscadorVertical.php"); ?>
</div>
<?php include_once('pie.php'); 

mysql_free_result($prensa);
?>
