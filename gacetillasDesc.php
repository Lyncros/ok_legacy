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

$id = -1;
if(isset($_GET['id'])){
	$id = strip_tags($_GET['id']);
}else{
	header("location: gacetillas.php");
}
mysql_select_db($database_config, $config);
$query_prensa = sprintf("SELECT * FROM gacetillas where id_gacetilla=%s", $id);
$prensa = mysql_query($query_prensa, $config) or die(mysql_error());
$row_prensa = mysql_fetch_assoc($prensa);
//$totalRows_prensa = mysql_num_rows($prensa);
 
$suc = 10;
?>
<?php include_once('cabezal.php'); ?>
<div id="contenido">
  <div id="izq">
    <div id="qsTitulo">Gacetillas</div>
    <div style="width:485px; float:left; margin-right:10px; font-size:.85em;margin-top:5px;min-height:415px;">
      <p class="tituGacetilla"><?php echo $row_prensa['titu_gacetilla']; ?></p>
      <p class="bajadaGacetilla"><?php echo $row_prensa['bajada_gacetilla']; ?></p>
      <p><?php echo $row_prensa['txt_gacetilla']; ?></p>
      <p style="text-align:right;"><a href="javascript: history.back();"><img src="images/volverDetalle.gif" width="21" height="23" alt="Volver" /></a></p>
    </div>
    <div style="float:left; width:252px; margin-top:30px;"><?php if($row_prensa['img_gacetilla'] != ""){ ?><img src="gacetillas/<?php echo $row_prensa['img_gacetilla'];?>" border="0" alt="<?php echo $row_prensa['titu_gacetilla'];?>" style="max-width:250px; max-height:400px;" /><?php } ?></div>
    <div class="clearfix"></div>
    <?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <?php include_once("buscadorVertical.php"); ?>
  </div>
  <?php include_once('pie.php'); ?>