<?php require_once('../Connections/config.php'); ?>
<?php
//date_default_timezone_set('America/Argentina/Buenos_Aires');

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

//$currentPage = $_SERVER["PHP_SELF"];
$currentPage ="javascript: ajax_loadContent('contactosResultado','contacots_filtro.php";

//echo $_SERVER['QUERY_STRING'];
//$filtro = explode('&', $_SERVER['QUERY_STRING'],9);
foreach ($_GET as $key => $value) $filtro["$key"]= $value;
//print_r($filtro);

if(array_key_exists('todos', $filtro)){
	$query_Recordset1 = "SELECT * FROM consultas order by apellido";
}else{
	$where=array();
	if($filtro['nombre'] != ""){
		array_push($where, "nombre LIKE '%" . $filtro['nombre']  . "%'");
	}
	if($filtro['apellido'] !=""){
		array_push($where, "apellido LIKE '%" . $filtro['apellido']  . "%'");
	}
	if($filtro['mail'] !=""){
		array_push($where, "email LIKE '%" . $filtro['mail']  . "%'");
	}
	if($filtro['categoria'] !=""){
		array_push($where, "categoria LIKE '%" . $filtro['categoria']  . "%'");
	}
	if($filtro['destino'] != -1){
		array_push($where, "destino='" . $filtro['destino']  . "'");
	}
	if($filtro['acceso'] != ""){
		array_push($where, "acceso LIKE '%" . $filtro['acceso']  . "%'");
	}
	if($filtro['desde'] != ""){
		array_push($where, "fecha >= '" . date('Y-m-d', strtotime($filtro['desde']))  . "'");
	}
	if($filtro['hasta'] != ""){
		array_push($where, "fecha <= '" . date('Y-m-d', strtotime($filtro['hasta']))   . "'");
	}
	$query_Recordset1 = "SELECT * FROM consultas WHERE ";
	foreach($where as $filtro){
		$query_Recordset1 .= $filtro . " AND ";
	}
	$query_Recordset1 = substr($query_Recordset1,0, -4) . "order by apellido";
}
//echo $query_Recordset1;

$maxRows_Recordset1 = 25;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysql_select_db($database_config, $config);
//$query_Recordset1 = "SELECT * FROM consultas ORDER BY apellido";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $config) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s')", $totalRows_Recordset1, $queryString_Recordset1);

?>
<table width="1000" border="0" cellpadding="2" cellspacing="1">
  <tr>
    <td class="titu_tabla" width="200">apellido y nombre</td>
    <td class="titu_tabla" width="100">email</td>
    <td class="titu_tabla" width="150">consulta</td>
    <td class="titu_tabla" width="120">categoria</td>
    <td class="titu_tabla" width="100">destino</td>
    <td class="titu_tabla" width="150">acceso</td>
    <td class="titu_tabla" width="80">fecha</td>
  </tr>
  <?php do { ?>
    <tr>
      <td title="<?php echo $row_Recordset1['apellido'].', '.$row_Recordset1['nombre']; ?>"><?php echo substr($row_Recordset1['apellido'].', '.$row_Recordset1['nombre'],0,30); ?></td>
      <td title="<?php echo $row_Recordset1['email']; ?>"><?php echo substr($row_Recordset1['email'],0,15); ?></td>
      <td title="<?php echo $row_Recordset1['consulta']; ?>"><?php echo substr($row_Recordset1['consulta'],0,20); ?></td>
      <td title="<?php echo $row_Recordset1['categoria']; ?>"><?php echo substr($row_Recordset1['categoria'],0,15); ?></td>
      <td title="<?php echo $row_Recordset1['destino']; ?>"><?php echo substr($row_Recordset1['destino'],0,15); ?></td>
      <td title="<?php echo $row_Recordset1['acceso']; ?>"><?php echo substr($row_Recordset1['acceso'],0,22); ?></td>
      <td align="center"><?php echo date("d-m-Y", strtotime($row_Recordset1['fecha'])); ?></td>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
<table border="0" align="center" cellspacing="5" id="paginado">
  <tr>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>">Primera</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>">Anterior</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>">Siguiente</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>">Ultima</a>
        <?php } // Show if not last page ?></td>
	<td>Total de registros: <?php echo $totalRows_Recordset1; ?></td>
	<td><a href="contactos2excel.php?q=<?php echo $query_Recordset1;?>">Exportar EXCEL</a></td>
  </tr>
</table>
<?php
mysql_free_result($Recordset1);
?>
