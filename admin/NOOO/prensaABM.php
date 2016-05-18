<?php require_once('../Connections/config.php'); ?>
<?php
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
$tfi_listprensa1 = new TFI_TableFilter($conn_config, "tfi_listprensa1");
$tfi_listprensa1->addColumn("medios.id_medio", "NUMERIC_TYPE", "id_medio", "=");
$tfi_listprensa1->addColumn("prensa.fecha_nota", "DATE_TYPE", "fecha_nota", "=");
$tfi_listprensa1->addColumn("prensa.titu_nota", "STRING_TYPE", "titu_nota", "%");
$tfi_listprensa1->addColumn("prensa.img_nota", "FILE_TYPE", "img_nota", "%");
$tfi_listprensa1->addColumn("prensa.link_nota", "STRING_TYPE", "link_nota", "%");
$tfi_listprensa1->Execute();

// Sorter
$tso_listprensa1 = new TSO_TableSorter("rsprensa1", "tso_listprensa1");
$tso_listprensa1->addColumn("medios.nombre_medio");
$tso_listprensa1->addColumn("prensa.fecha_nota");
$tso_listprensa1->addColumn("prensa.titu_nota");
$tso_listprensa1->addColumn("prensa.img_nota");
$tso_listprensa1->addColumn("prensa.link_nota");
$tso_listprensa1->setDefault("prensa.fecha_nota DESC");
$tso_listprensa1->Execute();

// Navigation
$nav_listprensa1 = new NAV_Regular("nav_listprensa1", "rsprensa1", "../", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_config, $config);
$query_Recordset1 = "SELECT nombre_medio, id_medio FROM medios ORDER BY nombre_medio";
$Recordset1 = mysql_query($query_Recordset1, $config) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

//NeXTenesio3 Special List Recordset
$maxRows_rsprensa1 = $_SESSION['max_rows_nav_listprensa1'];
$pageNum_rsprensa1 = 0;
if (isset($_GET['pageNum_rsprensa1'])) {
  $pageNum_rsprensa1 = $_GET['pageNum_rsprensa1'];
}
$startRow_rsprensa1 = $pageNum_rsprensa1 * $maxRows_rsprensa1;

// Defining List Recordset variable
$NXTFilter_rsprensa1 = "1=1";
if (isset($_SESSION['filter_tfi_listprensa1'])) {
  $NXTFilter_rsprensa1 = $_SESSION['filter_tfi_listprensa1'];
}
// Defining List Recordset variable
$NXTSort_rsprensa1 = "prensa.fecha_nota DESC";
if (isset($_SESSION['sorter_tso_listprensa1'])) {
  $NXTSort_rsprensa1 = $_SESSION['sorter_tso_listprensa1'];
}
mysql_select_db($database_config, $config);

$query_rsprensa1 = "SELECT medios.nombre_medio AS id_medio, prensa.fecha_nota, prensa.titu_nota, prensa.img_nota, prensa.link_nota, prensa.id_prensa FROM prensa LEFT JOIN medios ON prensa.id_medio = medios.id_medio WHERE {$NXTFilter_rsprensa1} ORDER BY {$NXTSort_rsprensa1}";
$query_limit_rsprensa1 = sprintf("%s LIMIT %d, %d", $query_rsprensa1, $startRow_rsprensa1, $maxRows_rsprensa1);
$rsprensa1 = mysql_query($query_limit_rsprensa1, $config) or die(mysql_error());
$row_rsprensa1 = mysql_fetch_assoc($rsprensa1);

if (isset($_GET['totalRows_rsprensa1'])) {
  $totalRows_rsprensa1 = $_GET['totalRows_rsprensa1'];
} else {
  $all_rsprensa1 = mysql_query($query_rsprensa1);
  $totalRows_rsprensa1 = mysql_num_rows($all_rsprensa1);
}
$totalPages_rsprensa1 = ceil($totalRows_rsprensa1/$maxRows_rsprensa1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listprensa1->checkBoundries();
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
  .KT_col_id_medio {width:70px; overflow:hidden;}
  .KT_col_fecha_nota {width:70px; overflow:hidden;}
  .KT_col_titu_nota {width:140px; overflow:hidden;}
  .KT_col_img_nota {width:140px; overflow:hidden;}
  .KT_col_link_nota {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listprensa1">
  <h1> Prensa
    <?php
  $nav_listprensa1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listprensa1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listprensa1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listprensa1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listprensa1'] == 1) {
?>
          <a href="<?php echo $tfi_listprensa1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listprensa1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="id_medio" class="KT_sorter KT_col_id_medio <?php echo $tso_listprensa1->getSortIcon('medios.nombre_medio'); ?>"> <a href="<?php echo $tso_listprensa1->getSortLink('medios.nombre_medio'); ?>">Medio</a></th>
            <th id="fecha_nota" class="KT_sorter KT_col_fecha_nota <?php echo $tso_listprensa1->getSortIcon('prensa.fecha_nota'); ?>"> <a href="<?php echo $tso_listprensa1->getSortLink('prensa.fecha_nota'); ?>">Fecha nota</a></th>
            <th id="titu_nota" class="KT_sorter KT_col_titu_nota <?php echo $tso_listprensa1->getSortIcon('prensa.titu_nota'); ?>"> <a href="<?php echo $tso_listprensa1->getSortLink('prensa.titu_nota'); ?>">Titulo</a></th>
            <th id="img_nota" class="KT_sorter KT_col_img_nota <?php echo $tso_listprensa1->getSortIcon('prensa.img_nota'); ?>"> <a href="<?php echo $tso_listprensa1->getSortLink('prensa.img_nota'); ?>">Imagen</a></th>
            <th id="link_nota" class="KT_sorter KT_col_link_nota <?php echo $tso_listprensa1->getSortIcon('prensa.link_nota'); ?>"> <a href="<?php echo $tso_listprensa1->getSortLink('prensa.link_nota'); ?>">Link</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listprensa1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><select name="tfi_listprensa1_id_medio" id="tfi_listprensa1_id_medio">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listprensa1_id_medio']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset1['id_medio']?>"<?php if (!(strcmp($row_Recordset1['id_medio'], @$_SESSION['tfi_listprensa1_id_medio']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['nombre_medio']?></option>
                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
              </select></td>
              <td><input type="text" name="tfi_listprensa1_fecha_nota" id="tfi_listprensa1_fecha_nota" value="<?php echo @$_SESSION['tfi_listprensa1_fecha_nota']; ?>" size="10" maxlength="22" /></td>
              <td><input type="text" name="tfi_listprensa1_titu_nota" id="tfi_listprensa1_titu_nota" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listprensa1_titu_nota']); ?>" size="20" maxlength="250" /></td>
              <td><input type="text" name="tfi_listprensa1_img_nota" id="tfi_listprensa1_img_nota" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listprensa1_img_nota']); ?>" size="20" maxlength="250" /></td>
              <td><input type="text" name="tfi_listprensa1_link_nota" id="tfi_listprensa1_link_nota" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listprensa1_link_nota']); ?>" size="20" maxlength="250" /></td>
              <td><input type="submit" name="tfi_listprensa1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsprensa1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="7"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsprensa1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_prensa" class="id_checkbox" value="<?php echo $row_rsprensa1['id_prensa']; ?>" />
                  <input type="hidden" name="id_prensa" class="id_field" value="<?php echo $row_rsprensa1['id_prensa']; ?>" /></td>
                <td><div class="KT_col_id_medio"><?php echo KT_FormatForList($row_rsprensa1['id_medio'], 10); ?></div></td>
                <td><div class="KT_col_fecha_nota"><?php echo KT_formatDate($row_rsprensa1['fecha_nota']); ?></div></td>
                <td><div class="KT_col_titu_nota"><?php echo KT_FormatForList($row_rsprensa1['titu_nota'], 20); ?></div></td>
                <td><div class="KT_col_img_nota"><?php echo KT_FormatForList($row_rsprensa1['img_nota'], 20); ?></div></td>
                <td><div class="KT_col_link_nota"><?php echo KT_FormatForList($row_rsprensa1['link_nota'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="prensaForm.php?id_prensa=<?php echo $row_rsprensa1['id_prensa']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsprensa1 = mysql_fetch_assoc($rsprensa1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listprensa1->Prepare();
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
        <a class="KT_additem_op_link" href="prensaForm.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($rsprensa1);
?>
