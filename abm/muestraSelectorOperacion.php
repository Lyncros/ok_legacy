<?php
include_once("./generic_class/class.cargaConfiguracion.php");

$conf=CargaConfiguracion::getInstance('');
$anchoPagina=$conf->leeParametro("ancho_pagina");
$valor=$_GET['v'];

$valor=str_replace("\\","",$valor);
$valor=str_replace("\"","",$valor);

//$array=array('Venta','Alquiler','Alquiler Temporario','Alquiler o Venta','Tasacion','Suspendido','Retirado','Alquilado','Vendido','Reservado');
$array=array('Venta','Alquiler','Alquiler Temporario','Alquiler o Venta','Tasacion','Alquilado','Reservado','Vendido','Suspendido','Retirado','No Ingresado','Tasado');

print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
print "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
print "<head>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
print "<title>O'Keefe Propiedades</title>\n";
print "<link href=\"css/ventanas.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

print "     <script type=\"text/javascript\">    \n";
print "      function actualizaOrigen(f) {\n";
print "			seleccion=leeCheckbox(f);\n";
//print " 		alert(seleccion);\n";
print "         opener.document.getElementById('foperacion').value=seleccion[0];\n";
print "         opener.document.getElementById('aux_operacion').value=seleccion[0];\n";
print "         opener.document.getElementById('aux_seloperacion').value=seleccion[1];\n";
//print "         opener.document.getElementById('seleccionOperacion').innerHTML=seleccion[1];\n";
print "			opener.filtra();\n";
print "			window.close();\n";
print "		}\n";
print "      function leeCheckbox(f) {\n";
print "			seleccion='';\n";
print "			campos='';\n";
print "			retorno= new Array(2);\n";
print " 		elementos=f.elements.length;\n";
print " 		for ( var n= 0 ; n < elementos; n++ ) {\n";
print "				largo=f.elements[n].name.length;\n";
print "				pref=f.elements[n].name.substring(0,2);\n";
print "				pid=f.elements[n].name.substring(3,largo);\n";
print " 		   if(f.elements[n].checked && pref=='op'){\n" ;
print "					if(seleccion.length>0){\n";
print "						seleccion+=',';\n";
print "						campos+=', ';\n";
print "					}\n";
print "					seleccion+=('"."\""."'+pid+'"."\""."');\n";
print "					campos+=pid;\n";
print "				}\n";
print " 		}\n";
print "			retorno[0]=seleccion;\n";
print "			retorno[1]=campos;\n";
print "				return retorno;\n";
//print "			window.close();\n";
print "		}\n";

print "    </script>\n";
print "</head>\n";
print "<body>\n";
print "    <form >\n";
print "		<table width=\"350\">";
print "             <tr><td class=\"cd_lista_filtro\">Seleccione los Tipos de Operaciones deseadas</td></tr>\n";
print "		   <tr><td>";

        $arraySeleccion = array();
        if($valor!=''){
//        	echo $valor." - ".str_replace("\\\"","'",$valor)."<br>";
//echo $valor."<br>";
        	$arraySeleccion=split(',',$valor);
//        	print_r($arraySeleccion);
        }
		print "<table align='center' bgcolor='#FFFFFF' style=\"width: 100%;\">";
		$col=0;
		foreach ($array as $funcion){
			if($col==2){
				print "</tr>";
				$col=0;
			}
			if($col==0){
				print "<tr class='campos'>";
			}
			$col++;
			print "<td width='50%'><input type='checkbox' id='op_".$funcion."' name='op_".$funcion."'";
			if (in_array($funcion,$arraySeleccion)){
				print " checked ";
			}
			print ">&nbsp;&nbsp;".$funcion."</td>\n";
		}
		print "</tr></table>\n";

print "        </td></tr>\n";
print "		   <tr><td align=\"right\"><input type=\"button\" class=\"boton_form\" name=\"enviar\" value=\"Enviar\" onclick=\"actualizaOrigen(this.form);\"/></td></tr>\n";
print "         </table>\n";
print "    </form>\n";
print "</body>\n";
print "</html>\n";



?>
