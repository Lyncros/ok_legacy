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
$formValidation->addField("id_medio", true, "numeric", "", "", "", "");
$formValidation->addField("fecha_nota", true, "date", "", "", "", "");
$formValidation->addField("titu_nota", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../prensa/");
  $deleteObj->setDbFieldName("img_nota");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("img_nota");
  $uploadObj->setDbFieldName("img_nota");
  $uploadObj->setFolder("../prensa/");
  $uploadObj->setResize("true", 800, 0);
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
$query_medios = "SELECT id_medio, nombre_medio FROM medios ORDER BY nombre_medio ASC";
$medios = mysql_query($query_medios, $config) or die(mysql_error());
$row_medios = mysql_fetch_assoc($medios);
$totalRows_medios = mysql_num_rows($medios);

// Make an insert transaction instance
$ins_prensa = new tNG_multipleInsert($conn_config);
$tNGs->addTransaction($ins_prensa);
// Register triggers
$ins_prensa->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_prensa->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_prensa->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_prensa->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$ins_prensa->setTable("prensa");
$ins_prensa->addColumn("id_medio", "NUMERIC_TYPE", "POST", "id_medio");
$ins_prensa->addColumn("fecha_nota", "DATE_TYPE", "POST", "fecha_nota");
$ins_prensa->addColumn("titu_nota", "STRING_TYPE", "POST", "titu_nota");
$ins_prensa->addColumn("img_nota", "FILE_TYPE", "FILES", "img_nota");
$ins_prensa->addColumn("link_nota", "STRING_TYPE", "POST", "link_nota");
$ins_prensa->setPrimaryKey("id_prensa", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_prensa = new tNG_multipleUpdate($conn_config);
$tNGs->addTransaction($upd_prensa);
// Register triggers
$upd_prensa->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_prensa->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_prensa->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_prensa->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$upd_prensa->setTable("prensa");
$upd_prensa->addColumn("id_medio", "NUMERIC_TYPE", "POST", "id_medio");
$upd_prensa->addColumn("fecha_nota", "DATE_TYPE", "POST", "fecha_nota");
$upd_prensa->addColumn("titu_nota", "STRING_TYPE", "POST", "titu_nota");
$upd_prensa->addColumn("img_nota", "FILE_TYPE", "FILES", "img_nota");
$upd_prensa->addColumn("link_nota", "STRING_TYPE", "POST", "link_nota");
$upd_prensa->setPrimaryKey("id_prensa", "NUMERIC_TYPE", "GET", "id_prensa");

// Make an instance of the transaction object
$del_prensa = new tNG_multipleDelete($conn_config);
$tNGs->addTransaction($del_prensa);
// Register triggers
$del_prensa->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_prensa->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_prensa->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_prensa->setTable("prensa");
$del_prensa->setPrimaryKey("id_prensa", "NUMERIC_TYPE", "GET", "id_prensa");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsprensa = $tNGs->getRecordset("prensa");
$row_rsprensa = mysql_fetch_assoc($rsprensa);
$totalRows_rsprensa = mysql_num_rows($rsprensa);
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
<script src="../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="../js/ui/ui.datepicker.js" type="text/javascript"></script>
<link href="../js/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: true,
  merge_down_value: false
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
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['id_prensa'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Prensa </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsprensa > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="id_medio_<?php echo $cnt1; ?>">Medio:</label></td>
            <td><select name="id_medio_<?php echo $cnt1; ?>" id="id_medio_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_medios['id_medio']?>"<?php if (!(strcmp($row_medios['id_medio'], $row_rsprensa['id_medio']))) {echo "SELECTED";} ?>><?php echo $row_medios['nombre_medio']?></option>
              <?php
} while ($row_medios = mysql_fetch_assoc($medios));
  $rows = mysql_num_rows($medios);
  if($rows > 0) {
      mysql_data_seek($medios, 0);
	  $row_medios = mysql_fetch_assoc($medios);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("prensa", "id_medio", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="fecha_nota_<?php echo $cnt1; ?>">Fecha nota:</label></td>
            <td><input type="text" name="fecha_nota_<?php echo $cnt1; ?>" id="fecha_nota_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsprensa['fecha_nota']); ?>" size="10" maxlength="22" />
            <script type="text/javascript">
                // BeginWebWidget jQuery_UI_Calendar: jQueryUICalendar1
                jQuery("#fecha_nota_<?php echo $cnt1; ?>").datepicker({numberOfMonths: 1,showButtonPanel: true, dateFormat: "dd-mm-yy"});
                // EndWebWidget jQuery_UI_Calendar: jQueryUICalendar1
              </script>
              <?php echo $tNGs->displayFieldHint("fecha_nota");?> <?php echo $tNGs->displayFieldError("prensa", "fecha_nota", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="titu_nota_<?php echo $cnt1; ?>">Titulo:</label></td>
            <td><input type="text" name="titu_nota_<?php echo $cnt1; ?>" id="titu_nota_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsprensa['titu_nota']); ?>" size="32" maxlength="250" />
              <?php echo $tNGs->displayFieldHint("titu_nota");?> <?php echo $tNGs->displayFieldError("prensa", "titu_nota", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="img_nota_<?php echo $cnt1; ?>">Imagen:</label></td>
            <td><input type="file" name="img_nota_<?php echo $cnt1; ?>" id="img_nota_<?php echo $cnt1; ?>" size="32" />
              <?php echo $tNGs->displayFieldError("prensa", "img_nota", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="link_nota_<?php echo $cnt1; ?>">Link:</label></td>
            <td><input type="text" name="link_nota_<?php echo $cnt1; ?>" id="link_nota_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsprensa['link_nota']); ?>" size="32" maxlength="250" />
              <?php echo $tNGs->displayFieldHint("link_nota");?> <?php echo $tNGs->displayFieldError("prensa", "link_nota", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_prensa_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsprensa['kt_pk_prensa']); ?>" />
        <?php } while ($row_rsprensa = mysql_fetch_assoc($rsprensa)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_prensa'] == "") {
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
mysql_free_result($medios);
?>
