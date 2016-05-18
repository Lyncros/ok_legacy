<?php require_once('../Connections/config.php'); ?>
<?php
// Valida Usuario
require_once('../inc/validaUsuario.php');

// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the required classes
require_once('../includes/tfi/TFI.php');
require_once('../includes/tso/TSO.php');
require_once('../includes/nav/NAV.php');

// Make unified connection variable
$conn_config = new KT_connection($config, $database_config);

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

// Filter
$tfi_listmedios1 = new TFI_TableFilter($conn_config, "tfi_listmedios1");
$tfi_listmedios1->addColumn("medios.nombre_medio", "STRING_TYPE", "nombre_medio", "%");
$tfi_listmedios1->addColumn("medios.logo_medio", "STRING_TYPE", "logo_medio", "%");
$tfi_listmedios1->Execute();

// Sorter
$tso_listmedios1 = new TSO_TableSorter("rsmedios1", "tso_listmedios1");
$tso_listmedios1->addColumn("medios.nombre_medio");
$tso_listmedios1->addColumn("medios.logo_medio");
$tso_listmedios1->setDefault("medios.nombre_medio");
$tso_listmedios1->Execute();

// Navigation
$nav_listmedios1 = new NAV_Regular("nav_listmedios1", "rsmedios1", "../", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rsmedios1 = $_SESSION['max_rows_nav_listmedios1'];
$pageNum_rsmedios1 = 0;
if (isset($_GET['pageNum_rsmedios1'])) {
  $pageNum_rsmedios1 = $_GET['pageNum_rsmedios1'];
}
$startRow_rsmedios1 = $pageNum_rsmedios1 * $maxRows_rsmedios1;

// Defining List Recordset variable
$NXTFilter_rsmedios1 = "1=1";
if (isset($_SESSION['filter_tfi_listmedios1'])) {
  $NXTFilter_rsmedios1 = $_SESSION['filter_tfi_listmedios1'];
}
// Defining List Recordset variable
$NXTSort_rsmedios1 = "medios.nombre_medio";
if (isset($_SESSION['sorter_tso_listmedios1'])) {
  $NXTSort_rsmedios1 = $_SESSION['sorter_tso_listmedios1'];
}
mysql_select_db($database_config, $config);

$query_rsmedios1 = "SELECT medios.nombre_medio, medios.logo_medio, medios.id_medio FROM medios WHERE {$NXTFilter_rsmedios1} ORDER BY {$NXTSort_rsmedios1}";
$query_limit_rsmedios1 = sprintf("%s LIMIT %d, %d", $query_rsmedios1, $startRow_rsmedios1, $maxRows_rsmedios1);
$rsmedios1 = mysql_query($query_limit_rsmedios1, $config) or die(mysql_error());
$row_rsmedios1 = mysql_fetch_assoc($rsmedios1);

if (isset($_GET['totalRows_rsmedios1'])) {
  $totalRows_rsmedios1 = $_GET['totalRows_rsmedios1'];
} else {
  $all_rsmedios1 = mysql_query($query_rsmedios1);
  $totalRows_rsmedios1 = mysql_num_rows($all_rsmedios1);
}
$totalPages_rsmedios1 = ceil($totalRows_rsmedios1/$maxRows_rsmedios1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listmedios1->checkBoundries();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_LIST_SETTINGS = {
  duplicate_buttons: false,
  duplicate_navigation: false,
  row_effects: true,
  show_as_buttons: true,
  record_counter: true
}
</script>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_nombre_medio {width:140px; overflow:hidden;}
  .KT_col_logo_medio {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listmedios1">
  <h1> Medios
    <?php
  $nav_listmedios1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listmedios1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listmedios1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listmedios1']; ?>
          <?php 
  // else Conditional region1
  } else { ?>
          <?php echo NXT_getResource("all"); ?>
          <?php } 
  // endif Conditional region1
?>
<?php echo NXT_getResource("records"); ?></a> &nbsp;
        &nbsp; </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="nombre_medio" class="KT_sorter KT_col_nombre_medio <?php echo $tso_listmedios1->getSortIcon('medios.nombre_medio'); ?>"> <a href="<?php echo $tso_listmedios1->getSortLink('medios.nombre_medio'); ?>">Medio</a></th>
            <th id="logo_medio" class="KT_sorter KT_col_logo_medio <?php echo $tso_listmedios1->getSortIcon('medios.logo_medio'); ?>"> <a href="<?php echo $tso_listmedios1->getSortLink('medios.logo_medio'); ?>">Logo</a></th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_rsmedios1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsmedios1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_medios" class="id_checkbox" value="<?php echo $row_rsmedios1['id_medio']; ?>" />
                  <input type="hidden" name="id_medio" class="id_field" value="<?php echo $row_rsmedios1['id_medio']; ?>" /></td>
                <td><div class="KT_col_nombre_medio"><?php echo KT_FormatForList($row_rsmedios1['nombre_medio'], 20); ?></div></td>
                <td align="center"><div class="KT_col_logo_medio"><img src="../medios/<?php echo KT_FormatForList($row_rsmedios1['logo_medio'], 20); ?>" width="80" /></div></td>
                <td><a class="KT_edit_link" href="mediosForm.php?id_medio=<?php echo $row_rsmedios1['id_medio']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsmedios1 = mysql_fetch_assoc($rsmedios1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listmedios1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a></div>
        <span>&nbsp;</span>
        <select name="no_new" id="no_new">
          <option value="1">1</option>
          <option value="3">3</option>
          <option value="6">6</option>
        </select>
        <a class="KT_additem_op_link" href="mediosForm.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsmedios1);
?>
