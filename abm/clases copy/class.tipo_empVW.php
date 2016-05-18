<?php
include_once ("generic_class/class.menu.php");
include_once ("clases/class.tipo_empBSN.php");
include_once ("clases/class.tipo_emp.php");
include_once ("inc/funciones.inc");
class Tipo_empVW {
	private $tipo_emp;
	private $arrayForm;
	public function __construct($_tipo_emp = 0) {
		Tipo_empVW::creaTipo_emp ();
		if ($_tipo_emp instanceof Tipo_emp) {
			Tipo_empVW::seteaTipo_emp ( $_tipo_emp );
		}
		if (is_numeric ( $_tipo_emp )) {
			if ($_tipo_emp != 0) {
				Tipo_empVW::cargaTipo_emp ( $_tipo_emp );
			}
		}
	}
	public function cargaTipo_emp($_tipo_emp) {
		$tipo_emp = new Tipo_empBSN ( $_tipo_emp );
		$this->seteaTipo_emp ( $tipo_emp->getObjeto () ); //tipo_emp());
	}
	public function getIdTipo_emp() {
		return $this->tipo_emp->getId_tipo_emp ();
	}
	protected function creaTipo_emp() {
		$this->tipo_emp = new tipo_emp ( );
	}
	protected function seteaTipo_emp($_tipo_emp) {
		$this->tipo_emp = $_tipo_emp;
		$tipo_emp = new Tipo_empBSN ( $_tipo_emp );
		$this->arrayForm = $tipo_emp->getObjetoView ();
	}
	public function cargaDatosTipo_emp() {
		print "<form action='carga_tipo_emp.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaTipo_emp(this);'>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'carga' );
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Tipos de Emprendimientos</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Tipo</td>";
		print "<td width='85%'><input class='campos' type='text' name='tipo_emp' id='tipo_emp' value='" . $this->arrayForm ['tipo_emp'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		print "<input type='hidden' name='id_tipo_emp' id='id_tipo_emp' value='" . $this->arrayForm ['id_tipo_emp'] . "'>\n";
		print "<br>";
		print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	
	/** * Lee desde un formulario los datos cargados para el tipo_emp. * Los registra en un objeto del tipo tipo_emp tipo_emp de esta clase * */
	public function leeDatosTipo_empVW() {
		$tipo_emp = new Tipo_empBSN ( );
		$this->tipo_emp = $tipo_emp->leeDatosForm ( $_POST );
	}
	
	/**    OK * Muestra una tabla con los datos de los tipo_emps y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaTipo_emp() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_tipo_emp.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Tipos de Emprendimientos</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Tipo de Propiedad</td>\n";
		print "	  </tr>\n";
		$evenBSN = new Tipo_empBSN ( );
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
				print "    <a href='javascript:envia(152," . $Even ['id_tipo_emp'] . ");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(153," . $Even ['id_tipo_emp'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td class='row" . $fila . "'>" . $Even ['tipo_emp'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_tipo_emp' id='id_tipo_emp' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}
	
	public function grabaModificacion() {
		$retorno = false;
		$tipo_emp = new Tipo_empBSN ( $this->tipo_emp );
		$retUPre = $tipo_emp->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabación de los datos.<br>";
		}
		return $retorno;
	}

	public function grabaTipo_emp() {
		$retorno = false;
		$tipo_emp = new Tipo_empBSN ( $this->tipo_emp );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//		
		//$existente = $tipo_emp->controlDuplicado ( $this->tipo_emp->getTipo_emp () );
		//		if($existente){
		//			echo "Ya existe un tipo_emp con ese Titulo";
		//		} else {			
		$retIPre=$tipo_emp->insertaDB();
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