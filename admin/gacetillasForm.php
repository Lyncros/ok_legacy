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
$formValidation->addField("titu_gacetilla", true, "text", "", "", "", "");
$formValidation->addField("txt_gacetilla", true, "text", "", "", "", "");
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
  $deleteObj->setFolder("../gacetillas/");
  $deleteObj->setDbFieldName("img_gacetilla");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("img_gacetilla");
  $uploadObj->setDbFieldName("img_gacetilla");
  $uploadObj->setFolder("../gacetillas/");
  $uploadObj->setResize("true", 250, 400);
  $uploadObj->setMaxSize(3500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

// Make an insert transaction instance
$ins_gacetillas = new tNG_multipleInsert($conn_config);
$tNGs->addTransaction($ins_gacetillas);
// Register triggers
$ins_gacetillas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_gacetillas->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_gacetillas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_gacetillas->registerTrigger("BEFORE", "Trigger_SetOrderColumn", 50);
$ins_gacetillas->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$ins_gacetillas->setTable("gacetillas");
$ins_gacetillas->addColumn("titu_gacetilla", "STRING_TYPE", "POST", "titu_gacetilla");
$ins_gacetillas->addColumn("bajada_gacetilla", "STRING_TYPE", "POST", "bajada_gacetilla");
$ins_gacetillas->addColumn("txt_gacetilla", "STRING_TYPE", "POST", "txt_gacetilla");
$ins_gacetillas->addColumn("fecha_gacetilla", "DATE_TYPE", "POST", "fecha_gacetilla");
$ins_gacetillas->addColumn("img_gacetilla", "FILE_TYPE", "FILES", "img_gacetilla");
$ins_gacetillas->setPrimaryKey("id_gacetilla", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_gacetillas = new tNG_multipleUpdate($conn_config);
$tNGs->addTransaction($upd_gacetillas);
// Register triggers
$upd_gacetillas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_gacetillas->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_gacetillas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_gacetillas->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$upd_gacetillas->setTable("gacetillas");
$upd_gacetillas->addColumn("titu_gacetilla", "STRING_TYPE", "POST", "titu_gacetilla");
$upd_gacetillas->addColumn("bajada_gacetilla", "STRING_TYPE", "POST", "bajada_gacetilla");
$upd_gacetillas->addColumn("txt_gacetilla", "STRING_TYPE", "POST", "txt_gacetilla");
$upd_gacetillas->addColumn("fecha_gacetilla", "DATE_TYPE", "POST", "fecha_gacetilla");
$upd_gacetillas->addColumn("img_gacetilla", "FILE_TYPE", "FILES", "img_gacetilla");
$upd_gacetillas->setPrimaryKey("id_gacetilla", "NUMERIC_TYPE", "GET", "id_gacetilla");

// Make an instance of the transaction object
$del_gacetillas = new tNG_multipleDelete($conn_config);
$tNGs->addTransaction($del_gacetillas);
// Register triggers
$del_gacetillas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_gacetillas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_gacetillas->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_gacetillas->setTable("gacetillas");
$del_gacetillas->setPrimaryKey("id_gacetilla", "NUMERIC_TYPE", "GET", "id_gacetilla");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsgacetillas = $tNGs->getRecordset("gacetillas");
$row_rsgacetillas = mysql_fetch_assoc($rsgacetillas);
$totalRows_rsgacetillas = mysql_num_rows($rsgacetillas);
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
<script src="../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
<script src="../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: true,
  merge_down_value: true
}
jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});

</script>
<link href="../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['id_gacetilla'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Gacetillas </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsgacetillas > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="titu_gacetilla_<?php echo $cnt1; ?>">Titulo:</label></td>
            <td><input type="text" name="titu_gacetilla_<?php echo $cnt1; ?>" id="titu_gacetilla_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsgacetillas['titu_gacetilla']); ?>" size="32" maxlength="250" />
              <?php echo $tNGs->displayFieldHint("titu_gacetilla");?> <?php echo $tNGs->displayFieldError("gacetillas", "titu_gacetilla", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="bajada_gacetilla_<?php echo $cnt1; ?>">Bajada:</label></td>
            <td><textarea name="bajada_gacetilla_<?php echo $cnt1; ?>" id="bajada_gacetilla_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsgacetillas['bajada_gacetilla']); ?></textarea>
              <?php echo $tNGs->displayFieldHint("bajada_gacetilla");?> <?php echo $tNGs->displayFieldError("gacetillas", "bajada_gacetilla", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="txt_gacetilla_<?php echo $cnt1; ?>">Texto:</label></td>
            <td><textarea name="txt_gacetilla_<?php echo $cnt1; ?>" id="txt_gacetilla_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsgacetillas['txt_gacetilla']); ?></textarea>
              <?php echo $tNGs->displayFieldHint("txt_gacetilla");?> <?php echo $tNGs->displayFieldError("gacetillas", "txt_gacetilla", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="fecha_gacetilla_<?php echo $cnt1; ?>">Fecha:</label></td>
            <td><input type="text" name="fecha_gacetilla_<?php echo $cnt1; ?>" id="fecha_gacetilla_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsgacetillas['fecha_gacetilla']); ?>" size="10" maxlength="22" />
              <script type="text/javascript">
// BeginWebWidget jQuery_UI_Calendar: jQueryUICalendar1
jQuery("#fecha_gacetilla_<?php echo $cnt1; ?>").datepicker();

// EndWebWidget jQuery_UI_Calendar: jQueryUICalendar1
              </script>
            <?php echo $tNGs->displayFieldHint("fecha_gacetilla");?> <?php echo $tNGs->displayFieldError("gacetillas", "fecha_gacetilla", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="img_gacetilla_<?php echo $cnt1; ?>">Imagen:</label></td>
            <td><input type="file" name="img_gacetilla_<?php echo $cnt1; ?>" id="img_gacetilla_<?php echo $cnt1; ?>" size="32" />
              <?php echo $tNGs->displayFieldError("gacetillas", "img_gacetilla", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_gacetillas_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsgacetillas['kt_pk_gacetillas']); ?>" />
        <?php } while ($row_rsgacetillas = mysql_fetch_assoc($rsgacetillas)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_gacetilla'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'id_gacetilla')" />
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