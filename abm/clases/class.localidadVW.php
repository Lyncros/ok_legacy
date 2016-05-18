<?php
include_once ("generic_class/class.menu.php");
include_once ("clases/class.localidadBSN.php");
include_once ("clases/class.localidad.php");
include_once ("clases/class.zonaBSN.php");
include_once ("inc/funciones.inc");
class LocalidadVW {
	private $localidad;
	private $arrayForm;
	public function __construct($_localidad = 0) {
		LocalidadVW::creaLocalidad ();
		if ($_localidad instanceof Localidad) {
			LocalidadVW::seteaLocalidad ( $_localidad );
		}
		if (is_numeric ( $_localidad )) {
			if ($_localidad != 0) {
				LocalidadVW::cargaLocalidad ( $_localidad );
			}
		}
	}
	
	public function cargaLocalidad($_localidad) {
		$localidad = new LocalidadBSN ( $_localidad );
		$this->seteaLocalidad ( $localidad->getObjeto () );
	}
	
	public function getId_loca() {
		return $this->localidad->getId_loca();
	}
	
	protected function creaLocalidad() {
		$this->localidad = new localidad ( );
	}
	
	protected function seteaLocalidad($_localidad) {
		$this->localidad = $_localidad;
		$localidad = new LocalidadBSN ( $_localidad );
		$this->arrayForm = $localidad->getObjetoView();
	}
	
	
	public function cargaDatosLocalidad() {
		$zonaBSN = new ZonaBSN ();
		print "<form action='carga_localidad.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaLocalidad(this);'>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'carga' );
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Localidades</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Zona</td>";
		print "<td width='85%'>";
		$zonaBSN->comboZona($this->arrayForm ['id_zona']);
		print "</td><td></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Descripci&oacute;n<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='nombre_loca' id='nombre_loca' value='" . $this->arrayForm ['nombre_loca'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<input type='hidden' name='id_loca' id='id_loca' value='" . $this->arrayForm ['id_loca'] . "'>\n";
		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	
	
	/** * Lee desde un formulario los datos cargados para el localidad. * Los registra en un objeto del tipo localidad localidad de esta clase * */
	public function leeDatosLocalidadVW() {
		$localidad = new LocalidadBSN ();
		$this->localidad = $localidad->leeDatosForm ($_POST);
	}
	
	
	/**    OK * Muestra una tabla con los datos de las zonas y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaLocalidad() {
		$zona = new Zona ();
		$zonaBSN = new ZonaBSN ();
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_loca.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Localidades</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Zona</td>\n";
		print "     <td class='cd_lista_titulo'>Localidad</td>\n";
		print "	  </tr>\n";
		$evenBSN = new LocalidadBSN ( );
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
				print "    <a href='javascript:envia(142," . $Even ['id_loca'] . ");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(143," . $Even ['id_loca'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td class='row" . $fila . "'>";
				$zonaBSN->setId ( $Even ['id_zona'] );
				$zonaBSN->cargaById( $Even['id_zona'] );
				print $zonaBSN->getObjeto()->getNombre_zona();
				print "</td>\n";
				print "	 <td class='row" . $fila . "'>" . $Even ['nombre_loca'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_loca' id='id_loca' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}
	
	
	public function grabaModificacion() {
		$retorno = false;
		$localidad = new LocalidadBSN ( $this->localidad );
		$retUPre = $localidad->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabación de los datos.<br>";
		}
		return $retorno;
	}
	
	
	public function grabaLocalidad() {
		$retorno = false;
		$localidad = new LocalidadBSN ( $this->localidad );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//		
		//$existente = $localidad->controlDuplicado ( $this->localidad->getNombre_zona () );
		//if($existente){
		//	echo "Ya existe un localidad con ese Titulo";
		//} else {
			$retIPre=$localidad->insertaDB();
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