<?php
$descripcion = "O´Keefe cuenta con un servicio de profesionales (Abogados, Martilleros e Ingenieros) para realizar tasaciones de todo tipo de propiedades.";
$titulo = "Tasaciones de inmuebles y propiedades | O'Keefe Inmobiliaria";
 ?>
<?php include_once('cabezal.php'); ?>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script> 
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<?php
if(isset($_POST['tasacion']) && $_POST['tasacion']==1){
	include_once ('inc/securimage.php');
	$securimage = new Securimage();
	$captcha=$_POST['captcha'];
	
	if ($securimage->check($captcha) == false) {
		?>
<div id="contenido">
<div id="izq">
  <div id="qsTitulo"><img src="images/tasacionesPedido.gif" width="200" height="24" alt="Pedido de Tasaciones de Propiedades"/></div>
  <div id="tasacionPedido">
    <div style="margin-bottom:20px;">O´Keefe cuenta con un servicio de profesionales (Abogados, Martilleros e Ingenieros) para realizar tasaciones de todo tipo de propiedades. Nuestra experiencia y profundo conocimiento de la situación del mercado inmobiliario actual, nos permite brindar una tasación justa y con los fundamentos claros y objetivos que usted se merece.
      <p><strong>Ofrecemos dos opciones de tasaciones:</strong><br />
      
      <ul style="list-style:decimal outside;">
        <li>PARA LA VENTA: Como parte de nuestro servicio de comercialización, ofrecemos el servicio de tasación de forma GRATUITA.</li>
        <li>PARA SU INFORMACIÓN: O´Keefe realiza un informe de tasación escrito, con el que usted tendrá en forma clara y objetiva el valor actualizado de su inmueble para su utilización en evaluación de proyectos inmobiliarios, sucesiones, divisiones, etc.</li>
      </ul>
      </p>
      <p>Complete el formulario con el pedido de tasación y el tasador se comunicará con usted a la brevedad.</p>
    </div>
    <form id="contacto" name="contacto" method="post" action="tasaciones.php">
      <div style="float:left; width:240px;"><span id="sprynombre"><span class="tasacionTituForm">Nombre:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
        <input name="nombre" type="text" tabindex="1" value="<?php echo $_POST['nombre']; ?>" />
        </span> <br />
        <span id="spryemail"><span class="tasacionTituForm"> E-mail:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
        <input name="email" type="text" tabindex="3" value="<?php echo $_POST['email']; ?>" />
        </span> <br />
        <span id="spryselect1"><span class="tasacionTituForm"> Tipo de Propiedad:</span> <span class="selectRequiredMsg">Debese seleccionar un tipo.</span><br />
        <select name="destino" tabindex="5">
          <option value="-1">Tipo de propiedad a tasar</option>
          <option value="dennis@okeefe.com.ar, felipe.atucha@okeefe.com.ar" <?php if($_POST['destino'] == "dennis@okeefe.com.ar, felipe.atucha@okeefe.com.ar"){ echo "selected"; }?> >Rural</option>
          <option value="charlie@okeefe.com.ar" <?php if($_POST['destino'] == "charlie@okeefe.com.ar"){ echo "selected"; }?>>Comercial y Residencial</option>
          <option value="ignacio@okeefe.com.ar" <?php if($_POST['destino'] == "ignacio@okeefe.com.ar"){ echo "selected"; }?>>Emprendimientos</option>
        </select>
        </span> <br />
        <br />
        <div>
          <div style="float:left; width:150px;"><img id="siimage" style="border: 1px solid #f26322; margin-right: 5px" src="inc/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA" align="left" /></div>
          <div style="float:left; width:30px; margin-left:10px;">
            <object type="application/x-shockwave-flash" data="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" height="20" width="20">
              <param name="movie" value="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" />
            </object>
            <br />
            <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'inc/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="inc/images/refresh.png" alt="Reload Image" width="20" height="20" vspace="5" border="0" align="bottom" onclick="this.blur()" /></a></div>
          <div class="clearfix"></div>
          <br />
          <div><span class="tasacionTituForm">Enter Code*:</span><br />
            <?php echo @$_SESSION['ctform']['captcha_error'] ?>
            <input type="text" name="captcha" size="12" maxlength="8" tabindex="7" />
          </div>
        </div>
      </div>
      <div style="float:right; width:240px;"><span id="spryapellido"><span class="tasacionTituForm">Apellido:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
        <input name="apellido" type="text" tabindex="2" value="<?php echo $_POST['apellido']; ?>" />
        </span> <br />
        <span id="sprytelefono"><span class="tasacionTituForm">Teléfono:</span><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
        <input name="telefono" type="text" tabindex="4" value="<?php echo $_POST['telefono']; ?>" />
        </span><br />
        <span id="sprytextarea1"><span class="tasacionTituForm">Consulta</span> <span class="textareaRequiredMsg">Un valor es requerido.</span><br />
        <textarea name="consulta" rows="8" tabindex="6"><?php echo $_POST['consulta']; ?></textarea>
        </span> </div>
      <div style="clear:both; padding-top:15px;"></div>
      <div class="clearfix"></div>
      <div id="enviado" style="display: none; margin-top:5px;font-size: 1em;" class="buscarTitu">El c&oacute;digo de seguridad es erroneo.</div>
      <div style="float:left; width:240px; margin-top:20px;">Todos los campos son obligatorios.</div>
      <div style="float:right; width:240px; text-align:right; margin-top:20px;">
        <input type="image" name="enviar2" id="enviar" src="images/enviarForm.gif" style="width:71px; height:20px; border:none;" />
      </div>
      <input type="hidden" name="tasacion" value="1" />
    </form>
  </div>
  <div style="float:right; width:229px; margin-top:10px;"><img src="images/contactoIMG.jpg" width="229" height="375" /> </div>
  <div class="clearfix"></div>
  <?php include_once("menuBajo.php"); ?>
</div>
<div id="derecha">
  <?php include_once("buscadorVertical.php"); ?>
</div>
  <!-- Google Code for Tasaciones Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 985947123;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "1JwYCO2CyQUQ87eR1gM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprynombre");
var sprytextfield2 = new Spry.Widget.ValidationTextField("spryapellido");
var sprytextfield3 = new Spry.Widget.ValidationTextField("spryemail", "email");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytelefono");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
</script>
<?php if(isset($_POST['tasacion']) && $_POST['tasacion']==1){ ?>
<script type="text/javascript">
	document.getElementById('enviado').style.display = "block";
	//tb_open_new("mailEnviado.html?TB_iframe=true&height=300&width=300");
	</script>
<?php } 
  			include_once('pie.php');
		  	exit;
      }else{

	header('Content-type: text/html; charset=utf-8');

	$de = $_POST['email'];
	$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];
		
	$mensaje = $deNombre . "<br />";
	$mensaje .= $_POST['telefono'] . "<br />";
	$mensaje .= $de . "<br />";
	$mensaje .= $_POST['consulta'] . "<br />";
		

	$para = $_POST['destino'];    
	//$para = "gustavo@zgroupsa.com.ar";    
	
	$Subject = 'Solicitud de Tasacion de '.$deNombre;
	
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
				echo "Erroro al enviar";
			}
	  }
}
?>
<div id="contenido">
  <div id="izq">
    <div id="qsTitulo"><img src="images/tasacionesPedido.gif" width="200" height="24" /></div>
    <div id="tasacionPedido">
      <div style="margin-bottom:20px;">O´Keefe cuenta con un servicio de profesionales (Abogados, Martilleros e Ingenieros) para realizar tasaciones de todo tipo de propiedades. Nuestra experiencia y profundo conocimiento de la situación del mercado inmobiliario actual, nos permite brindar una tasación justa y con los fundamentos claros y objetivos que usted se merece.
        <p><strong>Ofrecemos dos opciones de tasaciones:</strong><br />
        
        <ul style="list-style:decimal outside;">
          <li>PARA LA VENTA: Como parte de nuestro servicio de comercialización, ofrecemos el servicio de tasación de forma GRATUITA.</li>
          <li>PARA SU INFORMACIÓN: O´Keefe realiza un informe de tasación escrito, con el que usted tendrá en forma clara y objetiva el valor actualizado de su inmueble para su utilización en evaluación de proyectos inmobiliarios, sucesiones, divisiones, etc.</li>
        </ul>
        </p>
        <p>Complete el formulario con el pedido de tasación y el tasador se comunicará con usted a la brevedad.</p>
      </div>
      <form id="contacto" name="contacto" method="post" action="tasaciones.php">
        <div style="float:left; width:240px;"><span id="sprynombre"><span class="tasacionTituForm">Nombre:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="nombre" type="text" tabindex="1" />
          </span> <br />
          <span id="spryemail"><span class="tasacionTituForm"> E-mail:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
          <input name="email" type="text" tabindex="3" />
          </span> <br />
          <span id="spryselect1"><span class="tasacionTituForm"> Tipo de Propiedad:</span> <span class="selectRequiredMsg">Debese seleccionar un tipo.</span><br />
          <select name="destino" tabindex="5">
            <option value="-1">Tipo de propiedad a tasar</option>
            <option value="dennis@okeefe.com.ar, felipe.atucha@okeefe.com.ar">Rural</option>
            <option value="charlie@okeefe.com.ar">Comercial y Residencial</option>
            <option value="ignacio@okeefe.com.ar">Emprendimientos</option>
          </select>
          </span> <br />
          <br />
          <div>
            <div style="float:left; width:150px;"><img id="siimage" style="border: 1px solid #f26322; margin-right: 5px" src="inc/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA" align="left" /></div>
            <div style="float:left; width:30px; margin-left:10px;">
              <object type="application/x-shockwave-flash" data="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" height="20" width="20">
                <param name="movie" value="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" />
              </object>
              <br />
              <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'inc/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="inc/images/refresh.png" alt="Reload Image" width="20" height="20" vspace="5" border="0" align="bottom" onclick="this.blur()" /></a></div>
            <div class="clearfix"></div>
            <br />
            <div><span class="tasacionTituForm">Enter Code*:</span><br />
              <?php echo @$_SESSION['ctform']['captcha_error'] ?>
              <input type="text" name="captcha" size="12" maxlength="8" tabindex="7" />
            </div>
          </div>
        </div>
        <div style="float:right; width:240px;"><span id="spryapellido"><span class="tasacionTituForm">Apellido:</span> <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="apellido" type="text" tabindex="2" />
          </span> <br />
          <span id="sprytelefono"><span class="tasacionTituForm">Teléfono:</span><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
          <input name="telefono" type="text" tabindex="4" />
          </span><br />
          <span id="sprytextarea1"><span class="tasacionTituForm">Consulta</span> <span class="textareaRequiredMsg">Un valor es requerido.</span><br />
          <textarea name="consulta" rows="8" tabindex="6"></textarea>
          </span> </div>
        <div style="clear:both; padding-top:15px;"></div>
        <div class="clearfix"></div>
        <div id="enviado" style="display:none; margin-top:5px;font-size: 1em;" class="buscarTitu">Se ha enviado un mail con la información!!</div>
        <div style="float:left; width:240px; margin-top:20px;">Todos los campos son obligatorios.</div>
        <div style="float:right; width:240px; text-align:right; margin-top:20px;">
          <input type="image" name="enviar2" id="enviar" src="images/enviarForm.gif" style="width:71px; height:20px; border:none;" />
        </div>
        <input type="hidden" name="tasacion" value="1" />
      </form>
    </div>
    <div style="float:right; width:229px; margin-top:10px;"><img src="images/contactoIMG.jpg" width="229" height="375" /> </div>
    <div class="clearfix"></div>
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
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
</script>
  <?php if(isset($_POST['tasacion']) && $_POST['tasacion']==1){ ?>
  <script type="text/javascript">
	document.getElementById('enviado').style.display = "block";
	//tb_open_new("mailEnviado.html?TB_iframe=true&height=300&width=300");
	</script>
  <?php } ?>
  <!-- Google Code for Tasaciones Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 985947123;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "1JwYCO2CyQUQ87eR1gM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/985947123/?value=0&amp;label=1JwYCO2CyQUQ87eR1gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
  <?php include_once('pie.php'); ?>