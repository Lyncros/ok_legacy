<?php $suc = 8; ?>
<?php include_once('cabezal.php'); ?>

<?php
if(isset($_POST['contacto']) && $_POST['contacto']==1){
	header('Content-type: text/html; charset=utf-8');
	
	$de = $_POST['email'];
	$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];
	
	$mensaje = $deNombre . "<br />";
	$mensaje .= $_POST['telefono'] . "<br />";
	$mensaje .= $de . "<br />";
	$mensaje .= $_POST['consulta'] . "<br />";
	
	$Subject = 'Contacto de la WEB';
	$para = $_POST['destino'];

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
	}
}
?>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<div id="contenido">
  <div id="izq">
    <div id="qsTitulo"><img src="images/contactotitu.gif" width="88" height="24" /></div>
    <div id="tasacionPedido">
      <div style="margin-bottom:40px;">Para contactarse con nosotros,  complete sus datos y su consulta, dentro de las 24 hs. un representante de O´Keefe se pondrá en contacto con usted.</div>
      <form id="contacto" name="contacto" method="post" action="contacto.php">
        <div style="float:left; width:240px;"><span id="sprynombre"><span class="tasacionTituForm">Nombre:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="nombre" type="text" tabindex="1" /></span>
          <br /><br />
          <span id="spryemail"><span class="tasacionTituForm">
          E-mail:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
          <input name="email" type="text" tabindex="3" /></span>
        </div>
        <div style="float:right; width:240px;"><span id="spryapellido"><span class="tasacionTituForm">Apellido:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="apellido" type="text" tabindex="2" /></span>
          <br />
          <br />
          <span id="sprytelefono"><span class="tasacionTituForm">Teléfono:</span><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="telefono" type="text" tabindex="4" /></span>
        </div>
        <div style="clear:both; padding-top:15px;"><span id="sprytextarea1"><span class="tasacionTituForm">Consulta</span> <span class="textareaRequiredMsg">Un valor es requerido.</span><br />
          <textarea name="consulta" rows="6" tabindex="5"></textarea></span>
        </div>
        <div style="float:left; width:240px; margin-top:20px;">Todos los campos son obligatorios.</div>
        <div style="float:right; width:240px; text-align:right; margin-top:20px;">
          <input type="image" name="enviar2" id="enviar" src="images/enviarForm.gif" style="width:71px; height:20px; border:none;" />
          <div id="enviado" style="display:none; margin-top:10px;font-size: 1.1em;" class="buscarTitu">Se ha enviado un mail con la información!!</div>
        </div>
        <input type="hidden" name="destino" value="inmobiliaria@okeefe.com.ar" />
        <input type="hidden" name="contacto" value="1" />
      </form>
    </div>
    <div style="float:right; width:229px; margin-top:10px;"><img src="images/tasacionesIMG.jpg" width="229" height="375" /></div>
	<div class="clearfix" style="height:20px;"></div>
<?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <?php include_once("buscadorVertical.php"); ?>
  </div>
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
	//tb_open_new("mailEnviado.html?TB_iframe=true&height=300&width=300");
	</script>

<?php } ?>
  <?php include_once('pie.php'); ?>