<?php require_once('Connections/config.php'); ?>
<?php
if(isset($_GET['idempre'])) {
    $lista = $_GET['idempre'];
	$tipo = "empre";
}else {
    if(isset ($_GET['id'])) {
        $lista = $_GET['id'];
		$tipo = "prop";
    }else {
        $lista = $_POST['lista_id_prop'];
    }
}
//echo $lista . ' - ' . $destino;
//die()

if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
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
$para="";

if ((isset($_POST["contacto"])) && ($_POST["contacto"] == "1")) {
	include_once ('inc/securimage.php');
	$securimage = new Securimage();
	$captcha=$_POST['captcha'];

	if ($securimage->check($captcha) == false) {
		$nombre=$_POST['nombre'];
		$apellido=$_POST['apellido'];
		$mail=$_POST['email'];
		$para=$_POST['para'];
	}else{
	

		$editFormAction = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING'])) {
		  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
		}
		
		$insertSQL = sprintf("INSERT INTO consultas (nombre, apellido, email, categoria) VALUES (%s, %s, %s, %s)",
						   GetSQLValueString($_POST['nombre'], "text"),
						   GetSQLValueString($_POST['apellido'], "text"),
						   GetSQLValueString($_POST['email'], "text"),
						   GetSQLValueString('RECOMENDAR', "text"));
		
		mysql_select_db($database_config, $config);
		$Result1 = mysql_query($insertSQL, $config) or die(mysql_error());
		
		header('Content-type: text/html; charset=utf-8');
		
		$mensaje = $_POST['nombre'] . ' ' .$_POST['apellido'] . "<br />";
		if($_POST['tipo'] == "empre"){
			$mensaje .= "Te recomienda visitar este emprendimiento!!!<br /><br />";
			$mensaje .= "Para visitarla haga click <a href=\"http://www.okeefe.com.ar/detalleEmprendimiento.php?i=".intval($_POST['id'])."\">aqu&iacute;</a><br />";	
			$Subject = 'Te recominedo visitar este Emprendimiento';
		}else{
			$mensaje .= "Te recomienda visitar esta propiedad!!!<br /><br />";
			$mensaje .= "Para visitarla haga click <a href=\"http://www.okeefe.com.ar/detalleProp.php?codigo=".intval($_POST['id'])."\">aqu&iacute;</a><br />";	
			$Subject = 'Te recominedo visitar esta Propiedad';
		}
		
		$de = $_POST['email'];
		$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];
		
		$paramail = explode(';', $_POST['para']);    
		
		if(count($paramail) > 1){
			for($i=0; $i <= count($paramail); $i++){
				$para = $paramail[$i].";";
			}
		}else{
			$para = $_POST['para'];
		}
		
		//so we use the MD5 algorithm to generate a random hash
		$random_hash = md5(date('r', time()));
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "To: " . $para . "\r\n";
		$headers .= "From: ".$deNombre." <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
		//add boundary string and mime type specification
		//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
		
		
		if(mail( $para, $Subject, $mensaje, $headers )){
			header("location:mailEnviado.html");
		}else{
			echo "Erroro al enviar";
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
<link rel="canonical" href="http://www.okeefe.com.ar/detalleProp.php?filtro=&div=&textoFiltro=&dest=&id=<?php echo $lista; ?>"/>
</head>
<body onload="self.focus();">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="envio_email" name="envio_email">
  <table align="center" width="95%">
    <tr>
      <td class="txt_verde">Nombre <span id="nombre"><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
        <input name="nombre" type="text" value="<?php echo $nombre;?>" style="width:95%; border:thin #CCC solid; height:18px;" />
        </span> *</span> </span></td>
    <tr>
      <td class="txt_verde">Apellido <span id="apellido"><span class="textfieldRequiredMsg">A value is required.</span><br />
        <input name="apellido" type="text" value="<?php echo $apellido;?>" style="width:95%; border:thin #CCC solid; height:18px;" />
        </span> *</span
      ></span></td>
    </tr>
    <tr>
      <td class="txt_verde">E-mail <span id="emal"><span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
        <input name="email" type="text" value="<?php echo $email;?>" style="width:95%; border:thin #CCC solid; height:18px;" />
        </span> *</span> </span></td>
    </tr>
    <tr>
      <td  class="txt_verde"><span id="sprypara">Enviar a <span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
        <span id="spryemail">
        <input name="para" type="text" value="<?php echo $para;?>" style="width:95%; border:thin #CCC solid; height:18px;" />
        </span> *</span></td>
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
        </div>
        <div style="float:left; width:240px;"><span class="tasacionTituForm">Ingrese el c&oacute;digo*:</span><br />
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
  <input id="id" name="id" type="hidden" value="<?php echo $lista; ?>" />
  <input id="tipo" name="tipo" type="hidden" value="<?php echo $tipo; ?>" />
  <input type="hidden" name="contacto" value="1" />
</form>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprypara");
var sprytextfield2 = new Spry.Widget.ValidationTextField("nombre");
var sprytextfield3 = new Spry.Widget.ValidationTextField("apellido");
var sprytextfield4 = new Spry.Widget.ValidationTextField("emal", "email");
//-->
</script>
</body>
</html>
