<?php
include_once("inc/encabezado.php");
include_once("clases/class.eventoBSN.php");
include_once("clases/class.eventoVW.php");

include_once("./inc/encabezado_html.php");


$ingreso=true;
$id="";
$origen="calendario.php?i=";

$dia = $_GET['dia'];
$mes = $_GET['mes'];
$anio = $_GET['anio'];

//echo $dia . " - " . $mes  . " - " . $anio . " - " . $_GET['i'];

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$notiVW= new EventoVW($id);
} else {
	$notiVW= new EventoVW($id);
	if(isset($_POST['id_evento'])){
		$notiVW->leeDatosEventoVW();
		$id=$notiVW->getIdEvento();
		if ($_POST['id_evento']==0){
			$retorno=$notiVW->grabaEvento();			
		} else {
			$retorno=$notiVW->grabaModificacion();
			header('location:'.$origen.$id);
		}
		if(!$retorno){
			echo "Fallo el registro de los datos";
		} else {	
			$ingreso=false;
		}
	} 
}
if ($ingreso){
	$notiVW->cargaDatosEvento($dia,$mes,$anio);
}  else {
	header('location:'.$origen.$id);
}

include_once("./inc/pie.php");
?>

