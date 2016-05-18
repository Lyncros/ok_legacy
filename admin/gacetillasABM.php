<?php require_once('../Connections/config.php'); ?>
<?php
// Valida Usuario
require_once('../inc/validaUsuario.php');

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
$tor_listgacetillas1 = new TOR_SetOrder($conn_config, 'gacetillas', 'id_gacetilla', 'NUMERIC_TYPE', 'orden', 'listgacetillas1_orden_order');
$tor_listgacetillas1->Execute();

// Filter
$tfi_listgacetillas1 = new TFI_TableFilter($conn_config, "tfi_listgacetillas1");
$tfi_listgacetillas1->addColumn("gacetillas.fecha_gacetilla", "DATE_TYPE", "fecha_gacetilla", "=");
$tfi_listgacetillas1->addColumn("gacetillas.titu_gacetilla", "STRING_TYPE", "titu_gacetilla", "%");
$tfi_listgacetillas1->addColumn("gacetillas.bajada_gacetilla", "STRING_TYPE", "bajada_gacetilla", "%");
$tfi_listgacetillas1->addColumn("gacetillas.txt_gacetilla", "STRING_TYPE", "txt_gacetilla", "%");
$tfi_listgacetillas1->addColumn("gacetillas.img_gacetilla", "STRING_TYPE", "img_gacetilla", "%");
$tfi_listgacetillas1->Execute();

// Sorter
$tso_listgacetillas1 = new TSO_TableSorter("rsgacetillas1", "tso_listgacetillas1");
$tso_listgacetillas1->addColumn("gacetillas.orden"); // Order column
$tso_listgacetillas1->setDefault("gacetillas.orden");
$tso_listgacetillas1->Execute();

// Navigation
$nav_listgacetillas1 = new NAV_Regular("nav_listgacetillas1", "rsgacetillas1", "../", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rsgacetillas1 = $_SESSION['max_rows_nav_listgacetillas1'];
$pageNum_rsgacetillas1 = 0;
if (isset($_GET['pageNum_rsgacetillas1'])) {
  $pageNum_rsgacetillas1 = $_GET['pageNum_rsgacetillas1'];
}
$startRow_rsgacetillas1 = $pageNum_rsgacetillas1 * $maxRows_rsgacetillas1;

// Defining List Recordset variable
$NXTFilter_rsgacetillas1 = "1=1";
if (isset($_SESSION['filter_tfi_listgacetillas1'])) {
  $NXTFilter_rsgacetillas1 = $_SESSION['filter_tfi_listgacetillas1'];
}
// Defining List Recordset variable
$NXTSort_rsgacetillas1 = "gacetillas.orden";
if (isset($_SESSION['sorter_tso_listgacetillas1'])) {
  $NXTSort_rsgacetillas1 = $_SESSION['sorter_tso_listgacetillas1'];
}
mysql_select_db($database_config, $config);

$query_rsgacetillas1 = "SELECT gacetillas.fecha_gacetilla, gacetillas.titu_gacetilla, gacetillas.bajada_gacetilla, gacetillas.txt_gacetilla, gacetillas.img_gacetilla, gacetillas.id_gacetilla, gacetillas.orden FROM gacetillas WHERE {$NXTFilter_rsgacetillas1} ORDER BY {$NXTSort_rsgacetillas1}";
$query_limit_rsgacetillas1 = sprintf("%s LIMIT %d, %d", $query_rsgacetillas1, $startRow_rsgacetillas1, $maxRows_rsgacetillas1);
$rsgacetillas1 = mysql_query($query_limit_rsgacetillas1, $config) or die(mysql_error());
$row_rsgacetillas1 = mysql_fetch_assoc($rsgacetillas1);

if (isset($_GET['totalRows_rsgacetillas1'])) {
  $totalRows_rsgacetillas1 = $_GET['totalRows_rsgacetillas1'];
} else {
  $all_rsgacetillas1 = mysql_query($query_rsgacetillas1);
  $totalRows_rsgacetillas1 = mysql_num_rows($all_rsgacetillas1);
}
$totalPages_rsgacetillas1 = ceil($totalRows_rsgacetillas1/$maxRows_rsgacetillas1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listgacetillas1->checkBoundries();
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
  .KT_col_fecha_gacetilla {width:140px; overflow:hidden;}
  .KT_col_titu_gacetilla {width:140px; overflow:hidden;}
  .KT_col_bajada_gacetilla {width:140px; overflow:hidden;}
  .KT_col_txt_gacetilla {width:140px; overflow:hidden;}
  .KT_col_img_gacetilla {width:140px; overflow:hidden;}
</style>
<?php echo $tor_listgacetillas1->scriptDefinition(); ?>
</head>

<body>
<div class="KT_tng" id="listgacetillas1">
  <h1> Gacetillas
    <?php
  $nav_listgacetillas1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listgacetillas1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listgacetillas1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listgacetillas1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listgacetillas1'] == 1) {
?>
          <a href="<?php echo $tfi_listgacetillas1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listgacetillas1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="fecha_gacetilla" class="KT_col_fecha_gacetilla">Fecha</th>
            <th id="titu_gacetilla" class="KT_col_titu_gacetilla">Titulo</th>
            <th id="bajada_gacetilla" class="KT_col_bajada_gacetilla">Bajada</th>
            <th id="txt_gacetilla" class="KT_col_txt_gacetilla">Texto</th>
            <th id="img_gacetilla" class="KT_col_img_gacetilla">Imagen</th>
            <th id="orden" class="KT_sorter <?php echo $tso_listgacetillas1->getSortIcon('gacetillas.orden'); ?> KT_order"> <a href="<?php echo $tso_listgacetillas1->getSortLink('gacetillas.orden'); ?>"><?php echo NXT_getResource("Order"); ?></a> <a class="KT_move_op_link" href="#" onclick="nxt_list_move_link_form(this); return false;"><?php echo NXT_getResource("save"); ?></a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listgacetillas1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listgacetillas1_fecha_gacetilla" id="tfi_listgacetillas1_fecha_gacetilla" value="<?php echo @$_SESSION['tfi_listgacetillas1_fecha_gacetilla']; ?>" size="10" maxlength="22" /></td>
              <td><input type="text" name="tfi_listgacetillas1_titu_gacetilla" id="tfi_listgacetillas1_titu_gacetilla" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listgacetillas1_titu_gacetilla']); ?>" size="20" maxlength="250" /></td>
              <td><input type="text" name="tfi_listgacetillas1_bajada_gacetilla" id="tfi_listgacetillas1_bajada_gacetilla" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listgacetillas1_bajada_gacetilla']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listgacetillas1_txt_gacetilla" id="tfi_listgacetillas1_txt_gacetilla" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listgacetillas1_txt_gacetilla']); ?>" size="20" maxlength="100" /></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="submit" name="tfi_listgacetillas1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsgacetillas1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="8"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsgacetillas1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_gacetillas" class="id_checkbox" value="<?php echo $row_rsgacetillas1['id_gacetilla']; ?>" />
                  <input type="hidden" name="id_gacetilla" class="id_field" value="<?php echo $row_rsgacetillas1['id_gacetilla']; ?>" /></td>
                <td><div class="KT_col_fecha_gacetilla"><?php echo KT_formatDate($row_rsgacetillas1['fecha_gacetilla']); ?></div></td>
                <td><div class="KT_col_titu_gacetilla"><?php echo KT_FormatForList($row_rsgacetillas1['titu_gacetilla'], 20); ?></div></td>
                <td><div class="KT_col_bajada_gacetilla"><?php echo KT_FormatForList($row_rsgacetillas1['bajada_gacetilla'], 20); ?></div></td>
                <td><div class="KT_col_txt_gacetilla"><?php echo KT_FormatForList($row_rsgacetillas1['txt_gacetilla'], 20); ?></div></td>
                <td><div class="KT_col_img_gacetilla"><?php echo KT_FormatForList($row_rsgacetillas1['img_gacetilla'], 20); ?></div></td>
                <td class="KT_order"><input type="hidden" class="KT_orderhidden" name="<?php echo $tor_listgacetillas1->getOrderFieldName() ?>" value="<?php echo $tor_listgacetillas1->getOrderFieldValue($row_rsgacetillas1) ?>" />
                  <a class="KT_movedown_link" href="#move_down">v</a> <a class="KT_moveup_link" href="#move_up">^</a></td>
                <td><a class="KT_edit_link" href="gacetillasForm.php?id_gacetilla=<?php echo $row_rsgacetillas1['id_gacetilla']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsgacetillas1 = mysql_fetch_assoc($rsgacetillas1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listgacetillas1->Prepare();
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
        <a class="KT_additem_op_link" href="gacetillasForm.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsgacetillas1);
?>
