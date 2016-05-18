<?php
include_once ("generic_class/class.menu.php");
include_once ("clases/class.tipo_propBSN.php");
include_once ("clases/class.tipo_prop.php");
include_once ("inc/funciones.inc");
class Tipo_propVW {
	private $tipo_prop;
	private $arrayForm;
	public function __construct($_tipo_prop = 0) {
		Tipo_propVW::creaTipo_prop ();
		if ($_tipo_prop instanceof Tipo_prop) {
			Tipo_propVW::seteaTipo_prop ( $_tipo_prop );
		}
		if (is_numeric ( $_tipo_prop )) {
			if ($_tipo_prop != 0) {
				Tipo_propVW::cargaTipo_prop ( $_tipo_prop );
			}
		}
	}
	public function cargaTipo_prop($_tipo_prop) {
		$tipo_prop = new Tipo_propBSN ( $_tipo_prop );
		$this->seteaTipo_prop ( $tipo_prop->getObjeto () ); //tipo_prop());
	}
	public function getIdTipo_prop() {
		return $this->tipo_prop->getId_tipo_prop ();
	}
	protected function creaTipo_prop() {
		$this->tipo_prop = new tipo_prop ( );
	}
	protected function seteaTipo_prop($_tipo_prop) {
		$this->tipo_prop = $_tipo_prop;
		$tipo_prop = new Tipo_propBSN ( $_tipo_prop );
		$this->arrayForm = $tipo_prop->getObjetoView ();
	}
	public function cargaDatosTipo_prop() {
		print "<form action='carga_tipo_prop.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaTipo_prop(this);'>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'carga' );
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
	
	/** * Lee desde un formulario los datos cargados para el tipo_prop. * Los registra en un objeto del tipo tipo_prop tipo_prop de esta clase * */
	public function leeDatosTipo_propVW() {
		$tipo_prop = new Tipo_propBSN ( );
		$this->tipo_prop = $tipo_prop->leeDatosForm ( $_POST );
	}
	
	/**    OK * Muestra una tabla con los datos de los tipo_props y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaTipo_prop() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_tipo_prop.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Tipos de Propiedades</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
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
				print "    <a href='javascript:envia(102," . $Even ['id_tipo_prop'] . ");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(103," . $Even ['id_tipo_prop'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
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
	
	public function grabaModificacion() {
		$retorno = false;
		$tipo_prop = new Tipo_propBSN ( $this->tipo_prop );
		$retUPre = $tipo_prop->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabación de los datos.<br>";
		}
		return $retorno;
	}

	public function grabaTipo_prop() {
		$retorno = false;
		$tipo_prop = new Tipo_propBSN ( $this->tipo_prop );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//		
		//$existente = $tipo_prop->controlDuplicado ( $this->tipo_prop->getTipo_prop () );
		//		if($existente){
		//			echo "Ya existe un tipo_prop con ese Titulo";
		//		} else {			
		$retIPre=$tipo_prop->insertaDB();
		//			die();
		if ($retIPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		//		}
	// Fin control de Duplicados
		return $retorno;
	}
}
// fin clase
?>