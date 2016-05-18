<?php
include_once ("generic_class/class.menu.php");
include_once ("clases/class.zonaBSN.php");
include_once ("clases/class.zona.php");
include_once ("inc/funciones.inc");
class ZonaVW {
	private $zona;
	private $arrayForm;
	public function __construct($_zona = 0) {
		ZonaVW::creaZona ();
		if ($_zona instanceof Zona) {
			ZonaVW::seteaZona ( $_zona );
		}
		if (is_numeric ( $_zona )) {
			if ($_zona != 0) {
				ZonaVW::cargaZona ( $_zona );
			}
		}
	}
	
	public function cargaZona($_zona) {
		$zona = new ZonaBSN ( $_zona );
		$this->seteaZona ( $zona->getObjeto () );
	}
	
	public function getId_zona() {
		return $this->zona->getId_zona();
	}
	
	protected function creaZona() {
		$this->zona = new zona ( );
	}
	
	protected function seteaZona($_zona) {
		$this->zona = $_zona;
		$zona = new ZonaBSN ( $_zona );
		$this->arrayForm = $zona->getObjetoView();
	}
	
	
	public function cargaDatosZona() {
		print "<form action='carga_zona.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaZona(this);'>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'carga' );
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Zonas</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Descripci&oacute;n<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='nombre_zona' id='nombre_zona' value='" . $this->arrayForm ['nombre_zona'] . "' maxlength='250' size='80'></td</tr>\n";
		print "<input type='hidden' name='id_zona' id='id_zona' value='" . $this->arrayForm ['id_zona'] . "'>\n";
		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	
	
	/** * Lee desde un formulario los datos cargados para el zona. * Los registra en un objeto del tipo zona zona de esta clase * */
	public function leeDatosZonaVW() {
		$zona = new ZonaBSN ();
		$this->zona = $zona->leeDatosForm ($_POST);
	}
	
	
	/**    OK * Muestra una tabla con los datos de las zonas y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaZona() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_zona.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Zonas</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Zona</td>\n";
		print "	  </tr>\n";
		$evenBSN = new ZonaBSN ( );
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
				print "    <a href='javascript:envia(132," . $Even ['id_zona'] . ");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td  align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(133," . $Even ['id_zona'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['nombre_zona'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_zona' id='id_zona' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}
	
	
	public function grabaModificacion() {
		$retorno = false;
		$zona = new ZonaBSN ( $this->zona );
		$retUPre = $zona->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabaci—n de los datos.<br>";
		}
		return $retorno;
	}
	
	
	public function grabaZona() {
		$retorno = false;
		$zona = new ZonaBSN ( $this->zona );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//		
		//$existente = $zona->controlDuplicado ( $this->zona->getNombre_zona () );
		//if($existente){
		//	echo "Ya existe un zona con ese Titulo";
		//} else {
			$retIPre=$zona->insertaDB();
		//	die();
			if ($retIPre) {
				echo "Se proceso la grabacion en forma correcta<br>";
				$retorno = true;
			} else {
				echo "Fallo la grabación de los datos<br>";
			}
		//}
	// Fin control de Duplicados		
	return $retorno;
	}
}
// fin clase
?>