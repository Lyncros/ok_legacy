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
$tor_listhome1 = new TOR_SetOrder($conn_config, 'home', 'id_home', 'NUMERIC_TYPE', 'orden', 'listhome1_orden_order');
$tor_listhome1->Execute();

// Filter
$tfi_listhome1 = new TFI_TableFilter($conn_config, "tfi_listhome1");
$tfi_listhome1->addColumn("areas.id_area", "NUMERIC_TYPE", "id_area", "=");
$tfi_listhome1->addColumn("home.foto", "NUMERIC_TYPE", "foto", "=");
$tfi_listhome1->addColumn("home.alt", "STRING_TYPE", "alt", "%");
$tfi_listhome1->addColumn("home.link", "STRING_TYPE", "link", "%");
$tfi_listhome1->addColumn("home.activo", "CHECKBOX_1_0_TYPE", "activo", "%");
$tfi_listhome1->Execute();

// Sorter
$tso_listhome1 = new TSO_TableSorter("rshome1", "tso_listhome1");
$tso_listhome1->addColumn("home.orden"); // Order column
$tso_listhome1->setDefault("home.orden");
$tso_listhome1->Execute();

// Navigation
$nav_listhome1 = new NAV_Regular("nav_listhome1", "rshome1", "../", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_config, $config);
$query_Recordset1 = "SELECT area, id_area FROM areas ORDER BY area";
$Recordset1 = mysql_query($query_Recordset1, $config) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

//NeXTenesio3 Special List Recordset
$maxRows_rshome1 = $_SESSION['max_rows_nav_listhome1'];
$pageNum_rshome1 = 0;
if (isset($_GET['pageNum_rshome1'])) {
  $pageNum_rshome1 = $_GET['pageNum_rshome1'];
}
$startRow_rshome1 = $pageNum_rshome1 * $maxRows_rshome1;

// Defining List Recordset variable
$NXTFilter_rshome1 = "1=1";
if (isset($_SESSION['filter_tfi_listhome1'])) {
  $NXTFilter_rshome1 = $_SESSION['filter_tfi_listhome1'];
}
// Defining List Recordset variable
$NXTSort_rshome1 = "home.orden";
if (isset($_SESSION['sorter_tso_listhome1'])) {
  $NXTSort_rshome1 = $_SESSION['sorter_tso_listhome1'];
}
mysql_select_db($database_config, $config);

$query_rshome1 = "SELECT areas.area AS id_area, home.foto, home.alt, home.link, home.activo, home.id_home, home.orden FROM home LEFT JOIN areas ON home.id_area = areas.id_area WHERE {$NXTFilter_rshome1} ORDER BY {$NXTSort_rshome1}";
$query_limit_rshome1 = sprintf("%s LIMIT %d, %d", $query_rshome1, $startRow_rshome1, $maxRows_rshome1);
$rshome1 = mysql_query($query_limit_rshome1, $config) or die(mysql_error());
$row_rshome1 = mysql_fetch_assoc($rshome1);

if (isset($_GET['totalRows_rshome1'])) {
  $totalRows_rshome1 = $_GET['totalRows_rshome1'];
} else {
  $all_rshome1 = mysql_query($query_rshome1);
  $totalRows_rshome1 = mysql_num_rows($all_rshome1);
}
$totalPages_rshome1 = ceil($totalRows_rshome1/$maxRows_rshome1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listhome1->checkBoundries();
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
  .KT_col_id_area {width:140px; overflow:hidden;}
  .KT_col_foto {width:140px; overflow:hidden;}
  .KT_col_alt {width:140px; overflow:hidden;}
  .KT_col_link {width:140px; overflow:hidden;}
  .KT_col_activo {width:35px; overflow:hidden;}
</style>
<?php echo $tor_listhome1->scriptDefinition(); ?>
</head>

<body>
<div class="KT_tng" id="listhome1">
  <h1> Home
    <?php
  $nav_listhome1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listhome1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listhome1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listhome1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listhome1'] == 1) {
?>
          <a href="<?php echo $tfi_listhome1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listhome1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="id_area" class="KT_col_id_area">Area</th>
            <th id="foto" class="KT_col_foto">Foto</th>
            <th id="link" class="KT_col_alt">Texto ALT</th>
            <th id="link" class="KT_col_link">Link</th>
            <th id="activo" class="KT_col_activo">Activo</th>
            <th id="orden" class="KT_sorter <?php echo $tso_listhome1->getSortIcon('home.orden'); ?> KT_order"> <a href="<?php echo $tso_listhome1->getSortLink('home.orden'); ?>"><?php echo NXT_getResource("Order"); ?></a> <a class="KT_move_op_link" href="#" onclick="nxt_list_move_link_form(this); return false;"><?php echo NXT_getResource("save"); ?></a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listhome1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><select name="tfi_listhome1_id_area" id="tfi_listhome1_id_area">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listhome1_id_area']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset1['id_area']?>"<?php if (!(strcmp($row_Recordset1['id_area'], @$_SESSION['tfi_listhome1_id_area']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['area']?></option>
                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
              </select></td>
              <td><input type="text" name="tfi_listhome1_foto" id="tfi_listhome1_foto" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listhome1_foto']); ?>" size="20" maxlength="250" /></td>
              <td><input type="text" name="tfi_listhome1_alt" id="tfi_listhome1_alt" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listhome1_alt']); ?>" size="20" maxlength="250" /></td>
              <td><input type="text" name="tfi_listhome1_link" id="tfi_listhome1_link" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listhome1_link']); ?>" size="20" maxlength="250" /></td>
              <td><input  <?php if (!(strcmp(KT_escapeAttribute(@$_SESSION['tfi_listhome1_activo']),"1"))) {echo "checked";} ?> type="checkbox" name="tfi_listhome1_activo" id="tfi_listhome1_activo" value="1" /></td>
              <td>&nbsp;</td>
              <td><input type="submit" name="tfi_listhome1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rshome1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="7"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rshome1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_home" class="id_checkbox" value="<?php echo $row_rshome1['id_home']; ?>" />
                  <input type="hidden" name="id_home" class="id_field" value="<?php echo $row_rshome1['id_home']; ?>" /></td>
                <td><div class="KT_col_id_area"><?php echo KT_FormatForList($row_rshome1['id_area'], 20); ?></div></td>
                <td><div class="KT_col_foto"><?php echo KT_FormatForList($row_rshome1['foto'], 20); ?></div></td>
                <td><div class="KT_col_alt"><?php echo KT_FormatForList($row_rshome1['alt'], 20); ?></div></td>
                <td><div class="KT_col_link"><?php echo KT_FormatForList($row_rshome1['link'], 20); ?></div></td>
                <td><div class="KT_col_activo"><?php echo KT_FormatForList($row_rshome1['activo'], 5); ?></div></td>
                <td class="KT_order"><input type="hidden" class="KT_orderhidden" name="<?php echo $tor_listhome1->getOrderFieldName() ?>" value="<?php echo $tor_listhome1->getOrderFieldValue($row_rshome1) ?>" />
                  <a class="KT_movedown_link" href="#move_down">v</a> <a class="KT_moveup_link" href="#move_up">^</a></td>
                <td><a class="KT_edit_link" href="bannerCentroForm.php?id_home=<?php echo $row_rshome1['id_home']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rshome1 = mysql_fetch_assoc($rshome1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listhome1->Prepare();
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
        <a class="KT_additem_op_link" href="bannerCentroForm.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($rshome1);
?>
