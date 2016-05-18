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

function query_into_array($query) {
    settype($retval,"array");
    $result= mysql_query($query);
    $FILA=mysql_num_rows($result);
    $COLUMNA=mysql_num_fields($result);
    for($CONTADOR_FILA=0;
    $CONTADOR_FILA<$FILA;
    $CONTADOR_FILA++) {
        for($CONTADOR_COLUMNA=0;
        $CONTADOR_COLUMNA<$COLUMNA;
        $CONTADOR_COLUMNA++) {
            $retval[$CONTADOR_FILA][mysql_field_name($result,$CONTADOR_COLUMNA)] = mysql_result($result,$CONTADOR_FILA, mysql_field_name($result,$CONTADOR_COLUMNA));
        }
    }
    return $retval;
}
$pag = 1;
if(isset($_GET['p'])){
	$pag = $_GET['p'];
}
mysql_select_db($database_config, $config);
$query_Recordset1 = sprintf("SELECT * FROM inversiones WHERE activa_inv = 1 ORDER BY orden ASC limit %s,4", (($pag-1)*4));
$Recordset1 = mysql_query($query_Recordset1, $config) or die(mysql_error());
$row = query_into_array($query_Recordset1);
$totalRows = mysql_num_rows($Recordset1);
//print_r($row);
//die();

$suc=7;
?>
<?php include_once('cabezalBuscador.php'); ?>
<?php include_once("menu.php");?>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<div id="contenido">
  <div id="izq">
    <div style="margin:5px; text-align:left"><span style="color:#666; font-size:1.3em;">Estos son algunos de nuestros pr&oacute;ximos lanzamientos:</span></div>
    <div id="inversiones">
      <?php 
	$col = 1;
	for($i = 0; $i <= count($row)-1; $i++){ 
		if(!is_int($col/2)){
			$estilo = " border-right:thin #CCC solid; border-bottom:thin solid #CCC; float:left;";
			echo "      <div class=\"clearfix\"></div>\n";
			$col++;
		}else{
			$estilo = " border-bottom:thin solid #CCC; float:right;";
			$col = 1;
		}
	?>
      <div style="width:347px; padding:10px 20px 10px 10px; <?php echo $estilo; ?>">
        <div style="float:left; width:125px;"><img src="inversiones/<?php echo $row[$i]['foto_inv']; ?>" style="max-height:172px; max-width:123px;" border="0" /></div>
        <div style="float:right; width:215px;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" height="172">
            <tr>
              <td valign="top"><span class="tituloInv"><?php echo $row[$i]['titulo_inv']; ?></span><br />
                <span class="bajadaInv"><?php echo $row[$i]['bajada_inv']; ?></span></td>
            </tr>
            <tr>
              <td class="zonaInv" valign="bottom"><?php echo $row[$i]['zona_inv']; ?></td>
            </tr>
            <tr>
              <td class="descInv" valign="bottom"><?php echo $row[$i]['descrip_inv']; ?></td>
            </tr>
            <?php if($row[$i]['link_inv'] != "" || !is_null($row[$i]['link_inv'])){ ?>
            <tr>
              <td height="15" align="right"><a href="<?php echo $row[$i]['link_inv']; ?>"><img src="images/vermashome.gif" width="53" height="11" /></a></td>
            </tr>
            <?php } ?>
          </table>
        </div>
        <div class="clearfix"></div>
      </div>
      <?php } ?>
      <div class="clearfix"></div>
      <div id="paginasInv">Pagina:
        <?php
	  if($totalRows > 4){
	  	for($i=1; $i <= ceil($totalRows/4); $i++){
			 echo "<a href=\"".$_SERVER['PHP_SELF']."?p=".$i."\">".$i." - </a>";
		} 
	  }else{
		  echo "1";
	  }
	  ?>
      </div>
    </div>
    <div class="clearfix"></div>
    <?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <div style="margin-top:10px"><img src="images/enteratePrimero.gif" width="235" height="20" alt="Enterate Primero de los Lanzamientos" /></div>
    <form action="#" method="post" name="inversionesForm">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="formInver">
        <tr>
          <td><span id="sprytextfield1"><span class="txtFormInv">Nombre </span><span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
            <input name="nombre" type="text" />
            </span></td>
        </tr>
        <tr>
          <td><span class="txtFormInv">Apellido</span><br />
            <input name="apellido" type="text" /></td>
        </tr>
        <tr>
          <td><span class="txtFormInv">Tel&eacute;fono</span><br />
            <input name="telefono" type="text" /></td>
        </tr>
        <tr>
          <td><span class="txtFormInv">E-mail</span><br />
            <input name="mail" type="text" /></td>
        </tr>
        <tr>
          <td><span class="txtFormInv">Consulta</span><br />
            <textarea name="consulta" style="border:thin solid #CCC; width:98%; height:100px; resize:none;"></textarea></td>
        </tr>
        <tr>
          <td><div style="float:left; width:200px;">
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
              <input type="text" name="captcha" size="12" maxlength="8" />
            </div>
            <div class="clearfix"></div>
            <div id="enviado" style="display:none; margin-top:5px;font-size: 1.1em;" class="buscarTitu">El c&oacute;digo de seguridad es erroneo.</div></td>
        </tr>
        <tr>
          <td><div style="float:left; font-size:.75em; width:160px; padding-top:5px;">Todos los campos son obligatorios</div>
            <div style="float:right;">
              <input name="enviar2" id="enviar" src="images/enviarForm.gif" style="width:71px; height:20px; border:none;" type="image">
            </div></td>
        </tr>
      </table>
    </form>
    <div style="margin-top:10px"><img src="images/noTeQuedesAfuera.gif" width="235" height="20" alt="No te los pierdas" /></div>
  </div>
  <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script> 
  
  <!-- Google Code for Inversiones Conversion Page --> 
  <script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 985947123;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "5TJQCP2AyQUQ87eR1gM";
var google_conversion_value = 0;
/* ]]> */
</script> 
  <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
  <noscript>
  <div style="display:inline;"> <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/985947123/?value=0&amp;label=5TJQCP2AyQUQ87eR1gM&amp;guid=ON&amp;script=0"/> </div>
  </noscript>
  <?php include_once("pie.php"); ?>
<?php
mysql_free_result($Recordset1);
?>
