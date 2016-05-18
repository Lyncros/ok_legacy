<?php
include_once("./generic_class/class.cargaConfiguracion.php");
include_once("clases/class.emprendimientoBSN.php");

$conf=CargaConfiguracion::getInstance('');
$anchoPagina=$conf->leeParametro("ancho_pagina");
$loca=$_GET['l'];
$zona=$_GET['z'];
$valor=$_GET['v'];


$empBSN = new EmprendimientoBSN();

print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
print "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
print "<head>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
print "<title>O'Keefe Propiedades</title>\n";
print "<link href=\"css/ventanas.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

print "     <script type=\"text/javascript\">    \n";
print "     function actualizaOrigen(f) {\n";
print "		seleccion=leeCheckbox(f);\n";

print "         window.opener.document.getElementById('fid_emp').value=seleccion[0];\n";
print "         window.opener.document.getElementById('aux_emp').value=seleccion[0];\n";
print "         window.opener.document.getElementById('aux_selemp').value=seleccion[1];\n";

print "         window.close();\n";

print "     }\n";
print "      function leeCheckbox(f) {\n";
print "			seleccion='';\n";
print "			campos='';\n";
print "         retorno=new Array(2);\n";
print " 		elementos=f.elements.length;\n";
print " 		for ( var n= 0 ; n < elementos; n++ ) {\n";
print "				largo=f.elements[n].name.length;\n";
print "				pref=f.elements[n].name.substring(0,3);\n";
print "				pid=f.elements[n].name.substring(4,largo);\n";
print " 		   if(f.elements[n].checked && pref=='emp'){\n" ;
print "					if(seleccion.length>0){\n";
print "						seleccion+=',';\n";
print "						campos+=', ';\n";
print "					}\n";
print "					seleccion+=pid;\n";
print "					campos+=f.elements[n].value;\n";
print "				}\n";
print " 		}\n";
print " 		retorno[0]=seleccion;\n";
print "			retorno[1]=campos;\n";
print "				return retorno;\n";
//print "			window.close();\n";
print "		}\n";

print "    </script>\n";
print "</head>\n";
print "<body>\n";
print "    <form >\n";
print "		<table width=\"400\">";
print "             <tr><td class=\"cd_lista_filtro\">Seleccione los Emprendimientos deseados</td></tr>\n";
print "             <tr><td>\n";
$empBSN->checkboxEmprendimiento($valor,$zona,$loca);
print "             </td></tr>\n";
print "		   <tr><td align=\"right\"><input type=\"button\" class=\"boton_form\" name=\"enviar\" value=\"Enviar\" onclick=\"actualizaOrigen(this.form);\"/></td></tr>\n";
print "         </table>\n";
print "    </form>\n";
print "</body>\n";
print "</html>\n";
?>