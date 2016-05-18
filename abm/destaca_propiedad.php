<?php
include_once("inc/encabezado.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedad.php");

include_once("clases/class.perfilesBSN.php");
include_once("clases/class.sucursal.php");
include_once("clases/class.grupo_tipoprop.php");

include_once("./inc/encabezado_html.php");
if (isset($_GET['i'])){
	if($_GET['i'] !=''){

		$perf = new PerfilesBSN();

		$perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
		$perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

		$gtp = new Grupo_tipoprop();
		if($perfGpo=='SUPERUSER' || $perfGpo=='LECTURA' || $perfGpo=='admin' || $perfGpo=='GRAL' || $perfGpo=='STAFF' || $perfGpo=='GERENCIA') {
			$listaTPG=0;
		}else {
			$listaTPG = $gtp->listaTipopropGrupo($perfGpo);
		}

		$id= $_GET['i'];
		$propBSN= new propiedadBSN($id);
		$prop=new Propiedad();
		$prop=$propBSN->getObjeto();
		$tipo_prop=$prop->getId_tipo_prop();
		$suc=$prop->getId_sucursal();
		$pos=strpos($listaTPG,$tipo_prop);
		if($listaTPG==0 || $pos!==false){
			if(trim($perfSuc)==trim($suc) || $perfSuc=='Todas'){
				if($prop->getDestacado()==1){
					$ret=$propBSN->normalizaPropiedad();
				}else{
					$ret=$propBSN->destacaPropiedad();
				}
				//    			$propBSN->borraDB();
			}
		}
	}
}
$id=0;
$origen="lista_propiedad.php?i=0";
header('location:'.$origen.$id);
include_once("./inc/pie.php");
?>