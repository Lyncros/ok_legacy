<?php require_once('../Connections/config.php'); ?>
<?php
// Valida Usuario
require_once('../inc/validaUsuario.php');

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
$formValidation->addField("nombre_medio", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../medios/");
  $deleteObj->setDbFieldName("logo_medio");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("logo_medio");
  $uploadObj->setDbFieldName("logo_medio");
  $uploadObj->setFolder("../medios/");
  $uploadObj->setResize("true", 160, 160);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png, swf");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

// Make an insert transaction instance
$ins_medios = new tNG_multipleInsert($conn_config);
$tNGs->addTransaction($ins_medios);
// Register triggers
$ins_medios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_medios->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_medios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_medios->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$ins_medios->setTable("medios");
$ins_medios->addColumn("nombre_medio", "STRING_TYPE", "POST", "nombre_medio");
$ins_medios->addColumn("logo_medio", "FILE_TYPE", "FILES", "logo_medio");
$ins_medios->setPrimaryKey("id_medio", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_medios = new tNG_multipleUpdate($conn_config);
$tNGs->addTransaction($upd_medios);
// Register triggers
$upd_medios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_medios->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_medios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_medios->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$upd_medios->setTable("medios");
$upd_medios->addColumn("nombre_medio", "STRING_TYPE", "POST", "nombre_medio");
$upd_medios->addColumn("logo_medio", "FILE_TYPE", "FILES", "logo_medio");
$upd_medios->setPrimaryKey("id_medio", "NUMERIC_TYPE", "GET", "id_medio");

// Make an instance of the transaction object
$del_medios = new tNG_multipleDelete($conn_config);
$tNGs->addTransaction($del_medios);
// Register triggers
$del_medios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_medios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_medios->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_medios->setTable("medios");
$del_medios->setPrimaryKey("id_medio", "NUMERIC_TYPE", "GET", "id_medio");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsmedios = $tNGs->getRecordset("medios");
$row_rsmedios = mysql_fetch_assoc($rsmedios);
$totalRows_rsmedios = mysql_num_rows($rsmedios);
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
if (@$_GET['id_medio'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Medios </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsmedios > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="nombre_medio_<?php echo $cnt1; ?>">Nombre_medio:</label></td>
            <td><input type="text" name="nombre_medio_<?php echo $cnt1; ?>" id="nombre_medio_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmedios['nombre_medio']); ?>" size="32" maxlength="250" />
              <?php echo $tNGs->displayFieldHint("nombre_medio");?> <?php echo $tNGs->displayFieldError("medios", "nombre_medio", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="logo_medio_<?php echo $cnt1; ?>">Logo_medio:</label></td>
            <td><input type="file" name="logo_medio_<?php echo $cnt1; ?>" id="logo_medio_<?php echo $cnt1; ?>" size="32" />
              <?php echo $tNGs->displayFieldError("medios", "logo_medio", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_medios_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsmedios['kt_pk_medios']); ?>" />
        <?php } while ($row_rsmedios = mysql_fetch_assoc($rsmedios)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_medio'] == "") {
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