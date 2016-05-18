<?php 

require_once('Connections/config.php');
require_once('inc/funciones.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$nombre = "";
$apellido = "";
$telefono = "";
$mail = "";
$fnac = "";
$prof = "";
$tipodoc = "";
$nrodoc = "";
$comen = "";
$notificationClass = '';
$notificationMessage = '';

if ((isset($_POST["contacto"])) && ($_POST["contacto"] == "1")) {
	include_once ('inc/securimage.php');
	$securimage = new Securimage();
	$captcha = $_POST['captcha'];

	if ($securimage->check($captcha) == false) {
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$telefono = $_POST['telefono'];
		$mail = $_POST['email'];
		$fnac = $_POST['edad'];
		$prof = $_POST['profesion'];
		$tipodoc = $_POST['tipodoc'];
		$nrodoc = $_POST['nrodoc'];
		$comen = $_POST['comentario'];
		$notificationClass = 'error';
		$notificationMessage = 'El c&oacute;digo de seguridad y la imagen no coinciden. Por favor, intente nuevamente.';
	
	} else {

		$checkSQL = sprintf("SELECT * FROM cv WHERE email_cv=%s", GetSQLValueString($_POST['email'], "text"));
		mysql_select_db($database_config, $config);
		$checkResult = mysql_query($checkSQL, $config) or die(mysql_error());
		$checkTotal = mysql_num_rows($checkResult);
		
		if($checkTotal != 0){
			$insertSQL = sprintf("UPDATE cv SET nombre_cv=%s, apellido_cv=%s, email_cv=%s, telefono_cv=%s, nacim_cv=%s, prof_cv=%s, tipo_doc_cv=%s, nro_doc_cv=%s, comentario_cv=%s WHERE email_cv=%s",
							GetSQLValueString($_POST['nombre'], "text"),
							GetSQLValueString($_POST['apellido'], "text"),
							GetSQLValueString($_POST['email'], "text"),
							GetSQLValueString($_POST['telefono'], "text"),
							GetSQLValueString(date("Y-m-d",strtotime($_POST['edad'])), "date"),
							GetSQLValueString($_POST['profesion'], "text"),
							GetSQLValueString($_POST['tipodoc'], "text"),
							GetSQLValueString($_POST['nrodoc'], "int"),
							GetSQLValueString($_POST['comentario'], "text"),
							GetSQLValueString($_POST['email'], "text"));
		} else {
			$insertSQL = sprintf("INSERT INTO cv (nombre_cv, apellido_cv, email_cv, telefono_cv, nacim_cv, prof_cv, tipo_doc_cv, nro_doc_cv, comentario_cv) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
							GetSQLValueString($_POST['nombre'], "text"),
							GetSQLValueString($_POST['apellido'], "text"),
							GetSQLValueString($_POST['email'], "text"),
							GetSQLValueString($_POST['telefono'], "text"),
							GetSQLValueString(date("Y-m-d",strtotime($_POST['edad'])), "date"),
							GetSQLValueString($_POST['profesion'], "text"),
							GetSQLValueString($_POST['tipodoc'], "text"),
							GetSQLValueString($_POST['nrodoc'], "int"),
							GetSQLValueString($_POST['comentario'], "text"));
		}

		mysql_select_db($database_config, $config);
		$Result1 = mysql_query($insertSQL, $config) or die(mysql_error());
		
		if($_FILES['archivo']['name'] != "") {
			$idSQL = sprintf("SELECT id_cv FROM cv WHERE email_cv=%s", GetSQLValueString($_POST['email'], "text"));
			mysql_select_db($database_config, $config);
			$idResult = mysql_query($idSQL, $config) or die(mysql_error());
			$idCV = mysql_fetch_assoc($idResult);
			$id = $idCV['id_cv'];
			
			$filename = strtolower($_FILES['archivo']['name']);
			$whitelist = array('doc', 'pdf', 'docx'); 
			$blacklist = array('php', 'php3', 'php4', 'phtml', 'exe'); 
			$extension = end(explode('.', $filename));
			
			if(!in_array(end(explode('.', $filename)), $whitelist)) {
				echo 'Invalid file type';
				exit(0);
			}
			
			if(in_array(end(explode('.', $fileName)), $blacklist)){
				header(sprintf("Location: %s", $insertGoTo));;
			} else {
				$renamed = $_POST['nombre']."_".$_POST['apellido'];
				if ($_FILES["archivo"]["error"] > 0){
					echo "Return Code: " . $_FILES["archivo"]["error"] . "<br />";
				} else {
					if (file_exists("cv/" . $renamed. $extension)){
						echo $_FILES["archivo"]["name"] . " already exists. ";
					} else {
						$nombreCV = $id."_".$renamed.".".$extension;
						move_uploaded_file($_FILES["archivo"]["tmp_name"], "cv/" . $nombreCV);
					}
				}
			}
		}

		$to = array(
			'ignacio@okeefe.com.ar' => 'Ignacio',
			'juancruz@okeefe.com.ar' => 'Juan Cruz'
		);
		
		include_once("inc/class.mail.php");
		$file1 = isset($nombreCV) ? "cv/" . $nombreCV : '';
		$head = array(
			'to'      => $to,
			'from'    => array($_POST['email'] => $_POST['nombre'])
		);
		$subject = "Envio de CV desde la WEB";
		
		$body = "Nombre: " . $_POST['nombre'] . "<br>";
		$body .= "Apellido: " . $_POST['apellido'] . "<br>";
		$body .= "Telefono: " . $_POST['telefono'] . "<br>";
		$body .= "e-mail: " . $_POST['email'] . "<br>";
		$body .= "F. Nacimiento: " . $_POST['edad'] . "<br>";
		$body .= "Profesion: " . $_POST['profesion'] . "<br>";
		$body .= "Tipo Doc.: " . $_POST['tipodoc'] . "<br>";
		$body .= "Nro. Documento: " . $_POST['nrodoc'] . "<br>";
		$body .= "Comentarios: " . $_POST['comentario'] . "<br>";
		$body .= "Curriculum: <a href='http://www.okeefe.com.ar/cv/".$nombreCV."'>Descargar Curriculum</a>";
		$body .= "<br>"."* ".$file1;
		
		$files = array($file1);

		$ret = mail::send($head, $subject, $body, $files);
		if ($ret) {
			$notificationClass = 'success';
			$notificationMessage = 'Gracias por enviarnos su CV, nuestro departamento de ';
			$notificationMessage .= 'recursos humanos lo evaluar&aacute; a la brevedad.';
		} else {
			$notificationClass = 'error';
			$notificationMessage = 'Ocurri&oacute; un error al enviar su CV. Por favor intente nuevamente.';
		}
	}
}
//------------------------------------------------------------------------------
$suc = 12;
?>
<?php include_once('cabezal.php'); ?>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script> 
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="js/ui/ui.datepicker.js" type="text/javascript"></script>
<link href="js/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.file-wrapper {
	cursor: pointer;
	display: inline-block;
	overflow: hidden;
	position: relative;
}
.file-wrapper input {
	cursor: pointer;
	font-size: 100px;
	height: 100%;
	filter: alpha(opacity=1);
	-moz-opacity: 0.01;
	opacity: 0.01;
	position: absolute;
	right: 0;
	top: 0;
}
.file-wrapper .button {
	background: #008046;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	color: #fff;
	cursor: pointer;
	display: inline-block;
	font-size: 11px;
	font-weight: bold;
	margin-right: 5px;
	padding: 4px 18px;/*text-transform: uppercase;*/
}
</style>
<script type="text/javascript">
var SITE = SITE || {};

SITE.fileInputs = function() {
  var $this = $(this),
      $val = $this.val(),
      valArray = $val.split('\\'),
      newVal = valArray[valArray.length-1],
      $button = $this.siblings('.button'),
      $fakeFile = $this.siblings('.file-holder');
  if(newVal !== '') {
    $button.text('Adjuntar CV');
    if($fakeFile.length === 0) {
      $button.after('<span class="file-holder">' + newVal + '</span>');
    } else {
      $fakeFile.text(newVal);
    }
  }
};

$(document).ready(function() {
  $('.file-wrapper input[type=file]').bind('change focus focusin click', SITE.fileInputs);
});
</script>
<div id="contenido">
  <?php if (!empty($notificationClass) && !empty($notificationMessage)) { ?>

	  <div class="notification <?php echo $notificationClass ?>">
	  	<?php echo $notificationMessage ?>
	  </div>

  <?php } ?>
  <div id="izq">
    <div id="qsTitulo"><img src="images/cvtitu.gif" width="140" height="24" /></div>
    <div  id="tasacionPedido">
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="cv" id="cv">
        <div style="float:left; width:230px; height:200px;"><span id="sprynombre"><span class="tasacionTituForm">Nombre</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="nombre" type="text" value="<?php echo $nombre; ?>" maxlength="250" />
          </span><br />
          <span id="spryemail"><span class="tasacionTituForm">E-mail</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="email" value="<?php echo $mail; ?>" type="text" maxlength="250" />
          </span><br />
          <span id="spryedad"><span class="tasacionTituForm">F. Nacimiento</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="edad" value="<?php echo $fnac; ?>" id="edad" type="text" />
          </span><br />
          <span class="tasacionTituForm">Tipo Documento</span><br />
          <select name="tipodoc" id="tipodoc" style="width:100px;">
            <option value="DNI" <?php if(!isset($_POST['tipodoc']) || $_POST['tipodoc'] =="DNI"){ echo "selected=\"selected\"";}?> >DNI</option>
            <option value="LC" <?php if($_POST['tipodoc'] =="LC"){ echo "selected=\"selected\"";}?>>LC</option>
            <option value="LE" <?php if($_POST['tipodoc'] =="LE"){ echo "selected=\"selected\"";}?>>LE</option>
            <option value="CI" <?php if($_POST['tipodoc'] =="CI"){ echo "selected=\"selected\"";}?>>CI</option>
          </select>
          <br />
        </div>
        <div style="float:right; width:230px; height:200px;"> <span id="spryapellido"><span class="tasacionTituForm">Apellido</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="apellido" type="text" value="<?php echo $apellido; ?>" maxlength="250" />
          </span><br />
          <span id="sprytelefono"><span class="tasacionTituForm">Teléfono</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="telefono" value="<?php echo $telefono; ?>" type="text" maxlength="250" />
          </span><br />
          <span id="spryprofesion"><span class="tasacionTituForm">Profesión</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="profesion" value="<?php echo $prof; ?>" type="text" maxlength="250" />
          </span> <br />
          <span id="sprynrodoc"><span class="tasacionTituForm">Nro. documento</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="nrodoc" value="<?php echo $nrodoc; ?>" id="nrodoc" type="text" maxlength="12" />
          <span class="textfieldInvalidFormatMsg">Invalid format.</span></span> <br />
        </div>
        <div><span class="tasacionTituForm">Comentario</span><br />
          <textarea name="comentario"><?php echo $comen; ?></textarea>
        </div>
        <div style="clear:both; margin-bottom:10px;"><br />
          <span class="tasacionTituForm">PDF - DOC - DOCX</span><br />
          <span class="file-wrapper">
          <input name="archivo" type="file" id="archivo" required/>
          <span class="button">Adjuntar CV</span> </span>
        </div>
        
        <div style="float:left; width:200px;">
          <div style="float:left; width:150px;"><img id="siimage" style="border: 1px solid #f26322; margin-right: 5px" src="inc/securimage_show.php?sid=<?php echo md5(uniqid()); ?>" alt="CAPTCHA" align="left" /></div>
          <div style="float:left; width:30px; margin-left:10px;">
            <object type="application/x-shockwave-flash" data="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" height="20" width="20">
              <param name="movie" value="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" />
            </object>
            <br />
            <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'inc/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="inc/images/refresh.png" alt="Reload Image" width="20" height="20" vspace="5" border="0" align="bottom" onclick="this.blur()" /></a></div>
          <div class="clearfix"></div>
        </div>
        <div style="float:left; width:120px;"><span class="tasacionTituForm">Ingrese el c&oacute;digo*:</span><br />
          <?php echo @$_SESSION['ctform']['captcha_error'] ?>
          <input type="text" name="captcha" size="12" maxlength="8" style="width:100px;" />
        </div>
        
        
        <div style="text-align:right; margin-top:10px; float:right;">
          <div id="enviado" style="display:none;width:300px;float:left; margin-top:0px; font-size:.9em;" class="buscarTitu">Se ha enviado un mail con la información!!</div>
          <div style="float:right;"><input name="enviar" type="image" src="images/enviarForm.gif" style="width:71px; height:20px; border:none;" /></div>
        </div>
                <div class="clearfix"></div>

        <input type="hidden" name="destino" value="inmobiliaria@okeefe.com.ar" />
        <input type="hidden" name="contacto" value="1" />
      </form>
    </div>
    <div style="float:right; width:229px; margin-top:10px; height:400px;"><img src="images/tasacionesIMG.jpg" width="229" height="375" /></div>
    <div class="clearfix"></div>
    <?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <?php include_once("buscadorVertical.php"); ?>
  </div>
  <script type="text/javascript">
		<!--
		var sprytextfield1 = new Spry.Widget.ValidationTextField("sprynombre");
		var sprytextfield2 = new Spry.Widget.ValidationTextField("spryapellido");
		var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytelefono");
		var sprytextfield4 = new Spry.Widget.ValidationTextField("spryprofesion");
		var sprytextfield5 = new Spry.Widget.ValidationTextField("sprynrodoc", "integer");
		var sprytextfield6 = new Spry.Widget.ValidationTextField("spryedad");
		var sprytextfield7 = new Spry.Widget.ValidationTextField("spryemail", "email");
        //-->
   </script>
	<script type="text/javascript">
        // BeginWebWidget jQuery_UI_Calendar: jQueryUICalendar1
        jQuery("#edad").datepicker({numberOfMonths: 1,showButtonPanel: true, dateFormat: "dd-mm-yy", regional:'es'});
        // EndWebWidget jQuery_UI_Calendar: jQueryUICalendar1
    </script>
<?php if(isset($_POST['MM_insert']) && $_POST['MM_insert']=="cv") { ?>
	<script type="text/javascript">
	document.getElementById('enviado').style.display = "block";
	//tb_open_new("mailEnviado.html?TB_iframe=true&height=300&width=300");
	</script>
<?php } ?>
<?php include_once('pie.php'); ?>