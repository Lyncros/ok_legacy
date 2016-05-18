<?php
include_once("./generic_class/class.cargaConfiguracion.php");
include_once("clases/class.tipo_propBSN.php");

$conf=CargaConfiguracion::getInstance('');
$anchoPagina=$conf->leeParametro("ancho_pagina");
$valor=$_GET['v'];

$tipoBSN = new Tipo_propBSN();
//print_r($locaBSN);
print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
print "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
print "<head>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
print "<title>O'Keefe Propiedades</title>\n";
print "<link href=\"css/ventanas.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

print "     <script type=\"text/javascript\">    \n";
print "      function actualizaOrigen(f) {\n";
print "		seleccion=leeCheckbox(f);\n";
//print "         alert(seleccion[1]);\n";
print "         opener.document.getElementById('fid_tipo_prop').value=seleccion[0];\n";
print "         opener.document.getElementById('aux_prop').value=seleccion[0];\n";
print "         opener.document.getElementById('aux_selprop').value=seleccion[1];\n";
//print "         opener.document.getElementById('seleccionLocalidad').innerHTML=seleccion[1];\n";
print "		opener.filtra();\n";
print "		window.close();\n";
print "     }\n";
print "      function leeCheckbox(f) {\n";
print "			seleccion='';\n";
print "			campos='';\n";
print "         retorno=new Array(2);\n";
print " 		elementos=f.elements.length;\n";
print " 		for ( var n= 0 ; n < elementos; n++ ) {\n";
print "				largo=f.elements[n].name.length;\n";
print "				pref=f.elements[n].name.substring(0,2);\n";
print "				pid=f.elements[n].name.substring(3,largo);\n";
print " 		   if(f.elements[n].checked && pref=='tp'){\n" ;
print "					if(seleccion.length>0){\n";
print "						seleccion+=',';\n";
print "						campos+=', ';\n";
print "					}\n";
print "					seleccion+=pid;\n";
print "					campos+=f.elements[n].value;\n";
print "				}\n";
print " 		}\n";
//print "				return seleccion;\n";
//print "			window.close();\n";
print "			retorno[0]=seleccion;\n";
print "			retorno[1]=campos;\n";
print "				return retorno;\n";
print "		}\n";

print "    </script>\n";
print "</head>\n";
print "<body style='margin: 0px auto;'>\n";
print "    <form >\n";
print "		<table width=\"400\">";
print "             <tr><td class=\"cd_lista_filtro\">Seleccione los Tipos de Propiedad deseadas</td></tr>\n";
print "		   <tr><td>";
$tipoBSN->checkboxTipoProp($valor,1);
print "        </td></tr>\n";
print "		   <tr><td align=\"right\"><input type=\"button\" class=\"boton_form\" name=\"enviar\" value=\"Enviar\" onclick=\"actualizaOrigen(this.form);\"/></td></tr>\n";
print "         </table>\n";
print "    </form>\n";
print "</body>\n";
print "</html>\n";
?>