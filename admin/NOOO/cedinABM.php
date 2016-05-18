<?php require_once('../Connections/config.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the required classes
require_once('../includes/tor/TOR.php');
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

// Order
$tor_listcedin1 = new TOR_SetOrder($conn_config, 'cedin', 'id_cedin', 'NUMERIC_TYPE', 'orden', 'listcedin1_orden_order');
$tor_listcedin1->Execute();

// Filter
$tfi_listcedin1 = new TFI_TableFilter($conn_config, "tfi_listcedin1");
$tfi_listcedin1->addColumn("cedin.titulo_cedin", "STRING_TYPE", "titulo_cedin", "%");
$tfi_listcedin1->addColumn("cedin.link_cedin", "STRING_TYPE", "link_cedin", "%");
$tfi_listcedin1->Execute();

// Sorter
$tso_listcedin1 = new TSO_TableSorter("rscedin1", "tso_listcedin1");
$tso_listcedin1->addColumn("cedin.orden"); // Order column
$tso_listcedin1->setDefault("cedin.orden");
$tso_listcedin1->Execute();

// Navigation
$nav_listcedin1 = new NAV_Regular("nav_listcedin1", "rscedin1", "../", $_SERVER['PHP_SELF'], 30);

//NeXTenesio3 Special List Recordset
$maxRows_rscedin1 = $_SESSION['max_rows_nav_listcedin1'];
$pageNum_rscedin1 = 0;
if (isset($_GET['pageNum_rscedin1'])) {
  $pageNum_rscedin1 = $_GET['pageNum_rscedin1'];
}
$startRow_rscedin1 = $pageNum_rscedin1 * $maxRows_rscedin1;

// Defining List Recordset variable
$NXTFilter_rscedin1 = "1=1";
if (isset($_SESSION['filter_tfi_listcedin1'])) {
  $NXTFilter_rscedin1 = $_SESSION['filter_tfi_listcedin1'];
}
// Defining List Recordset variable
$NXTSort_rscedin1 = "cedin.orden";
if (isset($_SESSION['sorter_tso_listcedin1'])) {
  $NXTSort_rscedin1 = $_SESSION['sorter_tso_listcedin1'];
}
mysql_select_db($database_config, $config);

$query_rscedin1 = "SELECT cedin.titulo_cedin, cedin.link_cedin, cedin.id_cedin, cedin.orden FROM cedin WHERE {$NXTFilter_rscedin1} ORDER BY {$NXTSort_rscedin1}";
$query_limit_rscedin1 = sprintf("%s LIMIT %d, %d", $query_rscedin1, $startRow_rscedin1, $maxRows_rscedin1);
$rscedin1 = mysql_query($query_limit_rscedin1, $config) or die(mysql_error());
$row_rscedin1 = mysql_fetch_assoc($rscedin1);

if (isset($_GET['totalRows_rscedin1'])) {
  $totalRows_rscedin1 = $_GET['totalRows_rscedin1'];
} else {
  $all_rscedin1 = mysql_query($query_rscedin1);
  $totalRows_rscedin1 = mysql_num_rows($all_rscedin1);
}
$totalPages_rscedin1 = ceil($totalRows_rscedin1/$maxRows_rscedin1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listcedin1->checkBoundries();
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
  duplicate_navigation: true,
  row_effects: true,
  show_as_buttons: true,
  record_counter: true
}
</script>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_titulo_cedin {width:240px; overflow:hidden;}
  .KT_col_link_cedin {width:240px; overflow:hidden;}
</style>
<?php echo $tor_listcedin1->scriptDefinition(); ?>
</head>

<body>
<div class="KT_tng" id="listcedin1">
  <h1> Cedin
    <?php
  $nav_listcedin1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listcedin1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listcedin1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listcedin1']; ?>
          <?php 
  // else Conditional region1
  } else { ?>
          <?php echo NXT_getResource("all"); ?>
          <?php } 
  // endif Conditional region1
?>
        <?php echo NXT_getResource("records"); ?></a> &nbsp;
        &nbsp;
        <?php 
  // Show IF Conditional region2
  if (@$_SESSION['has_filter_tfi_listcedin1'] == 1) {
?>
          <a href="<?php echo $tfi_listcedin1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listcedin1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="titulo_cedin" class="KT_col_titulo_cedin">Titulo</th>
            <th id="link_cedin" class="KT_col_link_cedin">Link externo</th>
            <th id="orden" class="KT_sorter <?php echo $tso_listcedin1->getSortIcon('cedin.orden'); ?> KT_order"> <a href="<?php echo $tso_listcedin1->getSortLink('cedin.orden'); ?>"><?php echo NXT_getResource("Order"); ?></a> <a class="KT_move_op_link" href="#" onclick="nxt_list_move_link_form(this); return false;"><?php echo NXT_getResource("save"); ?></a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listcedin1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listcedin1_titulo_cedin" id="tfi_listcedin1_titulo_cedin" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcedin1_titulo_cedin']); ?>" size="20" maxlength="250" /></td>
              <td><input type="text" name="tfi_listcedin1_link_cedin" id="tfi_listcedin1_link_cedin" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcedin1_link_cedin']); ?>" size="20" maxlength="250" /></td>
              <td>&nbsp;</td>
              <td><input type="submit" name="tfi_listcedin1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rscedin1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rscedin1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_cedin" class="id_checkbox" value="<?php echo $row_rscedin1['id_cedin']; ?>" />
                  <input type="hidden" name="id_cedin" class="id_field" value="<?php echo $row_rscedin1['id_cedin']; ?>" /></td>
                <td><div class="KT_col_titulo_cedin"><?php echo KT_FormatForList($row_rscedin1['titulo_cedin'], 20); ?></div></td>
                <td><div class="KT_col_link_cedin"><?php echo KT_FormatForList($row_rscedin1['link_cedin'], 20); ?></div></td>
                <td class="KT_order"><input type="hidden" class="KT_orderhidden" name="<?php echo $tor_listcedin1->getOrderFieldName() ?>" value="<?php echo $tor_listcedin1->getOrderFieldValue($row_rscedin1) ?>" />
                  <a class="KT_movedown_link" href="#move_down">v</a> <a class="KT_moveup_link" href="#move_up">^</a></td>
                <td><a class="KT_edit_link" href="cedinForm.php?id_cedin=<?php echo $row_rscedin1['id_cedin']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rscedin1 = mysql_fetch_assoc($rscedin1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listcedin1->Prepare();
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
        <a class="KT_additem_op_link" href="cedinForm.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rscedin1);
?>
