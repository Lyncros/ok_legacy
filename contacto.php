<?php
$suc = 8; 
session_start();
if(!isset($_SESSION['ingreso'])){
    $_SESSION['ingreso'] = $_SERVER['HTTP_REFERER'];
}

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

?>
<?php include_once('cabezal.php'); ?>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script> 
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<?php
if(isset($_POST['contacto']) && $_POST['contacto']==1){
	
	include_once ('inc/securimage.php');
	$securimage = new Securimage();
	$captcha=$_POST['captcha'];
	
	if ($securimage->check($captcha) == false) {
		?>
<div id="contenido">
  <div id="izq">
    <div id="qsTitulo"><img src="images/contactotitu.gif" width="88" height="24" /></div>
    <div id="tasacionPedido">
      <div style="margin-bottom:40px;">Para contactarse con nosotros,  complete sus datos y su consulta, dentro de las 24 hs. un representante de O´Keefe se pondrá en contacto con usted.</div>
      <form id="contacto" name="contacto" method="post" action="contacto.php">
        <div style="float:left; width:240px;"><span id="sprynombre"><span class="tasacionTituForm">Nombre:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="nombre" type="text" tabindex="1" value="<?php echo $_POST['nombre']; ?>" />
          </span> <br />
          <span id="spryemail"><span class="tasacionTituForm"> E-mail:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
          <input name="email" type="text" tabindex="3" value="<?php echo $_POST['email']; ?>" />
          </span> </div>
        <div style="float:right; width:240px;"><span id="spryapellido"><span class="tasacionTituForm">Apellido:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="apellido" type="text" tabindex="2" value="<?php echo $_POST['apellido']; ?>" />
          </span> <br />
          <span id="sprytelefono"><span class="tasacionTituForm">Teléfono:</span><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="telefono" type="text" tabindex="4" value="<?php echo $_POST['telefono']; ?>" />
          </span> </div>
        <div style="clear:both; padding-top:15px;"><span id="sprytextarea1"><span class="tasacionTituForm">Consulta</span> <span class="textareaRequiredMsg">Un valor es requerido.</span><br />
          <textarea name="consulta" rows="6" tabindex="5"><?php echo $_POST['consulta']; ?></textarea>
          </span><br />
        </div>
        <div style="float:left; width:240px;">
          <div style="float:left; width:150px;"><img id="siimage" style="border: 1px solid #f26322; margin-right: 5px" src="inc/securimage_show.php?sid=<?php echo md5(uniqid()); ?>" alt="CAPTCHA" align="left" /></div>
          <div style="float:left; width:30px; margin-left:10px;">
            <object type="application/x-shockwave-flash" data="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" height="20" width="20">
              <param name="movie" value="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" />
            </object>
            <br />
            <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'inc/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="inc/images/refresh.png" alt="Reload Image" width="20" height="20" vspace="5" border="0" align="bottom" onclick="this.blur()" /></a></div>
          <div class="clearfix"></div>
        </div>
        <div style="float:right; width:240px;"><span class="tasacionTituForm">Enter Code*:</span><br />
          <?php echo @$_SESSION['ctform']['captcha_error'] ?>
          <input type="text" name="captcha" size="12" maxlength="8" />
        </div>
        <div class="clearfix"></div>
        <div style="float:left; width:240px; margin-top:20px;">Todos los campos son obligatorios.</div>
        <div style="float:right; width:240px; text-align:right; margin-top:20px;">
          <input type="image" name="enviar2" id="enviar" src="images/enviarForm.gif" style="width:71px; height:20px; border:none;" />
        </div>
        <div class="clearfix"></div>
        <div id="enviado" style="display:none; margin-top:5px;font-size: 1.1em;" class="buscarTitu">El c&oacute;digo de seguridad es erroneo.</div>
        <input type="hidden" name="destino" value="inmobiliaria@okeefe.com.ar" />
        <input type="hidden" name="contacto" value="1" />
      </form>
    </div>
    <div style="float:right; width:229px; margin-top:10px;"><img src="images/tasacionesIMG.jpg" width="229" height="375" /></div>
    <div class="clearfix" style="height:15px;"></div>
    <?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <?php include_once("buscadorVertical.php"); ?>
  </div>
  !-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '957821560969509');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=957821560969509&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->


<!-- Google Code for Remarketing Tag -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 933390723;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/933390723/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

  <script type="text/javascript">
        var sprytextfield1 = new Spry.Widget.ValidationTextField("sprynombre");
        var sprytextfield2 = new Spry.Widget.ValidationTextField("spryapellido");
        var sprytextfield3 = new Spry.Widget.ValidationTextField("spryemail", "email");
        var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytelefono");
        var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        </script>
  <?php if(isset($_POST['contacto']) && $_POST['contacto']==1){ ?>
  <script type="text/javascript">
            document.getElementById('enviado').style.display = "block";
            </script>
  <?php } ?>
  <?php include_once('pie.php'); ?>
<?php
		  exit;
	  }else{
		header('Content-type: text/html; charset=utf-8');
		
		$de = $_POST['email'];
		$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];
		
		$mensaje = $deNombre . "<br />";
		$mensaje .= $_POST['telefono'] . "<br />";
		$mensaje .= $de . "<br />";
		$mensaje .= $_POST['consulta'] . "<br />";
		
		$Subject = 'Contacto de la WEB';
		$para = $_POST['destino'];
	//	$para = "gustavo@zgroupsa.com.ar";
	
		//so we use the MD5 algorithm to generate a random hash
		$random_hash = md5(date('r', time()));
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "To: " . $para . "\r\n";
		$headers .= "From: ".$deNombre." <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
		//add boundary string and mime type specification
		//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
		
		
		if(!mail( $para, $Subject, $mensaje, $headers )){
			echo "Error al enviar";
		}else{
			require_once('Connections/config.php');

			$insertSQL = sprintf("INSERT INTO consultas (nombre, apellido, telefono, email, consulta, categoria, destino, acceso) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($_POST['nombre'], "text"),
							   GetSQLValueString($_POST['apellido'], "text"),
							   GetSQLValueString($_POST['telefono'], "text"),
							   GetSQLValueString($_POST['email'], "text"),
							   GetSQLValueString($_POST['consulta'], "text"),
							   GetSQLValueString('CONTACTO', "text"),
							   GetSQLValueString($_POST['destino'], "text"),
							   GetSQLValueString($_SESSION['ingreso'], "text"));
			mysql_select_db($database_config, $config);
			$Result1 = mysql_query($insertSQL, $config) or die(mysql_error());

			header("location: contacto_gracias.php");
		}
	}
}
?>
<div id="contenido">
  <div id="izq">
    <div id="qsTitulo"><img src="images/contactotitu.gif" width="88" height="24" /></div>
    <div id="tasacionPedido">
      <div style="margin-bottom:40px;">Para contactarse con nosotros,  complete sus datos y su consulta, dentro de las 24 hs. un representante de O´Keefe se pondrá en contacto con usted.</div>
      <form id="contacto" name="contacto" method="post" action="contacto.php">
        <div style="float:left; width:240px;"><span id="sprynombre"><span class="tasacionTituForm">Nombre:</span><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="nombre" type="text" tabindex="1" />
          </span><br />
          <span id="spryemail"><span class="tasacionTituForm">E-mail:</span><span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
          <input name="email" type="text" tabindex="3" />
          </span></div>
        <div style="float:right; width:240px;"><span id="spryapellido"><span class="tasacionTituForm">Apellido:</span><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="apellido" type="text" tabindex="2" />
          </span><br />
          <span id="sprytelefono"><span class="tasacionTituForm">Teléfono:</span><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="telefono" type="text" tabindex="4" />
          </span></div>
        <div style="clear:both; padding-top:15px;"><span id="sprytextarea1"><span class="tasacionTituForm">Consulta</span><span class="textareaRequiredMsg">Un valor es requerido.</span><br />
          <textarea name="consulta" rows="6" tabindex="5"></textarea>
          </span><br />
        </div>
        <div style="float:left; width:240px;">
          <div style="float:left; width:150px;"><img id="siimage" style="border: 1px solid #f26322; margin-right: 5px" src="inc/securimage_show.php?sid=<?php echo md5(uniqid()); ?>" alt="CAPTCHA" align="left" /></div>
          <div style="float:left; width:30px; margin-left:10px;">
            <object type="application/x-shockwave-flash" data="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" height="20" width="20">
              <param name="movie" value="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" />
            </object>
            <br />
            <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'inc/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="inc/images/refresh.png" alt="Reload Image" width="20" height="20" vspace="5" border="0" align="bottom" onclick="this.blur()" /></a></div>
          <div class="clearfix"></div>
        </div>
        <div style="float:right; width:240px;"><span class="tasacionTituForm">Ingrese el c&oacute;digo*:</span><br />
          <?php echo @$_SESSION['ctform']['captcha_error'] ?>
          <input type="text" name="captcha" size="12" maxlength="8" />
        </div>
        <div class="clearfix"></div>
        <div style="float:left; width:240px; margin-top:20px;">Todos los campos son obligatorios.</div>
        <div style="float:right; width:240px; text-align:right; margin-top:20px;">
          <input type="image" name="enviar2" id="enviar" src="images/enviarForm.gif" style="width:71px; height:20px; border:none;" />
        </div>
        <div class="clearfix"></div>
        <div id="enviado" style="display:none; margin-top:10px;font-size: 1.1em;" class="buscarTitu">Se ha enviado un mail con la información!!</div>
        <input type="hidden" name="destino" value="inmobiliaria@okeefe.com.ar" />
        <input type="hidden" name="contacto" value="1" />
      </form>
    </div>
    <div style="float:right; width:229px; margin-top:10px;"><img src="images/tasacionesIMG.jpg" width="229" height="375" /></div>
    <div class="clearfix" style="height:15px;"></div>
    <?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <?php include_once("buscadorVertical.php"); ?>
  </div>
  !-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '957821560969509');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=957821560969509&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->


<!-- Google Code for Remarketing Tag -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 933390723;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/933390723/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

  <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprynombre");
var sprytextfield2 = new Spry.Widget.ValidationTextField("spryapellido");
var sprytextfield3 = new Spry.Widget.ValidationTextField("spryemail", "email");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytelefono");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
</script>
  <?php include_once('pie.php'); ?>