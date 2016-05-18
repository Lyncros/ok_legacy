<?php require_once('../Connections/config.php'); ?>
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

mysql_select_db($database_config, $config);
$query_destinos = "SELECT destino FROM consultas GROUP BY destino ";
$destinos = mysql_query($query_destinos, $config) or die(mysql_error());
//$row_destinos = mysql_fetch_assoc($destinos);
//$totalRows_destinos = mysql_num_rows($destinos);

mysql_select_db($database_config, $config);
$query_categoria = "SELECT categoria FROM consultas GROUP BY categoria ";
$categorias = mysql_query($query_categoria, $config) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
	color: #333;
	font-size:14px;
}
.clearfix:after {
	visibility: hidden;
	display: block;
	font-size: 0;
	content: " ";
	clear: both;
	height: 0px;
}
.clearfix {
	display: inline-block;
	visibility: hidden;
	display: block;
	font-size: 0;
	content: " ";
	clear: both;
	height: 0px;
}

#contactos div {
	width: 30%;
	float: left;
}
#contactos div input {
	border: thin solid #CCC;
	width: 200px;
	padding:3px;
	margin-bottom:7px;
}
#contactos div select {
	border: thin solid #CCC;
	width: 200px;
	padding:3px;
	margin-bottom:7px;
	max-width:300px;
}
#contactos div select, option {
	max-width:550px;
}
#tituFiltro{
	height:22px;
	color:#FFF;
	padding-top:5px;
	padding-left:25px;
	font-weight:bold;
	background:url(../images/fabajo.gif) no-repeat 10px #008046;
	border-radius: 5px;
	-ms-border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-khtml-border-radius: 5px;
	cursor:pointer;
}
#filtroContactos{
	padding:20px;
	height:auto;
}
#contactosResultado {
	width: 1000px;
	margin: 0px auto;
}
#resFiltro{
	height: 22px;
	color: #FFF;
	padding-top: 5px;
	padding-left: 25px;
	font-weight: bold;
	background: #008046;
	border-radius: 5px;
	-ms-border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-khtml-border-radius: 5px;
	margin-top: 3px;
}
#contactosResultado td{
	overflow: hidden;
	background-color: #EEEEEE;
	height:22px;
}
#contactosResultado .titu_tabla{
	/* [disabled]background: #008046; */
	text-transform: capitalize;
	color: #008046;
	padding: 2px;
	border-top: thin solid #008046;
	border-bottom: thin solid #008046;
	background-color: #FFF;
	font-weight: bold;
}
#contactosResultado #paginado tr{
	background-color:#999;
}
#contactosResultado #paginado td{
	background-color:#999;
	padding:3px 10px;
}
#contactosResultado #paginado a{
	color:#FFF;
	text-decoration:none;
}
</style>
<link href="../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../js/themes/jquery.ui.tooltip.css"/>
<script src="../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
<script src="../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.tooltip.js"></script>
<script type="text/javascript" src="../js/ajax.js"></script>
<script type="text/javascript" src="../js/ajax-dynamic-content.js"></script>
<script type="text/javascript">
$(function() {
	$( document ).tooltip();
});
function ocultaDiv(divName) {
	if(document.getElementById(divName).style.display == "" || document.getElementById(divName).style.display == "block") {
    	document.getElementById(divName).style.display = "none";
		document.getElementById('tituFiltro').style.backgroundImage = "url(../images/fderecha.gif)";
  	}else {
		document.getElementById(divName).style.display = "block";
		document.getElementById('tituFiltro').style.backgroundImage = "url(../images/fAbajo.gif)";
	}
} 
jQuery(function($){
	$.datepicker.regional['es'] = {
		clearText: 'Borrar',
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});
function filtrar(actionVal){
	if(actionVal != -1){
		var nombre = document.getElementById('nombre').value;
		var apellido = document.getElementById('apellido').value;
		var mail = document.getElementById('mail').value;
		var categoria = document.getElementById('categoria').value;
		var destino = document.getElementById('destino').value;
		var acceso = document.getElementById('acceso').value;
		var desde = document.getElementById('desde').value;
		var hasta = document.getElementById('hasta').value;
		ajax_loadContent('contactosResultado','contacots_filtro.php?nombre='+nombre+'&apellido='+apellido+'&mail='+mail+'&categoria='+categoria+'&destino='+destino+'&acceso='+acceso+'&desde='+desde+'&hasta='+hasta);
	}else{
		ajax_loadContent('contactosResultado','contacots_filtro.php?todos=1');
	}
}
</script>
</head>

<body>
<div style="width:1000px; margin:10px auto;">
<div id="tituFiltro" onclick="ocultaDiv('filtroContactos');">Filtro de contactos</div>
  <div id="filtroContactos">
    <form id="contactos" name="filtroContactos">
      <div>Apellido<br />
        <input name="apellido" id="apellido" type="text" />
        <br />
        Nombre<br />
        <input name="nombre" id="nombre" type="text" />
        <br />
        e-mail<br />
        <input name="mail" id="mail" type="text" />
      </div>
      <div>Categoria<br />
              <input name="categoria" id="categoria" type="text" />

<!--        <select name="categoria" id="categoria">
        <option value="-1">Todas</option>
        <?php while($row_categorias = mysql_fetch_assoc($categorias)){ ?>
        <option value="<?php echo $row_categorias['categoria']; ?>"><?php echo $row_categorias['categoria']; ?></option>
        <?php } ?>
        </select>-->
        <br />
        Destino<br />
        <select name="destino" id="destino">
        <option value="-1">Todos</option>
        <?php while($row_destinos = mysql_fetch_assoc($destinos)){ ?>
        <option value="<?php echo $row_destinos['destino']; ?>"><?php echo $row_destinos['destino']; ?></option>
        <?php } ?>
        </select>
        <br />
        Acceso<br />
        <input name="acceso" id="acceso" type="text" />
      </div>
      <div>Desde<br />
        <input name="desde" id="desde" type="date" />
        <script type="text/javascript">
// BeginWebWidget jQuery_UI_Calendar: jQueryUICalendar1
jQuery("#desde").datepicker({numberOfMonths: 1, showButtonPanel: true, dateFormat: "dd-mm-yy", regional:'es'});

// EndWebWidget jQuery_UI_Calendar: jQueryUICalendar1
        </script>
<br />
        Hasta<br />
        <input name="hasta" id="hasta" type="date" />
        <script type="text/javascript">
// BeginWebWidget jQuery_UI_Calendar: jQueryUICalendar1
jQuery("#hasta").datepicker({numberOfMonths: 1,showButtonPanel: true, dateFormat: "dd-mm-yy", regional:'es'});

// EndWebWidget jQuery_UI_Calendar: jQueryUICalendar1
        </script>
        <br /><br />
        <input type="button" name="enviar" value="Consultar" style="width: 80px; background-color: #CCC" onclick="filtrar(1);" />&nbsp;&nbsp;
        <input type="reset" name="borrar" value="Borrar" style="width:80px; background-color:#CCC" onclick="filtrar(-1);" />
      </div>
      <div class="clearfix"></div>
    </form>
  </div>
      <div class="clearfix"></div>
<div id="resFiltro">Contactos</div>
  <div id="contactosResultado"></div>
</div>
<script type="text/javascript">
ajax_loadContent('contactosResultado','contacots_filtro.php?todos=1');
</script>
</body>
</html>
<?php
mysql_free_result($destinos);
?>
