<?php
class Menu {
	
/**
 * Despliega el menu o barra de herramientas para la vista en tabla.
 * 
 *
 * @param 	$nombre -> nombre del formulario del cual se levanta la opcion y se identifica el proceso deonde ir
 * 			$opcion -> nombre del campo que contiene la opcion del menu a ejecutar
 * 			$contenido -> Array que contiene las opciones del menu, por cada elemento se ingresa un array
 * 							que contiene como primer valor El nombre de la opcion
 * 										 como segundo valor La imagen asociada a la opcion.
 */
	public function barraHerramientas($nombre,$opcion,$contenido){

		print "<table align='center'>\n";
		$x=0;
		$cantitems=8;
		$filasmenu=intval(sizeof($contenido)/$cantitems);
		if (sizeof($contenido)%$cantitems!=0){
			$filasmenu++;
		}
		for($filas=0;$filas<$filasmenu;$filas++){
			print "  <tr>\n";
			for ($y=0 ; $y<$cantitems ; $y++) {
				if($contenido[$x][2]==''){
					$item_menu=$contenido[$x][1];
				} else {
					$item_menu="<img src='".$contenido[$x][2]."' alt='".$contenido[$x][1]."'>";
				}
				print "		<td class='cd_celda_menu' align='center'>&nbsp;&nbsp;<a href=\"javascript: submitform('".$contenido[$x][0].$x."')\" class='link_menu'>";
				print $item_menu."</a>&nbsp;&nbsp;</td>\n";
				$x++;
				if ($x==sizeof($contenido) && $y<$cantitems-1){
					print "		<td class='cd_celda_menu' colspan='";
					echo $cantitems - $y;
					print "'>&nbsp;</td>\n";
					break;
				}
			}
			print "  </tr>\n";
		}
		print "</table>\n";
		
		
		print "<SCRIPT language=\"JavaScript\">\n";
		print "function submitform(valor){";
		print "  document.forms.".$nombre.".".$opcion.".value=valor;";
		print "  document.forms.".$nombre.".submit();";
		print "}";
		print "</SCRIPT>";
	}
	
	public function abrirPopUp($link){
		print "<SCRIPT language=\"JavaScript\">\n";
		print "function popup(param){\n";
		print "var link='http://".$link."' + param ;\n";
		print "window.open(link);\n";
		print "}\n";
		print "</SCRIPT>";
			
	}
	
	public function redireccionURL($url){
		header("Location:http://$url");
	}
	
} // FIN CLASE

?>
