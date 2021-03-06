<?php
include_once ('generic_class/class.VW.php');
//include_once ("generic_class/class.menu.php");
include_once ("clases/class.rubroBSN.php");
include_once ("clases/class.rubro.php");
include_once ("inc/funciones.inc");

class RubroVW extends VW{
	protected $clase = "Rubro";
	protected $rubro;
	protected $nombreId='Id_rubro';
	
//	private $arrayForm;
	
	public function __construct($_rubro = 0) {
//		RubroVW::creaRubro ();
		RubroVW::creaObjeto ();
		if ($_rubro instanceof Rubro) {
//			RubroVW::seteaRubro ( $_rubro );
			RubroVW::seteaVW ( $_rubro );
		}
		if (is_numeric ( $_rubro )) {
			if ($_rubro != 0) {
//				RubroVW::cargaRubro ( $_rubro );
				RubroVW::cargaVW ( $_rubro );
			}
		}
		RubroVW:: cargaDefinicionForm();
	}

/*	
	public function cargaRubro($_rubro) {
		$rubro = new RubroBSN ( $_rubro );
		$this->seteaRubro ( $rubro->getObjeto () ); //rubro());
	}
	public function getIdRubro() {
		return $this->rubro->getId_rubro ();
	}
	protected function creaRubro() {
		$this->rubro = new rubro ( );
	}
	protected function seteaRubro($_rubro) {
		$this->rubro = $_rubro;
		$rubro = new RubroBSN ( $_rubro );
		$this->arrayForm = $rubro->getObjetoView ();
	}
*/
	
	public function cargaDatosVW($origen='n') {
		if($origen=='n'){
			$accion='carga_rubro.php';
		}else{
			$accion='popupCargaRubro.php';
		}
		print "<form action='".$accion."' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaRubro(this);'>\n";
		if($origen=='n'){
//			$menu = new Menu ( );
//			$menu->dibujaMenu ( 'carga' );
		}
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Tipos de Rubros de Contactos</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Denominacion<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='denominacion' id='denominacion' value='" . $this->arrayForm ['denominacion'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Detalle</td>";
		print "<td width='85%'><input class='campos' type='text' name='detalle' id='detalle' value='" . $this->arrayForm ['detalle'] . "' maxlength='1000' size='80'></td></tr>\n";
		print "<input type='hidden' name='id_rubro' id='id_rubro' value='" . $this->arrayForm ['id_rubro'] . "'>\n";
		print "<br>";
		print "<tr><td align='right' colspan='2'><input class='boton_form' type='submit' value='Enviar'><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n";
		print "<tr><td align='right' colspan='2'><input type='button' class='boton_form' value='Regresar' onclick='KillMe();'></td></tr>";
		print "</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	
	/** * Lee desde un formulario los datos cargados para el rubro. * Los registra en un objeto del tipo rubro rubro de esta clase * */
/*
	public function leeDatosVW() {
		$rubro = new RubroBSN ( );
		$this->rubro = $rubro->leeDatosForm ( $_POST );
	}
*/
	
	/**    OK * Muestra una tabla con los datos de los rubros y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaVW() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_rubro.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<div class='pg_titulo'>Listado de Tipos de Rubros de contacto</div>\n";
//		$menu = new Menu ( );
//		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Denominacion</td>\n";
		print "     <td class='cd_lista_titulo'>Detalle</td>\n";
		print "	  </tr>\n";
		$evenBSN = new RubroBSN ( );
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
				if($Even ['id_rubro']==1 || $Even ['id_rubro']==2){
					print "<td align='center' width='25' class='row" . $fila . "' colspan='2'>&nbsp;</td>";
				}else{
					print "	 <td align='center' width='25' class='row" . $fila . "'>";
					print "    <a href='javascript:envia(\"lista\",182," . $Even ['id_rubro'] . ");' border='0'>";
					print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
					print "	 <td align='center' width='25' class='row" . $fila . "'>";
					print "    <a href='javascript:envia(\"lista\",183," . $Even ['id_rubro'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
					print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
					print "  </td>\n";
				}
				print "	 <td class='row" . $fila . "'>" . $Even ['denominacion'] . "</td>\n";
				print "	 <td class='row" . $fila . "'>" . $Even ['detalle'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_rubro' id='id_rubro' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}

/*	
	public function grabaModificacion() {
		$retorno = false;
		$rubro = new RubroBSN ( $this->rubro );
		$retUPre = $rubro->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabaci�n de los datos.<br>";
		}
		return $retorno;
	}

	public function grabaDatosVW() {
		$retorno = false;
		$rubro = new RubroBSN ( $this->rubro );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//
		//$existente = $rubro->controlDuplicado ( $this->rubro->getRubro () );
		//		if($existente){
		//			echo "Ya existe un rubro con ese Titulo";
		//		} else {
		$retIPre=$rubro->insertaDB();
		//			die();
		if ($retIPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabaci�n de los datos<br>";
		}
		//		}
		// Fin control de Duplicados
		return $retorno;
	}
	*/
}
// fin clase
?>