<?php
include_once("inc/encabezado.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedadVW.php");
include_once("clases/class.operacionBSN.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.fotoBSN.php");
include_once("inc/funciones.inc");

include_once("./inc/encabezado_html.php");

$ingreso=true;
$id="";
$modi=0;
$origen="lista_propiedad.php?i=";
if(isset($_GET['rep']) && $_GET['rep']==0){
	$rep=1;
	$actual=0;
}
if(isset($_POST['rep']) && is_numeric($_POST['rep'])){
	$rep=$_POST['rep'];
	$actual=$_POST['actual'];
}
if($actual<=$rep) {

	if (isset($_GET['i'])){
		$id= $_GET['i'];
		$notiVW= new PropiedadVW($id);
	} else {
		$notiVW= new PropiedadVW($id);
		if(isset($_POST['id_prop'])){
			$oldid=$_POST['id_prop'];
			$notiVW->leeDatosPropiedadVW();
			$notiVW->seteaVW(duplicaMapas($notiVW->getVW(),$actual));

			$retorno=$notiVW->grabaPropiedad();

			if(!$retorno){
				echo "Fallo el registro de los datos";
			} else {
				$ingreso=false;

				// $notiVW->seteaVW(0);

				$newid=$notiVW->getId();

				$operacion=new Operacion();
				$operacion->setId_prop($newid);
				$operacion->setIntervino($_SESSION['UserId']);
				$operacion->setCfecha(date("d-m-Y"));
				$operacion->setOperacion($_POST['operacion']);
				$operacionBSN= new OperacionBSN($operacion);
				$ret=$operacionBSN->insertaDB();

				$datosprop=new DatospropBSN();
				$datosprop->clonaCaracteristicasByPropiedad($oldid,$newid);

				$fotoBSN = new FotoBSN();
				$fotoBSN->clonaFotosByPropiedad($oldid,$newid);
			}
		}
	}

	if($actual<$rep) {
		$actual++;
		$notiVW->cargaDatosClonacion($rep,$actual);
	}else{
		$_SESSION['opcionMenu']=2;
		$origen="lista_propiedad.php?i=";
		header('location:'.$origen);
	}


}else{
	$_SESSION['opcionMenu']=2;
	$origen="lista_propiedad.php?i=";
	header('location:'.$origen);
}
include_once("./inc/pie.php");


function duplicaMapas($propiedad,$pos){
	$conf = CargaConfiguracion::getInstance();
	$path = $conf->leeParametro('path_fotos');
	$pathC = $conf->leeParametro('path_fotos_chicas');
	$pl1=$propiedad->getPlano1();
	$pl2=$propiedad->getPlano2();
	$pl3=$propiedad->getPlano3();

	$propiedad->setPlano1((($pl1=duplicaImagen($pl1,$pos,$path,$pathC))!=false)?$pl1:'');
	$propiedad->setPlano2((($pl2=duplicaImagen($pl2,$pos,$path,$pathC))!=false)?$pl2:'');
	$propiedad->setPlano3((($pl3=duplicaImagen($pl3,$pos,$path,$pathC))!=false)?$pl3:'');

	return $propiedad;
}

// Utilizadas en la clonacion de imagenes y/o planos
function duplicaImagen($nombre,$actual,$path='',$pathC=''){
	if($nombre!=''){
		$nombreMod=renombraImagen($nombre,$actual);
		if($path!=''){
			copy($path."/".$nombre,$path."/".$nombreMod);
		}
		if($pathC!=''){
			copy($pathC."/".$nombre,$pathC."/".$nombreMod);
		}
	}else{
		$nombreMod=false;
	}
	return $nombreMod;
}

function renombraImagen($nomb,$act){
	$posg=strpos($nomb,'_');
	$posg2=strpos($nomb,'_',$posg+1)+1;
	$largo=strlen(strval($act-1));
	$nomMod =substr($nomb,0,$posg).'_'.date('YmdHis').$act.'_'.substr($nomb,$posg2);
	return $nomMod;
}
?>
