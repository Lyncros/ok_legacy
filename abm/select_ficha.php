<?php
session_start();
include_once('clases/class.loginWebUserBSN.php');

$usrBSN = new LoginWebUserBSN();
$usrBSN->cargaById($_SESSION['UserId']);
$usrEmail = $usrBSN->getObjeto()->getEmail();

$lista = $_GET['id'];
$destino = "ficha_prop_pdf.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Achaval Cornejo - Nordelta</title>
<link href="css/ventanas.css" rel="stylesheet" type="text/css" />
</head>
<body onload="self.focus();">
<form action="<?php echo $destino;?>" method="post" id="envio_email" name="envio_email">
  <table align="center" width="95%">
    <tr>
      <td style=" padding-top: 10px;"><span class="txt_verde">Elija el tipo de Ficha:</span><span id="sprydesde">
        <select name="tipo" id="tipo">
            <option value="1" selected="selected"> Completa</option>
            <option value="2">Compacta</option>
            <option value="3">Sin Cabezal</option>
        </select></td>
    </tr>
     <tr>
      <td align="right" style="padding-top:10px;"><input name="enviar" type="image" src="images/enviar.gif" /></td>
    </tr>
  </table>
  <input id="id_prop" name="id_prop" type="hidden" value="<?php echo $lista; ?>" />
</form>
</body>
</html>
