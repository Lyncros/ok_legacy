<?php
include_once ('generic_class/class.VW.php');
include_once ("generic_class/class.menu.php");
include_once ("clases/class.caracteristicaempBSN.php");
include_once ("clases/class.caracteristicaemp.php");

include_once ("clases/class.tipo_propBSN.php");

include_once ("inc/funciones.inc");

class CaracteristicaempVW extends VW {
	protected $clase="Caracteristicaemp";
	protected $caracteristicaemp;
	protected $nombreId="Id_carac";
	//	private $arrayForm;
	protected $arrayTipoProp;
	
	public function __construct($_caracteristicaemp = 0) {
//		CaracteristicaempVW::creaCaracteristicaemp ();
		CaracteristicaempVW::creaObjeto();
		if ($_caracteristicaemp instanceof Caracteristicaemp) {
//			CaracteristicaempVW::seteaCaracteristicaemp ( $_caracteristicaemp );
			CaracteristicaempVW::seteaVW( $_caracteristicaemp );
		}
		if (is_numeric ( $_caracteristicaemp )) {
			if ($_caracteristicaemp != 0) {
//				CaracteristicaempVW::cargaCaracteristicaemp ( $_caracteristicaemp );
				CaracteristicaempVW::cargaVW( $_caracteristicaemp );
			}
		}
		CaracteristicaempVW::cargaDefinicionForm();
	}

/*
 
 	public function cargaCaracteristicaemp($_caracteristicaemp) {
		$caracteristicaemp = new CaracteristicaempBSN ( $_caracteristicaemp );
		$this->seteaCaracteristicaemp ( $caracteristicaemp->getObjeto () );
		//caracteristicaemp());
	}

	public function getIdCaracteristicaemp() {
		return $this->caracteristicaemp->getId_carac ();
	}

	protected function creaCaracteristicaemp() {
		$this->caracteristicaemp = new caracteristicaemp ( );
	}

	protected function seteaCaracteristicaemp($_caracteristicaemp) {
		$this->caracteristicaemp = $_caracteristicaemp;
		$caracteristicaemp = new CaracteristicaempBSN ( $_caracteristicaemp );
		$this->arrayForm = $caracteristicaemp->getObjetoView ();
	}
*/
	public function cargaDatosVW() {

		if($this->arrayForm['id_carac']==0 || $this->arrayForm['id_carac']==''){
			$objBSN= new CaracteristicaempBSN();
			$orden=$objBSN->proximaPosicion();

		}else{
			$orden=$this->arrayForm['orden'];
		}

		//		$menu=new Menu();
		//		$menu->dibujaMenu('carga','opcion');
		print "<script type='text/javascript' language='javascript'>\n";
		print "function validaMaximo(){\n";
		print "     if(document.forms.carga.tipo.value=='Numerico'){;\n";
		print "			activaMaximo();\n";
		print "		}else{\n";
		print "			desactivaMaximo();\n";
		print "		}\n";
		print "     if(document.forms.carga.tipo.value=='Lista'){;\n";
		print "			activaLista();\n";
		print "		}else{\n";
		print "			desactivaLista();\n";
		print "		}\n";
		print "}\n";
		print "function activaMaximo(){\n";
		print "     document.getElementById(\"trmax\").style.display='table-row';\n";
		print "}\n";
		print "function desactivaMaximo(){\n";
		print "     document.getElementById(\"trmax\").style.display='none';\n";
		print "}\n";
		print "function activaLista(){\n";
		print "     document.getElementById(\"trlista\").style.display='table-row';\n";
		print "}\n";
		print "function desactivaLista(){\n";
		print "     document.getElementById(\"trlista\").style.display='none';\n";
		print "}\n";
		print "</script>\n";
		print "<form action='carga_caracteristicaemp.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaCaracteristicaemp(this);'>\n";
//		$menu = new Menu ( );
//		$menu->dibujaMenu ( 'carga' );
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Caracteristica de Emprendimientos</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Titulo</td>";
		print "<td width='85%'><input class='campos' type='text' name='titulo' id='titulo' value='" . $this->arrayForm ['titulo'] . "' maxlength='100' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Tipo de ingreso</td>";
		print "<td width='85%'>";
		armaTipoCampo ( $this->arrayForm ['tipo'] );
		print "</td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		print "<tr id='trmax' style='display:";
		if ($this->arrayForm ['tipo'] == 'Numerico') {
			$verMax = 'table-row';
		} else {
			$verMax = 'none';
		}
		print "$verMax' width='90%'><td class='cd_celda_texto' width='15%'>Valor Maximo</td>";
		print "<td width='85%'><input class='campos' type='text' name='maximo' id='maximo' value='";
		if (! is_numeric ( $this->arrayForm ['maximo'] )) {
			print "0";
		} else {
			print $this->arrayForm ['maximo'];
		}
		print "' maxlength='2' size='10'></td></tr>\n";
		print "<tr id='trlista' style='display:";
		if ($this->arrayForm ['tipo'] == 'Lista') {
			$verLis = 'table-row';
		} else {
			$verLis = 'none';
		}
		print "$verLis' width='90%'><td class='cd_celda_texto' width='15%'>Lista de valores</td>";
		print "<td width='85%'><input class='campos' type='text' name='lista' id='lista' value='";
		print $this->arrayForm ['lista'];
		print "' maxlength='300' size='80'></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Incluir Comentario</td>";
		print "<td width='85%'>";
		armaComentarioCampo ( $this->arrayForm ['comentario'] );
		print "</td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Orden</td>";
		print "<td width='85%'><input class='campos' type='text' name='orden' id='orden' value='" . $orden. "' maxlength='2' size='10'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		//		print "<td width='85%'><input class='campos' type='text' name='orden' id='orden' value='" . $this->arrayForm ['orden'] . "' maxlength='2' size='10'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		print "<input type='hidden' name='id_carac' id='id_carac' value='" . $this->arrayForm ['id_carac'] . "'>\n";
		print "<br>";
		print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}

	/** * Lee desde un formulario los datos cargados para el caracteristicaemp. * Los registra en un objeto del tipo caracteristicaemp caracteristicaemp de esta clase * */
	public function leeDatosVW() {
		$caracteristicaemp = new CaracteristicaempBSN ( );
		$this->caracteristicaemp = $caracteristicaemp->leeDatosForm ( $_POST );
		//		print_r ( $this->caracteristicaemp );
		//		print_r ( $_POST );
		$tipoProp = new Tipo_propBSN ( );
		$this->arrayTipoProp = $tipoProp->leechkTipoProp ( $_POST );
	}

	/**    OK * Muestra una tabla con los datos de los caracteristicaemps y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaVW() {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_carac.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<div class='pg_titulo'>Listado de Caracteristicas de Emprendimientos</div>\n";

		print "<form name='lista' method='POST' action='respondeMenu.php'>\n";

//		$menu = new Menu ( );
//		$menu->dibujaMenu ( 'lista', 'opcion' );

		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='4'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Titulo</td>\n";
		print "     <td class='cd_lista_titulo'>Tipo de Ingreso</td>\n";
		print "     <td class='cd_lista_titulo'>Valor Maximo</td>\n";
		print "     <td class='cd_lista_titulo'>Comentario</td>\n";
		print "     <td class='cd_lista_titulo'>Lista de Valores</td>\n";
		print "     <td class='cd_lista_titulo'>Orden</td>\n";
		print "	  </tr>\n";
		$evenBSN = new CaracteristicaempBSN();
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
				print "	 <td align='center' width='25' class=\"row" . $fila . "\">";
				print "    <a href='javascript:envia(\"lista\",162," . $Even ['id_carac'] . ");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td align='center' width='25' class=\"row" . $fila . "\">";
				print "    <a href='javascript:envia(\"lista\",163," . $Even ['id_carac'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td align='center' width='25' class=\"row" . $fila . "\">";
				print "    <a href='javascript:envia(\"lista\",164," . $Even ['id_carac'] . ");' border='0'>";
				print "       <img src='images/up.png' alt='Subir' border=0></a></td>";
				print "	 <td align='center' width='25' class=\"row" . $fila . "\">";
				print "    <a href='javascript:envia(\"lista\",165," . $Even ['id_carac'] . ");' border=0>";
				print "       <img src='images/down.png' alt='Bajar' border=0></a>";
				print "  </td>\n";
				print "	 <td class=\"row" . $fila . "\">" . $Even ['titulo'] . "</td>\n";
				print "	 <td class=\"row" . $fila . "\">" . $Even ['tipo'] . "</td>\n";
				print "	 <td class=\"row" . $fila . "\">" . $Even ['maximo'] . "</td>\n";
				print "	 <td class=\"row" . $fila . "\">" . $Even ['comentario'] . "</td>\n";
				print "	 <td class=\"row" . $fila . "\">" . $Even ['lista'] . "</td>\n";
				print "	 <td class=\"row" . $fila . "\">" . $Even ['orden'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_carac' id='id_carac' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}

/*	
	public function grabaModificacion() {
		$retorno = false;
		$caracteristicaemp = new CaracteristicaempBSN ( $this->caracteristicaemp );
		$retUPre = $caracteristicaemp->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
			//			$this->grabaDatosVW_Tipoprop ();
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}
	*/
	public function grabaDatosVW() {
		$retorno = false;
		$caracteristicaemp = new CaracteristicaempBSN ( $this->caracteristicaemp );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//
		//$existente = $caracteristicaemp->controlDuplicado ( $this->caracteristicaemp->getCaracteristicaemp () );
		//		if($existente){//			echo "Ya existe un caracteristicaemp con ese Titulo";
		//		} else {
		$retIPre=$caracteristicaemp->insertaDB();
		//			die();
		if ($retIPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
			$this->caracteristicaemp->setId_carac ( $caracteristicaemp->buscaID () );
			//			$this->grabaDatosVW_Tipoprop ();
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