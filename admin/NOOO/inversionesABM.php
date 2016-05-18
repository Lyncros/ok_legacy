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
$tor_listinversiones1 = new TOR_SetOrder($conn_config, 'inversiones', 'id_inversiones', 'NUMERIC_TYPE', 'orden', 'listinversiones1_orden_order');
$tor_listinversiones1->Execute();

// Filter
$tfi_listinversiones1 = new TFI_TableFilter($conn_config, "tfi_listinversiones1");
$tfi_listinversiones1->addColumn("inversiones.titulo_inv", "STRING_TYPE", "titulo_inv", "%");
$tfi_listinversiones1->addColumn("inversiones.bajada_inv", "STRING_TYPE", "bajada_inv", "%");
$tfi_listinversiones1->addColumn("inversiones.zona_inv", "STRING_TYPE", "zona_inv", "%");
$tfi_listinversiones1->addColumn("inversiones.descrip_inv", "STRING_TYPE", "descrip_inv", "%");
$tfi_listinversiones1->addColumn("inversiones.foto_inv", "STRING_TYPE", "foto_inv", "%");
$tfi_listinversiones1->addColumn("inversiones.activa_inv", "CHECKBOX_1_0_TYPE", "activa_inv", "%");
$tfi_listinversiones1->Execute();

// Sorter
$tso_listinversiones1 = new TSO_TableSorter("rsinversiones1", "tso_listinversiones1");
$tso_listinversiones1->addColumn("inversiones.orden"); // Order column
$tso_listinversiones1->setDefault("inversiones.orden");
$tso_listinversiones1->Execute();

// Navigation
$nav_listinversiones1 = new NAV_Regular("nav_listinversiones1", "rsinversiones1", "../", $_SERVER['PHP_SELF'], 30);

//NeXTenesio3 Special List Recordset
$maxRows_rsinversiones1 = $_SESSION['max_rows_nav_listinversiones1'];
$pageNum_rsinversiones1 = 0;
if (isset($_GET['pageNum_rsinversiones1'])) {
  $pageNum_rsinversiones1 = $_GET['pageNum_rsinversiones1'];
}
$startRow_rsinversiones1 = $pageNum_rsinversiones1 * $maxRows_rsinversiones1;

// Defining List Recordset variable
$NXTFilter_rsinversiones1 = "1=1";
if (isset($_SESSION['filter_tfi_listinversiones1'])) {
  $NXTFilter_rsinversiones1 = $_SESSION['filter_tfi_listinversiones1'];
}
// Defining List Recordset variable
$NXTSort_rsinversiones1 = "inversiones.orden";
if (isset($_SESSION['sorter_tso_listinversiones1'])) {
  $NXTSort_rsinversiones1 = $_SESSION['sorter_tso_listinversiones1'];
}
mysql_select_db($database_config, $config);

$query_rsinversiones1 = "SELECT inversiones.titulo_inv, inversiones.bajada_inv, inversiones.zona_inv, inversiones.descrip_inv, inversiones.foto_inv, inversiones.activa_inv, inversiones.id_inversiones, inversiones.orden FROM inversiones WHERE {$NXTFilter_rsinversiones1} ORDER BY {$NXTSort_rsinversiones1}";
$query_limit_rsinversiones1 = sprintf("%s LIMIT %d, %d", $query_rsinversiones1, $startRow_rsinversiones1, $maxRows_rsinversiones1);
$rsinversiones1 = mysql_query($query_limit_rsinversiones1, $config) or die(mysql_error());
$row_rsinversiones1 = mysql_fetch_assoc($rsinversiones1);

if (isset($_GET['totalRows_rsinversiones1'])) {
  $totalRows_rsinversiones1 = $_GET['totalRows_rsinversiones1'];
} else {
  $all_rsinversiones1 = mysql_query($query_rsinversiones1);
  $totalRows_rsinversiones1 = mysql_num_rows($all_rsinversiones1);
}
$totalPages_rsinversiones1 = ceil($totalRows_rsinversiones1/$maxRows_rsinversiones1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listinversiones1->checkBoundries();
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
  .KT_col_titulo_inv {width:140px; overflow:hidden;}
  .KT_col_bajada_inv {width:140px; overflow:hidden;}
  .KT_col_zona_inv {width:140px; overflow:hidden;}
  .KT_col_descrip_inv {width:140px; overflow:hidden;}
  .KT_col_foto_inv {width:140px; overflow:hidden;}
  .KT_col_activa_inv {width:35px; overflow:hidden;}
</style>
<?php echo $tor_listinversiones1->scriptDefinition(); ?>
</head>

<body>
<div class="KT_tng" id="listinversiones1">
  <h1> Inversiones
    <?php
  $nav_listinversiones1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listinversiones1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listinversiones1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listinversiones1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listinversiones1'] == 1) {
?>
          <a href="<?php echo $tfi_listinversiones1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listinversiones1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="titulo_inv" class="KT_col_titulo_inv">Titulo</th>
            <th id="bajada_inv" class="KT_col_bajada_inv">Bajada</th>
            <th id="zona_inv" class="KT_col_zona_inv">Zona</th>
            <th id="descrip_inv" class="KT_col_descrip_inv">Descrip</th>
            <th id="foto_inv" class="KT_col_foto_inv">Foto</th>
            <th id="activa_inv" class="KT_col_activa_inv">Activa</th>
            <th id="orden" class="KT_sorter <?php echo $tso_listinversiones1->getSortIcon('inversiones.orden'); ?> KT_order"> <a href="<?php echo $tso_listinversiones1->getSortLink('inversiones.orden'); ?>"><?php echo NXT_getResource("Order"); ?></a> <a class="KT_move_op_link" href="#" onclick="nxt_list_move_link_form(this); return false;"><?php echo NXT_getResource("save"); ?></a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listinversiones1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listinversiones1_titulo_inv" id="tfi_listinversiones1_titulo_inv" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinversiones1_titulo_inv']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinversiones1_bajada_inv" id="tfi_listinversiones1_bajada_inv" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinversiones1_bajada_inv']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinversiones1_zona_inv" id="tfi_listinversiones1_zona_inv" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinversiones1_zona_inv']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinversiones1_descrip_inv" id="tfi_listinversiones1_descrip_inv" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinversiones1_descrip_inv']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinversiones1_foto_inv" id="tfi_listinversiones1_foto_inv" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinversiones1_foto_inv']); ?>" size="20" maxlength="100" /></td>
              <td><input  <?php if (!(strcmp(KT_escapeAttribute(@$_SESSION['tfi_listinversiones1_activa_inv']),"1"))) {echo "checked";} ?> type="checkbox" name="tfi_listinversiones1_activa_inv" id="tfi_listinversiones1_activa_inv" value="1" /></td>
              <td>&nbsp;</td>
              <td><input type="submit" name="tfi_listinversiones1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsinversiones1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="9"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsinversiones1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_inversiones" class="id_checkbox" value="<?php echo $row_rsinversiones1['id_inversiones']; ?>" />
                  <input type="hidden" name="id_inversiones" class="id_field" value="<?php echo $row_rsinversiones1['id_inversiones']; ?>" /></td>
                <td><div class="KT_col_titulo_inv"><?php echo KT_FormatForList($row_rsinversiones1['titulo_inv'], 20); ?></div></td>
                <td><div class="KT_col_bajada_inv"><?php echo KT_FormatForList($row_rsinversiones1['bajada_inv'], 20); ?></div></td>
                <td><div class="KT_col_zona_inv"><?php echo KT_FormatForList($row_rsinversiones1['zona_inv'], 20); ?></div></td>
                <td><div class="KT_col_descrip_inv"><?php echo KT_FormatForList($row_rsinversiones1['descrip_inv'], 20); ?></div></td>
                <td><div class="KT_col_foto_inv"><?php echo KT_FormatForList($row_rsinversiones1['foto_inv'], 20); ?></div></td>
                <td><div class="KT_col_activa_inv"><?php echo KT_FormatForList($row_rsinversiones1['activa_inv'], 5); ?></div></td>
                <td class="KT_order"><input type="hidden" class="KT_orderhidden" name="<?php echo $tor_listinversiones1->getOrderFieldName() ?>" value="<?php echo $tor_listinversiones1->getOrderFieldValue($row_rsinversiones1) ?>" />
                  <a class="KT_movedown_link" href="#move_down">v</a> <a class="KT_moveup_link" href="#move_up">^</a></td>
                <td><a class="KT_edit_link" href="inversionesForm.php?id_inversiones=<?php echo $row_rsinversiones1['id_inversiones']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsinversiones1 = mysql_fetch_assoc($rsinversiones1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listinversiones1->Prepare();
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
        <a class="KT_additem_op_link" href="inversionesForm.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsinversiones1);
?>
