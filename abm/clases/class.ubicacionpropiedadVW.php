<?php
include_once ('generic_class/class.VW.php');
//include_once ("generic_class/class.menu.php");
include_once ("clases/class.ubicacionpropiedadBSN.php");
include_once ("clases/class.ubicacionpropiedad.php");
include_once ("inc/funciones.inc");

class UbicacionpropiedadVW extends VW{
	protected $clase="Ubicacionpropiedad";
	protected $ubicacionpropiedad;
	protected $nombreId="Id_ubica";

	public function __construct($_ubicacionpropiedad = 0) {
		UbicacionpropiedadVW::creaObjeto();
		if ($_ubicacionpropiedad instanceof Ubicacionpropiedad) {
			UbicacionpropiedadVW::seteaVW( $_ubicacionpropiedad );
		}
		if (is_numeric ( $_ubicacionpropiedad )) {
			if ($_ubicacionpropiedad != 0) {
				UbicacionpropiedadVW::cargaVW( $_ubicacionpropiedad );
			}
		}
		UbicacionpropiedadVW::cargaDefinicionForm();
	}

	public function cargaDatosVW() {
		$ubiBSN = new UbicacionpropiedadBSN();
		print "<form action='carga_ubicacionpropiedad.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaUbicacionpropiedad(this);'>\n";
		print "<script type='text/javascript' >\n";
		print "function controlZona(){\n";
		print "   if(document.getElementById('zonaPrinc').checked){\n";
		print "   	vista='none';\n";
		print "   	document.getElementById('id_padre').value=0;\n";
		print "   	document.getElementById('txtUbica').innerHTML='Zona principal';\n";
		print "   }else{\n";
		print "   	vista='block';\n";
		print "   	seleccionaZona();\n";
		print "   }\n";
		print "   	document.getElementById('botonZona').style.display=vista;\n";
		print "}\n";
		print "function seleccionaZona(){\n";
		print "   window.open('seleccionaZona.php?v='+document.getElementById('id_padre').value+'&z=0&t=r', 'ventana', 'menubar=1,resizable=1,width=700,height=550');\n";
		print "}\n";
		print "</script>\n";

		print "<div class='pg_titulo'>Carga de Ubicaciones de propiedades</div>\n";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Depende de zona </td>";
		print "<td width='85%'>";
		print "<input type='checkbox' ";
		if($this->arrayForm ['id_padre']==0){
			print " checked ";
			$display='none';
			$valorPadre=0;
		}else{
			$display='block';
			$valorPadre=$this->arrayForm ['id_padre'];
		}
		print " id='zonaPrinc' name='zonaPrinc' onclick='javascript: controlZona();'>";
		print "<label for='zonaPrinc'>Es zona principal. Para ver zonas secundarias destilde el casillero y/u oprima el boton</label><br />";
		print "<input type='hidden' id='id_padre' name='id_padre' value='$valorPadre'>";
		print "<input type='button' id='botonZona' title='Seleciones dependencia ...' value='Seleciones dependencia ...' style='display: $display;' onclick=\"seleccionaZona();\">";
		print "<div id='txtUbica'>Zona principal</div>";


		print "</td><td></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Descripci&oacute;n<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='nombre_ubicacion' id='nombre_ubicacion' value='" . $this->arrayForm ['nombre_ubicacion'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<input type='hidden' name='id_ubica' id='id_ubica' value='" . $this->arrayForm ['id_ubica'] . "'>\n";
		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</form>\n";
	}

	/**    OK * Muestra una tabla con los datos de las zonas y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaVW() {
		$fila = 0;
		$ubiBSN = new UbicacionpropiedadBSN();
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_ubica.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "function filtro(opc){\n";
		print "     campoNombre='valorFiltro';\n";
		print "  if(opc==0){\n";
		print "     document.getElementById('valorFiltro').value='';\n";
		print "  }\n";
		print "  destino='tablaZonas'\n";
		print "  filtraUbicacion(campoNombre,destino);\n";
		print "}\n";
		print "function seleccionaZona(){\n";
		print "   window.open('seleccionaZona.php?v='+document.getElementById('id_padre').value+'&z=0&t=r', 'ventana', 'menubar=1,resizable=1,width=700,height=550');\n";
		print "}\n";

		print "</script>\n";
		print "<div class='pg_titulo'>Listado de Ubicacionpropiedades</div>\n";

		print "<div id='auto_datos'>\n";
		print "Filtrar por: <input class='campos' name='valorFiltro' id='valorFiltro' type='text' value='' style='width:300px;' onkeyup='filtro(1);'>\n";
 		print " <input type='button' value='Borrar filtro' onclick='filtro(0);' />\n";
		print "</div>\n";

		print "<form name='lista' method='POST' action='respondeMenu.php'>";

		$evenBSN = new UbicacionpropiedadBSN ( );
//		$arrayEven = $evenBSN->cargaColeccionForm();
		$arrayEven = $evenBSN->cargaColeccionFiltro('');
		print "<div id='tablaZonas'>";
		$this->despliegaTabla($arrayEven);
		print "</div>";
			
		
		print "<input type='hidden' name='id_ubica' id='id_ubica' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}

	public function despliegaTabla($arrayDatos){
		$ubiBSN=new UbicacionpropiedadBSN();
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
//		print "     <td class='cd_lista_titulo'>Zona</td>\n";
		print "     <td class='cd_lista_titulo'>Ubicacionpropiedad</td>\n";
		print "	  </tr>\n";
		if (sizeof ( $arrayDatos ) == 0) {
			print "No existen datos para mostrar";
		} else {
			$padAnt=-1;
			foreach ( $arrayDatos as $Even ) {
				if ($fila == 0) {
					$fila = 1;
				} else {
					$fila = 0;
				}

				if($padAnt!=$Even ['id_padre']){
					$ubiBSN->setId ( $Even ['id_padre'] );
					$ubiBSN->cargaById( $Even['id_padre'] );
					$nombre= $ubiBSN->getObjeto()->getNombre_ubicacion();
					$padAnt=$Even['id_padre'];
				}
				
				print "<tr>\n";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(\"lista\",132," . $Even ['id_ubica'] . ");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(\"lista\",133," . $Even ['id_ubica'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
//				print "	 <td class='row" . $fila . "'>";
//				$ubiBSN->setId ( $Even ['id_padre'] );
//				$ubiBSN->cargaById( $Even['id_padre'] );
//				print $ubiBSN->getObjeto()->getNombre_ubicacion();
//				print $nombre;
//				print "</td>\n";
				print "	 <td class='row" . $fila . "'>" . $Even ['nombre_ubicacion'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
	}
}
// fin clase
?>
