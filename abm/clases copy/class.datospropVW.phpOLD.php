<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.datosprop.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedadVW.php");
include_once("clases/class.caracteristicaBSN.php");
include_once("clases/class.tipo_caracBSN.php");
include_once("clases/class.auxiliaresPGDAO.php");
include_once("inc/funciones.inc");

class DatospropVW {

	private $datosprop;
	private $arrayForm;

	public function __construct($_datosprop=0) {
		DatospropVW::creaDatosprop();
		if($_datosprop instanceof Datosprop ) {
			DatospropVW::seteaDatosprop($_datosprop);
		}
		if (is_numeric($_datosprop)) {
			if($_datosprop!=0) {
				DatospropVW::cargaDatosprop($_datosprop);
			}
		}
	}


	public function cargaDatosprop($_datosprop) {
		$datosprop=new DatospropBSN($_datosprop);
		$this->seteaDatosprop($datosprop->getObjeto()); //datosprop());
	}

	public function getIdDatosprop() {
		return $this->datosprop->getId_prop_carac();

	}

	protected function creaDatosprop() {
		$this->datosprop=new datosprop();
	}

	protected function seteaDatosprop($_datosprop) {
		$this->datosprop=$_datosprop;
		$datosprop=new DatospropBSN($_datosprop);
		$this->arrayForm=$datosprop->getObjetoView();

	}


	public function cargaDatosDatosprop($id_prop,$pagorig) {

		$caracBSN = new CaracteristicaBSN();
		$carac = new Caracteristica();
		$auxiliar = new AuxiliaresPGDAO();
		$datosProp2 = new Datosprop();
		$propBSN = new PropiedadBSN($id_prop);
		$propiedad = $propBSN->getObjeto();
		$propVW = new PropiedadVW($id_prop);
		$arrayCarac = $auxiliar->coleccionTipopropCarac($propiedad->getId_tipo_prop());
		print "<form action='carga_datosprop.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaDatosprop(this);'>\n";

		$menu=new Menu();
		$menu->dibujaMenu('carga');



		print "<table width='95%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>Carga de Caracteristicas</td></tr>\n";

		$propVW->muestraDomicilio();


		$tipoCarac='';
		foreach ($arrayCarac as $elemCarac) {
			if($tipoCarac != $elemCarac[1]) {
				if($tipoCarac!='') {
					print "</tr></table></td>\n</tr>\n</table></td></tr>\n";
				}
				print "<tr><td align='center' style='padding-bottom:20px;'>";
				print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";
				print "<tr><td class='celda_titu_carac'>".$elemCarac[1]."</td></tr>\n";
				$tipoCarac=$elemCarac[1];
				$cols=$elemCarac[2];
				$col=0;
				$ancho=intval(100/$cols);
				print "<tr><td style='padding-top:5px;'><table cellspacing='2' width='100%'>\n";
			}
			if($col==$cols) {
				print "</tr>\n";
				$col=0;
			}
			if($col==0) {
				print "<tr>\n";
			}
			$col++;
			$caracBSN->cargaById($elemCarac[0]);
			$carac=$caracBSN->getObjeto();

			$datosProp2->setId_prop($id_prop);
			$datosProp2->setId_carac($elemCarac['0']);

			$datosPropBSN = new DatospropBSN();
			$datosPropBSN->seteaBSN($datosProp2);
			$arrayDatos=$datosPropBSN->cargaColeccionForm();
			switch ($carac->getTipo()) {
				case 'CheckBox':
					$this->armaCheckbox($carac->getId_carac(),$carac->getTitulo(),$arrayDatos[0]['contenido'],$carac->getComentario(),$arrayDatos[0]['comentario'],$ancho);
					break;
				case 'Lista':
					$this->armaLista($carac->getId_carac(),$carac->getTitulo(),$carac->getLista(),$arrayDatos[0]['contenido'],$ancho);
					break;
				case 'Numerico':
					$this->armaNumerico($carac->getId_carac(),$carac->getTitulo(),$carac->getMaximo(),$carac->getComentario(),$arrayDatos[0]['contenido'],$arrayDatos[0]['comentario'],$ancho);
					break;
				case 'Texto':
					$this->armaTexto($carac->getId_carac(),$carac->getTitulo(),$arrayDatos[0]['contenido'],$ancho);
					break;
				case 'Texto Largo':
					$this->armaTextoLargo($carac->getId_carac(),$carac->getTitulo(),$arrayDatos[0]['contenido'],$ancho);
					break;
				case 'Web':
					$this->armaWeb($carac->getId_carac(),$carac->getTitulo(),$arrayDatos[0]['contenido'],$ancho);
					break;
			}
		}
		if($pagorig==1) {
			print "<input type='hidden' name='pagorig' id='pagorig' value='".$pagorig."'>\n";
		}
		print "<input type='hidden' name='id_prop' id='id_prop' value='".$id_prop."'>\n";
		print "</tr></table></td>\n</tr></table></td>\n</tr>\n";

		print "<tr><td colspan='3' align='right'><br><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		//		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}



	/****************
	*    Vista datos propiedad
	*
	*/
	public function vistaDatosProp($id_prop) {

		$caracBSN = new CaracteristicaBSN();
		$carac = new Caracteristica();
		$auxiliar = new AuxiliaresPGDAO();
		$datosProp2 = new Datosprop();
		$propBSN = new PropiedadBSN($id_prop);
		$propiedad = $propBSN->getObjeto();
		$propVW = new PropiedadVW($id_prop);
		$arrayCarac = $auxiliar->coleccionTipopropCarac($propiedad->getId_tipo_prop());


		print "<table width='95%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>Caracteristicas</td></tr>\n";

		//        $propVW->muestraDomicilio();


		$tipoCarac='';
		foreach ($arrayCarac as $elemCarac) {
			if($tipoCarac != $elemCarac[1]) {
				if($tipoCarac!='') {
					print "</tr></table></td>\n</tr>\n</table></td></tr>\n";
				}
				print "<tr><td align='center' style='padding-bottom:20px;'>";
				print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";
				print "<tr><td class='celda_titu_carac'>".$elemCarac[1]."</td></tr>\n";
				$tipoCarac=$elemCarac[1];
				$cols=$elemCarac[2];
				$col=0;
				$ancho=intval((100-$cols*15)/$cols);
				print "<tr><td style='padding-top:5px;'><table cellspacing='2' width='100%'>\n";
			}
			if($col==$cols) {
				print "</tr>\n";
				$col=0;
			}
			if($col==0) {
				print "<tr>\n";
			}
			$col++;
			$caracBSN->cargaById($elemCarac[0]);
			$carac=$caracBSN->getObjeto();

			$datosProp2->setId_prop($id_prop);
			$datosProp2->setId_carac($elemCarac['0']);

			$datosPropBSN = new DatospropBSN();
			$datosPropBSN->seteaBSN($datosProp2);
			$arrayDatos=$datosPropBSN->cargaColeccionForm();
			switch ($carac->getTipo()) {
				case 'CheckBox':
					echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>";
					echo  ($arrayDatos[0]['contenido'] == "on") ? "<img src='images/tilde.png' width='14' height='15'>" : "<img src='images/tilde_no.png' width='14' height='15'>";
					echo "</td>";
					break;
				case 'Lista':
					echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . $arrayDatos[0]['contenido'] . "</td>";
					break;
				case 'Numerico':
					echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . number_format($arrayDatos[0]['contenido'],2, '.', ',') . "</td>";
					break;
				case 'Texto':
					echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . $arrayDatos[0]['contenido'] . "</td>";
					break;
				case 'Texto Largo':
					echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . $arrayDatos[0]['contenido'] . "</td>";
					break;
				case 'Web':
					echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . $arrayDatos[0]['contenido'] . "</td>";
					break;
			}
		}
		print "</tr></table></td>\n</tr></table></td>\n</tr>\n";

		print "<tr><td colspan='3' align='right'><br><br /></td></tr>\n</table>\n";
		//		print "</td></tr>\n</table>\n";
	}


/**
 * Muestra los datos de las caracteristicas de una propiedad tomando como base el tipo de vista a mostrar en base al parametro PUBLICAS
 * para PUBLICAS -1:  Todas   0: Privadas  1: Publicas
 *
 * @param int $_id_prop
 * @param int $_publicas
 */
	public function vistaDatosPropSh($id_prop,$_publicas=-1) {
		$iconoExp='images/down.png';    // Si se cambian las imagenes de expandir y contraer, 
		$iconoCont='images/up.png';		//recordar cambiarlas en la carga de la pagina
		$caracBSN = new CaracteristicaBSN();
		$carac = new Caracteristica();
		$auxiliar = new AuxiliaresPGDAO();
		$datosProp2 = new Datosprop();
		$propBSN = new PropiedadBSN($id_prop);
		$propiedad = $propBSN->getObjeto();
		$propVW = new PropiedadVW($id_prop);
		$arrayCarac = $auxiliar->coleccionTipopropCaracPublicas($propiedad->getId_tipo_prop(),$_publicas);
		$flagD=0;
		$cantCat=0;

		print "<table width='95%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>";
		if ($_publicas==1){
			print "Caracteristicas";
		}else {
			print "Datos de Uso Interno";
		}
		print "</td></tr>\n";
		print "<tr><td align='left'>";
			print "<img src='$iconoExp' onclick=\"javascript:ShowAll('dtc_".$_publicas."_','ftc_".$_publicas."_','cantTC$_publicas')\"> Expandir todo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			print "<img src='$iconoCont' onclick=\"javascript:HideAll('dtc_".$_publicas."_','ftc_".$_publicas."_','cantTC$_publicas')\"> Contraer todo";

		print "</td></tr>\n";

		//        $propVW->muestraDomicilio();


		$tipoCarac='';
		//        print_r($arrayCarac);
		foreach ($arrayCarac as $elemCarac) {
			$caracBSN->cargaById($elemCarac[0]);
			$carac=$caracBSN->getObjeto();

			$datosProp2->setId_prop($id_prop);
			$datosProp2->setId_carac($elemCarac['0']);

			$datosPropBSN = new DatospropBSN();
			$datosPropBSN->seteaBSN($datosProp2);
			$arrayDatos=$datosPropBSN->cargaColeccionForm();
//echo $arrayDatos[0]['contenido']."<br>";
			if(trim($arrayDatos[0]['contenido'])!='0' && trim($arrayDatos[0]['contenido'])!='0.00' && trim($arrayDatos[0]['contenido'])!='on' && trim($arrayDatos[0]['contenido'])!='' && trim($arrayDatos[0]['contenido'])!='Sin definir'){
				$flagD=1;
				if($tipoCarac != $elemCarac[1]) {
					if($tipoCarac!='') {
						$cantCat++;
						print "</tr></table></div></td>\n</tr>\n</table></td></tr>\n";
					}
					print "<tr><td align='center' style='padding-bottom:0px;'>";
					print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";
					print "<tr><td class='celda_titu_carac'>";
					
//					print "<img src='images/down.png' id='ftc_".$elemCarac[3]."' onclick=\"javascript:ShowHide('dtc_','ftc_',".$elemCarac[3].")\">";
					print "<img src='$iconoExp' id='ftc_".$_publicas."_".$cantCat."' onclick=\"javascript:ShowHide('dtc_".$_publicas."_','ftc_".$_publicas."_',".$cantCat.")\">";
					print $elemCarac[1]."</td></tr>\n";
					$tipoCarac=$elemCarac[1];
					$cols=$elemCarac[2];
					$col=0;
					$ancho=intval((100-$cols*15)/$cols);
					print "<tr><td style='padding-top:5px;'><div id='dtc_".$_publicas."_".$cantCat."' name='dtc_".$_publicas."_".$cantCat."' style='display:none;'><table cellspacing='2' width='100%'>\n";
				}
				if($col==$cols) {
					print "</tr>\n";
					$col=0;
				}
				if($col==0) {
					print "<tr>\n";
				}
				$col++;
				switch ($carac->getTipo()) {
					case 'CheckBox':
						echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>";
						echo  ($arrayDatos[0]['contenido'] == "on") ? "<img src='images/tilde.png' width='14' height='15'>" : "<img src='images/tilde_no.png' width='14' height='15'>";
						echo "</td>";
						break;
					case 'Lista':
						echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . $arrayDatos[0]['contenido'] . "</td>";
						break;
					case 'Numerico':
						echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . number_format($arrayDatos[0]['contenido'],2, '.', ',') . "</td>";
						break;
					case 'Texto':
						echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . $arrayDatos[0]['contenido'] . "</td>";
						break;
					case 'Texto Largo':
						echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . $arrayDatos[0]['contenido'] . "</td>";
						break;
					case 'Web':
						echo "<td class='cd_celda_texto' width='15%'>" . $carac->getTitulo() . "</td><td width='$ancho%'>" . $arrayDatos[0]['contenido'] . "</td>";
						break;
				}
			}
		}
		print "<input type='hidden' id='cantTC$_publicas' value='$cantCat'>";
		if(sizeof($arrayCarac)<1 || $flagD==0){
			print "<br> No existen datos a mostrar";
		}

		print "</tr></table></td>\n</tr></table></td>\n</tr>\n";

		print "<tr><td colspan='3' align='right'><br><br /></td></tr>\n</table>\n";
		//		print "</td></tr>\n</table>\n";
	}


	/*
	Arma una lista desplegable con los valores pasados en LISTA, dejando marcado como Seleccion el dato pasado en CONTENIDO
	*/
	protected function armaLista($id_carac,$titulo,$lista,$contenido,$ancho) {
		$array=split(';',$lista);
		$campo='l_'.$id_carac;
		$ancho-=20;
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		print "<td width='".$ancho."%'>";
		print "<select name='".$campo."' id='".$campo."' class='campos'>\n";
		for ($pos=0;$pos<sizeof($array);$pos++) {
			print "<option value='".$array[$pos]."'";
			if ($array[$pos]==$contenido) {
				print " SELECTED ";
			}
			print ">".$array[$pos]."</option>\n";
		}
		print "</select>\n";
		print "</td>\n";
	}

	/* Arma una campo de ingreso ( si maximo = 0) o una lista desplegable con valores numericos desde 1 hasta el MAXIMO, dejando Seleccionado el valor indicado en CONTENIDO
	en el caso de estar indicado el ingreso de comentarios, habilita un campo de texto para el mismo.
	*/
	protected function armaNumerico($id_carac,$titulo,$maximo,$comentario,$contenido,$contcoment,$ancho) {
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=20;
		print "<td width='$ancho%'>";
		if($maximo>0) {
			$campo='n_'.$id_carac;
			print "<select name='".$campo."' id='".$campo."' class='campos'>\n";
			for ($pos=1;$pos<=$maximo;$pos++) {
				print "<option value='".$pos."'";
				if ($pos==$contenido) {
					print " SELECTED ";
				}
				print ">".$pos."</option>\n";
			}
			print "</select>\n";
		}else {
			$campo='n_'.$id_carac;
			$this->armaCampo($campo,$contenido);
		}
		if($comentario=='Si') {
			$campo='com_'.$id_carac;
			$this->armaCampo($campo,$contcoment);
		}
		print "</td>\n";

	}

	// Arma un campo de ingreso de tipo Texto
	protected function armaCampo($campo,$contenido) {
		print "<input class='campos' type='text' name='$campo' id='$campo' value='".	$contenido ."' maxlength='200' size='40'>";
	}

	protected function armaCheckbox($id_carac,$titulo,$contenido,$comentario,$contcoment,$ancho) {
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=20;
		print "<td width='$ancho%'>";
		$campo='c_'.$id_carac;
		print "<input type='checkbox' id='".$campo."' name='".$campo."'";
		if ($contenido=='on') {
			print " checked ";
		}
		print ">\n";
		if($comentario=='Si') {
			$campo='com_'.$id_carac;
			$this->armaCampo($campo,$contcoment);
		}
		print "</td>\n";

	}

	protected function armaTexto($id_carac,$titulo,$contenido,$ancho) {
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=20;
		print "<td width='$ancho%'>";
		$campo='t_'.$id_carac;
		$this->armaCampo($campo,$contenido);
		print "</td>\n";
	}

	protected function armaTextoLargo($id_carac,$titulo,$contenido,$ancho) {
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=20;
		print "<td width='$ancho%'>";
		$campo='b_'.$id_carac;
		//		$this->armaCampo($campo,$contenido);
		print "<textarea rows='6' class='campos_area' name='$campo' id='$campo' style=\"width:95%;\">".	$contenido ."</textarea>";
		print "</td>\n";
	}


	protected function armaWeb($id_carac,$titulo,$contenido,$ancho) {
		include_once("fckeditor/fckeditor.php") ;
		$sBasePath = "/fckeditor/" ;
		$campo='w_'.$id_carac;
		$rFCKeditor = new FCKeditor($campo) ;
		$rFCKeditor->BasePath	= $sBasePath ;
		$rFCKeditor->Value		= $contenido ;
		$rFCKeditor->Height = 400;
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=20;
		print "<td width='$ancho%'>";
		$rFCKeditor->Create() ;
		print "</td>\n";
	}




	public function cargaFiltroDatosprop($array,$tipo_prop) {
		$caracBSN = new CaracteristicaBSN();
		$tipocarac = new Tipo_caracBSN();
		$carac = new Caracteristica();
		//		echo "Prop DPVW -> $tipo_prop<br>";
		$arrayCarac=$caracBSN->coleccionCaracteristicasBuscador($tipo_prop);

		$tipoCarac='';
		$cant=sizeof($arrayCarac);
		if($cant < 4 && $cant != 0) {
			$cols=$cant;
		}else {
			$cols=4;
		}
		$col=0;
		$inicio=0;
		$anchocol= floor(100/$cols);
		print "<table width='100%'>";
		foreach ($arrayCarac as $elemCarac) {
			if($col==$cols ) {
				print "</tr>\n";
				$col=0;
			}
			if($col==0) {
				print "<tr>\n";
			}
			$col++;

			print "<td width='$anchocol'>\n";
			if(key_exists($elemCarac['id_carac'],$array)===true) {
				$valor=$array[$elemCarac['id_carac']][1];
			}else {
				$valor='';
			}

			switch ($elemCarac['tipo']) {
				case 'CheckBox':
					$this->armaCheckboxFiltro($elemCarac['id_carac'],$elemCarac['titulo'],$valor,'','',80);
					break;
				case 'Lista':
					$this->armaListaFiltro($elemCarac['id_carac'],$elemCarac['titulo'],$elemCarac['lista'],$valor,80);
					break;
				case 'Numerico':
					$compara=$caracBSN->cargaComparacionCarac($elemCarac['id_carac']);
					$this->armaNumericoFiltro($elemCarac['id_carac'],$elemCarac['titulo'],$elemCarac['maximo'],'',$valor,'',80,$compara);
					break;
				case 'Texto':
					$this->armaTextoFiltro($elemCarac['id_carac'],$elemCarac['titulo'],$valor,80);
					break;
				case 'Texto Largo':
					$this->armaTextoLargoFiltro($elemCarac['id_carac'],$elemCarac['titulo'],$valor,80);
					break;
				case 'Web':
					$this->armaWebFiltro($elemCarac['id_carac'],$elemCarac['titulo'],$valor,80);
					break;
			}
			print "</td>\n";

		}
		print "</tr></table>\n";

	}



	/*
	Arma una lista desplegable con los valores pasados en LISTA, dejando marcado como Seleccion el dato pasado en CONTENIDO
	*/
	protected function armaListaFiltro($id_carac,$titulo,$lista,$contenido,$ancho) {
		$array=split(';',$lista);
		$campo='l_'.$id_carac;
		$ancho-=20;
		print "<table width='100%'><tr>";
		print "<td class='cd_celda_texto'>$titulo</td></tr>";
		print "<tr><td width='".$ancho."%'>";
		print "<select name='".$campo."' id='".$campo."' class='campos'>\n";
		for ($pos=0;$pos<sizeof($array);$pos++) {
			print "<option value='".$array[$pos]."'";
			if ($array[$pos]==$contenido) {
				print " SELECTED ";
			}
			print ">".$array[$pos]."</option>\n";
		}
		print "</select>\n";
		print "</td></tr></table>\n";
	}

	/* Arma una campo de ingreso ( si maximo = 0) o una lista desplegable con valores numericos desde 1 hasta el MAXIMO, dejando Seleccionado el valor indicado en CONTENIDO
	en el caso de estar indicado el ingreso de comentarios, habilita un campo de texto para el mismo.
	*/
	protected function armaNumericoFiltro($id_carac,$titulo,$maximo,$comentario,$contenido,$contcoment,$ancho,$compara) {
		print "<table width='100%'><tr>";
		print "<td class='cd_celda_texto' width='15%'>$titulo ";
		print "</td></tr>";
		$ancho-=20;
		print "<tr><td width='$ancho%'>";
		if($maximo>0) {
			$campo='n_'.$id_carac;
			print "<select name='".$campo."' id='".$campo."' class='campos'>\n";
			for ($pos=1;$pos<=$maximo;$pos++) {
				print "<option value='".$pos."'";
				if ($pos==$contenido) {
					print " SELECTED ";
				}
				print ">".$pos."</option>\n";
			}
			print "</select>\n";
		}else {
			$campo='n_'.$id_carac;
			if($compara=='><') {
				$this->armaCampoEntre($campo,$contenido);
			}else {
				$this->armaCampo($campo,$contenido);
			}
		}
		if($comentario=='Si') {
			$campo='com_'.$id_carac;
			$this->armaCampo($campo,$contcoment);
		}
		print "</td></tr></table>\n";

	}

	protected function armaCampoEntre($campo,$contenido) {
		print "<script type='text/javascript' language='javascript'>\n";
		print "function armaCompara(destino){\n";
		print "     val1=document.getElementById('a1'+destino).value;\n";
		print "    	val2=document.getElementById('a2'+destino).value;\n";
		print "     if(isNaN(val1) || isNaN(val2) || parseInt(val1) > parseInt(val2)){\n";
		print "         alert(\"Debe ingresar valores numericos y el segundo debe ser mayor al primero\");\n";
		print "     	document.getElementById('a1'+destino).focus();\n";
		print "     }else{\n";
		print "     	document.getElementById(destino).value=val1+' and '+val2;\n";
		print "     }\n";
		print "}\n";
		print "</script>\n";
		$cont1 = trim(substr($contenido,0,strpos($contenido,'and')));
		$cont2 = trim(substr($contenido,strpos($contenido,'and')+3));
		print " Desde ";
		print "<input class='campos' type='text' name='a1".$campo."' id='a1".$campo."' value='".$cont1 ."' maxlength='10' size='20'><br>";
		print " Hasta ";
		print "<input class='campos' type='text' name='a2".$campo."' id='a2".$campo."' value='".$cont2 ."' maxlength='10' size='20' onblur='javascript:armaCompara(\"$campo\");'>";
		print "<input class='campos' type='hidden' name='$campo' id='$campo' value='".	$contenido ."' maxlength='10' size='20'>";

	}

	// Arma un campo de ingreso de tipo Texto
	protected function armaCampoFiltro($campo,$contenido) {
		print "<input class='campos' type='text' name='$campo' id='$campo' value='".	$contenido ."' maxlength='200' size='40'>";
	}

	protected function armaCheckboxFiltro($id_carac,$titulo,$contenido,$comentario,$contcoment,$ancho) {
		print "<table width='100%'><tr>";
		print "<td class='cd_celda_texto' width='15%'>$titulo</td></tr>";
		$ancho-=20;
		print "<tr><td width='$ancho%'>";
		$campo='c_'.$id_carac;
		print "<input type='checkbox' id='".$campo."' name='".$campo."'";
		if ($contenido=='on') {
			print " checked ";
		}
		print ">\n";
		if($comentario=='Si') {
			$campo='com_'.$id_carac;
			$this->armaCampo($campo,$contcoment);
		}
		print "</td></tr></table>\n";

	}

	protected function armaTextoFiltro($id_carac,$titulo,$contenido,$ancho) {
		print "<table width='100%'><tr>";
		print "<td class='cd_celda_texto' width='15%'>$titulo</td></tr>";
		$ancho-=20;
		print "<tr><td width='$ancho%'>";
		$campo='t_'.$id_carac;
		$this->armaCampo($campo,$contenido);
		print "</td></tr></table>\n";
	}

	protected function armaTextoLargoFiltro($id_carac,$titulo,$contenido,$ancho) {
		print "<table width='100%'><tr>";
		print "<td class='cd_celda_texto' width='15%'>$titulo</td></tr>";
		$ancho-=20;
		print "<tr><td width='$ancho%'>";
		$campo='b_'.$id_carac;
		print "<textarea cols='40' rows='4' class='campos_area' name='$campo' id='$campo'>".	$contenido ."</textarea>";
		print "</td></tr></table>\n";
	}


	protected function armaWebFiltro($id_carac,$titulo,$contenido,$ancho) {
		include_once("fckeditor/fckeditor.php") ;
		$sBasePath = "/fckeditor/" ;
		$campo='w_'.$id_carac;
		$rFCKeditor = new FCKeditor($campo) ;
		$rFCKeditor->BasePath	= $sBasePath ;
		$rFCKeditor->Value		= $contenido ;
		$rFCKeditor->Height = 400;
		print "<table width='100%'><tr>";
		print "<td class='cd_celda_texto' width='15%'>$titulo</td></tr>";
		$ancho-=20;
		print "<tr><td width='$ancho%'>";
		$rFCKeditor->Create() ;
		print "</td></tr></table>\n";
	}

	/**
     * Lee desde un formulario los datos cargados para el datosprop.
     * Los registra en un objeto del tipo datosprop datosprop de esta clase
     *
     */
	public function leeDatosDatospropVW() {
		$datosaux=new Datosprop();
		$this->datosprop=array();
		$campos = array_keys($_POST);
		foreach ($campos as $datos) {
			$posg=strpos($datos,"_");
			$tipo=substr($datos,0,$posg);
			$id_carac=substr($datos,$posg+1);
			$id_prop=$_POST['id_prop'];
			if($tipo=='c' || $tipo=='l' || $tipo=='n' || $tipo=='t' || $tipo=='w' || $tipo=='b') {
				$this->datosprop[$id_carac]=array('id_prop'=>$id_prop,'contenido'=>$_POST[$datos]);
			}elseif($tipo=='com') {
				$this->datosprop[$id_carac]['comentario']=$_POST[$datos];
			}
		}
	}


	/**
     * Lee desde un formulario los datos cargados para el datosprop.
     * Los registra en un objeto del tipo datosprop de esta clase
     *
     */
	public function leeFiltroDatospropVW() {
		$datosaux=new Datosprop();
		$this->datosprop=array();
		$campos = array_keys($_POST);
		foreach ($campos as $datos) {
			$posg=strpos($datos,"_");
			$tipo=substr($datos,0,$posg);
			$id_carac=substr($datos,$posg+1);
			if($tipo=='c' || $tipo=='l' || $tipo=='n' || $tipo=='t' || $tipo=='w' || $tipo=='b') {
				if($_POST[$datos]!='' && $_POST[$datos]!='Sin definir'){
					$this->datosprop[$id_carac]=array($tipo,$_POST[$datos]);
				}
			}
		}
		return $this->datosprop;
	}

	/**    OK
     * Muestra una tabla con los datos de los datosprops y una barra de herramientas o menu
     * conde se despliegan las opciones ingresables para cada item
     *
     */
	public function vistaTablaDatosprop($id_prop) {
		$caracBSN = new CaracteristicaBSN();
		$carac = new Caracteristica();
		$auxiliar = new AuxiliaresPGDAO();
		$datosProp2 = new Datosprop();
		$propBSN = new PropiedadBSN($id_prop);
		$propiedad = $propBSN->getObjeto();
		$propVW = new PropiedadVW($id_prop);
		$arrayCarac = $auxiliar->coleccionTipopropCarac($propiedad->getId_tipo_prop());
		//		print "<form action='carga_datosprop.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaDatosprop(this);'>\n";

		//		$menu=new Menu();
		//		$menu->dibujaMenu('carga');



		print "<table width='95%' align='center' bgcolor='#FFFFFF'>\n";

		//		print "<tr><td class='cd_celda_titulo'>Carga de Caracteristicas</td></tr>\n";

		$propVW->muestraDomicilio();


		$tipoCarac='';
		foreach ($arrayCarac as $elemCarac) {
			if($tipoCarac != $elemCarac[1]) {
				if($tipoCarac!='') {
					print "</tr></table></td>\n</tr>\n</table></td></tr>\n";
				}
				print "<tr><td align='center' style='padding-bottom:20px;'>";
				print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";
				print "<tr><td class='celda_titu_carac'>".$elemCarac[1]."</td></tr>\n";
				$tipoCarac=$elemCarac[1];
				$cols=$elemCarac[2];
				$col=0;
				$ancho=intval(100/$cols);
				print "<tr><td style='padding-top:5px;'><table cellspacing='2' width='100%'>\n";
			}
			if($col==$cols) {
				print "</tr>\n";
				$col=0;
			}
			if($col==0) {
				print "<tr>\n";
			}
			$col++;
			$caracBSN->cargaById($elemCarac[0]);
			$carac=$caracBSN->getObjeto();

			$datosProp2->setId_prop($id_prop);
			$datosProp2->setId_carac($elemCarac['0']);

			$datosPropBSN = new DatospropBSN();
			$datosPropBSN->seteaBSN($datosProp2);
			$arrayDatos=$datosPropBSN->cargaColeccionForm();

			print "<tr>\n";
			print "	 <td class='row".$fila."'>".$carac->getTitulo()."</td>\n";
			print "	 <td class='row".$fila."'>".$this->muestraDato($carac->getTipo(),$arrayDatos[0]['contenido'])."</td>\n";
			print "	 <td class='row".$fila."'>".$arrayDatos[0]['comentario']."</td>\n";
			print "	</tr>\n";
		}
		print "</tr></table></td>\n</tr></table></td>\n</tr>\n";

		print "<tr><td colspan='3' align='right'><br><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
	}


	protected function muestraDato($tipo,$contenido) {
		if ($var=="CheckBox") {
			if($contenido=='on') {
				$imagen='check.png';
			}else {
				$imagen='uncheck.png';
			}
			print "<img src='images/$imagen' border=0>\n";
		}else {
			print "$contenido\n";
		}
	}

	public function grabaModificacion() {
		$retorno=false;
		$datosprop=new DatospropBSN($this->datosprop);
		$retUPre=$datosprop->actualizaDB();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaDatosprop($id_prop) {
		$retorno=false;
		$datosprop=new DatospropBSN();
		$retIPre=$datosprop->grabaCaracteristica_Prop($id_prop,$this->datosprop);
		if ($retIPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		//		} // Fin control de Duplicados
		return $retorno;
	}



} // fin clase


?>