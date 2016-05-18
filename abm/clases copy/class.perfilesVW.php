<?php
include_once ("generic_class/class.menu.php");
include_once ("clases/class.perfilesBSN.php");
include_once ("clases/class.perfiles.php");
include_once ("inc/funciones.inc");

class PerfilesVW {
	private $perfiles;
	private $arrayForm;
	
	public function __construct($_perfil='') {
		PerfilesVW::creaPerfiles();
		if ($_perfil instanceof Perfiles ) {
			PerfilesVW::seteaPerfiles( $_perfil);
		}
		if (!is_numeric ( $_perfil)) {
			if ($_perfil != '') {
				PerfilesVW::cargaPerfiles ( $_perfil );
			}
		}
	}
	
	public function cargaPerfiles($_perfil) {
		$perfil = new PerfilesBSN( $_perfil );
		$this->seteaPerfiles( $perfil->getObjeto () );
	}
	
	public function getPerfil() {
		return $this->perfiles->getPerfil();
	}
	
	protected function creaPerfiles() {
		$this->perfiles = new Perfiles();
	}
	
	protected function seteaPerfiles($_perfil) {
		$this->perfiles = $_perfil;
		$perfil = new PerfilesBSN( $_perfil);
		$this->arrayForm = $perfil->getObjetoView();
	}
	
	
	public function cargaDatosPerfil() {
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'carga' ,'opcion');
		print "<form action='carga_perfil.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaPerfil(this);'>\n";
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Perfiles</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Perfil<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='perfil' id='perfil' value='" . $this->arrayForm ['perfil'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Descripci&oacute;n<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='descripcion' id='descripcion' value='" . $this->arrayForm ['descripcion'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<br>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";		
		print "<input type='hidden' name='auxperf' id='auxperf' value='" . $this->arrayForm ['perfil'] . "'>";		
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	
	
	/** * Lee desde un formulario los datos cargados para el perfiles. * Los registra en un objeto del tipo perfiles perfiles de esta clase * */
	public function leeDatosPerfilesVW() {
		$perfil = new PerfilesBSN();
		$this->perfiles= $perfil->leeDatosForm ($_POST);
	}
	
	
	/**    OK * Muestra una tabla con los datos de las perfiless y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaPerfiles() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.perfil.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Perfiles</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='3'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Perfil</td>\n";
		print "     <td class='cd_lista_titulo'>Descripcion</td>\n";
		print "	  </tr>\n";
		$evenBSN = new PerfilesBSN();
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
				print "    <a href='javascript:envia(822,\"" . $Even ['perfil'] . "\");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td  align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(823,\"" . $Even ['perfil'] . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(824,\"" . $Even ['perfil'] . "\");' border='0'>";
				print "       <img src='images/asignar.png' alt='Asignar' title='Asignar' border=0></a></td>";
				print "	 <td  class='row" . $fila . "'>" . $Even ['perfil'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['descripcion'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='perfil' id='perfil' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}
	
	
	public function grabaModificacion() {
		$retorno = false;
		$perfiles = new PerfilesBSN ( $this->perfiles );
		$retUPre = $perfiles->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabaci�n de los datos.<br>";
		}
		return $retorno;
	}
	
	
	public function grabaPerfiles() {
		$retorno = false;
		$perfiles = new PerfilesBSN ( $this->perfiles );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//		
		//$existente = $perfiles->controlDuplicado ( $this->perfiles->getNombre_perfiles () );
		//if($existente){
		//	echo "Ya existe un perfiles con ese Titulo";
		//} else {
			$retIPre=$perfiles->insertaDB();
		//	die();
			if ($retIPre) {
				echo "Se proceso la grabacion en forma correcta<br>";
				$retorno = true;
			} else {
				echo "Fallo la grabaci�n de los datos<br>";
			}
		//}
	// Fin control de Duplicados		
	return $retorno;
	}
}
// fin clase
?>