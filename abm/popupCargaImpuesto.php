<?php
include_once("inc/encabezado.php");
include_once("clases/class.impuestoBSN.php");
include_once("clases/class.impuestoVW.php");

include_once("./inc/encabezado_html.php");

if(isset($_GET['imp'])){
	$impuesto=0;
}else{
	$impuesto=$_POST['id_impuesto'];
}


?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>O'Keefe Propiedades</title>
<script LANGUAGE="JavaScript" type="text/javascript"
	src="inc/funciones.js"></script>
<script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
<link href="css/agenda.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
            var StayAlive = 1;
            function KillMe(){
                setTimeout("self.close();",StayAlive * 1000);
            }
        </script>
</head>
<body>

<?php

$ingreso=true;
$id="";

if (isset($_GET['imp'])){
	$id= $_GET['imp'];
	$notiVW= new ImpuestoVW($id);
} else {
	$notiVW= new ImpuestoVW($id);
	if(isset($_POST['id_impuesto'])){
		$notiVW->leeDatosVW();
		$id=$notiVW->getIdImpuesto();
		if ($_POST['id_impuesto']==0){
			$retorno=$notiVW->grabaDatosVW();
		}
		$ingreso=false;
	}
}
if ($ingreso){
	$notiVW->cargaDatosVW('p');
}  else {
	echo "<script type=\"text/javascript\">KillMe(); </script>\n";
}

include_once("./inc/pie.php");
?>