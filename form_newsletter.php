<?php require_once('Connections/config.php'); ?>
<?php
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


$nombre="";
$apellido="";
$mail="";
$tipo="";

if ((isset($_POST["contacto"])) && ($_POST["contacto"] == "1")) {
	include_once ('inc/securimage.php');
	$securimage = new Securimage();
	$captcha=$_POST['captcha'];

	if ($securimage->check($captcha) == false) {
		$nombre=$_POST['nombre'];
		$apellido=$_POST['apellido'];
		$mail=$_POST['email'];
		$tipo=$_POST['tipo'];
	}else{
		$editFormAction = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING'])) {
		  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
		}
		
		if ((isset($_POST["contacto"])) && ($_POST["contacto"] == "1")) {
		  $insertSQL = sprintf("INSERT INTO susc_newsletter (nombre_susc, apellido_susc, email_susc, area) VALUES (%s, %s, %s, %s)",
							   GetSQLValueString($_POST['nombre'], "text"),
							   GetSQLValueString($_POST['apellido'], "text"),
							   GetSQLValueString($_POST['email'], "text"),
							   GetSQLValueString($_POST['tipo'], "int"));
		
		  mysql_select_db($database_config, $config);
		  $Result1 = mysql_query($insertSQL, $config) or die(mysql_error());
		
		  $insertGoTo = "mailEnviado.html";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
			$insertGoTo .= $_SERVER['QUERY_STRING'];
		  }
		
			header('Content-type: text/html; charset=utf-8');
		
			$de = $_POST['email'];
			$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];
		
			$mensaje = $de . "<br />";
			$mensaje .= $deNombre . "<br />";
			
			switch($_POST['tipo']){
				case 1:
					$asunto="News Rural";
					$mail="dennis@okeefe.com.ar";
					break;
				case 2:
					$asunto="News Residencial";
					$mail="paulo@okeefe.com.ar";
					break;
				case 3:
					$asunto="News Comercial";
					$mail="tomas@okeefe.com.ar";
					break;
				case 4:
					$asunto="News Emprendimientos";
					$mail="ignacio@okeefe.com.ar";
					break;
			}
		
			//so we use the MD5 algorithm to generate a random hash
			$random_hash = md5(date('r', time()));
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=UTF-8\r\n";
			$headers .= "To: " . $para . "\r\n";
		//	$headers .= "To: soporte@zgroupsa.com.ar\r\n";
			$headers .= "From: ".$deNombre." <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
			//add boundary string and mime type specification
			//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
			
			
			if(mail( $mail, $asunto, $mensaje, $headers )){
				header("location:mailEnviado.html");
			}else{
				echo "Erroro al enviar";
			}
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Inmobiliaria O’Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires. Solicita que deje su mail para poder enviarle nuestra oferta de propiedades.</title>
<META NAME="DESCRIPTION" CONTENT="Inmobiliaria O’Keefe presenta las novedades de compra, venta y alquiler de departamentos, casas, oficinas, galpones, locales, lotes, quintas, chacras y campos en pesos y financiados en la zona sur del gran Buenos Aires. Solicita que deje su mail para poder enviarle nuestra oferta de propiedades.">
<META NAME="KEYWORDS" CONTENT="Inmobiliaria Quilmes, inmobiliaria en Hudson, emprendimientos, Casas, Departamentos, Oficinas, countries, barrios cerrados, alquileres zona sur, Locales, Campos, Chacras, quintas, Hudson Park, lotes, terrenos, Quilmes, Bernal, Hudson, Country Abril, fideicomisos inmobiliarios, Ida Goldoni, Haras del Sur, Campos de Roca,area60,fincas de Iraola, Fincas de Hudson, El Carmencito, Country Club el Carmen, Néstor Rojo, Otamendi, Fogola, Abril Country Club, Club de campo, Greenville, Ombúes de Hudson, Altos de Hudson, Villalobos, Toribio Achaval, Landing, Urban Network, Nuñez, Veltri, Mabel Enriquez, Fornaroli Grande, Surbania, Gabaston, Diseñarq, Remax, Brandsen, Chascomús, Pila, Castelli, Dolores, General Guido, Maipú, Magdalena, Veronica, San Vicente, Ranchos, General Belgrano, Ayacucho, San Miguel del Monte, El Pato, Abasto, El Peligro, Bavio, Lezama, Chivilcoy, 25 de Mayo, Saladillo, General Alvear, Las Flores, Tandil, Olavarría, Balcarce, Rauch, 9 de Julio, Cañuelas, La Plata, General Madariaga, General Lavalle, Coronel Pringles, Bolivar, Bragado, Lobos, Roque Perez, Navarro, Azul, Alberti, Cuenca del salado, Chacras, Administración de campos, venta de hacienda, arrendamientos, Estancias, Patagonia, Cascos antiguos, Soja, polo, Pato, coto de caza, pesca, Avícola, porcinos, Haras, emprendimientos productivos, Forestación,  campo, campos, argentina, negocios, inmobiliario, inmobiliaria, rural, trigo, cría, buenos aires, provincia, república, venta, arrendamiento, fin de semana, weekend, wee-kend, turismo, oportunidad, caza, campos en argentina, emprendimientos, venta campos, campos venta, comprar campos, Tierras del Sur,  venta de ranchos, compra de estancias, venta de granjas, ranchos en Argentina, tierras para turismo, tierras de la Patagonia Argentina, Inmobiliaria rural, tierras para coto de caza, bienes, listado agrícola, Tierras en Alquiler, Fincas, Terrenos en Alquiler, Alquiler de Fincas, Terrenos, Agente de Bienes, agente inmobiliario, Comprar, Vender, Tierras Alquileres, Argentina Inmobiliaria, los campos para la venta, ranchos en la Patagonia, las tierras de la Patagonia, tambo, producción de leche, Real State, Farms, Nordheimer, Elizalde Garraham, Castex, Compañía Argentina de tierras, CAT, L J Ramos, Emir Carrillo, Girotti Campos, Impacto, Jorge Dorado, Monasterio Tattersall, CAIR, Cámara Argentina de Inmobiliarias Rurales, Jasale, Pereyra, Alzaga Unzue y Cia, Dasilva Enriquez.">
<META HTTP-EQUIV="CHARSET" CONTENT="ISO-8859-1">
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="css/okeefeVentanas.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
</head>
<body onload="self.focus();">
<p style="padding:5px 12px;">Si desea recibir nuestro Newsletter, complete el formulario:</p>
<form action="<?php echo $editFormAction; ?>" method="POST" id="envio_email" name="envio_email">
  <table align="center" width="95%">
    <tr>
      <td class="txt_verde"><span id="sprytextfield1">Nombre <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
        <input name="nombre" type="text" value="<?php echo $nombre; ?>" style="width:95%; border:thin #CCC solid; height:18px;" />
        </span></td>
    <tr>
      <td class="txt_verde"><span id="sprytextfield2">Apellido <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
        <input name="apellido" type="text" value="<?php echo $apellido; ?>" style="width:95%; border:thin #CCC solid; height:18px;" />
        </span></td>
    </tr>
    <tr>
      <td class="txt_verde"><span id="sprytextfield3">E-mail<span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
        <input name="email" type="text" value="<?php echo $mail; ?>" style="width:95%; border:thin #CCC solid; height:18px;" />
        </span></td>
    </tr>
    <tr>
      <td class="txt_verde"><span id="spryselect1">Tipo de informaci&oacute;n<br />
        <select name="tipo">
          <option value="-1">Seleccione tipo de información</option>
          <option value="1" <?php if($tipo == 1){ echo "selected=\"selected\""; }?>>Rural</option>
          <option value="2" <?php if($tipo == 2){ echo "selected=\"selected\""; }?>>Residencial</option>
          <option value="3" <?php if($tipo == 3){ echo "selected=\"selected\""; }?>>Comercial</option>
          <option value="4" <?php if($tipo == 4){ echo "selected=\"selected\""; }?>>Emprendimientos</option>
        </select>
        <span class="selectInvalidMsg">Por favor selecciones un item.</span><span class="selectRequiredMsg">Por favor selecciones un item.</span></span></td>
    </tr>
    <tr>
      <td><div style="float:left; width:200px; margin-top:10px;">
          <div style="float:left; width:150px;"><img id="siimage" style="border: 1px solid #f26322; margin-right: 5px" src="inc/securimage_show.php?sid=<?php echo md5(uniqid()); ?>" alt="CAPTCHA" align="left" /></div>
          <div style="float:left; width:30px; margin-left:10px;">
            <object type="application/x-shockwave-flash" data="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" height="20" width="20">
              <param name="movie" value="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" />
            </object>
            <br />
            <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'inc/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="inc/images/refresh.png" alt="Reload Image" width="20" height="20" vspace="5" border="0" align="bottom" onclick="this.blur()" /></a></div>
          <div class="clearfix"></div>
        </div>
        <div style="float:left; width:120px; margin-top:10px;"><span class="tasacionTituForm">Ingrese el c&oacute;digo*:</span><br />
          <?php echo @$_SESSION['ctform']['captcha_error'] ?>
          <input type="text" name="captcha" size="12" maxlength="8" />
        </div>
        <div class="clearfix"></div>
        <div id="enviado" style="display:none; margin-top:5px;font-size: 1.1em;" class="buscarTitu">El c&oacute;digo de seguridad es erroneo.</div></td>
    </tr>
    <tr>
      <td align="right"><input name="enviar" type="submit" class="submitBtn" value="Enviar" /></td>
    </tr>
  </table>
  <input type="hidden" name="contacto" value="1" />
</form>
<!-- Google Code for Newsletter Conversion Page --> 
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 985947123;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "c9UACPWByQUQ87eR1gM";
var google_conversion_value = 0;
/* ]]> */
</script> 
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;"> <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/985947123/?value=0&amp;label=c9UACPWByQUQ87eR1gM&amp;guid=ON&amp;script=0"/> </div>
</noscript>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "email");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {invalidValue:"-1"});
</script>
</body>
</html>
