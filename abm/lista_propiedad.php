<?php
include_once("inc/encabezado.php");
include_once("clases/class.propiedadVW.php");

include_once("inc/encabezado_html.php");

if(!isset($_SESSION['inicio'])){
	print "	<script type=\"text/javascript\">\n";
	echo "ventana('rssNovedades.php', 'Novedades', 400, 400);\n";
	$_SESSION['inicio'] = 1;
	print "</script>\n";
}
	

$idcrm='';
if(isset($_GET['b'])){
	$idcrm=$_GET['b'];
}

if (isset($_GET['i'])){
	$id= $_GET['i'];
	if(!isset($_POST['pagina'])){
		$pag=1;
	}else{
		$pag=$_POST['pagina'];
	}
	if(isset($_GET['t']) && $_GET['t'] ==1){
		print "<script type=\"text/javascript\">\n";
		print "window.open('fichaTasacion.php?id=".$id."', 'FichaTasacion','');\n";
		print "</script>\n";
	}
	$postreVW= new PropiedadVW();
	$postreVW->vistaTablaPropiedad($pag,$idcrm);
}
include_once("inc/pie.php");
?>

