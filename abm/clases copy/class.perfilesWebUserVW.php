<?php
include_once ("generic_class/class.menu.php");
include_once ("clases/class.perfilesWebUserBSN.php");
include_once ("clases/class.perfileswebuser.php");
include_once ("inc/funciones.inc");

class PerfilesWebUserVW {
	private $perfileswebuser;
	private $arrayForm;
	
	public function __construct($_perfil='') {
		PerfilesWebUserVW::creaPerfilesWebUser();
		if ($_perfil instanceof Perfileswebuser ) {
			PerfilesWebUserVW::seteaPerfilesWebUser( $_perfil);
		}
		if (!is_numeric ( $_perfil)) {
			if ($_perfil != '') {
				PerfilesWebUserVW::cargaPerfilesWebUser ( $_perfil );
			}
		}
	}
	
	public function cargaPerfilesWebUser($_perfil) {
		$perfil = new PerfilesWebUserBSN( $_perfil );
		$this->seteaPerfilesWebUser( $perfil->getObjeto () );
	}
	
	public function getPerfilWebUser() {
		return $this->perfileswebuser->getPerfil();
	}
	
	protected function creaPerfilesWebUser() {
		$this->perfileswebuser = new Perfileswebuser();
	}
	
	protected function seteaPerfilesWebUser($_perfil) {
		$this->perfileswebuser = $_perfil;
		$perfil = new PerfilesWebUserBSN( $_perfil);
		$this->arrayForm = $perfil->getObjetoView();
	}
	
	
	public function cargaAsignacionUsuario(){
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'carga' ,'opcion');

		print "<form name='carga' method='post' action='carga_perfilWU.php' >";
		print "	<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Asignacion de usuarios al Perfil ".$this->arrayForm['perfil']."</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		$perfilBSN= new PerfilesBSN();
		print "<tr><td class='cd_celda_texto' width='15%'>Perfil<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>";
		
		$perfilBSN->comboPerfiles($this->arrayForm['perfil']);
		print "</td></tr>\n";		
		print "<tr><td class='cd_celda_texto' width='15%'>Usuario<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>";
		$usuario = new LoginwebuserBSN();
		$usuario->comboUsuarios($this->arrayForm['id_user']);
		print "</td></tr>\n";
		print "<br>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";		
		print "<input type='hidden' name='auxperfil' id='auxperfil' value='" . $this->arrayForm ['perfil'] . "'>";		
		print "<input type='hidden' name='auxuser' id='auxuser' value='" . $this->arrayForm ['id_user'] . "'>";		
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";

		print "</form>\n";		
	}
	
	
	/** * Lee desde un formulario los datos cargados para el perfiles. * Los registra en un objeto del tipo perfiles perfiles de esta clase * */
	public function leeDatosPerfilesWebUserVW() {
		$perfil = new PerfilesWebUserBSN();
		$this->perfiles= $perfil->leeDatosForm ($_POST);
	}
	
	
	/**    OK * Muestra una tabla con los datos de las perfiless y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaPerfilesWebUser() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id,perfil){\n";
		print "     document.forms.lista.id_user.value=id;\n";
		print "     document.forms.lista.perfil.value=perfil;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Usuarios con perfil ".$this->perfileswebuser->getPerfil()."</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Perfil</td>\n";
		print "     <td class='cd_lista_titulo'>Usuario</td>\n";
		print "	  </tr>\n";
		$perf= new Perfileswebuser($this->perfileswebuser->getPerfil());
		$evenBSN = new PerfilesWebUserBSN($perf);
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
				print "    <a href='javascript:envia(8242," . $Even ['id_user'] .",\"". $Even ['perfil'] ."\");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td  align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(8243," . $Even ['id_user'] .",\"". $Even ['perfil'] . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				
				print "	 <td  class='row" . $fila . "'>" . $Even ['perfil'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['usuario'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='perfil' id='perfil' value=''>";
		print "<input type='hidden' name='id_user' id='id_user' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}
	
	
	public function grabaModificacion() {
		$retorno = false;
		$perfiles = new PerfilesWebUserBSN( $this->perfileswebuser );
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
		$perfiles = new PerfilesWebUserBSN( $this->perfileswebuser );
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