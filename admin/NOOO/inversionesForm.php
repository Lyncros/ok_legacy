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
$formValidation->addField("titulo_inv", true, "text", "", "", "", "");
$formValidation->addField("bajada_inv", true, "text", "", "", "", "");
$formValidation->addField("zona_inv", true, "text", "", "", "", "");
$formValidation->addField("descrip_inv", true, "text", "", "", "", "");
$formValidation->addField("foto_inv", true, "text", "", "", "", "");
$formValidation->addField("activa_inv", true, "", "", "", "", "");
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
  $deleteObj->setFolder("../inversiones/");
  $deleteObj->setDbFieldName("foto_inv");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("foto_inv");
  $uploadObj->setDbFieldName("foto_inv");
  $uploadObj->setFolder("../inversiones/");
  $uploadObj->setResize("true", 123, 172);
  $uploadObj->setMaxSize(3500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

// Make an insert transaction instance
$ins_inversiones = new tNG_multipleInsert($conn_config);
$tNGs->addTransaction($ins_inversiones);
// Register triggers
$ins_inversiones->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_inversiones->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_inversiones->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_inversiones->registerTrigger("BEFORE", "Trigger_SetOrderColumn", 50);
$ins_inversiones->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$ins_inversiones->setTable("inversiones");
$ins_inversiones->addColumn("titulo_inv", "STRING_TYPE", "POST", "titulo_inv");
$ins_inversiones->addColumn("bajada_inv", "STRING_TYPE", "POST", "bajada_inv");
$ins_inversiones->addColumn("zona_inv", "STRING_TYPE", "POST", "zona_inv");
$ins_inversiones->addColumn("descrip_inv", "STRING_TYPE", "POST", "descrip_inv");
$ins_inversiones->addColumn("foto_inv", "FILE_TYPE", "FILES", "foto_inv");
$ins_inversiones->addColumn("link_inv", "STRING_TYPE", "POST", "link_inv");
$ins_inversiones->addColumn("activa_inv", "CHECKBOX_1_0_TYPE", "POST", "activa_inv", "0");
$ins_inversiones->setPrimaryKey("id_inversiones", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_inversiones = new tNG_multipleUpdate($conn_config);
$tNGs->addTransaction($upd_inversiones);
// Register triggers
$upd_inversiones->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_inversiones->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_inversiones->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_inversiones->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$upd_inversiones->setTable("inversiones");
$upd_inversiones->addColumn("titulo_inv", "STRING_TYPE", "POST", "titulo_inv");
$upd_inversiones->addColumn("bajada_inv", "STRING_TYPE", "POST", "bajada_inv");
$upd_inversiones->addColumn("zona_inv", "STRING_TYPE", "POST", "zona_inv");
$upd_inversiones->addColumn("descrip_inv", "STRING_TYPE", "POST", "descrip_inv");
$upd_inversiones->addColumn("foto_inv", "FILE_TYPE", "FILES", "foto_inv");
$upd_inversiones->addColumn("link_inv", "STRING_TYPE", "POST", "link_inv");
$upd_inversiones->addColumn("activa_inv", "CHECKBOX_1_0_TYPE", "POST", "activa_inv");
$upd_inversiones->setPrimaryKey("id_inversiones", "NUMERIC_TYPE", "GET", "id_inversiones");

// Make an instance of the transaction object
$del_inversiones = new tNG_multipleDelete($conn_config);
$tNGs->addTransaction($del_inversiones);
// Register triggers
$del_inversiones->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_inversiones->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_inversiones->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_inversiones->setTable("inversiones");
$del_inversiones->setPrimaryKey("id_inversiones", "NUMERIC_TYPE", "GET", "id_inversiones");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsinversiones = $tNGs->getRecordset("inversiones");
$row_rsinversiones = mysql_fetch_assoc($rsinversiones);
$totalRows_rsinversiones = mysql_num_rows($rsinversiones);
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
if (@$_GET['id_inversiones'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Inversiones </h1>
  <div class="KT_tngform">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" enctype="multipart/form-data" id="form1">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsinversiones > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="titulo_inv_<?php echo $cnt1; ?>">Titulo:</label></td>
            <td><input type="text" name="titulo_inv_<?php echo $cnt1; ?>" id="titulo_inv_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinversiones['titulo_inv']); ?>" size="32" maxlength="100" />
              <?php echo $tNGs->displayFieldHint("titulo_inv");?> <?php echo $tNGs->displayFieldError("inversiones", "titulo_inv", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="bajada_inv_<?php echo $cnt1; ?>">Bajada:</label></td>
            <td><input type="text" name="bajada_inv_<?php echo $cnt1; ?>" id="bajada_inv_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinversiones['bajada_inv']); ?>" size="32" maxlength="100" />
              <?php echo $tNGs->displayFieldHint("bajada_inv");?> <?php echo $tNGs->displayFieldError("inversiones", "bajada_inv", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="zona_inv_<?php echo $cnt1; ?>">Zona:</label></td>
            <td><input type="text" name="zona_inv_<?php echo $cnt1; ?>" id="zona_inv_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinversiones['zona_inv']); ?>" size="32" maxlength="100" />
              <?php echo $tNGs->displayFieldHint("zona_inv");?> <?php echo $tNGs->displayFieldError("inversiones", "zona_inv", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="descrip_inv_<?php echo $cnt1; ?>">Descrip:</label></td>
            <td><textarea name="descrip_inv_<?php echo $cnt1; ?>" id="descrip_inv_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsinversiones['descrip_inv']); ?></textarea>
              <?php echo $tNGs->displayFieldHint("descrip_inv");?> <?php echo $tNGs->displayFieldError("inversiones", "descrip_inv", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="foto_inv_<?php echo $cnt1; ?>">Foto:</label></td>
            <td>
              <input name="foto_inv_<?php echo $cnt1; ?>" type="file" id="foto_inv_<?php echo $cnt1; ?>" value="undefined" size="32" />
            
              <?php echo $tNGs->displayFieldHint("foto_inv");?> <?php echo $tNGs->displayFieldError("inversiones", "foto_inv", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="link_inv_<?php echo $cnt1; ?>">Link:</label></td>
            <td><input type="text" name="link_inv_<?php echo $cnt1; ?>" id="link_inv_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinversiones['link_inv']); ?>" size="32" maxlength="250" />
              <?php echo $tNGs->displayFieldHint("link_inv");?> <?php echo $tNGs->displayFieldError("inversiones", "link_inv", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="activa_inv_<?php echo $cnt1; ?>">Activa:</label></td>
            <td><input  <?php if (!(strcmp(KT_escapeAttribute($row_rsinversiones['activa_inv']),"1"))) {echo "checked";} ?> type="checkbox" name="activa_inv_<?php echo $cnt1; ?>" id="activa_inv_<?php echo $cnt1; ?>" value="1" />
              <?php echo $tNGs->displayFieldError("inversiones", "activa_inv", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_inversiones_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsinversiones['kt_pk_inversiones']); ?>" />
        <?php } while ($row_rsinversiones = mysql_fetch_assoc($rsinversiones)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_inversiones'] == "") {
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