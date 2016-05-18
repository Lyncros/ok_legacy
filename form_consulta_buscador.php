<?php 

require_once('Connections/config.php');
require_once('inc/funciones.php');

header('Content-type: text/html; charset=utf-8');
require_once('lib-nusoap/nusoap.php');

session_start();
if(!isset($_SESSION['ingreso'])){
    $_SESSION['ingreso'] = $_SERVER['HTTP_REFERER'];
}

$wsdl = "http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client = new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice

//¿ocurrio error al llamar al web service?
if ($client->fault) { // si
    echo 'No se pudo completar la operación';
    die();
}else { // no
    $error = $client->getError();
    if ($error) { // Hubo algun error
        echo 'Error:' . $error;
    }
}

$zona = $client->call('ListarZonaPrincipal',array(),'');
//print_r($zona);
//die();

$tipoProp = $client->call('ListarTipoProp',array(),'');
//print_r($tipoProp);
//die();

$nombre = "";
$apellido = "";
$telefono = "";
$mail = "";
$consulta = "";
$opcOperacion = "";
$opcInmueble = "";
$opcZona = "";
$opcLocalidad = "";
$opcAmbientes = "";
$opcMoneda = "";
$opcDesdehasta = "";

if(isset($_POST['contacto']) && $_POST['contacto'] == 1) {
	
	include_once ('inc/securimage.php');
	$securimage = new Securimage();
	$captcha=$_POST['captcha'];
	
	if ($securimage->check($captcha) == false) {
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$telefono = $_POST['telefono'];
		$mail = $_POST['email'];
		$consulta = $_POST['consulta'];
		$opcOperacion = $_POST['opcTipoOper'];
		$opcInmueble = $_POST['opcTipoProp'];
		$opcZona = $_POST['opcZona'];
		$opcLocalidad = $_POST['opcLocalidad'];
		$opcAmbientes = $_POST['opcAmbientes'];
		$opcMoneda = $_POST['opcMonedaVenta'];
	} else {
		$de = $_POST['email'];
		$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];
		
		$mensaje = $deNombre . "<br />";
		$mensaje .= $_POST['telefono'] . "<br />";
		$mensaje .= $de . "<br />";
		$mensaje .= $_POST['consulta'] . "<br /><br />";
		$mensaje .= "Busqueda solicitada:<br />";
		$mensaje .= "Operacion: " . $_POST['opcTipoOper'] . "<br />";
		foreach($tipoProp as $tipo){
			if($tipo['id_tipo_prop'] == $_POST['opcTipoProp']){
				$tipoProp = $tipo['tipo_prop'];
			}
		}
		$mensaje .= "Tipo de propiedad: " . $tipoProp . "<br />";
		$localidades = $_POST['opcLocalidad'];
		$loca = "";
		for($i=0; $i < count($localidades); $i++){
			$loca .= $localidades[$i] . " - ";
		}
		$mensaje .= "Localidades: ". $loca . "<br />";
		if($_POST['opcAmbientes'] != 0){
			$mensaje .= "Ambientes: ". $_POST['opcAmbientes'] . "<br />";
		}
		if($_POST['opcDespachos'] != 0){
			$mensaje .= "Despachos: ". $_POST['opcDespachos'] . "<br />";
		}
		if($_POST['opcSupTotal'] != 0){
			$mensaje .= "Cantidad de Ha: ". $_POST['opcSupTotal'] . "<br />";
		}
		$mensaje .= "Moneda: " .  $_POST['opcMonedaVenta'] . "<br />";
		$mensaje .= "Desde: " .  $_POST['desde'] . "<br />";
		$mensaje .= "Hasta: " .  $_POST['hasta'] . "<br />";

		switch ($_POST['opcTipoProp']) {
			case 6:
			case 16:
				$destino = "juancruz@okeefe.com.ar, dennis@okeefe.com.ar, sebastian.amado@okeefe.com.ar";
				break;
			default:
				$destino = 'juancruz@okeefe.com.ar, paulo@okeefe.com.ar, tomas@okeefe.com.ar';
		}
		
		//so we use the MD5 algorithm to generate a random hash
		$random_hash = md5(date('r', time()));
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "To: " . $destino . "\r\n";
		$headers .= "From: " . $deNombre . " <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
		//add boundary string and mime type specification
		
		$insertSQL = sprintf("INSERT INTO consultas (nombre, apellido, telefono, email, consulta, categoria, destino, acceso) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['nombre'], "text"),
						   GetSQLValueString($_POST['apellido'], "text"),
						   GetSQLValueString($_POST['telefono'], "text"),
						   GetSQLValueString($_POST['email'], "text"),
						   GetSQLValueString($mensaje, "text"),
						   GetSQLValueString('BUSQUEDA', "text"),
						   GetSQLValueString($destino, "text"),
						   GetSQLValueString($_SESSION['ingreso'], "text"));
		
		mysql_select_db($database_config, $config);
		$Result1 = mysql_query($insertSQL, $config) or die(mysql_error());
		
		$subject = 'Solicitud de Busqueda';
		if(mail($destino, $subject, $mensaje, $headers)) {
			echo "<script>window.location='mailEnviado.html';</script>";
		} else {
			echo "Error al enviar";
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>O'Keefe Propiedades</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="css/okeefeVentanas.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/ajax.js" type="text/javascript" /></script>
<script language="javascript" src="js/ajax-dynamic-content.js" type="text/javascript" /></script>
<script type="text/javascript">
function abreLocalidad(){
	var zona = document.getElementById('opcZona').value;
	var loca = document.getElementById('opcLocalidad').value;
	if(zona == 0){
		alert("Previamente debe seleccionar una ZONA");
	}else{
	//	window.open('armaComboBarrioVentana.php?zona='+zona+'&loca='+loca, 'localidades', 'status=0,toolbar=0,menubar=0,location=0,width=350,height=500');
	//	self.parent.tb_show(null, 'armaComboBarrioVentana.php?zona='+zona+'&loca='+loca+'&TB_iframe=true&height=550&width=300&modal=true', null);
		ajax_loadContent('Localidad','armaComboBarrioVentana.php?zona='+zona+'&loca='+loca);
	}
}
function actualizaAmbientes(prop){
	document.getElementById('ambientes').style.display = 'none';
	document.getElementById('despachos').style.display = 'none';
	document.getElementById('supTotal').style.display = 'none';
	switch(prop){
		case 'departamentos y p.h.':
		case 'casas':
		case 'quintas':
		 document.getElementById('ambientes').style.display = 'block';
		  break;
		case 'oficinas':
		 document.getElementById('despachos').style.display = 'block';
		  break;
		case 'campos':
		 document.getElementById('supTotal').style.display = 'block';
		  break;
		}
}

function actualizaDesdeHasta(){
	var vdesde = '';
	var vhasta = '';
	var vcadena = '';
	
	if(document.getElementById('desde').value != document.getElementById('desde').defaultValue && document.getElementById('desde').value != ''){
		vdesde = document.getElementById('desde').value;
	}else{
		document.getElementById('desde').value = document.getElementById('desde').defaultValue;
	}
	
	if(document.getElementById('hasta').value != document.getElementById('hasta').defaultValue && document.getElementById('hasta').value != ''){
		if( document.getElementById('hasta').value <  vdesde){
			alert('El valor Hasta es menor que Desde');
			document.getElementById('hasta').focus();
		}else{
			vhasta = document.getElementById('hasta').value;
		}
	}else{
		document.getElementById('hasta').value = document.getElementById('hasta').defaultValue;
	}
	if(vdesde != '' || vhasta != ''){
		if(vdesde != ''){
			vcadena = vdesde + ' AND ';
		}else{
			vcadena = '0 AND ';
		}
		if(vhasta != '' && vhasta != 0){
			vcadena += vhasta;
		}else{
			vcadena += '99999999999';
		}
	}else{
		vcadena = '';
	}
	document.getElementById('opcPrecioVenta').value = vcadena ;
	//alert(document.getElementById('opcPrecioVenta').value);
}
function actualizaUbica(){
	var valor = 0;
	if(document.getElementById('opcLocalidad').value == 0){
		valor = document.getElementById('opcZona').value;
	}else{
		valor = document.getElementById('opcLocalidad').value;
	}
	document.getElementById('opcUbica').value = valor;
}
</script>
</head>
<body style="width: 800px; margin:10px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="buscador" name="buscador" onsubmit="actualizaDesdeHasta(); actualizaUbica();">
  <div id="buscadorHoriz" style="clear:both; border-bottom:solid thin #CCC; height:120px; width:100%;">
    <div style="float:left; width:90px;"><img src="images/buscarprop.gif" width="80" height="30" /></div>
    <div style="width:215px; float:left;">
      <div>
        <select name="opcTipoOper">
          <option value="" <?php if($operacion == ""){ echo "selected=\"selected\""; }?>>Operación</option>
          <option value="Venta" <?php if($opcOperacion == "Venta"){ echo "selected=\"selected\""; }?>>Venta</option>
          <option value="Alquiler" <?php if($opcOperacion == "Alquiler"){ echo "selected=\"selected\""; }?>>Alquiler</option>
          <option value="Alquiler Temporario" <?php if($opcOperacion == "Alquiler Temporario"){ echo "selected=\"selected\""; }?>>Alquiler temporario</option>
        </select>
      </div>
      <div>
        <select name="opcTipoProp" id="opcTipoProp" onchange="actualizaAmbientes(this.value);">
          <option value='0' <?php if($opcInmueble == ""){ echo "selected=\"selected\""; }?>>Inmueble</option>
          <?php foreach($tipoProp as $tipo){ ?>
          <option value='<?php echo $tipo['id_tipo_prop'];?>' <?php if($opcInmueble == $tipo['id_tipo_prop']){ echo "selected=\"selected\""; }?>><?php echo $tipo['tipo_prop'];?></option>
          <?php } ?>
        </select>
      </div>
      <div>
        <select name="opcZona" id="opcZona" onchange="abreLocalidad();">
          <option value='0' <?php if($opcZona == ""){ echo "selected=\"selected\""; }?>>Zona</option>
          <?php foreach($zona as $ubi){ ?>
          <option value='<?php echo $ubi['id_ubica'];?>' <?php if(isset($_POST['opcZona']) && $ubi['id_ubica'] == $_POST['opcZona']){ echo "selected"; } ?>><?php echo $ubi['nombre_ubicacion'];?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div style="width:215px; float:left; padding-left:8px;">
      <div id="Localidad">
        <select name="opcLocalidad" size="6" multiple="multiple" id="opcLocalidad">
          <option value='0' selected="selected">Localidad</option>
        </select>
      </div>
    </div>
    <div style="width:215px; float:left; padding-left:8px;">
      <div style="display:block;" id="ambientes">
        <select name="opcAmbientes" id="opcAmbientes">
          <option value="0" selected="selected">Ambientes</option>
          <option value=" =1">1</option>
          <option value=" =2">2</option>
          <option value=" =3">3</option>
          <option value=" =4">4</option>
          <option value=" >=5">5 o más</option>
        </select>
      </div>
      <div style="display:none;" id="despachos">
        <select name="opcDespachos" id="opcDespachos">
          <option value="0" selected="selected">Cant. Despachos</option>
          <option value=" =1">1</option>
          <option value=" =2">2</option>
          <option value=" =3">3</option>
          <option value=" =4">4</option>
          <option value=" >=5">5 o más</option>
        </select>
      </div>
      <div style="display:none;" id="supTotal">
        <select name="opcSupTotal" id="opcSupTotal">
          <option value="0" selected="selected">Cantidad de Ha</option>
          <option value=" 50 AND 100 ">Entre 50 y 100Ha</option>
          <option value=" 100 AND 200">Entre 100 y 200Ha</option>
          <option value=" 200 AND 300">Entre 200 y 300Ha</option>
          <option value=" 300 AND 500">Entre 300 y 500Ha</option>
          <option value=" >=500">Más de 500Ha</option>
        </select>
      </div>
      <div>
        <select name="opcMonedaVenta" id="opcMonedaVenta">
          <option value=''  selected="selected">Moneda</option>
          <option value='U$S'>u$s</option>
          <option value='$'>$</option>
        </select>
      </div>
      <div>
        <div style="float:left; width:140px;">
          <select name="desde" id="desde" onblur="actualizaDesdeHasta();">
            <option value=''  selected="selected">Desde</option>
            <option value='0'>0</option>
            <option value='100000'>100.000</option>
            <option value='150000'>150.000</option>
            <option value='200000'>200.000</option>
            <option value='250000'>250.000</option>
            <option value='300000'>300.000</option>
            <option value='400000'>400.000</option>
            <option value='500000'>500.000</option>
          </select>
          <select name="hasta" id="hasta" onblur="actualizaDesdeHasta();">
            <option value=''  selected="selected">Hasta</option>
            <option value='100000'>100.000</option>
            <option value='150000'>150.000</option>
            <option value='200000'>200.000</option>
            <option value='250000'>250.000</option>
            <option value='300000'>300.000</option>
            <option value='400000'>400.000</option>
            <option value='500000'>500.000 o más</option>
          </select>
        </div>
        <div style="width:68px; float:right;"> </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <table width="100%" border="0" cellspacing="10" cellpadding="0" bgcolor="#FFFFFF">
    <tr>
      <td><table width="100%" border="0" cellspacing="5" cellpadding="0" bgcolor="#FFFFFF">
          <tr>
            <td class="txt_verde" width="50%"><span id="nombre">Nombre <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
              <input name="nombre" type="text" value="<?php echo $nombre;?>" style="width:92%; border:thin #CCC solid; height:18px;" />
              </span> *</td>
            <td rowspan="4" class="txt_verde" valign="top">Consulta<br />
              <textarea name="consulta" style="width:95%; height: 150px; border:thin #CCC solid;" ><?php echo $consulta;?></textarea></td>
          </tr>
          <tr>
            <td class="txt_verde"><span id="apellido">Apellido <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
              <input name="apellido" type="text" value="<?php echo $apellido;?>" style="width:92%; border:thin #CCC solid; height:18px;" />
              </span> *</td>
          </tr>
          <tr>
            <td class="txt_verde">Teléfono<br />
              <input name="telefono" type="text" value="<?php echo $telefono;?>" style="width:92%; border:thin #CCC solid; height:18px;" /></td>
          </tr>
          <tr>
            <td class="txt_verde"><span id="mail">E-mail <span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
              <input name="email" type="text" value="<?php echo $mail;?>" style="width:92%; border:thin #CCC solid; height:18px;" />
              </span> *</td>
          </tr>
          <tr>
            <td><div style="float:left; width:240px;">
                <div style="float:left; width:150px;"><img id="siimage" style="border: 1px solid #f26322; margin-right: 5px" src="inc/securimage_show.php?sid=<?php echo md5(uniqid()); ?>" alt="CAPTCHA" align="left" /></div>
                <div style="float:left; width:30px; margin-left:10px;">
                  <object type="application/x-shockwave-flash" data="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" height="20" width="20">
                    <param name="movie" value="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" />
                  </object>
                  <br />
                  <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'inc/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="inc/images/refresh.png" alt="Reload Image" width="20" height="20" vspace="5" border="0" align="bottom" onclick="this.blur()" /></a></div>
                <div class="clearfix"></div>
              </div></td>
            <td><div><span class="tasacionTituForm">Ingrese el c&oacute;digo*:</span><br />
                <?php echo @$_SESSION['ctform']['captcha_error']; ?>
                <input type="text" name="captcha" size="12" maxlength="8" />
              </div></td>
          </tr>
          <tr>
            <td class="txt_verde" height="20">* Campos obligatorios </td>
            <td align="right" style="padding-right: 20px;"><input type="hidden" name="opcPrecioVenta" id="opcPrecioVenta" value="" />
              <input type="hidden" name="opcUbica" id="opcUbica" value="" />
              <input type="hidden" name="contacto" value="1" />
              <input name="enviar" type="submit" class="submitBtn" value="Enviar" /></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <div id="enviado" style="display:none; margin-top:5px;font-size: 1.1em;" class="buscarTitu">El c&oacute;digo de seguridad es erroneo.</div>
</form>
<?php if(isset($_POST['contacto']) && $_POST['contacto']==1){ ?>
<script type="text/javascript">
            document.getElementById('enviado').style.display = "block";
            </script>
<?php } ?>
<?php if(isset($_POST['opcLocalidad']) && $_POST['opcLocalidad'] != 0){ ?>
<script type="text/javascript">
	abreLocalidad();
</script>
<?php } ?>
<script type="text/javascript">
		var sprytextfield1 = new Spry.Widget.ValidationTextField("nombre");
		var sprytextfield2 = new Spry.Widget.ValidationTextField("apellido");
		var sprytextfield3 = new Spry.Widget.ValidationTextField("mail", "email");
 </script>
</body>
</html>