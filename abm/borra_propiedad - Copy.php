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
		if($perfGpo=='SUPERUSER' || $perfGpo=='LECTURA' || $perfGpo=='admin' || $perfGpo=='GRAL') {
			$listaTPG=0;
		}else {
			$listaTPG=$gtp->listaTipopropGrupo($perfGpo);
		}

		$id= $_GET['i'];
		$propBSN= new propiedadBSN($id);
		$prop=new Propiedad();
		$prop=$propBSN->getObjeto();
		$tipo_prop=$prop->getId_tipo_prop();
		$suc=$prop->getId_sucursal();
		      print "<script type='text/javascript' language='javascript'>\n";
		      print "confirma=confirm('Esta seguro que quiere eliminar este registro?');\n" ;
        	print "if(confirma){\n";
		      print "		 alert(\"CONFIRMO\");\n";
		      print "}else{\n";
		      print "		 alert(\"NO CONFIRMO\");\n";
		      print "}\n";
		      print "</script>\n";
//		      die();
		if(($listaTPG==0 || strpos($tipo_prop,$listaTPG)!==false) && ($perfSuc==$suc || $perfSuc=='Todas')){
		      print "<script type='text/javascript' language='javascript'>\n";
		      print "confirma=confirm('Esta seguro que quiere eliminar este registro?');\n" ;
        	print "if(confirma){\n";
		      print "		 alert(\"CONFIRMO\");\n";
		      print "}else{\n";
		      print "		 alert(\"NO CONFIRMO\");\n";
		      print "}\n";
		      print "</script>\n";
//    			$propBSN->borraDB();
		}
	}
}
	$id=0;
	$origen="lista_propiedad.php?i=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
/*
	include_once("inc/encabezado.php");
include_once("clases/class.propiedadBSN.php");
include_once("./inc/encabezado_html.php");
if (isset($_GET['i'])){
	if($_GET['i'] !=''){
		$id= $_GET['i'];
		$propBSN= new propiedadBSN($id);
		$propBSN->borraDB();
	}
}
	$id=0;
	$origen="lista_propiedad.php?i=";
	header('location:'.$origen.$id);
	include_once("./inc/pie.php");*/
?>