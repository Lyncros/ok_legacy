<?php

include_once('clases/class.loginVW.php');
include_once('clases/class.loginwebuser.php');
include_once('clases/class.auxiliaresPGDAO.php');
include_once('clases/class.loginWebUserBSN.php');
include_once('clases/class.perfilesWebUserPGDAO.php');
include_once('clases/class.perfilesBSN.php');
include_once ('clases/class.telefonosVW.php');

class LoginWebUserVW extends LoginVW {

	private $loginwebuser;
	private $perfwebuser=array();
	private $estwebuser=array();
	private $infowebuser=array();
	private $arrayForm;

	public function __construct($_login=""){
		LoginWebUserVW::creaLoginWebUser();
		if ($_login  instanceof LoginWebUser){
			LoginWebUserVW::seteaLoginWebUserVW($_login);
		}
		if (is_string($_login)){
			if($_login!=""){
				LoginWebUserVW::cargaLoginUsuario($_login);
			}
		}
	}


	public function cargaLoginUsuario($_login){
		$login=new LoginWebUserBSN($_login);
		$this->seteaLoginWebUserVW($login->getObjeto());
	}

	public function getIdUser(){
		return $this->loginwebuser->getId_user();
	}

	private function creaLoginWebUser(){
		$this->loginwebuser = new LoginWebUser();
	}

	public function seteaLoginWebUserVW($_login){
		$this->loginwebuser=$_login;
		$login= new LoginWebUserBSN($_login);
		$this->arrayForm=$login->getObjetoView();
	}


	public function getLoginWebUserVW(){
		return $this->loginwebuser;
	}


	public function grabaModificacion(){
		$retorno=false;
		$login=new LoginWebUserBSN($this->loginwebuser);
		$login->cargaPrivados();
		$retAWU=$login->actualizaDB();
		//		$retAnexos=$login->grabaAnexos($this->perfwebuser,$this->estwebuser,$this->infowebuser);
		//		if ($retAnexos && $retAWU){
		//			echo "Se proceso la grabacion en forma correcta<br>";
		//			$retorno=true;
		//		} else {
		//			echo "Fallo la grabación de los datos<br>";
		//		}
		return $retAWU;
	}


	public function grabaLogon(){
		$retorno=false;
		$this->loginwebuser->setFecha_base(date("d-m-Y"));
		$login=new LoginWebUserBSN($this->loginwebuser);
		$existente=$login->controlDuplicado();
		if($existente){
			echo "Ya existe un usuario con esa Identificacion o ese mail";
		} else {
			$retIWU=$login->insertaDB();
			if ($retIWU){
				echo "Se proceso la grabacion en forma correcta<br>";
				$retorno=true;
			} else {
				echo "Fallo la grabación de los datos<br>";
			}
		}
		return $retorno;
	}


	//	public function cargaDatosLogin($_codseg){
	public function cargaDatosLogin(){
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'f1' ,'opcion');

		print "<script type='text/javascript' >\n";
		print "function telefonos(id){\n";
		print "   window.open('carga_Telefonos.php?t=0&tc=U&c='+id, 'ventana', 'menubar=1,resizable=1,width=950,height=550');\n";
		print "}\n";

		if($this->arrayForm['id_user']==0){

			//	print "<script type='text/javascript' src='../../js/md5.js'></script>\n";

			print "function calculaMD5(elem) {\n";
			print "var pw = elem;\n";
			print "var enc = hex_md5(pw);\n";
			print "return (hex_md5(pw));\n";
			print "}\n";

			print "function encripta() {\n";
			//			print "var ep=calculaMD5(document.forms['f1'].elements['clave'].value);\n";
			//			print "var en=calculaMD5(document.forms['f1'].elements['password2'].value);\n";
			//			print "document.forms['f1'].elements['clave'].value = ep;\n";
			//			print "document.forms['f1'].elements['password2'].value = en;\n";

			print "}\n";

			print "function valida() {\n";
			print "var np=document.forms['f1'].elements['clave'].value;\n";
			print "var rp=document.forms['f1'].elements['password2'].value;\n";
			print "if (np == rp){\n";
			print "	encripta();\n";
			print "return (true);\n";
			print "} else {\n";
			print "	alert('Error al reingresar la nueva clave. Por favor reingrese los valores');\n";
			print "return (false);";
			print "}\n";
			print "}\n";


		}
		print "</script>";

		print "<form name='f1' method='post' action='cargadatosLogon.php' ";
		if($this->arrayForm['id_user']==0){
			print "onsubmit='return valida();'";
		}
		print ">\n";

		if($this->arrayForm['id_user']!=0){
			$visibilidad="hidden";
		} else {
			$visibilidad="text";
		}

		print "<INPUT type='hidden' name='id_user' id='id_user' value='".$this->arrayForm['id_user']."'>\n";
		print "	<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Usuarios</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Usuario<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>";
		if($visibilidad=="hidden") {
			print 						$this->arrayForm['usuario'];
		}//else{
		print "<input class='campos' type='$visibilidad' name='usuario' id='usuario' value='" . $this->arrayForm ['usuario'] . "' maxlength='250' size='80'>";
		//		}
		print "</td></tr>\n";
		if($visibilidad<>"hidden") {
			print "<tr><td colspan='2'>";
			print "<table wdth='100%'>";
			print "<tr><td class='cd_celda_texto' width='15%'>Clave<span class='obligatorio'>&nbsp;&bull;</span></td>";
			print "<td width='35%'><input class='campos' type='password' name='clave' id='clave' value='" . $this->arrayForm ['clave'] . "' maxlength='20' size='40'></td>\n";
			print "<td class='cd_celda_texto' width='15%'>Confirma<span class='obligatorio'>&nbsp;&bull;</span></td>";
			print "<td width='35%'><input class='campos' type='password' name='password2' id='password2' value='" . $this->arrayForm ['clave'] . "' maxlength='20' size='40'></td></tr>\n";
			print "</table>";
			print "</td></tr>\n";
		}

		print "<tr><td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='nombre' id='nombre' value='" . $this->arrayForm ['nombre'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Apellido<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='apellido' id='apellido' value='" . $this->arrayForm ['apellido'] . "' maxlength='250' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>E-Mail<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='email' id='email' value='" . $this->arrayForm ['email'] . "' maxlength='250' size='80'></td></tr>\n";

		//print "<tr><td class='cd_celda_texto' width='15%'>Telefono<span class='obligatorio'>&nbsp;&bull;</span></td>";
		//print "<td width='85%'><input class='campos' type='text' name='telefono' id='telefono' value='" . $this->arrayForm ['telefono'] . "' maxlength='250' size='80'></td</tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Telefono</td><td>";
		if($this->arrayForm['id_user']!=0){
			$telVW = new TelefonosVW();
			$telVW->vistaTablaTelefonos('U',$this->arrayForm ['id_user'],'v');
			print "<br /><input class='boton_form' type='button' value='Cargar Telefono' onclick=\"javascript:telefonos(".$this->arrayForm ['id_user']."); \"><br />\n";
		}
		print "</td></tr>";
		/*
		 print "				<table>";
		 print "					<tr>";
		 print '						<td align ="center">Codigo de Verificación <img src="'.$_codseg.'" alt="" width="150" height="50" /><br /><br />Ingrese el Codio de Verificación de la Imagen: <input type="text" size="8" maxlength="8" name="code" id="code" value="" /></td>';
		 print "					</tr>";
		 print "             </table>";
		 */


		print "<br>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "<input type='hidden' name='id_user' id='id_user' value='" . $this->arrayForm ['id_user'] . "'>";
		print "<input type='hidden' name='activa' id='activa' value='" . $this->arrayForm ['activa'] . "'>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";

		print "</form>\n";
	}


	public function vistaTablaUsuarios() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_user.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Usuarios</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='4'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Usuario</td>\n";
		print "     <td class='cd_lista_titulo'>Nombre y Apellido</td>\n";
		print "     <td class='cd_lista_titulo'>eMail</td>\n";
		print "     <td class='cd_lista_titulo'>Telefono</td>\n";
		print "	  </tr>\n";
		$evenBSN = new LoginWebUserBSN();
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
				print "    <a href='javascript:envia(812,\"" . $Even ['id_user'] . "\");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td  align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(813,\"" . $Even ['id_user'] . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				if($Even['activa']==1){
					$opcion=815;
					$icono="desactiva.png";
					$alt="Desactivar";
				}else {
					$opcion=814;
					$icono="activa.png";
					$alt="Activar";
				}
				print "    <a href='javascript:envia($opcion,\"" . $Even ['id_user'] . "\");' border='0'>";
				print "       <img src='images/$icono' alt='$alt' title='$alt' border=0></a></td>";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(816,\"" . $Even ['id_user'] . "\");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td  class='row" . $fila . "'>" . $Even ['usuario'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['nombre']." ".$Even ['apellido'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['email'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['telefono'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_user' id='id_user' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}

	public function cargaAsignacionUsuario(){
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'carga' ,'opcion');

		print "<form name='carga' method='post' action='asignaPerfil.php' ";
		print "	<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Asignacion de Perfil al Usuario</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Usuario<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>";
		print 						$this->arrayForm['usuario'];
		print "<tr><td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>" . $this->arrayForm ['nombre']." " . $this->arrayForm ['apellido']. "</td></tr>\n";
		$perfilWU = new PerfilesWebUserBSN('',$this->arrayForm['id_user']);
		$perfil=$perfilWU->coleccionPerfilesUsuario($this->arrayForm['id_user']);
		$perfilBSN= new PerfilesBSN();
		print "<tr><td class='cd_celda_texto' width='15%'>Perfil<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>";

		$perfilBSN->comboPerfiles($perfil);
		print "</td></tr>\n";
		print "<br>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "<input type='hidden' name='id_user' id='id_user' value='" . $this->arrayForm ['id_user'] . "'>";
		print "<input type='hidden' name='auxperfil' id='auxperfil' value='" . $perfil . "'>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";

		print "</form>\n";
	}



	public function leeDatosLoginView(){
		$this->leeDatosLoginWU();
		//		$this->perfwebuser=$this->leeDatosPerfilesWU();
	}


	protected function leeDatosLoginWU(){
		$loginBSN=new LoginWebUserBSN();
		$this->loginwebuser=$loginBSN->leeDatosForm($_POST);
	}



}// Fin Clase

?>