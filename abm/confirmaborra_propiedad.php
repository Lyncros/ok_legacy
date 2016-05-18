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

		$id= $_GET['i'];
		      print "<script type='text/javascript' language='javascript'>\n";
		      print "confirma=confirm('Esta seguro que quiere eliminar este registro?');\n" ;
        	print "if(confirma){\n";
		      print "   pagina='borra_propiedad.php?i=".$id."';\n";
		      print "}else{\n";
		      print "   pagina='lista_propiedad.php?i='\n";
		      print "}\n";
          print "document.location.href=pagina;\n";
		      print "</script>\n";
		}
	}
//	$id=0;
//	$origen="lista_propiedad.php?i=";
//	header('location:'.$origen.$id);
	include_once("./inc/pie.php");
?>