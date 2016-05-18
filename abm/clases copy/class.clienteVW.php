<?php

include_once('clases/class.loginVW.php');
include_once('clases/class.cliente.php');
include_once('clases/class.auxiliaresPGDAO.php');
include_once('clases/class.clienteBSN.php');
include_once ('clases/class.telefonosVW.php');
include_once ('clases/class.domicilioVW.php');

class ClienteVW extends LoginVW {

	private $cliente;
	private $perfwebuser=array();
	private $estwebuser=array();
	private $infowebuser=array();
	private $arrayForm;

	public function __construct($_login=""){
		ClienteVW::creaCliente();
		if ($_login  instanceof Cliente){
			ClienteVW::seteaClienteVW($_login);
		}
		if (is_string($_login)){
			if($_login!=""){
				ClienteVW::cargaCliente($_login);
			}
		}
	}


	public function cargaCliente($_login){
		$login=new ClienteBSN($_login);
		$this->seteaClienteVW($login->getObjeto());
	}

	public function getIdCli(){
		return $this->cliente->getId_cli();
	}

	private function creaCliente(){
		$this->cliente = new Cliente();
	}

	public function seteaClienteVW($_login){
		$this->cliente=$_login;
		$login= new ClienteBSN($_login);
		$this->arrayForm=$login->getObjetoView();
	}


	public function getClienteVW(){
		return $this->cliente;
	}


	public function grabaModificacion(){
		$retorno=false;
		$login=new ClienteBSN($this->cliente);
		$login->cargaPrivados();
		$retAWU=$login->actualizaDB();
		return $retAWU;
	}


	public function grabaDatosVW(){
		$retorno=false;
		$this->cliente->setFecha_base(date("d-m-Y"));
		$login=new ClienteBSN($this->cliente);
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
	public function cargaDatosVW($timestamp){

//		$timestamp=date('YmdHis');

		print "<script type='text/javascript' >\n";
		print "function telefonos(id,div){\n";
		print "   window.open('carga_Telefonos.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=950,height=550');\n";
		print "}\n";
		print "function domicilio(id,div){\n";
		print "   window.open('carga_Domicilio.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=950,height=550');\n";
		print "}\n";

		if($this->arrayForm['id_cli']==0 || $this->arrayForm['id_cli']==''){

			$this->arrayForm['id_cli']=$timestamp;
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

			$operacion='n';
			$visibilidad="text";
			
		}else{
			$visibilidad="hidden";
			$operacion='m';
			
		}
		print "</script>";

		print "<form name='f1' method='post' action='carga_cliente.php' ";
		if($this->arrayForm['id_cli']==0){
			print "onsubmit='return valida();'";
		}
		print " onfocus=\"listaTelefonos('C',$timestamp, 'div_tel');\">\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'f1' ,'opcion');
/*
		if($this->arrayForm['id_cli']!=$timestamp){
			$visibilidad="hidden";
			$operacion='m';
		} else {
			$operacion='n';
			$visibilidad="text";
		}
*/
		print "<INPUT type='hidden' name='operacion' id='operacion' value='".$operacion."'>\n";
		print "<INPUT type='hidden' name='id_cli' id='id_cli' value='".$this->arrayForm['id_cli']."'>\n";
		print "	<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Clientes";
// Quitar esta linea cuando se habilite la carga de usuario para el cliente
		print "<input class='campos' type='hidden' name='usuario' id='usuario' value='$timestamp' maxlength='250' size='80'>";
		print "</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		/*
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
			*/

		print "<tr><td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='nombre' id='nombre' value='" . $this->arrayForm ['nombre'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Apellido<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='apellido' id='apellido' value='" . $this->arrayForm ['apellido'] . "' maxlength='250' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>E-Mail<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='email' id='email' value='" . $this->arrayForm ['email'] . "' maxlength='250' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Domicilio</td><td>";
		//		if($this->arrayForm['id_cli']!=0){
		print "<div name='div_dom' id='div_dom'>";
		$domVW = new DomicilioVW();
		$domVW->vistaTablaVW('C',$this->arrayForm['id_cli'],'v');
		print "</div>";
		print "<br /><input class='boton_form' type='button' value='Cargar Domicilio' onclick=\"javascript:domicilio(".$this->arrayForm ['id_cli'].",'div_dom'); \"><br />\n";
		//		}
		print "</td></tr>";

		print "<tr><td class='cd_celda_texto' width='15%'>Telefono</td><td>";
		//		if($this->arrayForm['id_cli']!=0){
		print "<div name='div_tel' id='div_tel'>";
		$telVW = new TelefonosVW();
		$telVW->vistaTablaTelefonos('U',$this->arrayForm['id_cli'],'v');
		print "</div>";
		print "<br /><input class='boton_form' type='button' value='Cargar Telefono' onclick=\"javascript:telefonos(".$this->arrayForm ['id_cli'].",'div_tel'); \"><br />\n";
		//		}
		print "</td></tr>";

		print "<tr><td class='cd_celda_texto' width='15%'>Observaciones<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><textarea rows='6' class='campos_area' name='observacion' id='observacion'>".$this->arrayForm ['observacion']."</textarea></td></tr>\n";

		/*
		 print "				<table>";
		 print "					<tr>";
		 print '						<td align ="center">Codigo de Verificación <img src="'.$_codseg.'" alt="" width="150" height="50" /><br /><br />Ingrese el Codio de Verificación de la Imagen: <input type="text" size="8" maxlength="8" name="code" id="code" value="" /></td>';
		 print "					</tr>";
		 print "             </table>";
		 */


		print "<br>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "<input type='hidden' name='id_cli' id='id_cli' value='" . $this->arrayForm ['id_cli'] . "'>";
		print "<input type='hidden' name='activa' id='activa' value='" . $this->arrayForm ['activa'] . "'>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";

		print "</form>\n";
	}


	public function vistaTablaVW() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_cli.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Clientes</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
//		print "     <td class='cd_lista_titulo'>Usuario</td>\n";
		print "     <td class='cd_lista_titulo'>Nombre y Apellido</td>\n";
		print "     <td class='cd_lista_titulo'>eMail</td>\n";
		//		print "     <td class='cd_lista_titulo'>Telefono</td>\n";
		print "	  </tr>\n";
		$evenBSN = new ClienteBSN();
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
				print "    <a href='javascript:envia(62,\"" . $Even ['id_cli'] . "\");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td  align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(63,\"" . $Even ['id_cli'] . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
//				print "	 <td  class='row" . $fila . "'>" . $Even ['usuario'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['nombre']." ".$Even ['apellido'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['email'] . "</td>\n";
				//				print "	 <td  class='row" . $fila . "'>" . $Even ['telefono'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_cli' id='id_cli' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}

	public function cargaAsignacionCliente(){
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
		$perfilWU = new PerfilesWebUserBSN('',$this->arrayForm['id_cli']);
		$perfil=$perfilWU->coleccionPerfilesUsuario($this->arrayForm['id_cli']);
		$perfilBSN= new PerfilesBSN();
		print "<tr><td class='cd_celda_texto' width='15%'>Perfil<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>";

		$perfilBSN->comboPerfiles($perfil);
		print "</td></tr>\n";
		print "<br>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "<input type='hidden' name='id_cli' id='id_cli' value='" . $this->arrayForm ['id_cli'] . "'>";
		print "<input type='hidden' name='auxperfil' id='auxperfil' value='" . $perfil . "'>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";

		print "</form>\n";
	}

	public function leeDatosVW(){
		$loginBSN=new ClienteBSN();
		$this->cliente=$loginBSN->leeDatosForm($_POST);
	}



}// Fin Clase

?>