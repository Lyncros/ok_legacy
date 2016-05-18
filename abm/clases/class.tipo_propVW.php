<?php
include_once ('generic_class/class.VW.php');
//include_once ("generic_class/class.menu.php");
include_once ("clases/class.tipo_propBSN.php");
include_once ("clases/class.tipo_prop.php");
include_once ("inc/funciones.inc");

class Tipo_propVW extends VW{
	protected $clase="Tipo_prop";
	protected $tipo_prop;
	protected $nombreId="Id_tipo_prop";

	public function __construct($_tipo_prop = 0) {
		Tipo_propVW::creaObjeto();
		if ($_tipo_prop instanceof Tipo_prop) {
			Tipo_propVW::seteaVW( $_tipo_prop );
		}
		if (is_numeric ( $_tipo_prop )) {
			if ($_tipo_prop != 0) {
				Tipo_propVW::cargaVW( $_tipo_prop );
			}
		}
		Tipo_propVW::cargaDefinicionForm();
	}

	public function cargaDatosVW() {
		print "<form action='carga_tipo_prop.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaTipo_prop(this);'>\n";
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Tipos de propiedad</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Tipo<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='tipo_prop' id='tipo_prop' value='" . $this->arrayForm ['tipo_prop'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Subtipo</td>";
		print "<td width='85%'><input class='campos' type='text' name='subtipo_prop' id='subtipo_prop' value='" . $this->arrayForm ['subtipo_prop'] . "' maxlength='1000' size='80'></td></tr>\n";
		print "<input type='hidden' name='id_tipo_prop' id='id_tipo_prop' value='" . $this->arrayForm ['id_tipo_prop'] . "'>\n";
		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}

	/**    OK * Muestra una tabla con los datos de los tipo_props y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaVW() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_tipo_prop.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<div class='pg_titulo'>Listado de Tipos de Propiedades</div>\n";
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Tipo de Propiedad</td>\n";
		print "     <td class='cd_lista_titulo'>Subtipo de Propiedad</td>\n";
		print "	  </tr>\n";
		$evenBSN = new Tipo_propBSN ( );
		$arrayEven = $evenBSN->cargaColeccionForm ();
		if (sizeof ( $arrayEven ) == 0) {
			print "No existen datos para mostrar";
		} else {
			foreach ( $arrayEven as $Even ) {
				if ($fila == 0) {
					$fila = 1;
				} else {
					$fila = 0;
				}
				print "<tr>\n";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(\"lista\",102," . $Even ['id_tipo_prop'] . ");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(\"lista\",103," . $Even ['id_tipo_prop'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td class='row" . $fila . "'>" . $Even ['tipo_prop'] . "</td>\n";
				print "	 <td class='row" . $fila . "'>" . $Even ['subtipo_prop'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_tipo_prop' id='id_tipo_prop' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}


}
// fin clase
?>