<?php require_once('../Connections/config.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_config = new KT_connection($config, $database_config);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("area", true, "numeric", "", "", "", "");
$formValidation->addField("foto", true, "", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_SetOrderColumn trigger
//remove this line if you want to edit the code by hand 
function Trigger_SetOrderColumn(&$tNG) {
  $orderFieldObj = new tNG_SetOrderField($tNG);
  $orderFieldObj->setFieldName("orden");
  return $orderFieldObj->Execute();
}
//end Trigger_SetOrderColumn trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../bannerTop/");
  $deleteObj->setDbFieldName("foto");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("foto");
  $uploadObj->setDbFieldName("foto");
  $uploadObj->setFolder("../bannerTop/");
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png, swf");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

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
$query_areas = "SELECT * FROM areas ORDER BY area ASC";
$areas = mysql_query($query_areas, $config) or die(mysql_error());
$row_areas = mysql_fetch_assoc($areas);
$totalRows_areas = mysql_num_rows($areas);

// Make an insert transaction instance
$ins_bannertop = new tNG_multipleInsert($conn_config);
$tNGs->addTransaction($ins_bannertop);
// Register triggers
$ins_bannertop->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_bannertop->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_bannertop->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_bannertop->registerTrigger("BEFORE", "Trigger_SetOrderColumn", 50);
$ins_bannertop->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$ins_bannertop->setTable("bannertop");
$ins_bannertop->addColumn("area", "NUMERIC_TYPE", "POST", "area");
$ins_bannertop->addColumn("foto", "FILE_TYPE", "FILES", "foto");
$ins_bannertop->addColumn("link", "STRING_TYPE", "POST", "link");
$ins_bannertop->addColumn("activo", "CHECKBOX_1_0_TYPE", "POST", "activo", "0");
$ins_bannertop->setPrimaryKey("id_home", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_bannertop = new tNG_multipleUpdate($conn_config);
$tNGs->addTransaction($upd_bannertop);
// Register triggers
$upd_bannertop->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_bannertop->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_bannertop->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_bannertop->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$upd_bannertop->setTable("bannertop");
$upd_bannertop->addColumn("area", "NUMERIC_TYPE", "POST", "area");
$upd_bannertop->addColumn("foto", "FILE_TYPE", "FILES", "foto");
$upd_bannertop->addColumn("link", "STRING_TYPE", "POST", "link");
$upd_bannertop->addColumn("activo", "CHECKBOX_1_0_TYPE", "POST", "activo");
$upd_bannertop->setPrimaryKey("id_home", "NUMERIC_TYPE", "GET", "id_home");

// Make an instance of the transaction object
$del_bannertop = new tNG_multipleDelete($conn_config);
$tNGs->addTransaction($del_bannertop);
// Register triggers
$del_bannertop->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_bannertop->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_bannertop->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_bannertop->setTable("bannertop");
$del_bannertop->setPrimaryKey("id_home", "NUMERIC_TYPE", "GET", "id_home");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsbannertop = $tNGs->getRecordset("bannertop");
$row_rsbannertop = mysql_fetch_assoc($rsbannertop);
$totalRows_rsbannertop = mysql_num_rows($rsbannertop);
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
<?php echo $tNGs->displayValidationRules();?>
<script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: true,
  merge_down_value: false
}
</script>
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['id_home'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Bannertop </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsbannertop > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="area_<?php echo $cnt1; ?>">Area:</label></td>
            <td><select name="area_<?php echo $cnt1; ?>" id="area_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_areas['id_area']?>"<?php if (!(strcmp($row_areas['id_area'], $row_rsbannertop['area']))) {echo "SELECTED";} ?>><?php echo $row_areas['area']?></option>
              <?php
} while ($row_areas = mysql_fetch_assoc($areas));
  $rows = mysql_num_rows($areas);
  if($rows > 0) {
      mysql_data_seek($areas, 0);
	  $row_areas = mysql_fetch_assoc($areas);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("bannertop", "area", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="foto_<?php echo $cnt1; ?>">Foto:</label></td>
            <td><input type="file" name="foto_<?php echo $cnt1; ?>" id="foto_<?php echo $cnt1; ?>" size="32" />
              <?php echo $tNGs->displayFieldError("bannertop", "foto", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="link_<?php echo $cnt1; ?>">Link:</label></td>
            <td><input type="text" name="link_<?php echo $cnt1; ?>" id="link_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsbannertop['link']); ?>" size="32" maxlength="250" />
              <?php echo $tNGs->displayFieldHint("link");?> <?php echo $tNGs->displayFieldError("bannertop", "link", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="activo_<?php echo $cnt1; ?>">Activo:</label></td>
            <td><input  <?php if (!(strcmp(KT_escapeAttribute($row_rsbannertop['activo']),"1"))) {echo "checked";} ?> type="checkbox" name="activo_<?php echo $cnt1; ?>" id="activo_<?php echo $cnt1; ?>" value="1" />
              <?php echo $tNGs->displayFieldError("bannertop", "activo", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_bannertop_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsbannertop['kt_pk_bannertop']); ?>" />
        <?php } while ($row_rsbannertop = mysql_fetch_assoc($rsbannertop)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_home'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
            <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
            <?php }
      // endif Conditional region1
      ?>
<input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
        </div>
      </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($areas);
?>
