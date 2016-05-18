<?php require_once('../Connections/config.php'); ?>
<?php
// Valida Usuario
require_once('../inc/validaUsuario.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');
?><?php
// Make unified connection variable
$conn_config = new KT_connection($config, $database_config);
?><?php
//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_config, "../");
//Grand Levels: Any
$restrict->Execute();
//End Restrict Access To Page
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>
<style type="text/css">
.menu2 {padding:0 0 0 32px; margin:0; list-style:none; height:30px; background:#efefef url(button1.gif); position:relative; border:1px solid #000; border-width:0 1px; border-bottom:1px solid #444;}
.menu2 li {float:left;}
.menu2 li a {display:block; float:left; height:30px; line-height:30px; color:#aaa; text-decoration:none; font-size:12px; font-family:arial, verdana, sans-serif; font-weight:bold; text-align:center; padding:0 0 0 8px; cursor:pointer;}
.menu2 li a b {float:left; display:block; padding:0 16px 0 8px;}
.menu2 li.current a {color:#fff; background:url(button3.gif);}
.menu2 li.current a b {background:url(button3.gif) no-repeat right top;}
.menu2 li a:hover {color:#fff; background:#000 url(button4.gif);}
.menu2 li a:hover b {background:url(button4.gif) no-repeat right top;}
.menu2 li.current a:hover {color:#fff; background:#000 url(button3.gif); cursor:default;}
.menu2 li.current a:hover b {background:url(button3.gif) no-repeat right top;}

body {
	background-color: #003399;
	margin: 0px;
	padding: 0px;
}
</style>
</head>

<body>

<ul class="menu2">
<?php if($_SESSION['kt_login_level'] == 9){ ?>
<li><a href="bannerTopABM.php" target="centro"><b>Banners Cabezal</b></a></li>
<li><a href="bannerCentroABM.php" target="centro"><b>Banners Centrales</b></a></li>
<li><a href="mediosABM.php" target="centro"><b>Medios de Prensa</b></a></li>
<li><a href="prensaABM.php" target="centro"><b>Prensa</b></a></li>
<li><a href="inversionesABM.php" target="centro"><b>Inversiones</b></a></li>
<li><a href="cedinABM.php" target="centro"><b>CEDIN</b></a></li>
<li><a href="contactos_consulta.php" target="centro"><b>Contactos</b></a></li>
<?php } ?>
<li><a href="gacetillasABM.php" target="centro"><b>GACETILLAS</b></a></li>
<li><a href="finalizar.php"><b>Finalizar</b></a></li>
</ul>

</body>
</html>
