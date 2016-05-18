<html>
<head>
<script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
<link href="css/agenda.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
<div id='filtro'>
<?php
include_once("inc/class.cuadroBuscador.php");

$cuadro=new cuadrosBuscador();

$cuadro->armaScriptFiltrado();
//$filtro=array(array('pepe','1'));
//$textoFiltro=array(array('Persona','Pepe'));
$filtro='';
$textoFiltro='';
$cuadro->armaCuadroFiltro($filtro,$textoFiltro);
$cuadro->armaEstructuraContenedores();

?>
</div>

<div id='datos'>
</div>
<?php
$cuadro->armaVentanaModal();
?>
</div>

</body>
</html>