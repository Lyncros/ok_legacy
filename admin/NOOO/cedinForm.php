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
$formValidation->addField("titulo_cedin", true, "text", "", "", "", "");
$formValidation->addField("link_cedin", true, "text", "", "", "", "");
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

// Make an insert transaction instance
$ins_cedin = new tNG_multipleInsert($conn_config);
$tNGs->addTransaction($ins_cedin);
// Register triggers
$ins_cedin->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_cedin->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_cedin->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_cedin->registerTrigger("BEFORE", "Trigger_SetOrderColumn", 50);
// Add columns
$ins_cedin->setTable("cedin");
$ins_cedin->addColumn("titulo_cedin", "STRING_TYPE", "POST", "titulo_cedin");
$ins_cedin->addColumn("link_cedin", "STRING_TYPE", "POST", "link_cedin");
$ins_cedin->setPrimaryKey("id_cedin", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_cedin = new tNG_multipleUpdate($conn_config);
$tNGs->addTransaction($upd_cedin);
// Register triggers
$upd_cedin->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_cedin->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_cedin->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_cedin->setTable("cedin");
$upd_cedin->addColumn("titulo_cedin", "STRING_TYPE", "POST", "titulo_cedin");
$upd_cedin->addColumn("link_cedin", "STRING_TYPE", "POST", "link_cedin");
$upd_cedin->setPrimaryKey("id_cedin", "NUMERIC_TYPE", "GET", "id_cedin");

// Make an instance of the transaction object
$del_cedin = new tNG_multipleDelete($conn_config);
$tNGs->addTransaction($del_cedin);
// Register triggers
$del_cedin->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_cedin->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_cedin->setTable("cedin");
$del_cedin->setPrimaryKey("id_cedin", "NUMERIC_TYPE", "GET", "id_cedin");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscedin = $tNGs->getRecordset("cedin");
$row_rscedin = mysql_fetch_assoc($rscedin);
$totalRows_rscedin = mysql_num_rows($rscedin);
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
  merge_down_value: true
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
if (@$_GET['id_cedin'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Cedin </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rscedin > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="titulo_cedin_<?php echo $cnt1; ?>">Titulo:</label></td>
            <td><input type="text" name="titulo_cedin_<?php echo $cnt1; ?>" id="titulo_cedin_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscedin['titulo_cedin']); ?>" size="32" maxlength="250" />
              <?php echo $tNGs->displayFieldHint("titulo_cedin");?> <?php echo $tNGs->displayFieldError("cedin", "titulo_cedin", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="link_cedin_<?php echo $cnt1; ?>">Link externo:</label></td>
            <td><input type="text" name="link_cedin_<?php echo $cnt1; ?>" id="link_cedin_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscedin['link_cedin']); ?>" size="32" maxlength="250" />
              <?php echo $tNGs->displayFieldHint("link_cedin");?> <?php echo $tNGs->displayFieldError("cedin", "link_cedin", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_cedin_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rscedin['kt_pk_cedin']); ?>" />
        <?php } while ($row_rscedin = mysql_fetch_assoc($rscedin)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_cedin'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'id_cedin')" />
            </div>
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