<?php
session_start();
include_once('clases/class.loginwebuserBSN.php');

$usrBSN = new LoginwebuserBSN();
$usrBSN->cargaById($_SESSION['UserId']);
$usrEmail = $usrBSN->getObjeto()->getEmail();

$lista = $_GET['id'];
$destino = "ficha_prop_pdf.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>O'Keefe - Nordelta</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="css/ventanas.css" rel="stylesheet" type="text/css" />
</head>
<body onload="self.focus();">
<form action="<?php echo $destino;?>" method="post" id="envio_email" name="envio_email">
  <table align="center" width="95%">
    <tr>
      <td style=" padding-top: 10px;"><span class="txt_verde">De:</span><br /><span id="sprydesde">
        <input name="desde" type="text" value="<?php echo $usrEmail;?>" style="width:95%; border:thin #CCC solid; height:18px;" />
      <span class="textfieldRequiredMsg">A value is required.</span></span> *</td>
    </tr>
    <tr>
      <td style=" padding-top: 10px;"><span class="txt_verde">Para (separados por ; hasta 5 cuentas):</span><br /><span id="spryemail">
        <input name="email" type="text" value="" style="width:95%; border:thin #CCC solid; height:18px;" />
        <span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span></span> *</td>
    </tr>
    <tr>
      <td style=" padding-top: 10px;"><span class="txt_verde">Asunto:</span><br />
        <span id="spryasunto">
        <input name="asunto" type="text" value="" style="width:95%; border:thin #CCC solid; height:18px;" />
        <span class="textfieldRequiredMsg">A value is required.</span></span> *</td>
    </tr>
<!--    <tr>
      <td style=" padding-top: 10px;"><span class="txt_verde">Adjunto:</span><br />
        <input name="adjunto" id="adjunto" type="file" value="" style="width:95%; border:thin #CCC solid; height:18px;" />
        </td>
    </tr>-->
    <tr>
      <td style=" padding-top: 10px;"><span class="txt_verde">Comentarios:</span><br />
      <textarea name="comentario" cols="100" rows="5" style="width:95%; border:thin #CCC solid;">
Muchas gracias por contactarnos.
Adjunto la ficha de referencia.

O'Keefe Propiedades
      </textarea></td>
    </tr>
    <tr>
      <td align="right" style="padding-top:10px;"><input name="enviar" type="submit" value="enviar" /></td>
    </tr>
  </table>
  <input id="id_prop" name="id_prop" type="hidden" value="<?php echo $lista; ?>" />
  <input id="op" name="op" type="hidden" value="mail" />
</form>
<script type="text/javascript">
<!--
var sprytextfield3 = new Spry.Widget.ValidationTextField("spryemail");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprydesde", "email");
var sprytextfield2 = new Spry.Widget.ValidationTextField("spryasunto");
//-->
</script>
</body>
</html>
