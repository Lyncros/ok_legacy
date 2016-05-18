<?php
include_once("inc/encabezado.php");
include_once("clases/class.rubroBSN.php");
include_once("clases/class.rubroVW.php");

if(isset($_GET['u'])){
	$rubro=0;
}else{
	$rubro=$_POST['id_rubro'];
}

include_once 'inc/encabezado_pop.php';
?>
<?php

$ingreso=true;
$id="";

if (isset($_GET['u'])){
	$id= $_GET['u'];
	$notiVW= new RubroVW($id);
} else {
	$notiVW= new RubroVW($id);
	if(isset($_POST['id_rubro'])){
		$notiVW->leeDatosVW();
		$id=$notiVW->getIdRubro();
		if ($_POST['id_rubro']==0){
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