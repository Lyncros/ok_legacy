<?php

include_once("generic_class/class.menu.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedad.php");
include_once("clases/class.tipo_propBSN.php");
include_once ("clases/class.zonaBSN.php");
include_once ("clases/class.emprendimientoBSN.php");
include_once ("clases/class.localidadBSN.php");
include_once("inc/funciones.inc");
include_once("inc/funciones_gmap.inc");
include_once("clases/class.sucursal.php");
include_once("clases/class.datospropVW.php");
include_once('generic_class/class.upload.php');
include_once("generic_class/class.cargaConfiguracion.php");
include_once("clases/class.perfilesBSN.php");
include_once("clases/class.grupo_tipoprop.php");

class PropiedadVW {

	private $propiedad;
	private $arrayForm;
	private $path;
	private $pathC;
	private $anchoG;
	private $anchoC;

	public function __construct($_propiedad=0) {
		PropiedadVW::creaPropiedad();
		if ($_propiedad instanceof Propiedad) {
			PropiedadVW::seteaPropiedad($_propiedad);
		}
		if (is_numeric($_propiedad)) {
			if ($_propiedad != 0) {
				PropiedadVW::cargaPropiedad($_propiedad);
			}
		}
		$conf = CargaConfiguracion::getInstance();
		$this->path = $conf->leeParametro('path_fotos');
		$this->pathC = $conf->leeParametro('path_fotos_chicas');
		$this->anchoC = $conf->leeParametro('ancho_foto_chica');
		$this->anchoG = $conf->leeParametro('ancho_foto_grande');
	}

	public function cargaPropiedad($_propiedad) {
		$propiedad = new PropiedadBSN($_propiedad);
		$this->seteaPropiedad($propiedad->getObjeto()); //propiedad());
	}

	public function getIdPropiedad() {
		return $this->propiedad->getId_prop();
	}
	
	public function getIdTipoProp(){
		return $this->propiedad->getId_tipo_prop();
	}

	protected function creaPropiedad() {
		$this->propiedad = new propiedad();
	}

	protected function seteaPropiedad($_propiedad) {
		$this->propiedad = $_propiedad;
		$propiedad = new PropiedadBSN($_propiedad);
		$this->arrayForm = $propiedad->getObjetoView();
	}

	public function cargaDatosPropiedad($id_cli=0) {
		$zonaBSN = new ZonaBSN ();
		$locaBSN = new LocalidadBSN();
		$tipo_propBSN = new Tipo_propBSN();
		$sucursal = new Sucursal();
		$perf = new PerfilesBSN();

		$perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
		$perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

		$gtp = new Grupo_tipoprop();
		if ($perfGpo == 'SUPERUSER' || $perfGpo == 'LECTURA' || $perfGpo == 'admin' || $perfGpo == 'GRAL' ) {
			$listaTPG = 0;
		} else {
			$listaTPG = $gtp->listaTipopropGrupo($perfGpo);
		}

		if ($this->arrayForm['id_cliente'] == 0 || $this->arrayForm['id_cliente'] == '') {
			$this->arrayForm['id_cliente'] = $id_cli;
		}

		print "<script type='text/javascript' language='javascript'>\n";
		print "function validaIntermediacion(){\n";
		print "     if(document.forms.carga.intermediacion.value=='Exclusiva'){;\n";
		print "			desactiva('trint');\n";
		print "		}else{\n";
		print "			activa('trint');\n";
		print "		}\n";
		print "}\n";
		print "function validaOperacion(){\n";
		print "     if(document.forms.carga.operacion.value=='Tasacion'){;\n";
		print "			activa('troper');\n";
		print "		}else{\n";
		print "			desactiva('troper');\n";
		print "		}\n";
		print "}\n";
		print "function activa(campo){\n";
		print "     document.getElementById(campo).style.display='table-row';\n";
		print "}\n";
		print "function desactiva(campo){\n";
		print "     document.getElementById(campo).style.display='none';\n";
		print "}\n";

		print "</script>\n";

		mapaGmap();

		$menu = new Menu();
		$menu->dibujaMenu('carga');

		print "<form action='carga_propiedad.php' name='carga' id='carga' enctype='multipart/form-data' method='post' onSubmit='javascript: return ValidaPropiedad(this);'>\n";

		print "<table width='100%' align='center' bgcolor='#f1f1f1'>\n";
		if ($this->arrayForm['id_prop'] != 0) {
			$titulo = "Edición de la Propiedades";
		} else {
			$titulo = "Carga de Propiedades";
		}
		print "<tr><td class='pg_titulo'>$titulo</td></tr>\n";

		print "<tr><td align='center'>";


		if((($listaTPG==0 || strpos($listaTPG,$this->arrayForm['id_tipo_prop'])!==false) && ($perfSuc==$this->arrayForm['id_sucursal'] || $perfSuc=='Todas')) || $this->arrayForm['id_prop']=='' || $this->arrayForm['id_prop']==0){

			print "<table width='100%' align='center'>\n";

			print "<tr><td class='cd_celda_texto'>Sucursal<span class='obligatorio'>&nbsp;&bull;</span><br />";
			$sucursal->comboSucursal($this->arrayForm['id_sucursal']);
			print "</td>";

			print "<td class='cd_celda_texto'>Zona<span class='obligatorio'>&nbsp;&bull;</span><br />";
			if ($this->arrayForm['id_zona'] == '') {
				$this->arrayForm['id_zona'] = 0;
			}
			if ($this->arrayForm['id_loca'] == '') {
				$this->arrayForm['id_loca'] = 0;
			}
			$zonaBSN = new ZonaBSN ();
			$zonaBSN->comboZona($this->arrayForm['id_zona'], $this->arrayForm['id_loca'], 2, 'id_zona', 'id_loca', 'id_emp');
			print "</td>\n";

			print "<td class='cd_celda_texto'>Localidad<span class='obligatorio'>&nbsp;&bull;</span><br />";
			print "<div id='localidad'>";
			$loca = new Localidad();
			$loca->setId_loca($this->arrayForm['id_loca']);
			$loca->setId_zona($this->arrayForm['id_zona']);
			$locaBSN = new LocalidadBSN($loca);
			$locaBSN->comboLocalidad($this->arrayForm['id_loca'], $this->arrayForm['id_zona'], 2, 'id_loca', 'campos_btn', 'id_emp');
			print "</div>";
			print "</td>\n";
			print "</tr>\n";

			print "<tr><td class='cd_celda_texto'>Calle<span class='obligatorio'>&nbsp;&bull;</span><br />";
			print "<input class='campos' type='text' name='calle' id='calle' value='" . $this->arrayForm['calle'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'>Nro<br />";
			print "<input class='campos' type='text' name='nro' id='nro' value='" . $this->arrayForm['nro'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'>Piso<br />";
			print "<input class='campos' type='text' name='piso' id='piso' value='" . $this->arrayForm['piso'] . "' maxlength='250' size='80'></td>\n";
			print "</tr>\n";

			print "<tr>\n";
			print "<td class='cd_celda_texto'>Dpto<br />";
			print "<input class='campos' type='text' name='dpto' id='dpto' value='" . $this->arrayForm['dpto'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'>Entre<br />";
			print "<input class='campos' type='text' name='entre1' id='entre1' value='" . $this->arrayForm['entre1'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'> y <br />";
			print "<input class='campos' type='text' name='entre2' id='entre2' value='" . $this->arrayForm['entre2'] . "' maxlength='250' size='80'></td>\n";
			print "</tr>\n";

			print "<tr><td colspan='4' class='separador'><hr /></td></tr>";

			print "<tr><td class='cd_celda_texto'>Nombre de Edificio <br />";
			print "<input class='campos' type='text' name='nomedif' id='nomedif' value='" . $this->arrayForm['nomedif'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'>Tipo de Propiedad<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
			$tipo_propBSN->comboTipoProp($this->arrayForm['id_tipo_prop'], 2, 'id_tipo_prop', 'campos_btn', 'subtipo', $this->arrayForm['subtipo_prop']);
			print "</td>\n";
			print "<td class='cd_celda_texto'>Subtipo Propiedad<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
			print "<div id='subtipo'>";
			$tipo_propBSN->comboSubtipoProp($this->arrayForm['subtipo_prop'], $this->arrayForm['id_tipo_prop'], 2);
			print "</div>";
			print "</td>\n";
			print "</tr>\n";

			print "<tr>\n";
			print "<td class='cd_celda_texto'>Emprendimiento<span class='obligatorio'>&nbsp;&bull;</span><br />";
			print "<div id='emprendimiento'>";
			$empre = new Emprendimiento();
			$empre->setId_loca($this->arrayForm['id_loca']);
			$empre->setId_zona($this->arrayForm['id_zona']);
			$empBSN = new EmprendimientoBSN($empre);
			$empBSN->comboEmprendimiento($this->arrayForm['id_emp'], 3, $this->arrayForm['id_zona'], $this->arrayForm['id_loca']);
			print "</div>";
			print "</td>\n";
			print "<td class='cd_celda_texto'colspan='2'> Descripcion <br />";
			print "<input class='campos' type='text' name='descripcion' id='descripcion' value='" . $this->arrayForm['descripcion'] . "' maxlength='250' size='80'></td>\n";
			print "</tr>\n";

			if ($this->arrayForm['id_prop'] == 0) {
				print "<tr><td class='cd_celda_texto' colspan='2'>Operacion<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
				armaTipoOperacion($this->arrayForm['operacion'], 2);
				print "</td>\n";
				print "<td class='cd_celda_texto'>Compartir<br />\n";
				print "<input type='checkbox' name='compartir' id='compartir' ";
				if ($this->arrayForm['compartir'] == 1) {
					print "checked>";
				} else {
					print ">";
				}
				print "</td>\n";
				print "</tr>\n";


				print "<tr id='troper' style='display:";
				if ($this->arrayForm['operacion'] == 'Tasacion') {
					$verMax = 'table-row';
				} else {
					$verMax = 'none';
				}
				print "$verMax' width='100%'>";
				print "<td class='cd_celda_texto' colspan='3'>Comentario de la Tasación<br />\n";
				print "<input class='campos' type='text' name='comentario' id='comentario' value='" . $this->arrayForm['comentario'] . "' maxlength='250' size='80'></td>\n";
				print "</tr>\n";
			} else {
				print "<tr><td class='cd_celda_texto' colspan='2'>Operacion<span class='obligatorio'>&nbsp;&bull;</span><br /><span class='campos'>" . $this->arrayForm['operacion'] . "</span>\n";

				print "<input type='hidden' name='operacion' id='operacion' value='" . $this->arrayForm['operacion'] . "'>\n";
				print "<input type='hidden' name='comentario' id='comentario' value='" . $this->arrayForm['comentario'] . "'>\n";
			}



			if ($this->arrayForm['id_sucursal'] == "NOR") {
				print "<tr><td class='cd_celda_texto'>ID Parcela<br />";
				print "<input class='campos' type='text' name='id_parcela' id='id_parcela' value='" . $this->arrayForm['id_parcela'] . "' maxlength='250' size='80'></td>\n";
				print "<td class='cd_celda_texto'>ID Ccomercial <br />";
				print "<input class='campos' type='text' name='id_comercial' id='id_comercial' value='" . $this->arrayForm['id_comercial'] . "' maxlength='250' size='80'></td>\n";
				print "<td></td>\n";
				print "</tr>\n";
			}

			print "<tr>\n";
			print "<td class='cd_celda_texto'>Intermediacion<br />\n";
			armaIntermediacion($this->arrayForm['intermediacion']);
			print "</td>\n";

			print "<td id='trint' style='display:";
			if ($this->arrayForm['intermediacion'] != 'Exclusiva' && $this->arrayForm['intermediacion'] != '') {
				$verMax = 'table-column';
			} else {
				$verMax = 'none';
			}
			print "$verMax;' class='cd_celda_texto' colspan='2'>Inmobiliaria<br />";
			armaInmo($this->arrayForm['id_inmo']);
			print "</td>\n";

			print "<td class='cd_celda_texto'>Publica Precio en WEB<br />\n";
			print "<input type='checkbox' name='publicaprecio' id='publicaprecio' ";
			if ($this->arrayForm['publicaprecio'] == 1) {
				print "checked>";
			} else {
				print ">";
			}
			print "</td>\n";

			print "</tr>\n";

			print "<tr><td colspan='4' class='separador'><hr /></td></tr>";

			print "<tr><td class='cd_celda_texto'>Plano 1<br />";
			print "<input type='hidden' name='plano1' id='plano1' value='".$this->arrayForm['plano1']."'>".$this->arrayForm['plano1']." <input type='checkbox' name='bplano1' id='bplano1' > Marque la casilla para eliminar el Plano";
			print "<input class='campos' type='file' name='hplano1' id='hplano1' maxlength='250' size='28' ></td>\n";
			print "<td class='cd_celda_texto'>Plano 2<br />";
			print "<input type='hidden' name='plano2' id='plano2' value='".$this->arrayForm['plano2']."'>".$this->arrayForm['plano2']." <input type='checkbox' name='bplano2' id='bplano2' > Marque la casilla para eliminar el Plano";
			print "<input class='campos' type='file' name='hplano2' id='hplano2' maxlength='250' size='28' ></td>\n";
			print "<td class='cd_celda_texto'>Plano3<br />";
			print "<input type='hidden' name='plano3' id='plano3' value='".$this->arrayForm['plano2']."'>".$this->arrayForm['plano3']." <input type='checkbox' name='bplano3' id='bplano3' > Marque la casilla para eliminar el Plano";
			print "<input class='campos' type='file' name='hplano3' id='hplano3' maxlength='250' size='28'></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class='cd_celda_texto' colspan='2'>Video<br />";
			print "<input class='campos' type='text' name='video' id='video' value='" . $this->arrayForm['video'] . "' maxlength='250' size='80'></td>\n";

			print "<td class='cd_celda_texto'><br /><input type='button' class='campos_btn' value=\"Muestra mapa de ubicacion\" id='ver' name='ver' onclick='javascript: popupMapa(\"p\");'></td>\n";
			print "</tr>\n";

			print "<input class='campos' type='hidden' name='goglat' id='goglat' value='";
			if ($this->arrayForm['goglat'] == "") {
				echo "0";
			} else {
				echo $this->arrayForm['goglat'];
			}
			print "' maxlength='250' size='80'>\n";
			print "<input class='campos' type='hidden' name='goglong' id='goglong' value='";
			if ($this->arrayForm['goglong'] == "") {
				echo "0";
			} else {
				echo $this->arrayForm['goglong'];
			}
			print "' maxlength='250' size='80'>\n";

			print "<br>";
			print "<tr><td colspan='4' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		} else {
			echo "No posee permisos para editar esta propiedad.";
		}
		print "<input type='hidden' name='id_prop' id='id_prop' value='" . $this->arrayForm['id_prop'] . "'>\n";
		print "<input type='hidden' name='id_cliente' id='id_cliente' value='" . $this->arrayForm['id_cliente'] . "'>\n";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";

		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}

	public function cargaDatosPropiedadDiv($id_cli=0,$div='propiedad') {
		$zonaBSN = new ZonaBSN ();
		$locaBSN = new LocalidadBSN();
		$tipo_propBSN = new Tipo_propBSN();
		$sucursal = new Sucursal();
		$perf = new PerfilesBSN();

		$perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
		$perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

		$gtp = new Grupo_tipoprop();
		if ($perfGpo == 'SUPERUSER' || $perfGpo == 'LECTURA' || $perfGpo == 'admin' || $perfGpo == 'GRAL' ) {
			$listaTPG = 0;
		} else {
			$listaTPG = $gtp->listaTipopropGrupo($perfGpo);
		}

		if ($this->arrayForm['id_cliente'] == 0 || $this->arrayForm['id_cliente'] == '') {
			$this->arrayForm['id_cliente'] = $id_cli;
		}

		print "<script type='text/javascript' language='javascript'>\n";
		print "function validaIntermediacion(){\n";
		print "     if(document.forms.carga.intermediacion.value=='Exclusiva'){;\n";
		print "			desactiva('trint');\n";
		print "		}else{\n";
		print "			activa('trint');\n";
		print "		}\n";
		print "}\n";
		print "function validaOperacion(){\n";
		print "     if(document.forms.carga.operacion.value=='Tasacion'){;\n";
		print "			activa('troper');\n";
		print "		}else{\n";
		print "			desactiva('troper');\n";
		print "		}\n";
		print "}\n";
		print "function activa(campo){\n";
		print "     document.getElementById(campo).style.display='table-row';\n";
		print "}\n";
		print "function desactiva(campo){\n";
		print "     document.getElementById(campo).style.display='none';\n";
		print "}\n";

		print "</script>\n";

		mapaGmap();

		print "<div id='$div' name='$div'>\n";

		print "<table width='100%' align='center' bgcolor='#f1f1f1'>\n";
		if ($this->arrayForm['id_prop'] != 0) {
			$titulo = "Edición de datos de Propiedades";
		} else {
			$titulo = "Carga de datos de Propiedades";
		}
		print "<tr><td class='pg_titulo'>$titulo</td></tr>\n";

		print "<tr><td align='center'>";


		if((($listaTPG==0 || strpos($listaTPG,$this->arrayForm['id_tipo_prop'])!==false) && ($perfSuc==$this->arrayForm['id_sucursal'] || $perfSuc=='Todas')) || $this->arrayForm['id_prop']=='' || $this->arrayForm['id_prop']==0){

			print "<table width='100%' align='center'>\n";

			print "<tr><td class='cd_celda_texto'>Sucursal<span class='obligatorio'>&nbsp;&bull;</span><br />";
			$sucursal->comboSucursal($this->arrayForm['id_sucursal']);
			print "</td>";

			print "<td class='cd_celda_texto'>Zona<span class='obligatorio'>&nbsp;&bull;</span><br />";
			if ($this->arrayForm['id_zona'] == '') {
				$this->arrayForm['id_zona'] = 0;
			}
			if ($this->arrayForm['id_loca'] == '') {
				$this->arrayForm['id_loca'] = 0;
			}
			$zonaBSN = new ZonaBSN ();
			$zonaBSN->comboZona($this->arrayForm['id_zona'], $this->arrayForm['id_loca'], 2, 'id_zona', 'id_loca', 'id_emp');
			print "</td>\n";

			print "<td class='cd_celda_texto'>Localidad<span class='obligatorio'>&nbsp;&bull;</span><br />";
			print "<div id='localidad'>";
			$loca = new Localidad();
			$loca->setId_loca($this->arrayForm['id_loca']);
			$loca->setId_zona($this->arrayForm['id_zona']);
			$locaBSN = new LocalidadBSN($loca);
			$locaBSN->comboLocalidad($this->arrayForm['id_loca'], $this->arrayForm['id_zona'], 2, 'id_loca', 'campos_btn', 'id_emp');
			print "</div>";
			print "</td>\n";
			print "</tr>\n";

			print "<tr><td class='cd_celda_texto'>Calle<span class='obligatorio'>&nbsp;&bull;</span><br />";
			print "<input class='campos' type='text' name='calle' id='calle' value='" . $this->arrayForm['calle'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'>Nro<br />";
			print "<input class='campos' type='text' name='nro' id='nro' value='" . $this->arrayForm['nro'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'>Piso<br />";
			print "<input class='campos' type='text' name='piso' id='piso' value='" . $this->arrayForm['piso'] . "' maxlength='250' size='80'></td>\n";
			print "</tr>\n";

			print "<tr>\n";
			print "<td class='cd_celda_texto'>Dpto<br />";
			print "<input class='campos' type='text' name='dpto' id='dpto' value='" . $this->arrayForm['dpto'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'>Entre<br />";
			print "<input class='campos' type='text' name='entre1' id='entre1' value='" . $this->arrayForm['entre1'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'> y <br />";
			print "<input class='campos' type='text' name='entre2' id='entre2' value='" . $this->arrayForm['entre2'] . "' maxlength='250' size='80'></td>\n";
			print "</tr>\n";

			print "<tr><td colspan='4' class='separador'><hr /></td></tr>";

			print "<tr><td class='cd_celda_texto'>Nombre de Edificio <br />";
			print "<input class='campos' type='text' name='nomedif' id='nomedif' value='" . $this->arrayForm['nomedif'] . "' maxlength='250' size='80'></td>\n";
			print "<td class='cd_celda_texto'>Tipo de Propiedad<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
			$tipo_propBSN->comboTipoProp($this->arrayForm['id_tipo_prop'], 2, 'id_tipo_prop', 'campos_btn', 'subtipo', $this->arrayForm['subtipo_prop'],'datosprop');
			print "</td>\n";
			print "<td class='cd_celda_texto'>Subtipo Propiedad<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
			print "<div id='subtipo'>";
			$tipo_propBSN->comboSubtipoProp($this->arrayForm['subtipo_prop'], $this->arrayForm['id_tipo_prop'], 2);
			print "</div>";
			print "</td>\n";
			print "</tr>\n";

			print "<tr>\n";
			print "<td class='cd_celda_texto'>Emprendimiento<span class='obligatorio'>&nbsp;&bull;</span><br />";
			print "<div id='emprendimiento'>";
			$empre = new Emprendimiento();
			$empre->setId_loca($this->arrayForm['id_loca']);
			$empre->setId_zona($this->arrayForm['id_zona']);
			$empBSN = new EmprendimientoBSN($empre);
			$empBSN->comboEmprendimiento($this->arrayForm['id_emp'], 3, $this->arrayForm['id_zona'], $this->arrayForm['id_loca']);
			print "</div>";
			print "</td>\n";
			print "<td class='cd_celda_texto'colspan='2'> Descripcion <br />";
			print "<input class='campos' type='text' name='descripcion' id='descripcion' value='" . $this->arrayForm['descripcion'] . "' maxlength='250' size='80'></td>\n";
			print "</tr>\n";

			if ($this->arrayForm['id_prop'] == 0) {
				print "<tr><td class='cd_celda_texto' colspan='2'>Operacion<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
				armaTipoOperacion($this->arrayForm['operacion'], 2);
				print "</td>\n";
				print "<td class='cd_celda_texto'>Compartir<br />\n";
				print "<input type='checkbox' name='compartir' id='compartir' ";
				if ($this->arrayForm['compartir'] == 1) {
					print "checked>";
				} else {
					print ">";
				}
				print "</td>\n";
				print "</tr>\n";


				print "<tr id='troper' style='display:";
				if ($this->arrayForm['operacion'] == 'Tasacion') {
					$verMax = 'table-row';
				} else {
					$verMax = 'none';
				}
				print "$verMax' width='100%'>";
				print "<td class='cd_celda_texto' colspan='3'>Comentario de la Tasación<br />\n";
				print "<input class='campos' type='text' name='comentario' id='comentario' value='" . $this->arrayForm['comentario'] . "' maxlength='250' size='80'></td>\n";
				print "</tr>\n";
			} else {
				print "<tr><td class='cd_celda_texto' colspan='2'>Operacion<span class='obligatorio'>&nbsp;&bull;</span><br /><span class='campos'>" . $this->arrayForm['operacion'] . "</span>\n";

				print "<input type='hidden' name='operacion' id='operacion' value='" . $this->arrayForm['operacion'] . "'>\n";
				print "<input type='hidden' name='comentario' id='comentario' value='" . $this->arrayForm['comentario'] . "'>\n";
			}



			if ($this->arrayForm['id_sucursal'] == "NOR") {
				print "<tr><td class='cd_celda_texto'>ID Parcela<br />";
				print "<input class='campos' type='text' name='id_parcela' id='id_parcela' value='" . $this->arrayForm['id_parcela'] . "' maxlength='250' size='80'></td>\n";
				print "<td class='cd_celda_texto'>ID Ccomercial <br />";
				print "<input class='campos' type='text' name='id_comercial' id='id_comercial' value='" . $this->arrayForm['id_comercial'] . "' maxlength='250' size='80'></td>\n";
				print "<td></td>\n";
				print "</tr>\n";
			}

			print "<tr>\n";
			print "<td class='cd_celda_texto'>Intermediacion<br />\n";
			armaIntermediacion($this->arrayForm['intermediacion']);
			print "</td>\n";

			print "<td id='trint' style='display:";
			if ($this->arrayForm['intermediacion'] != 'Exclusiva' && $this->arrayForm['intermediacion'] != '') {
				$verMax = 'table-column';
			} else {
				$verMax = 'none';
			}
			print "$verMax;' class='cd_celda_texto' colspan='2'>Inmobiliaria<br />";
			armaInmo($this->arrayForm['id_inmo']);
			print "</td>\n";

			print "<td class='cd_celda_texto'>Publica Precio en WEB<br />\n";
			print "<input type='checkbox' name='publicaprecio' id='publicaprecio' ";
			if ($this->arrayForm['publicaprecio'] == 1) {
				print "checked>";
			} else {
				print ">";
			}
			print "</td>\n";

			print "</tr>\n";

			print "<tr><td colspan='4' class='separador'><hr /></td></tr>";

			print "<tr><td class='cd_celda_texto'>Plano 1<br />";
			print "<input type='hidden' name='plano1' id='plano1' value='".$this->arrayForm['plano1']."'>".$this->arrayForm['plano1']." <input type='checkbox' name='bplano1' id='bplano1' > Marque la casilla para eliminar el Plano";
			print "<input class='campos' type='file' name='hplano1' id='hplano1' maxlength='250' size='28' ></td>\n";
			print "<td class='cd_celda_texto'>Plano 2<br />";
			print "<input type='hidden' name='plano2' id='plano2' value='".$this->arrayForm['plano2']."'>".$this->arrayForm['plano2']." <input type='checkbox' name='bplano2' id='bplano2' > Marque la casilla para eliminar el Plano";
			print "<input class='campos' type='file' name='hplano2' id='hplano2' maxlength='250' size='28' ></td>\n";
			print "<td class='cd_celda_texto'>Plano3<br />";
			print "<input type='hidden' name='plano3' id='plano3' value='".$this->arrayForm['plano2']."'>".$this->arrayForm['plano3']." <input type='checkbox' name='bplano3' id='bplano3' > Marque la casilla para eliminar el Plano";
			print "<input class='campos' type='file' name='hplano3' id='hplano3' maxlength='250' size='28'></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class='cd_celda_texto' colspan='2'>Video<br />";
			print "<input class='campos' type='text' name='video' id='video' value='" . $this->arrayForm['video'] . "' maxlength='250' size='80'></td>\n";

			print "<td class='cd_celda_texto'><br /><input type='button' class='campos_btn' value=\"Muestra mapa de ubicacion\" id='ver' name='ver' onclick='javascript: popupMapa(\"p\");'></td>\n";
			print "</tr>\n";

			print "<input class='campos' type='hidden' name='goglat' id='goglat' value='";
			if ($this->arrayForm['goglat'] == "") {
				echo "0";
			} else {
				echo $this->arrayForm['goglat'];
			}
			print "' maxlength='250' size='80'>\n";
			print "<input class='campos' type='hidden' name='goglong' id='goglong' value='";
			if ($this->arrayForm['goglong'] == "") {
				echo "0";
			} else {
				echo $this->arrayForm['goglong'];
			}
			print "' maxlength='250' size='80'>\n";

			print "<br>";
		} else {
			echo "No posee permisos para editar esta propiedad.";
		}
		print "<input type='hidden' name='id_prop' id='id_prop' value='" . $this->arrayForm['id_prop'] . "'>\n";
		print "<input type='hidden' name='id_cliente' id='id_cliente' value='" . $this->arrayForm['id_cliente'] . "'>\n";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";

		print "</td></tr>\n</table>\n";
		print "</div>\n";
	}
	
	
	
	
	
	
	/**
	 * Lee desde un formulario los datos cargados para el propiedad.
	 * Los registra en un objeto del tipo propiedad propiedad de esta clase
	 *
	 */
	public function leeDatosPropiedadVW() {
		$propiedad = new PropiedadBSN();
		if($_POST['publicaprecio']=="on"){
			$_POST['publicaprecio']=1;
		}
		if($_POST['bplano1']=="on"){
			$this->borraPlano($_POST['plano1']);
			$nombre1="";
		}else{
			if ($_FILES['hplano1']['type']=='image/jpeg' || $_FILES['hplano1']['type']=='image/gif' || $_FILES['hplano1']['type']=='image/png'){
				$imgplano1=true;
			}
			if (trim($_FILES['hplano1']['name'])=='' || !isset($_FILES['hplano1']['name']) || !$imgplano1){
				$nombre1=$_POST['plano1'];
			} else {
				$nombre1=$this->UploadPlanos('hplano1');
			}
		}
		$_POST['plano1']=$nombre1;

		if($_POST['bplano2']=="on"){
			$this->borraPlano($_POST['plano2']);
			$nombre2="";
		}else{
			if ($_FILES['hplano2']['type']=='image/jpeg' || $_FILES['hplano2']['type']=='image/gif' || $_FILES['hplano2']['type']=='image/png'){
				$imgplano2=true;
			}
			if (trim($_FILES['hplano2']['name'])=='' || !isset($_FILES['hplano2']['name']) || !$imgplano2){
				$nombre2=$_POST['plano2'];
			} else {
				$nombre2=$this->UploadPlanos('hplano2');
			}
		}
		$_POST['plano2']=$nombre2;

		if($_POST['bplano3']=="on"){
			$this->borraPlano($_POST['plano3']);
			$nombre3="";
		}else{
			if ($_FILES['hplano3']['type']=='image/jpeg' || $_FILES['hplano3']['type']=='image/gif' || $_FILES['hplano3']['type']=='image/png'){
				$imgplano3=true;
			}
			if (trim($_FILES['hplano3']['name'])=='' || !isset($_FILES['hplano3']['name']) || !$imgplano3){
				$nombre3=$_POST['plano3'];
			} else {
				$nombre3=$this->UploadPlanos('hplano3');
			}
		}
		$_POST['plano3']=$nombre3;

		$this->propiedad = $propiedad->leeDatosForm($_POST);

		if ($this->propiedad->getCompartir() == "on") {
			$this->propiedad->setCompartir(1);
		} else {
			$this->propiedad->setCompartir(0);
		}
		if ($this->propiedad->getActiva() == "on") {
			$this->propiedad->setActiva(1);
		} else {
			$this->propiedad->setActiva(0);
		}
		// Si se activa lo de publicacion desde el Menu, quitar el comentario anterior y comentariar la linea siguiente
		//        $this->propiedad->setActiva(0);
	}

	public function UploadPlanos($plano) {
		if ($_FILES[$plano]['type'] == 'image/jpeg' || $_FILES[$plano]['type'] == 'image/gif' || $_FILES[$plano]['type'] == 'image/png') {
			$planoup = new Upload($_FILES[$plano], 'es_ES');
			$nom = $_FILES[$plano]['name'];
			$nombre = 'PL_' . $_POST['id_prop'] . '_' . substr($nom, 0, strlen($nom) - 4);
			if ($planoup->uploaded) {
				$planoup->image_resize = true;
				$planoup->image_ratio_y = true;
				$planoup->file_new_name_body = $nombre;

				$planoup->image_x = $this->anchoG;

				$planoup->Process($this->path);

				// we check if everything went OK
				if ($planoup->processed) {

				} else {
					// one error occured
					echo '<fieldset>';
					echo '  <legend>file not uploaded to the wanted location</legend>';
					echo '  Error: ' . $planoup->error . '';
					echo '</fieldset>';
				}

				$planoup->image_resize = true;
				$planoup->image_ratio_y = true;

				$planoup->image_x = $this->anchoC;
				$planoup->file_new_name_body = $nombre;

				$planoup->Process($this->pathC);

				// we check if everything went OK
				if ($planoup->processed) {

				} else {
					// one error occured
					echo '<fieldset>';
					echo '  <legend>file not uploaded to the wanted location</legend>';
					echo '  Error: ' . $planoup->error . '';
					echo '</fieldset>';
				}
			} else {
				echo '<fieldset>';
				echo '  <legend>file not uploaded on the server</legend>';
				echo '  Error: ' . $planoup->error . '';
				echo '</fieldset>';
			}

			$retorno = $planoup->file_dst_name;
		} else {
			$retorno = 'NULL';
		}
		return $retorno;
	}

	protected function borraPlano($_nombre){
		$nombre=$this->path."/".$_nombre;
		if(file_exists($nombre)){
			unlink($nombre);
		}
		$nombre=$this->pathC."/".$_nombre;
		if(file_exists($nombre)){
			unlink($nombre);
		}
	}


	/*     * ****************
	 *
	 * Vista datos propiedad
	 */

	public function vistaDatosPropiedad($id_cli=0) {
		$zonaBSN = new ZonaBSN ();
		$locaBSN = new LocalidadBSN();
		$tipo_propBSN = new Tipo_propBSN();
		$sucursal = new Sucursal();

		if ($this->arrayForm['id_cliente'] == 0 || $this->arrayForm['id_cliente'] == '') {
			$this->arrayForm['id_cliente'] = $id_cli;
		}

		print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='pg_titulo'>Propiedades</td></tr>\n";

		print "<tr><td align='center'>";
		print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Sucursal</td>";
		print "<td width='35%' colspan='3'>";
		print $sucursal->nombreSucursal($this->arrayForm['id_sucursal']);
		print "</td></tr>";

		print "<tr><td class='cd_celda_texto' width='15%'>Zona</td>";
		print "<td width='35%'>";
		$zonaBSN = new ZonaBSN($this->arrayForm['id_zona']);
		echo $zonaBSN->getObjeto()->getNombre_zona();
		print "</td>\n";

		print "<td class='cd_celda_texto' width='15%'>Localidad</td>";
		print "<td width='35%'>";
		print "<div id='localidad'>";

		$locaBSN = new LocalidadBSN($this->arrayForm['id_loca']);
		echo $locaBSN->getObjeto()->getNombre_loca();
		print "</div>";
		print "</td></tr>\n";

		print "<td class='cd_celda_texto' width='15%'>Emprendimiento</td>";
		print "<td width='35%'>";
		print "<div id='emprendimiento'>";
		$empBSN = new EmprendimientoBSN($this->arrayForm['id_emp']);
		echo $empBSN->getObjeto()->getNombre();

		print "</div>";
		print "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Calle</td>";
		print "<td width='35%'>" . $this->arrayForm['calle'] . "</td>\n";

		print "<td class='cd_celda_texto' width='15%'>Nro</td>";
		print "<td width='35%'>" . $this->arrayForm['nro'] . "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Piso</td>";
		print "<td width='35%'>" . $this->arrayForm['piso'] . "</td>\n";

		print "<td class='cd_celda_texto' width='15%'>Dpto</td>";
		print "<td width='35%'>" . $this->arrayForm['dpto'] . "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Entre</td>";
		print "<td width='35%'>" . $this->arrayForm['entre1'] . "</td>\n";

		print "<td class='cd_celda_texto' width='15%'> y </td>";
		print "<td width='35%'>" . $this->arrayForm['entre2'] . "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Nombre de Edificio </td>";
		print "<td width='90%' colspan='3'>" . $this->arrayForm['nomedif'] . "</td></tr>\n";

		if ($this->arrayForm['id_sucursal'] == "NOR") {
			print "<tr><td class='cd_celda_texto' width='15%'>ID Parcela</td>";
			print "<td width='35%'>" . $this->arrayForm['id_parcela'] . "</td>\n";
			print "<td class='cd_celda_texto' width='15%'>ID Ccomercial </td>";
			print "<td width='35%'>" . $this->arrayForm['id_comercial'] . "</td></tr>\n";
		}
		print "<tr><td class='cd_celda_texto' width='15%'> Descripcion </td>";
		print "<td width='90%' colspan='3'>" . $this->arrayForm['descripcion'] . "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Tipo de Prop.</td>\n";
		print "<td width='35%'>";
		$tipo_propBSN = new Tipo_propBSN($this->arrayForm['id_tipo_prop']);
		echo $tipo_propBSN->getObjeto()->getTipo_prop() . "</td>\n";

		print "<td class='cd_celda_texto' width='15%'>Subtipo Prop.</td>\n";
		print "<td width='35%'>";
		print "<div id='subtipo'>";
		echo $this->arrayForm['subtipo_prop'];
		print "</div>";
		print "</td></tr>\n";

		print "<td class='cd_celda_texto' width='15%'>Intermediacion</td>\n";
		print "<td width='85%' colspan='3'>" . $this->arrayForm['intermediacion'];
		print "</td>\n";
		print "</tr>\n";

		print "<tr id='trint' style='display:";
		if ($this->arrayForm['intermediacion'] != 'Exclusiva' && $this->arrayForm['intermediacion'] != '') {
			$verMax = 'table-row';
		} else {
			$verMax = 'none';
		}
		print "$verMax width='100%'><td class='cd_celda_texto' width='15%'>Inmobiliaria</td>";
		print "<td width='85%' colspan='3'>";
		armaInmo($this->arrayForm['id_inmo']);
		print "</td></tr>";

		if ($this->arrayForm['id_prop'] == 0) {
			print "<tr><td class='cd_celda_texto' width='15%'>Operacion</td>\n";
			print "<td width='85%' colspan='3'>" . $this->arrayForm['operacion'];
			print "</td></tr>\n";

			print "<tr id='troper' style='display:";
			if ($this->arrayForm['operacion'] == 'Tasacion') {
				$verMax = 'table-row';
			} else {
				$verMax = 'none';
			}
			print "$verMax' width='100%'>";
			print "<td class='cd_celda_texto' width='15%'>Comentario</td>\n";
			print "<td width='85%' colspan='3'>" . $this->arrayForm['comentario'] . "</td></tr>\n";
		}
		print "<br>";
		print "     </table>\n";
		print "</td></tr>\n</table>\n";
	}

	//-----------------------------------------
	//
	// Muestra Ficha de la Propiedad
	//
	//------------------------------------------

	public function vistaDatosPropiedadBuscador($id_cli=0) {
		$tipo_propBSN = new Tipo_propBSN();
		$sucursal = new Sucursal();

		if ($this->arrayForm['id_cliente'] == 0 || $this->arrayForm['id_cliente'] == '') {
			$this->arrayForm['id_cliente'] = $id_cli;
		}
		$zonaBSN = new ZonaBSN($this->arrayForm['id_zona']);
		$locaBSN = new LocalidadBSN($this->arrayForm['id_loca']);
		ob_start();
		print "<script language=\"javascript\" src=\"Scripts/AC_RunActiveContent.js\" type=\"text/javascript\"></script>\n";
		print "<table width=\"945\" height=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
		print "    <tr>\n";
		print "        <td valign=\"top\"><table width=\"100%\" height=\"426\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                <tr>\n";
		print "                    <td width=\"470\" style=\"padding-right:1px;\" valign=\"top\"><table width=\"470\" height=\"426\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                            <tr>\n";
		print "                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_tl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                <td class=\"tabla_blco\" width=\"460\"></td>\n";
		print "                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_tr.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                            </tr>\n";
		print "                            <tr>\n";
		print "                                <td colspan=\"3\" class=\"tabla_blco\"><table width=\"100%\" height=\"426\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                        <tr>\n";
		print "                                            <td height=\"63\" style=\"font-size:12pt; color:#00491B; font-weight:bold; padding-left:15px;\">" . $zonaBSN->getObjeto()->getNombre_zona() . "</td>\n";
		print "                                        </tr>\n";
		print "                                        <tr>\n";
		print "                                            <td height=\"363\" valign=\"top\"><script language=\"javascript\">\n";
		print "                                                if (AC_FL_RunContent == 0) {\n";
		print "                                                    alert(\"This page requires AC_RunActiveContent.js.\");\n";
		print "                                                } else {\n";
		print "                                                    AC_FL_RunContent(\n";
		print "                                                    'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',\n";
		print "                                                    'width', '470',\n";
		print "                                                    'height', '363',\n";
		print "                                                    'src', 'images/galeria_ficha_prop',\n";
		print "                                                    'quality', 'high',\n";
		print "                                                    'pluginspage', 'http://www.macromedia.com/go/getflashplayer',\n";
		print "                                                    'align', 'top',\n";
		print "                                                    'play', 'true',\n";
		print "                                                    'loop', 'true',\n";
		print "                                                    'scale', 'showall',\n";
		print "                                                    'wmode', 'transparent',\n";
		print "                                                    'devicefont', 'false',\n";
		print "                                                    'id', 'lupa',\n";
		print "                                                    'bgcolor', '#ffffff',\n";
		print "                                                    'name', 'lupa',\n";
		print "                                                    'menu', 'true',\n";
		print "                                                    'allowFullScreen', 'false',\n";
		print "                                                    'allowScriptAccess','sameDomain',\n";
		print "                                                    'movie', 'images/galeria_ficha_prop',\n";
		print "                                                    'FlashVars', 'id_prop=" . $this->arrayForm['id_prop'] . "',\n";
		print "                                                    'salign', 'tl'\n";
		print "                                                ); //end AC code\n";
		print "                                                }\n";
		print "                                                </script>\n";
		print "                                                <noscript>\n";
		print "                                                    <object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553530000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0\" width=\"470\" height=\"363\" id=\"lupa\" align=\"top\">\n";
		print "                                                        <param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
		print "                                                        <param name=\"allowFullScreen\" value=\"false\" />\n";
		print "                                                        <param name=\"movie\" value=\"images/galeria_ficha_prop.swf?id_prop=" . $this->arrayForm['id_prop'] . "\" />\n";
		print "                                                        <param name=\"quality\" value=\"high\" />\n";
		print "                                                        <param name=\"wmode\" value=\"transparent\" />\n";
		print "                                                        <param name=\"bgcolor\" value=\"#ffffff\" />\n";
		print "                                                        <embed src=\"images/galeria_ficha_prop.swf\" width=\"470\" height=\"363\" align=\"top\" quality=\"high\" bgcolor=\"#ffffff\" name=\"lupa\" allowScriptAccess=\"sameDomain\" allowFullScreen=\"false\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" movie=\"images/galeria_ficha_prop.swf?id_prop=" . $this->arrayForm['id_prop'] . "\" wmode=\"transparent\" />\n";
		print "                                                    </object>\n";
		print "                                                </noscript></td>\n";
		print "                                        </tr>\n";
		print "                                    </table></td>\n";
		print "                            </tr>\n";
		print "                            <tr>\n";
		print "                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_bl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                <td class=\"tabla_blco\" width=\"460\"></td>\n";
		print "                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_br.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                            </tr>\n";
		print "                        </table></td>\n";
		print "                    <td style=\"padding-left:1px;\" valign=\"top\"><table width=\"100%\" height=\"426\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                            <tr>\n";
		print "                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_tl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                <td class=\"tabla_blco\"></td>\n";
		print "                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_tr.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                            </tr>\n";
		print "                            <tr>\n";
		print "                                <td class=\"tabla_blco\"></td>\n";
		print "                                <td class=\"tabla_blco\" valign=\"top\" align=\"center\"><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                        <tr>\n";
		print "                                            <td height=\"63\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                                    <tr>\n";
		print "                                                        <td style=\"font-size:12pt; color:#00491B; font-weight:bold;\">" . $locaBSN->getObjeto()->getNombre_loca() . " - " . $this->arrayForm['operacion'] . "</td>\n";
		print "                                                        <td style=\"font-size:12pt; color:#00491B;\" align=\"right\">" . strtoupper($this->arrayForm['id_sucursal']) . str_repeat("0", 5 - strlen(strval($this->getIdPropiedad()))) . $this->getIdPropiedad() . "</td>\n";
		print "                                                    </tr>\n";
		print "                                                </table></td>\n";
		print "                                        </tr>\n";
		print "                                        <tr>\n";
		print "                                            <td valign=\"top\" height=\"283\"><table width=\"100%\" height=\"300\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f5f4ee\">\n";
		print "                                                    <tr>\n";
		print "                                                        <td style=\"padding-left:15px; padding-right:15px; padding-top:15px;\" valign=\"top\" height=\"98\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                                                <tr>\n";
		print "                                                                    <td style=\"padding-right:1px;\" width=\"50%\"><table width=\"100%\" height=\"68\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                                                            <tr>\n";
		print "                                                                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_tl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                                                                <td class=\"tabla_blco\"></td>\n";
		print "                                                                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_tr.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                                                            </tr>\n";
		print "                                                                            <tr>\n";
		print "                                                                                <td class=\"tabla_blco\"></td>\n";
		print "                                                                                <td class=\"tabla_blco\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                                                                        <tr>\n";
		print "                                                                                            <td class=\"desc_resumen\" style=\"padding-top:10px; padding-left:10px;\">Precio</td>\n";
		print "                                                                                        </tr>\n";
		print "                                                                                        <tr>\n";
		print "                                                                                            <td class=\"precio_ficha\" style=\"padding-left:10px;\">\n";
		$datosProp2 = new Datosprop();
		$datosProp2->setId_prop($this->getIdPropiedad());
		switch ($this->arrayForm['operacion']){
			case "Alquiler":
				$datosProp2->setId_carac(166);
				break;
			case "Venta":
				$datosProp2->setId_carac(165);
				break;
			default:
				$datosProp2->setId_carac(165);
				break;
		}
		$datosPropBSN = new DatospropBSN();
		$datosPropBSN->seteaBSN($datosProp2);
		$arrayDatos = $datosPropBSN->cargaColeccionForm();
		if ($arrayDatos[0]['contenido'] == "Sin definir") {
			$moneda = "";
		} else {
			$moneda = $arrayDatos[0]['contenido'];
		}
		switch ($this->arrayForm['operacion']){
			case "Alquiler":
				$datosProp2->setId_carac(164);
				break;
			case "Venta":
				$datosProp2->setId_carac(161);
				break;
			default:
				$datosProp2->setId_carac(161);
				break;
		}
		$datosPropBSN = new DatospropBSN();
		$datosPropBSN->seteaBSN($datosProp2);
		$arrayDatos = $datosPropBSN->cargaColeccionForm();

		if ($arrayDatos[0]['contenido'] == 0) {
			$valor = "Consulte";
		} else {
			$valor = number_format($arrayDatos[0]['contenido'], 0, ",", ".");
		}
		echo $moneda . " " . $valor . "\n";
		print "                                                                                            </td>\n";
		print "                                                                                        </tr>\n";
		print "                                                                                    </table></td>\n";
		print "                                                                                <td class=\"tabla_blco\"></td>\n";
		print "                                                                            </tr>\n";
		print "                                                                            <tr>\n";
		print "                                                                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_bl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                                                                <td class=\"tabla_blco\"></td>\n";
		print "                                                                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_br.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                                                            </tr>\n";
		print "                                                                        </table></td>\n";
		print "                                                                    <td style=\"padding-left:1px;\"><table width=\"100%\" height=\"68\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                                                            <tr>\n";
		print "                                                                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_tl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                                                                <td class=\"tabla_blco\"></td>\n";
		print "                                                                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_tr.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                                                            </tr>\n";
		print "                                                                            <tr>\n";
		print "                                                                                <td class=\"tabla_blco\"></td>\n";
		print "                                                                                <td class=\"tabla_blco\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                                                                        <tr>\n";
		print "                                                                                            <td class=\"desc_resumen\" style=\"padding-top:10px; padding-left:10px;\">Superficie</td>\n";
		print "                                                                                        </tr>\n";
		print "                                                                                        <tr>\n";
		print "                                                                                            <td class=\"superficie_ficha\" style=\"padding-left:10px;\">\n";
		$datosProp2->setId_carac(198);
		$datosPropBSN = new DatospropBSN();
		$datosPropBSN->seteaBSN($datosProp2);
		$arrayDatos = $datosPropBSN->cargaColeccionForm();

		print intval($arrayDatos[0]['contenido']) . " m2</td>\n";
		print "                                                                                        </tr>\n";
		print "                                                                                    </table></td>\n";
		print "                                                                                <td class=\"tabla_blco\"></td>\n";
		print "                                                                            </tr>\n";
		print "                                                                            <tr>\n";
		print "                                                                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_bl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                                                                <td class=\"tabla_blco\"></td>\n";
		print "                                                                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_br.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                                                            </tr>\n";
		print "                                                                        </table></td>\n";
		print "                                                                </tr>\n";
		print "                                                            </table></td>\n";
		print "                                                    </tr>\n";
		print "                                                    <tr>\n";
		print "                                                        <td style=\"padding-left:15px; padding-right:15px;\" valign=\"top\"><span style=\"font-weight: bold;\">Descripción:</span><br />\n";
		print "                                                            <span class=\"desc_resumen\">";
		$datosProp2->setId_carac(255);
		$datosPropBSN = new DatospropBSN();
		$datosPropBSN->seteaBSN($datosProp2);
		$arrayDatos = $datosPropBSN->cargaColeccionForm();

		print $arrayDatos[0]['contenido'] . "</span></td>\n";
		print "                                                    </tr>\n";
		print "                                                    <tr>\n";
		print "                                                        <td height=\"1\" style=\"padding-left:15px; padding-right:15px;\"><hr /></td>\n";
		print "                                                    </tr>\n";
		print "                                                    <tr>\n";
		print "                                                        <td height=\"50\"></td>\n";
		print "                                                    </tr>\n";
		print "                                                </table></td>\n";
		print "                                        </tr>\n";
		print "                                        <tr>\n";
		print "                                            <td height=\"63\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                                    <tr>\n";
		print "                                                        <td valign=\"top\" width=\"30%\" align=\"center\"><a href=\"mail_cta_form.php?id=" . $this->getIdPropiedad() . "&TB_iframe=true&height=350&width=400&modal=false\" class=\"thickbox\"><img src=\"images/enviar_mail.gif\" width=\"92\" height=\"14\" border=\"0\" /></a></td>\n";
		print "                                                        <td valign=\"top\" width=\"20%\" align=\"center\"><a href=\"javascript: ventana('imprimir_prop.php?id=" . $this->getIdPropiedad() . "','Impresión de Ficha de la propiedad', 'location=no,status=1,scrollbars=1, width=970,height=600');\"><img src=\"images/imprimir.gif\" width=\"55\" height=\"15\" border=\"0\" /></a></td>\n";
		print "                                                        <td valign=\"top\" width=\"25%\" align=\"center\"><a href=\"ficha_prop_pdf.php?id=".$this->getIdPropiedad()."\"><img src=\"images/ficha_pdf.gif\" width=\"82\" height=\"14\" border=\"0\" /></a></td>\n";
		print "                                                        <td valign=\"top\" width=\"25%\" align=\"center\"></td>\n";
		print "                                                    </tr>\n";
		print "                                                </table></td>\n";
		print "                                        </tr>\n";
		print "                                    </table></td>\n";
		print "                                <td class=\"tabla_blco\"></td>\n";
		print "                            </tr>\n";
		print "                            <tr>\n";
		print "                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_bl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                                <td class=\"tabla_blco\"></td>\n";
		print "                                <td width=\"4\" height=\"4\"><img src=\"images/blanco_br.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                            </tr>\n";
		print "                        </table></td>\n";
		print "                </tr>\n";
		print "            </table></td>\n";
		print "    </tr>\n";
		print "    <tr>\n";
		print "        <td style=\"padding-top:4px; padding-bottom:4px;\"><table width=\"100%\" height=\"54\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                <tr>\n";
		print "                    <td width=\"4\" height=\"4\"><img src=\"images/blanco_tl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                    <td class=\"tabla_blco\"></td>\n";
		print "                    <td width=\"4\" height=\"4\"><img src=\"images/blanco_tr.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                </tr>\n";
		print "                <tr>\n";
		print "                    <td class=\"tabla_blco\"></td>\n";
		print "                    <td class=\"tabla_blco\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\">\n";
		print "                            <tr>\n";
		print "                                <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                        <tr>\n";
		print "                                            <td width=\"50%\" valign=\"top\">\n";
		// Muestra caracteristicas de la propiedad
		$carVW = new DatospropVW();
		$carVW->vistaDatosPropBuscador($this->arrayForm['id_prop']);

		print "                                            </td>\n";
		print "                                            <td valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
		print "                                                    <tr>\n";
		print "                                                        <td style=\"padding-left:15px; padding-top: 2px;\"><table width=\"100%\" border=\"0\">\n";
		print "                                                    <tr><td class='datos_resumen'><img src=\"images/flechita.gif\" width=\"16\" height=\"16\" />Ubicación</td></tr>\n";
		print "                                                                <tr>\n";
		print "                                                                    <td align=\"left\" valign=\"top\">\n";
		// Muestra Google Maps de la propiedad
		print "        <script type=\"text/javascript\" language=\"JavaScript\">\n";
		print "            var map = null;\n";
		print "            var geocoder = null;\n";
		print "            var contextmenu;\n";
		print "            function load() {\n";
		print "                if (GBrowserIsCompatible()) {\n";
		print "                    var point;\n";
		print "                    map=new GMap2(document.getElementById(\"map\"), { size: new GSize(425,425) });\n";
		print "                   map.setUIToDefault();\n";
		print "                    createContextMenu(map);\n";
		print "                    var address='';\n";

		print "            point = new GLatLng(" . $this->arrayForm['goglat'] . "," . $this->arrayForm['goglong'] . ");\n";

		print "            var marker = new GMarker(point);\n";
		print "            map.setCenter(point,17);\n";
		print "            map.addOverlay(marker);\n";
		print "            map.setMapType(G_HYBRID_MAP);\n";

		print "        }\n";
		print "    }\n";

		print "    function createContextMenu(map) {\n";
		print "        contextmenu = document.createElement(\"div\");\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "        contextmenu.style.background=\"#ffffff\";\n";
		print "        contextmenu.style.border=\"1px solid #8888FF\";\n";

		print "        contextmenu.innerHTML = '<a href=\"javascript:zoomIn()\"><div class=\"context\">&nbsp;&nbsp;Zoom in&nbsp;&nbsp;</div></a>'\n";
		print "            + '<a href=\"javascript:zoomOut()\"><div class=\"context\">&nbsp;&nbsp;Zoom out&nbsp;&nbsp;</div></a>'\n";
		print "            + '<a href=\"javascript:zoomInHere()\"><div class=\"context\">&nbsp;&nbsp;Zoom in here&nbsp;&nbsp;</div></a>'\n";
		print "            + '<a href=\"javascript:zoomOutHere()\"><div class=\"context\">&nbsp;&nbsp;Zoom out here&nbsp;&nbsp;</div></a>'\n";
		print "            + '<a href=\"javascript:centreMapHere()\"><div class=\"context\">&nbsp;&nbsp;Centre map here&nbsp;&nbsp;</div></a>';\n";

		print "        map.getContainer().appendChild(contextmenu);\n";
		print "        GEvent.addListener(map,\"singlerightclick\",function(pixel,tile) {\n";
		print "            clickedPixel = pixel;\n";
		print "            var x=pixel.x;\n";
		print "            var y=pixel.y;\n";
		print "            if (x > map.getSize().width - 120)\n";
		print "            {\n";
		print "                x = map.getSize().width - 120\n";
		print "            }\n";
		print "            if (y > map.getSize().height - 100)\n";
		print "            {\n";
		print "                y = map.getSize().height - 100\n";
		print "            }\n";
		print "            var pos = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(x,y));\n";
		print "            pos.apply(contextmenu);\n";
		print "            contextmenu.style.visibility = \"visible\";\n";
		print "        });\n";
		print "        GEvent.addListener(map, \"click\", function() {\n";
		print "            contextmenu.style.visibility=\"hidden\";\n";
		print "        });\n";
		print "    }\n";
		print "    function zoomIn() {\n";
		print "        map.zoomIn();\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		print "    function zoomOut() {\n";
		print "        map.zoomOut();\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		print "    function zoomInHere() {\n";
		print "        var point = map.fromContainerPixelToLatLng(clickedPixel)\n";
		print "        map.zoomIn(point,true);\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		print "    function zoomOutHere() {\n";
		print "        var point = map.fromContainerPixelToLatLng(clickedPixel)\n";
		print "        map.setCenter(point,map.getZoom()-1);\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		print "    function centreMapHere() {\n";
		print "        var point = map.fromContainerPixelToLatLng(clickedPixel)\n";
		print "        map.setCenter(point);\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		//        print "    load();\n";

		print "        </script>\n";
		print "        <div id=\"map\" style=\"width: 425px; height: 425px; border: solid thick #CCC;\"></div>\n";


		print "                                                                     </td>\n";
		print "                                                                </tr>\n";
		print "                                                    <tr><td class='datos_resumen' style=\"padding-top:20px;\"><img src=\"images/flechita.gif\" width=\"16\" height=\"16\" />Planos</td></tr>\n";
		for ($i = 1; $i <= 3; $i++) {
			if ($this->arrayForm['plano' . $i] != "") {
				print "                                                                <tr>\n";
				print "                                                                     <td><img src=\"fotos/" . $this->arrayForm['plano' . $i] . "\" border=\"0\" width='425'>\n";
				print "                                                                     </td>\n";
				print "                                                                </tr>\n";
			}
		}
		print "                                                            </table></td>\n";
		print "                                                    </tr>\n";
		print "                                                </table>\n";
		print "                                             </td>\n";
		print "                                        </tr>\n";
		print "                                    </table></td>\n";
		print "                            </tr>\n";
		print "                            <tr>\n";
		print "                                <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                        <tr>\n";
		print "                                            <td width=\"50%\" style=\"padding-right:20px;\"></td>\n";
		print "                                            <td></td>\n";
		print "                                        </tr>\n";
		print "                                    </table>\n";
		print "                                    \n";
		print "                                </td>\n";
		print "                            </tr>\n";
		print "                            <tr>\n";
		print "                                <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                        <tr>\n";
		print "                                            <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print "                                                    <tr>\n";
		print "                                                        <td valign=\"top\" class=\"legales_ficha\">Toda la información comercial, descripción,  precios, planos, imágenes, medidas y superficies que se proporcionan en esta web se basa en información que consideramos fiable y que es proporcionada por terceros. No podemos asegurar que sea exacta ni completa, representa material preliminar al solo efecto informativo e ilustrativo de las características del inmueble, pudiendo estar sujeta a errores, omisiones y cambios, incluyendo el precio o la retirada de oferta sin previo aviso.<br />\n";
		print "                                                            Recomendamos que el interesado consulte con sus profesionales las medidas, superficies que surjen de la documentación final. </td>\n";
		print "                                                    </tr>\n";
		print "                                                </table>\n";
		print "                                            </td>\n";
		print "                                        </tr>\n";
		print "                                    </table></td>\n";
		print "                            </tr>\n";
		print "                        </table></td>\n";
		print "                    <td class=\"tabla_blco\"></td>\n";
		print "                </tr>\n";
		print "                <tr>\n";
		print "                    <td><img src=\"images/blanco_bl.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                    <td class=\"tabla_blco\"></td>\n";
		print "                    <td><img src=\"images/blanco_br.png\" width=\"4\" height=\"4\" /></td>\n";
		print "                </tr>\n";
		print "            </table></td>\n";
		print "    </tr>\n";
		print "</table>\n";
		ob_flush();
	}

	public function vistaPlanosPropiedad($id_cli=0) {
		$flagP = 0;
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo' colspan='2'>Planos de la Propiedad</td></tr>\n";

		if ($this->arrayForm['plano1'] != '' && $this->arrayForm['plano1'] != 'NULL') {
			$flagP++;
			print "<tr><td class='cd_celda_texto' width='15%'>Plano 1</td>";
			print "<td width='35%'>" . $this->arrayForm['plano1'] . "<br><img src='" . $this->pathC . "/" . $this->arrayForm['plano1'] . "'></td></tr>\n";
		}
		if ($this->arrayForm['plano2'] != '' && $this->arrayForm['plano2'] != 'NULL') {
			$flagP++;
			print "<tr><td class='cd_celda_texto' width='15%'>Plano 2</td>";
			print "<td width='35%'>" . $this->arrayForm['plano2'] . "<br><img src='" . $this->pathC . "/" . $this->arrayForm['plano2'] . "'></td></tr>\n";
		}
		if ($this->arrayForm['plano3'] != '' && $this->arrayForm['plano3'] != 'NULL') {
			$flagP++;
			print "<tr><tr><td class='cd_celda_texto' width='15%'>Plano3</td>";
			print "<td width='35%'>" . $this->arrayForm['plano3'] . "<br><img src='" . $this->pathC . "/" . $this->arrayForm['plano3'] . "'></td></tr>\n";
		}
		if ($flagP == 0) {
			print "<tr><tr><td>No se hay Planos disponibles para mostrar</td>";
		}
		print "     </table>\n";
	}

	protected function armaCRMParam() {
		$arraykey = array_keys($_POST);
		$crmpar='';
		foreach ($arraykey as $clave){
			if($clave!='crmpar' && $clave!='crmtxt' && $clave!='adjuntos'){
				$crmpar.=($clave."->".$_POST[$clave]."|");
			}
		}
		return substr($crmpar,0,strlen($crmpar)-1);
	}

	public function armaCRMValoresFiltro() {

	}

	public function armaPOSTCRM() {
		$arrayPost = array();
		$crmpar = $_SESSION['crmpar'];
		$arrayElem = explode("|", $crmpar);
		foreach ($arrayElem as $elem) {
			$array = explode("->", $elem);
			$_POST[$array[0]] = $array[1];
		}
	}

	/**    OK
	 * Muestra una tabla con los datos de los propiedads y una barra de herramientas o menu
	 * conde se despliegan las opciones ingresables para cada item
	 *
	 */
	public function vistaTablaPropiedad($pagina=1, $idcrm='') {
		$zona = new Zona ();
		$zonaBSN = new ZonaBSN ();
		$local = new Localidad();
		$localaBSN = new LocalidadBSN();
		$tipopropBSN = new Tipo_propBSN();
		$tipo_propBSN = new Tipo_propBSN();
		$sucursal = new Sucursal();
		$config = CargaConfiguracion::getInstance();
		$registros = $config->leeParametro('regprod_adm');
		$perf = new PerfilesBSN();
		$perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
		$perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

		$crmtxt='';
		$identificador='';
		if($idcrm!='') {
			$identificador=$idcrm;
			$_SESSION['idcrm']=$idcrm;
		}else {
			if(isset($_SESSION['idcrm'])) {
				$identificador= $_SESSION['idcrm'];
			}
		}

		$gtp = new Grupo_tipoprop();
		if ($perfGpo == 'SUPERUSER' || strtoupper($perfGpo) == 'LECTURA' || $perfGpo == 'admin' || $perfGpo == 'GRAL') {
			$listaTPG = 0;
		} else {
			$listaTPG = $gtp->listaTipopropGrupo($perfGpo);
		}

		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";

		print "function publicar(id){\n";
		print "   window.open('publicar_propiedad.php?i='+id, 'ventana', 'menubar=1,resizable=1,width=350,height=250');\n";
		print "}\n";

		print "function publicarPrecio(id,publica){\n";
		print "   window.open('publica_precioPropiedad.php?i='+id+'&pub='+publica, 'ventana', 'menubar=1,resizable=1,width=350,height=250');\n";
		print "}\n";

		print "function relacionar(id){\n";
		print "   window.open('carga_contactoZP.php?i='+id, 'ventana', 'menubar=1,resizable=1,width=450,height=450');\n";
		print "}\n";

		print "function paginar(pagina){\n";
		print "   document.lista.action='lista_propiedad.php?i=';\n";
		print "   document.forms.lista.filtro.value=1;\n";
		print "   document.forms.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
		print "   document.forms.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_selloca.value=document.forms.lista.aux_selloca.value;\n";
		print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
		print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
		print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
		print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
		print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n"; //.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
		print "   document.forms.lista.seloperacion.value=document.forms.lista.aux_seloperacion.value;\n";
		print "   document.forms.lista.pagina.value=pagina;\n";
		print "   document.lista.submit();\n";
		//        print "   filtra();\n";
		print "}\n";

		print "function ordenar(campo){\n";
		print "   document.lista.action='lista_propiedad.php?i=';\n";
		print "   auxcampo=document.forms.lista.campo.value;\n";
		print "   auxorden=document.forms.lista.orden.value;\n";
		print "   if(campo==auxcampo){\n";
		print " 	  if(auxorden==0){\n";
		print "             auxorden=1;\n";
		print "       }else{\n";
		print "             auxorden=0;\n";
		print "       }\n";
		print "    }else{\n";
		print "       document.forms.lista.campo.value=campo;\n";
		print "        auxorden=0;\n";
		print "    }\n";
		print "   document.forms.lista.orden.value=auxorden;\n";
		print "   document.forms.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
		print "   document.forms.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.filtro.value=1;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_selloca.value=document.forms.lista.aux_selloca.value;\n";
		print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
		print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
		print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
		print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
		print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n"; //.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
		print "   document.forms.lista.seloperacion.value=document.forms.lista.aux_seloperacion.value;\n";
		print "   document.lista.submit();\n";
		print "}\n";

		print "function filtra(){\n";
		print "   document.lista.action='lista_propiedad.php?i=';\n";
		print "   document.forms.lista.filtro.value=1;\n";
		print "   document.forms.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
		print "   document.forms.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_selloca.value=document.forms.lista.aux_selloca.value;\n";
		print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
		print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
		print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
		print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
		print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n"; //.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
		print "   document.forms.lista.seloperacion.value=document.forms.lista.aux_seloperacion.value;\n";
		print "   document.forms.lista.pagina.value=1;\n";
		print "   document.lista.submit();\n";
		print "}\n";
		print "function limpiafiltro(){\n";
		print "   for(x=0;x<document.lista.elements.length;x++){\n";
		print "		document.lista.elements[x].value='';\n";
		print "   }\n";
		print "   document.lista.action='lista_propiedad.php?i=';\n";
		print "   document.forms.lista.fid_codigo.value=0;\n";
		print "   document.forms.lista.fid_calle.value='';\n";
		print "   document.forms.lista.fid_zona.value=0;\n";
		print "   document.forms.lista.filtro.value=0;\n";
		print "   document.forms.lista.fid_loca.value=0;\n";
		print "   document.forms.lista.fid_selloca.value='';\n";
		print "   document.forms.lista.fid_emp.value=0;\n";
		print "   document.forms.lista.fid_selemp.value='';\n";
		print "   document.forms.lista.fid_tipo_prop.value=0;\n";
		print "   document.forms.lista.fid_seltipo_prop.value='';\n";
		print "   document.forms.lista.foperacion.value='';\n";
		print "   document.forms.lista.seloperacion.value='';\n";
		print "   document.forms.lista.pagina.value=1;\n";
		print "   document.lista.submit();\n";
		print "}\n";
		print "function marcar(prop){\n";
		print "   document.forms.lista.id_prop.value=prop;\n";
		print "}\n";
		print "function popupLocalidad() {\n";
		print "	elemZona=document.getElementById('aux_zona').value;\n";
		print "  if(elemZona!=0){\n";
		print "	    elemLoca=document.getElementById('aux_loca').value;\n";
		print "     window.open('muestraSelectorLocalidad.php?l='+elemZona+'&v='+elemLoca,'Seleccion de Localidad', 'location=1,status=1,scrollbars=1, width=420,height=400');\n";
		print "  } else {\n";
		print "     alert(\"Debe seleccionar una ZONA para poder seleccionar Localidades\");\n";
		print "  }\n";
		print "}\n";
		print "function popupTipoprop() {\n";
		print "	    elemProp=document.getElementById('aux_prop').value;\n";
		print "	    window.open('muestraSelectorTipoprop.php?v='+elemProp,'Seleccion de Tipo de Propiedad', 'location=1,status=1,scrollbars=1, width=420,height=200');\n";
		print "}\n";
		print "function popupEmprendimiento() {\n";
		print " 	elemZona=document.getElementById('aux_zona').value;\n";
		print " 	elemLoca=document.getElementById('aux_loca').value;\n";
		print "	    elemEmp=document.getElementById('aux_emp').value;\n";
		print "     if(elemLoca==''){\n";
		print "         elemLoca=0;\n";
		print "     }\n";
		print "	    window.open('muestraSelectorEmprendimiento.php?v='+elemEmp+'&z='+elemZona+'&l='+elemLoca,'Seleccion de Emprendimientos', 'location=1,status=1,scrollbars=1, width=450,height=400');\n";
		print "}\n";
		print "function popupOperacion() {\n";
		print "	    elemOper=document.getElementById('aux_operacion').value;\n";
		print "	    window.open('muestraSelectorOperacion.php?v='+elemOper,'Seleccion de Tipo de Operacion', 'location=1,status=1,scrollbars=1, width=370,height=200');\n";
		print "}\n";

		print "function agregarSeleccion(i){\n";
		print "     var adj = new Array();\n";
		print "     var existe;\n";
		print "     adj = document.getElementById('adjuntos').value.split(',');\n";
		print "     existe = adj.indexOf(String(i));\n";
		//        print "     alert('Existe: '+existe)\n";
		print "     if(existe == -1){\n";
		print "         if(adj[0] == ''){\n";
		print "             adj[0] = i\n";
		print "         }else{\n";
		print "             adj.push(i);\n";
		print "         }\n";
		print "         document.getElementById('seleccionar'+i).src = 'images/basket_delete.png';\n";
		print "         document.getElementById('seleccion').style.display = 'block'\n";
		print "     }else{\n";
		print "         adj.splice(Number(existe),1);\n";
		print "         document.getElementById('seleccionar'+i).src = 'images/basket_add.png';\n";
		print "     }\n";
		print "         document.getElementById('adjuntos').value = adj\n";
		print "         var txt=document.getElementById('cuantos')\n";
		print "         if(adj.length > 0){\n";
		print "             txt.innerHTML = '('+adj.length+')';\n";
		print "         }else{\n";
		print "             txt.innerHTML = '';\n";
		print "         }\n";
		print "		grabarSeleccion();\n";
		print "}\n";

		print "function limpiarAdjuntos(){\n";
		print "     var adj = new Array();\n";
		print "     adj = document.getElementById('adjuntos').value.split(',');\n";
		print "     for(k=0; k < adj.length; k++){\n";
		print "         if(document.getElementById('seleccionar'+adj[k])){\n";
		print "             document.getElementById('seleccionar'+adj[k]).src = 'images/basket_add.png';\n";
		print "         }\n";
		print "     }\n";
		print "     document.getElementById('seleccion').style.display = 'none'\n";
		print "     document.getElementById('adjuntos').value = ''\n";
		print "		grabarSeleccion();\n";
		print "}\n";

		print "function verSeleccion(){\n";
		print "     document.lista.action='lista_propiedad.php?i=';\n";
		print "     document.getElementById('vistasel').value = 1;\n";
		print "   document.forms.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
		print "   document.forms.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_selloca.value=document.forms.lista.aux_selloca.value;\n";
		print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
		print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
		print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
		print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
		print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n"; //.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
		print "   document.forms.lista.seloperacion.value=document.forms.lista.aux_seloperacion.value;\n";
		print "     document.forms.lista.pagina.value=1;\n";
		print "     document.lista.submit();\n";
		print "}\n";
		print "function verLista(){\n";
		print "     document.lista.action='lista_propiedad.php?i=';\n";
		print "     document.getElementById('vistasel').value = 0;\n";
		print "     document.forms.lista.pagina.value=1;\n";
		print "   document.forms.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
		print "   document.forms.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_selloca.value=document.forms.lista.aux_selloca.value;\n";
		print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
		print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
		print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
		print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
		print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n"; //.options[document.getElementById(\
		print "     document.lista.submit();\n";
		print "}\n";
		print "function grabarSeleccion(){\n";
		print "  if(document.getElementById('identificador').value!=''){\n";
		//		print "alert('llega');\n";
		print "     pasaDatosCrm('identificador','crmpar','crmtxt','adjuntos');\n";
		print "  }\n";
		print "}\n";

		print "function limpiaLocalidad(zonaant){\n";
		print "if(zonaant!=document.getElementById('aux_zona').value){\n";
		print "document.getElementById('aux_loca').value='';\n";
		print "}\n";
		print "}\n";

		print "function ActTotal(cant){\n";
		print "     var txt=document.getElementById('total')\n";
		print "     txt.innerHTML = 'Total de Propiedades: '+cant;\n";
		print "}\n";

		print "$(document).ready(function(){\n";
		print "    $(\"#aux_codigo\").autocomplete(\"autocompletar.php?arg=1\");\n";
		print "    $(\"#aux_calle\").autocomplete(\"autocompletar.php?arg=2\");\n";
		print "});\n";

		print "function handleKeyPress(e,form){\n";
		print "     document.lista.action='lista_propiedad.php?i=';\n";
		print "     var key=e.keyCode || e.which;\n";
		print "     if (key==13){\n";
		print "     document.forms.lista.pagina.value=1;\n";
		print "     document.lista.submit();\n";
		print "     }\n";
		print "}\n";

		print "</script>\n";

		print "<div class='pg_titulo' style='float: left;'>Listado de Propiedades Disponibles</div>\n";

		print "<div id='seleccion' style='float: right; padding:5px;'>\n";
		print "		<span class='row' align='left' style='border-bottom: #FFF thin solid;border-top: #FFF thin solid;padding-left:3px; padding-right:3px; font-weight: bold;font-size:16px;'>Selección&nbsp;\n";
		print "		<img src='images/trash.png' onclick='javascript:limpiarAdjuntos();' title='Limpiar Selección' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;\n";
		print "         <img src='images/basket_edit.png' onclick='javascript:verSeleccion();' title='Ver Selección' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;\n";
		print "         <img src='images/email.png' onclick='javascript://verSeleccion();' title='Enviar Selección' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;<span id='cuantos'>" . $cantSel . "</span>\n";
		print "         <img src='images/page_save.png' onclick='javascript://grabarSeleccion();' title='Grabar Selección' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;\n";
		print "         <img src='images/eye.png' onclick='javascript:verLista();' title='Ver Lista' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;\n";
		print "</div>\n";

		print "<br><br>\n";

		print "<form name='lista' id='lista' method='POST' action='respondeMenu.php'>";

		$menu = new Menu();
		$menu->dibujaMenu('lista');

		if (isset($_POST) && sizeof($_POST) > 0) {
			if (isset($_POST['crmpar']) && $_POST['crmpar'] != '' && !isset($_SESSION['crmpar'])) {
				$this->armaPOSTCRM();
			}
			$_SESSION['filtro'] = $_POST;
			$_SESSION['crmpar'] = $this->armaCRMParam();
		}

		if (isset($_SESSION['filtro']['fid_codigo']) && $_SESSION['filtro']['fid_codigo'] != 0) {
			$aux_codigo = $_SESSION['filtro']['fid_codigo'];
			$crmtxt = "Codigo: " . $aux_codigo . "|";
		} else {
			$aux_codigo = 0;
		}
		if (isset($_SESSION['filtro']['fid_calle']) && $_SESSION['filtro']['fid_calle'] != '') {
			$aux_calle = $_SESSION['filtro']['fid_calle'];
			$crmtxt.= ( "Calle: " . $aux_calle . "|");
		} else {
			$aux_calle = '';
		}
		if (isset($_SESSION['filtro']['fid_zona']) && $_SESSION['filtro']['fid_zona'] != 0) {
			$aux_zona = $_SESSION['filtro']['fid_zona'];
			$zonaBSN->cargaById($aux_zona);
			$crmtxt.= ( "Zona: " . $zonaBSN->getObjeto()->getNombre_zona() . "|");
		} else {
			$aux_zona = 0;
		}
		if (isset($_SESSION['filtro']['fid_loca']) && $_SESSION['filtro']['fid_loca'] != 0) {
			$aux_loca = $_SESSION['filtro']['fid_loca'];
		} else {
			$aux_loca = 0;
		}
		if (isset($_SESSION['filtro']['fid_selloca']) && $_SESSION['filtro']['fid_selloca'] != '') {
			$aux_selloca = $_SESSION['filtro']['fid_selloca'];
			$crmtxt.= ( "Localidad: " . $aux_selloca . "|");
		} else {
			$aux_selloca = '';
		}
		if (isset($_SESSION['filtro']['fid_emp']) && $_SESSION['filtro']['fid_emp'] != 0) {
			$aux_emp = $_SESSION['filtro']['fid_emp'];
		} else {
			$aux_emp = 0;
		}
		if (isset($_SESSION['filtro']['fid_selemp']) && $_SESSION['filtro']['fid_selemp'] != '') {
			$aux_selemp = $_SESSION['filtro']['fid_selemp'];
			$crmtxt.= ( "Emprendimiento: " . $aux_selemp . "|");
		} else {
			$aux_selemp = '';
		}
		if (isset($_SESSION['filtro']['fid_tipo_prop']) && $_SESSION['filtro']['fid_tipo_prop'] != 0) {
			$aux_prop = $_SESSION['filtro']['fid_tipo_prop'];
		} else {
			$aux_prop = 0;
		}
		if (isset($_SESSION['filtro']['fid_seltipo_prop']) && $_SESSION['filtro']['fid_seltipo_prop'] != '') {
			$aux_selprop = $_SESSION['filtro']['fid_seltipo_prop'];
			$crmtxt.= ( "Tipo Prop: " . $aux_selprop . "|");
		} else {
			$aux_selprop = '';
		}
		if (isset($_SESSION['filtro']['foperacion']) && $_SESSION['filtro']['foperacion'] != '') {
			$aux_operacion = str_replace("\\", "", $_SESSION['filtro']['foperacion']);
			$vista_filaEstado='none';		// desactivo la vista de la seleccion de Muestra por
		} else {
			$aux_operacion = '';
			$vista_filaEstado='';		// activo la vista de la seleccion de Muestra por

		}
		if (isset($_SESSION['filtro']['seloperacion']) && $_SESSION['filtro']['seloperacion'] != '') {
			$aux_seloperacion = str_replace("\\", "", $_SESSION['filtro']['seloperacion']);
			$crmtxt.= ( "Operacion: " . $aux_seloperacion . "|");
		} else {
			$aux_seloperacion = '';
		}

		if (isset($_SESSION['filtro']['orden']) && $_SESSION['filtro']['orden'] != 0) {
			$aux_orden = 1;
		} else {
			$aux_orden = 0;
		}
		if (isset($_SESSION['filtro']['adjuntos']) && $_SESSION['filtro']['adjuntos'] != '') {
			$aux_adjuntos = $_SESSION['filtro']['adjuntos'];
			$sel = split(',', $aux_adjuntos);
			$cantSel = "(" . count($sel) . ")";
		} else {
			$aux_adjuntos = '';
			$cantSel = '';
			$sel = array();
		}

		if (isset($_SESSION['filtro']['vistaestado']) && $_SESSION['filtro']['vistaestado'] != '') {
			$aux_vistaestado = $_SESSION['filtro']['vistaestado'];
		} else {
			$aux_vistaestado = 1;
		}
		if (isset($_SESSION['filtro']['vistazona']) && $_SESSION['filtro']['vistazona'] != '') {
			$aux_vistazona = $_SESSION['filtro']['vistazona'];
		} else {
			$aux_vistazona = 0;
		}

		//---------------------------   INCLUIR MENU de MUESTRA DE DATOS
		print "<div id='menu_buscador'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='titu_filtro' colspan='2'>Mostrar</td>";
		print "	   </tr>";
		print "    <tr id='filaEstado' name='filaEstado' style='display: $vista_filaEstado;'>\n";
		print "     <td class='titu_filtro' colspan='2'>segun Estado</td>";
		print "	   </tr>";
		print "    <tr id='filaEstado' name='filaEstado' style='display: $vista_filaEstado;'>\n";
		print "     <td class='cd_celda_filtro'>Activas</td>";
		print "		<td><input type='radio' name='vistaestado' id='vistaestado' value='1' onClick='filtra();' ";
		if($aux_vistaestado!=0  && $aux_vistaestado!=2 ){
			print " checked ";
		}
		print "	></td>";
		print "	   </tr>";
		print "    <tr id='filaEstado' name='filaEstado' style='display: $vista_filaEstado;'>\n";
		print "     <td class='cd_celda_filtro'>Inactivas</td>";
		print "		<td><input type='radio' name='vistaestado' id='vistaestado' value='2' onClick='filtra();' ";
		if($aux_vistaestado==2){
			print " checked ";
		}
		print "	></td>";
		print "	   </tr>";
		print "    <tr id='filaEstado' name='filaEstado' style='display: $vista_filaEstado;'>\n";
		print "     <td class='cd_celda_filtro'>Todas</td>";
		print "		<td><input type='radio' name='vistaestado' id='vistaestado' value='0' onClick='filtra();' ";
		if($aux_vistaestado==0){
			print " checked ";
		}
		print "	></td>";
		print "	   </tr>";
		print "    <tr>\n";
		print "     <td class='titu_filtro' colspan='2'>segun Sucursal</td>";
		print "	   </tr>";
		print "    <tr>\n";
		print "     <td class='cd_celda_filtro'>Todas</td>";
		print "		<td><input type='radio' name='vistazona' id='vistazona' value='0' onClick='filtra();' ";
		if($aux_vistazona!=1){
			print " checked ";
		}
		print "	></td>";
		print "	   </tr>";
		print "    <tr>\n";
		print "     <td class='cd_celda_filtro'>Sucursal</td>";
		print "		<td><input type='radio' name='vistazona' id='vistazona' value='1' onClick='filtra();' ";
		if($aux_vistazona==1){
			print " checked ";
		}
		print "	></td>";
		print "	   </tr>";
		print "	   </table>";
		//        print "	   </div>";
		// ---------------------------  FIN MUESTRA DE DATOS

		//        print "<div id='menu_buscador'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='titu_filtro'>Filtrar por </td>";
		print "	</tr>";
		print "    <tr>\n";
		print "     <td class='cantidad_filtro' id='total'></td>";
		print "	</tr>";
		print "<tr>\n";
		print "		<td class='cd_celda_filtro'>Código<br /><input class='campos' name='aux_codigo' id='aux_codigo' type='text' value='$aux_codigo' onkeypress='//handleKeyPress(event,this.form);'></td>";
		print "	</tr>";
		print "<tr>\n";
		print "		<td class='cd_celda_filtro'>Calle<br /><input class='campos' name='aux_calle' id='aux_calle' type='text' value='$aux_calle'>\n";
		print "</td>";
		print "	</tr>";

		print "<tr>\n";
		print "		<td class='cd_celda_filtro'>Zona<br />";
		$zona1BSN = new ZonaBSN ();
		$zona1BSN->comboZona($aux_zona, $aux_loca, 3, 'aux_zona', 'aux_loca');
		print "</td>\n";
		print "	</tr>";

		print "<tr>\n";
		print "		<td class='cd_celda_texto'><input type='button' class='campos_btn' value=\"Localidad\" id='ver' name='ver' onclick='javascript: popupLocalidad();'>\n";
		print "             <div id='localidad' class='buscado'>" . str_replace(",", "<br />", $aux_selloca) . "</div>\n";
		print "     <input class='campos' name='aux_loca' id='aux_loca' type='hidden' value='$aux_loca'>\n<input class='campos' name='aux_selloca' id='aux_selloca' type='hidden' value='$aux_selloca'></td>\n";
		print "	</tr>";

		print "<tr>\n";
		print "		<td class='cd_celda_texto'><input type='button' class='campos_btn' value=\"Emprendimiento\" id='ver' name='ver' onclick='javascript: popupEmprendimiento();'>\n";
		print "             <div id='emprendimiento' class='buscado'>" . str_replace(",", "<br />", $aux_selemp) . "</div>\n";
		print "     <input class='campos' name='aux_emp' id='aux_emp' type='hidden' value='$aux_emp'>\n<input class='campos' name='aux_selemp' id='aux_selemp' type='hidden' value='$aux_selemp'></td>\n";
		print "	</tr>";


		print "<tr>\n";
		print "		<td class='cd_celda_texto'><input type='button' class='campos_btn' value=\"Tipo de Prop.\" id='ver' name='ver' onclick='javascript: popupTipoprop();'>";
		print "             <div id='selTipoprop' class='buscado'>" . str_replace(",", "<br />", $aux_selprop) . "</div>";
		print "     <input class='campos' name='aux_prop' id='aux_prop' type='hidden' value='$aux_prop'>\n<input class='campos' name='aux_selprop' id='aux_selprop' type='hidden' value='$aux_selprop'></td>\n";
		print "	</tr>";

		print "<tr>\n";
		print "		<td class='cd_celda_texto'><input type='button' class='campos_btn' value=\"Tipo de Operación\" id='ver' name='ver' onclick='javascript: popupOperacion();'>";
		print "             <div id='operacion' class='buscado'>" . str_replace(",", "<br />", $aux_seloperacion) . "</div>";
		print "     <input class='campos' name='aux_operacion' id='aux_operacion' type='hidden' value='$aux_operacion'>\n<input class='campos' name='aux_seloperacion' id='aux_seloperacion' type='hidden' value='$aux_seloperacion'></td>\n";
		print "</tr>\n";


		$img1 = $img2 = $img3 = $img4 = $img5 = $img6 = $img7 = $img8 = $img9 = "spacer.gif";

		if (isset($_SESSION['filtro']['campo']) && is_numeric($_SESSION['filtro']['campo'])) {
			$campolocal = $_SESSION['filtro']['campo'];
			switch ($campolocal) {
				case 1:
					$aux_campo = 'id_prop';
					if ($_SESSION['filtro']['orden'] == 1) {
						$img1 = "up.png";
					} else {
						$img1 = "down.png";
					}
					break;
				case 2:
					$aux_campo = 'id_zona';
					if ($_SESSION['filtro']['orden'] == 1) {
						$img2 = "up.png";
					} else {
						$img2 = "down.png";
					}
					break;
				case 3:
					$aux_campo = 'id_loca';
					if ($_SESSION['filtro']['orden'] == 1) {
						$img3 = "up.png";
					} else {
						$img3 = "down.png";
					}
					break;
				case 4:
					$aux_campo = 'calle';
					if ($_SESSION['filtro']['orden'] == 1) {
						$img4 = "up.png";
					} else {
						$img4 = "down.png";
					}
					break;
				case 5:
					$aux_campo = 'id_tipo_prop';
					if ($_SESSION['filtro']['orden'] == 1) {
						$img5 = "up.png";
					} else {
						$img5 = "down.png";
					}
					break;
				case 6:
					$aux_campo = 'CAST(suptot AS DECIMAL)';
					if ($_SESSION['filtro']['orden'] == 1) {
						$img6 = "up.png";
					} else {
						$img6 = "down.png";
					}
					break;
				case 7:
					$aux_campo = 'CAST(cantamb AS DECIMAL)';
					if ($_SESSION['filtro']['orden'] == 1) {
						$img7 = "up.png";
					} else {
						$img7 = "down.png";
					}
					break;
				case 9:
					$aux_campo = 'CAST(valven AS DECIMAL)';
					if ($_SESSION['filtro']['orden'] == 1) {
						$img9 = "up.png";
					} else {
						$img9 = "down.png";
					}
					break;
				default:
					break;
			}
		} else {
			$aux_campo = '';
		}

		print "<tr id='filtro' name='filtro'><td >";
		$dpropVW = new DatospropVW();
		$arraydp = array();
		$arrayDB = array();

		if (isset($_POST['filtro']) && $_POST['filtro'] != 0) {
			$arraydp = $dpropVW->leeFiltroDatospropVW();  // Lee los valores desde el filtro del formulario
		}

		$_SESSION['crmtxt'] = $crmtxt . $dpropVW->armaTextoFiltroCRM($arraydp, $aux_prop);  // cargo el contenido del filtro en un string

		$dpropVW->cargaFiltroDatosprop($arraydp, $aux_prop); // Carga nuevamente el filtro con los valores leidos
		$dpropBSN = new DatospropBSN();
		$arrayDB = $arraydp;



		if ($_POST['vistasel'] != '1') {
			$propsIN = $dpropBSN->armaArrayIN($arrayDB);  // armo un string con los ID de las propiedades que cumplen con los filtros de caracteristicas
		} else {
			$propsIN = $aux_adjuntos;
		}
		print "</td></tr>";

		print "<tr>\n";
		print "		<td class='row' style='padding-left:3px; padding-right:3px;padding-bottom:10px;'><div style='float: left;'><input class='boton_form_filtro' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></div><div style='float: right;'><input class='boton_form_filtro' type='button' onclick='javascript:filtra();' value='Enviar'></div></td>\n";
		print "</tr>";
		if ($aux_adjuntos == '') {
			$display = 'none';
		} else {
			$display = 'block';
		}
		print "</table>\n";
		print "</div>";

		//--------------------------------- Mostrar Resultados
		print "<div id='resultado'>";

		// Fin Filtro

		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='orden_tab'>&nbsp;</td>\n";
		print "     <td class='orden_tab' width='70'><a href='javascript:ordenar(1);'>C&oacute;digo<img src='images/" . $img1 . "' id='img_1' border='0'></a></td>\n";
		//print "     <td class='orden_tab'>Sucursal</td>\n";
		print "     <td class='orden_tab' width='95'><a href='javascript:ordenar(2);'>Zona<img src='images/" . $img2 . "' id='img_2' border='0'></a></td>\n";
		print "     <td class='orden_tab' width='95'><a href='javascript:ordenar(3);'>Localidad<img src='images/" . $img3 . "' id='img_3' border='0'></a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(4);'>Dirección<img src='images/" . $img4 . "' id='img_4' border='0'></a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(5);'>Tipo<img src='images/" . $img5 . "' id='img_5' border='0'></a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(6);'>Sup.<img src='images/" . $img6 . "' id='img_6' border='0'></a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(7);'>Amb.<img src='images/" . $img7 . "' id='img_7' border='0'></a></td>\n";
		print "     <td class='orden_tab' width='95'><a href='javascript:ordenar(9);'>Valor<img src='images/" . $img9 . "' id='img_9' border='0'></a></td>\n";
		print "	  </tr>\n";

		//   COMIENZO DE UNIFICACION
		$evenBSN = new PropiedadBSN();
		//		$arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $aux_zona, $aux_loca, $aux_prop, $aux_operacion, 0, $propsIN, $pagina, $aux_campo, $aux_orden,$aux_vistaestado,$aux_vistazona);
		$arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $aux_zona, $aux_loca, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden,$aux_vistaestado,$aux_vistazona);
		$cantreg = sizeof($arrayDatos);
		$cantr = $cantreg / $registros;
		$cante = $cantreg % $registros;
		// FIN DE UNIFICACION

		print "<script type='text/javascript' language='javascript'>\n";
		print "ActTotal(" . $cantreg . ")\n";
		print "</script>\n";

		if ($cante != 0) {
			$paginas = intval($cantr + 1);
		} else {
			$paginas = $cantr;
		}

		$arrayEven = array_slice($arrayDatos, (($pagina - 1) * $registros), $registros);   // AGREGADO PARA UNIFICAR BUSQUEDA

		if (sizeof($arrayEven) == 0) {
			print "No existen datos para mostrar";
		} else {
			foreach ($arrayEven as $Even) {
				if ($fila == 0) {
					$fila = 1;
				} else {
					$fila = 0;
				}

				print "<tr>\n";

/*				
					print "	 <td class='row" . $fila . "'>";

				print "<table width='100%'><tr>";
				if (in_array($Even['id_prop'], $sel)) {
					$basket = "basket_delete.png";
				} else {
					$basket = "basket_add.png";
				}
				print "<td class='row" . $fila . "'><img src='images/" . $basket . "' id='seleccionar" . $Even['id_prop'] . "' title='Seleccionar' border='0' style='cursor: pointer;' onclick=\"javascript: agregarSeleccion(" . $Even['id_prop'] . ");\"></td>";

				if ($listaTPG == 0 || strpos($listaTPG, $Even['id_tipo_prop'])!==false) {
					if ($perfSuc == $Even['id_sucursal'] || $perfSuc == 'Todas') {
						if ($Even['activa'] == 0) {
							print "<td class='row" . $fila . "'>       <img src='images/web.png' alt='Publicar la propiedad' title='Publicar la propiedad' border='0' style='cursor: pointer;' onclick=\"javascript: publicar(" . $Even['id_prop'] . ");\"></td>";
						} else {
							print "<td class='row" . $fila . "'>       <img src='images/web_no.png' alt='Publicar la propiedad' title='Publicar la propiedad' border='0' style='cursor: pointer;' onclick=\"javascript: publicar(" . $Even['id_prop'] . ");\"></td>";
						}
					}
				}

				if ($listaTPG == 0 || strpos($listaTPG, $Even['id_tipo_prop'])!==false) {
					if ($perfSuc == $Even['id_sucursal'] || $perfSuc == 'Todas') {
						print "<td class='row" . $fila . "' rowspan='2'><input type='radio' name='id_prop' id='id_prop' value='" . $Even['id_prop'] . "' onclick='javascript:marcar(" . $Even['id_prop'] . ");'></td>";
					}
				}

				
				print "</tr><tr>";
				if ($listaTPG == 0 || strpos($listaTPG, $Even['id_tipo_prop'])!==false) {
					if ($perfSuc == $Even['id_sucursal'] || $perfSuc == 'Todas') {
						print "<td class='row" . $fila . "'>       <img src='images/group_edit.png' alt='Relaciones de la propiedad' title='Relaciones de  la propiedad' border='0' style='cursor: pointer;' onclick=\"javascript: relacionar(" . $Even['id_prop'] . ");\"></td>";
					}
				}
				if ($listaTPG == 0 || strpos($listaTPG, $Even['id_tipo_prop'])!==false) {
					if ($perfSuc == $Even['id_sucursal'] || $perfSuc == 'Todas') {
						if ($Even['publicaprecio'] == 0) {
							print "<td class='row" . $fila . "'>       <img src='images/money_add.png' alt='Publicar Precio' title='Publicar Precio' border='0' style='cursor: pointer;' onclick=\"javascript: publicarPrecio(" . $Even['id_prop'] . ",1);\"></td>";
						} else {
							print "<td class='row" . $fila . "'>       <img src='images/money_delete.png' alt='Publicar Precio' title='Publicar Precio' border='0' style='cursor: pointer;' onclick=\"javascript: publicarPrecio(" . $Even['id_prop'] . ",0);\"></td>";
						}
					}
				}


				print "</tr></table>";

				print "</td>";
			
	
*/
				
				
				print "	 <td class='row" . $fila . "' width='70'>";

				if (in_array($Even['id_prop'], $sel)) {
					$basket = "basket_delete.png";
				} else {
					$basket = "basket_add.png";
				}
				print "&nbsp;<img src='images/" . $basket . "' id='seleccionar" . $Even['id_prop'] . "' title='Seleccionar' border='0' style='cursor: pointer;' onclick=\"javascript: agregarSeleccion(" . $Even['id_prop'] . ");\">";

				if ($listaTPG == 0 || strpos($listaTPG, $Even['id_tipo_prop'])!==false) {
					if ($perfSuc == $Even['id_sucursal'] || $perfSuc == 'Todas') {
						if ($Even['activa'] == 0) {
							print "&nbsp;&nbsp;<img src='images/web.png' alt='Publicar la propiedad' title='Publicar la propiedad' border='0' style='cursor: pointer;' onclick=\"javascript: publicar(" . $Even['id_prop'] . ");\">";
						} else {
							print "&nbsp;&nbsp;<img src='images/web_no.png' alt='Publicar la propiedad' title='Publicar la propiedad' border='0' style='cursor: pointer;' onclick=\"javascript: publicar(" . $Even['id_prop'] . ");\">";
						}
					}
				}

				if ($listaTPG == 0 || strpos($listaTPG, $Even['id_tipo_prop'])!==false) {
					if ($perfSuc == $Even['id_sucursal'] || $perfSuc == 'Todas') {
						print "&nbsp;<input type='radio' name='id_prop' id='id_prop' value='" . $Even['id_prop'] . "' onclick='javascript:marcar(" . $Even['id_prop'] . ");'>";
					}
				}

				print "</td>";


				print "  <td class='row" . $fila . "'>";
				print "<a href='#' onclick=\"javascript: ventana('muestra_datosprop.php?i=".$Even['id_prop']."', 'Datos de la propiedad', 980, 900);\" title='Datos de la propiedad' style='display: block;'>";
				print $Even['id_sucursal'].str_repeat("0",5-strlen(strval($Even['id_prop']))).$Even['id_prop'];
				print "</a></td>\n";

				print "	 <td class='row".$fila."'>";
				print "<a href='#' onclick=\"javascript: ventana('muestra_datosprop.php?i=".$Even['id_prop']."', 'Datos de la propiedad', 980, 900);\" title='Datos de la propiedad' style='display: block;'>";
				print substr($Even['nombre_zona'], 0, 15);
				print "</a></td>\n";
				print "	 <td class='row".$fila."'>";
				print "<a href='#' onclick=\"javascript: ventana('muestra_datosprop.php?i=".$Even['id_prop']."', 'Datos de la propiedad', 980, 900);\" title='Datos de la propiedad' style='display: block;'>";
				print substr($Even['nombre_loca'],0,15);
				print "</a></td>\n";
				print "<td class='row".$fila."'>\n";
				print "<a href='#' onclick=\"javascript: ventana('muestra_datosprop.php?i=".$Even['id_prop']."', 'Datos de la propiedad', 980, 900);\" title='Datos de la propiedad' style='display: block;'>";
				print $Even['calle']. " " . $Even['nro'] . " ".$Even['piso']." ".$Even['dpto']."</a></td>\n";
				print "	 <td class='row".$fila."' align='center'>";
				print "<a href='#' onclick=\"javascript: ventana('muestra_datosprop.php?i=".$Even['id_prop']."', 'Datos de la propiedad', 980, 900);\" title='Datos de la propiedad' style='display: block;'>";
				switch ($Even['id_tipo_prop']) {
					case 1:
						print "<img src='images/tipo_depto.png' border='0' title='Departamento' />";
						break;
					case 2:
						print "<img src='images/tipo_local.png' border='0' title='Local' />";
						break;
					case 3:
						print "<img src='images/tipo_ph.png' border='0' title='PH' />";
						break;
					case 6:
						print "<img src='images/tipo_terreno.png' border='0' title='Terreno' />";
						break;
					case 7:
						print "<img src='images/tipo_terreno.png' border='0' title='".$Even['tipo_prop']."' />";
						break;
					case 9:
						print "<img src='images/tipo_casa.png' border='0' title='Casa' />";
						break;
					case 11:
						print "<img src='images/tipo_oficina.png' border='0' title='Oficina' />";
						break;
					case 15:
						print "<img src='images/tipo_galpon.png' border='0' title='Galpón' />";
						break;
				}
				print "</a></td>\n";
				print "	 <td class='row".$fila."' align='right'>\n";
				print "<a href='#' onclick=\"javascript: ventana('muestra_datosprop.php?i=".$Even['id_prop']."', 'Datos de la propiedad', 980, 900);\" title='Datos de la propiedad' style='display: block;'>";
				print number_format($Even['suptot'], 0, ',','.')."</a></td>\n";
				print "	 <td class='row".$fila."' align='center'>";
				print "<a href='#' onclick=\"javascript: ventana('muestra_datosprop.php?i=".$Even['id_prop']."', 'Datos de la propiedad', 980, 900);\" title='Datos de la propiedad' style='display: block;'>";
				if($Even['cantamb'] != "Sin definir") {
					print number_format($Even['cantamb'], 0);
				}
				print "</a></td>\n";
				print "	 <td class='row".$fila."' align='right'>";
				print "<a href='#' onclick=\"javascript: ventana('muestra_datosprop.php?i=".$Even['id_prop']."', 'Datos de la propiedad', 980, 900);\" title='Datos de la propiedad' style='display: block;'>";
				if($Even['monven'] != "Sin definir" && $Even['valven'] != 0) {
					print $Even['monven']." ".number_format($Even['valven'], 0, ',','.');
				}else {
					if($Even['monalq'] != "Sin definir") {
						print $Even['monalq']." ".number_format($Even['valalq'], 0, ',','.');
					}
				}
				print "</a></td>\n";

				print "	</tr>\n";
			}
			//------Paginado--------------------

			print "<tr>\n";
			print "<td valign=\"middle\" colspan=\"2\"><a class='boton_form_filtro' style='padding-top:5px;' href=\"javascript:ventana('lista_propiedadGoogle.php', 'Busqueda en GoogleMaps', 980, 900);\">Ver en GoogleMaps</a></td>\n";

			print "		<td align='right' colspan='7' style='padding-top:10px; padding-bottom:10px;'>";
			if ($pagina > 1) {
				print "    <a href='javascript:paginar(1);'>";
				print "       <img src='images/resultset_first.png' alt='Inicio' title='Inicio' border='0' style='vertical-align: middle;'></a>&nbsp;";
				if ($pagina > 2) {
					print "    <a href='javascript:paginar(" . ($pagina - 1) . ");'>";
					print "       <img src='images/resultset_previous.png' alt='Anterior' title='Anterior' border='0' style='vertical-align: middle;'></a>&nbsp;-&nbsp;";
				}
				for ($x = $pagina - 5; $x < $pagina; $x++) {
					if ($x > 0) {
						print "<a href='javascript:paginar(" . $x . ");'>$x</a>&nbsp;-&nbsp;";
					}
				}
			}
			print "<span style='font-weight: bold; color:#FFF; background-color:#99BBEA;'>&nbsp;$pagina&nbsp;</span>-&nbsp;";
			if ($pagina < $paginas) {
				for ($x = $pagina + 1; $x < $pagina + 5; $x++) {
					if ($x <= $paginas) {
						print "<a href='javascript:paginar(" . $x . ");'>" . $x . "</a>&nbsp;-&nbsp;";
					}
				}
				if ($pagina < $paginas - 1) {
					print "    <a href='javascript:paginar(" . ($pagina + 1) . ");'>";
					print "       <img src='images/resultset_next.png' alt='Siguiente' title='Siguiente' border='0' style='vertical-align: middle;'></a>&nbsp;";
				}
				print "    <a href='javascript:paginar($paginas);'>";
				print "       <img src='images/resultset_last.png' alt='Ultima' title='Ultima' border='0' style='vertical-align: middle;'></a>&nbsp;";
			}
			print "</td></tr>";
		}

		print "  </table>\n";
		print "</div>";
		//        print "<input type='hidden' name='id_prop' id='id_prop' value=''>";
		print "<input type='hidden' name='aux_id_prop' id='aux_id_prop' value=''>\n";
		print "<input type='hidden' name='fid_tipo_prop' id='fid_tipo_prop' value=''>\n";
		print "<input type='hidden' name='fid_seltipo_prop' id='fid_seltipo_prop' value=''>\n";
		print "<input type='hidden' name='fid_codigo' id='fid_codigo' value='0'>\n";
		print "<input type='hidden' name='fid_calle' id='fid_calle' value=''>\n";
		print "<input type='hidden' name='fid_zona' id='fid_zona' value=''>\n";
		print "<input type='hidden' name='fid_loca' id='fid_loca' value=''>\n";
		print "<input type='hidden' name='fid_selloca' id='fid_selloca' value=''>\n";
		print "<input type='hidden' name='fid_emp' id='fid_emp' value=''>\n";
		print "<input type='hidden' name='fid_selemp' id='fid_selemp' value=''>\n";
		print "<input type='hidden' name='foperacion' id='foperacion' value=''>\n";
		print "<input type='hidden' name='seloperacion' id='seloperacion' value=''>\n";
		print "<input type='hidden' name='filtro' id='filtro' value='0'>\n";
		print "<input type='hidden' name='campo' id='campo' value='$campolocal'>\n";
		print "<input type='hidden' name='orden' id='orden' value='$aux_orden'>\n";
		print "<input type='hidden' name='adjuntos' id='adjuntos' value='$aux_adjuntos'>\n";
		print "<input type='hidden' name='vistasel' id='vistasel' value='0'>\n";

		print "<input type='hidden' name='crmtxt' id='crmtxt' value='" . $_SESSION['crmtxt'] . "'>\n";
		print "<input type='hidden' name='crmpar' id='crmpar' value='" . $_SESSION['crmpar'] . "'>\n";
		print "<input type='hidden' name='identificador' id='identificador' value='$identificador'>\n";

		print "<input type='hidden' name='pagina' id='pagina' value='$pagina'>\n";

		print "</form>\n";

	}

	/**    OK
	 * Muestra muestra la los resultados en GoogleMpas
	 *
	 *
	 */

	public function vistaGoogleMaps(){
		// MAPA DE GOOGLE MPAS---------------------------
		$evenBSN = new PropiedadBSN();
		$arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $aux_zona, $aux_loca, $aux_prop, $aux_operacion, 0, $propsIN, $pagina, $aux_campo, $aux_orden);

		$arrayEvenMapa = $arrayDatos;
		// MODIFICACION PARA UNIFICAR LA DE ARRIBA Y EL COMENTARIO DE ABAJO

		print "     <tr>\n";
		print "       <td colspan=\"9\">\n";
		print "        <script type=\"text/javascript\" src=\"inc/markerclusterer.js\" language=\"JavaScript\" /></script>\n";
		print "        <script type=\"text/javascript\" language=\"JavaScript\">\n";
		print "            var map = null;\n";
		print "            var geocoder = null;\n";
		print "            var contextmenu;\n";
		print "            function load() {\n";
		print "                if (GBrowserIsCompatible()) {\n";
		print "                 var map = new GMap2(document.getElementById(\"map\"));\n";
		print "                 map.setUIToDefault();\n";
		print "                 createContextMenu(map);\n";
		print "                 map.setCenter(new GLatLng(-34.6049311, -58.3865514), 13);\n";
		print "                 var mcOptions = { gridSize: 50, maxZoom: 15};\n";
		print "                 var markers = [];\n";
		print "                 var address='';\n";
		foreach ($arrayEvenMapa as $Even) {
			print "                 var latlng = new GLatLng(" . $Even['goglat'] . "," . $Even['goglong'] . "," . $Even['id_prop'] . ");\n";
			print "                 var marker = new GMarker(latlng);\n";
			print "                 markers.push(marker);\n";
			print "                 GEvent.addListener(marker, \"click\", function() {ventana('muestra_datosprop.php?i=" . $Even['id_prop'] . "', 'Datos de la propiedad', 980, 900);});\n";
		}

		print "                 var mc = new MarkerClusterer(map, markers, mcOptions);\n";

		print "        }\n";
		print "    }\n";

		print "    function createContextMenu(map) {\n";
		print "        contextmenu = document.createElement(\"div\");\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "        contextmenu.style.background=\"#ffffff\";\n";
		print "        contextmenu.style.border=\"1px solid #8888FF\";\n";

		print "        contextmenu.innerHTML = '<a href=\"javascript:zoomIn()\"><div class=\"context\">&nbsp;&nbsp;Zoom in&nbsp;&nbsp;</div></a>'\n";
		print "            + '<a href=\"javascript:zoomOut()\"><div class=\"context\">&nbsp;&nbsp;Zoom out&nbsp;&nbsp;</div></a>'\n";
		print "            + '<a href=\"javascript:zoomInHere()\"><div class=\"context\">&nbsp;&nbsp;Zoom in here&nbsp;&nbsp;</div></a>'\n";
		print "            + '<a href=\"javascript:zoomOutHere()\"><div class=\"context\">&nbsp;&nbsp;Zoom out here&nbsp;&nbsp;</div></a>'\n";
		print "            + '<a href=\"javascript:centreMapHere()\"><div class=\"context\">&nbsp;&nbsp;Centre map here&nbsp;&nbsp;</div></a>';\n";

		print "        map.getContainer().appendChild(contextmenu);\n";
		print "        GEvent.addListener(map,\"singlerightclick\",function(pixel,tile) {\n";
		print "            clickedPixel = pixel;\n";
		print "            var x=pixel.x;\n";
		print "            var y=pixel.y;\n";
		print "            if (x > map.getSize().width - 120)\n";
		print "            {\n";
		print "                x = map.getSize().width - 120\n";
		print "            }\n";
		print "            if (y > map.getSize().height - 100)\n";
		print "            {\n";
		print "                y = map.getSize().height - 100\n";
		print "            }\n";
		print "            var pos = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(x,y));\n";
		print "            pos.apply(contextmenu);\n";
		print "            contextmenu.style.visibility = \"visible\";\n";
		print "        });\n";
		print "        GEvent.addListener(map, \"click\", function() {\n";
		print "            contextmenu.style.visibility=\"hidden\";\n";
		print "        });\n";
		print "    }\n";
		print "    function zoomIn() {\n";
		print "        map.zoomIn();\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		print "    function zoomOut() {\n";
		print "        map.zoomOut();\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		print "    function zoomInHere() {\n";
		print "        var point = map.fromContainerPixelToLatLng(clickedPixel)\n";
		print "        map.zoomIn(point,true);\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		print "    function zoomOutHere() {\n";
		print "        var point = map.fromContainerPixelToLatLng(clickedPixel)\n";
		print "        map.setCenter(point,map.getZoom()-1);\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";
		print "    function centreMapHere() {\n";
		print "        var point = map.fromContainerPixelToLatLng(clickedPixel)\n";
		print "        map.setCenter(point);\n";
		print "        contextmenu.style.visibility=\"hidden\";\n";
		print "    }\n";

		print "        </script>\n";
		print "        <div id=\"map\" style=\"width: 970px; height: 810px; border: solid thick #CCC;\"></div>\n";
		print "       </td>\n";
		print "     </tr>\n";

	}





	/**    OK
	 * Muestra una tabla con los datos de los propiedads y una barra de herramientas o menu
	 * conde se despliegan las opciones ingresables para cada item
	 *
	 */
	public function vistaTablaBuscador($pagina=1) {
		echo "obsoleta";
	}

	/*
	 public function vistaTablaBuscadorTabs($pagina=1) {
		$zona = new Zona ();
		$zonaBSN = new ZonaBSN ();
		$local = new Localidad();
		$localaBSN = new LocalidadBSN();
		$tipopropBSN = new Tipo_propBSN();
		$tipo_propBSN = new Tipo_propBSN();
		$sucursal = new Sucursal();
		$config = CargaConfiguracion::getInstance();
		$registros = $config->leeParametro('regprod_adm');
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "   document.forms.lista.id_prop.value=id;\n";
		print "   document.lista.action='muestra_datosprop.php';\n";
		print "   window.open('muestra_datosprop.php?i='+id);\n";
		print "}\n";

		print "function paginar(pagina){\n";
		print "   document.lista.action='filtro_datosprop.php?i=';\n";
		print "   document.forms.lista.filtro.value=1;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
		print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
		print "   document.forms.lista.pagina.value=pagina;\n";
		print "   document.lista.submit();\n";
		//        print "   filtra();\n";
		print "}\n";

		print "function ordenar(campo){\n";
		print "   document.lista.action='filtro_datosprop.php?i=';\n";
		print "   auxcampo=document.forms.lista.campo.value;\n";
		print "   auxorden=document.forms.lista.orden.value;\n";
		//		print "	  campimg=\"img_\" + campo;\n";
		print "   if(campo==auxcampo){\n";
		print " 	  if(auxorden==0){\n";
		print "             auxorden=1;\n";
		//		print "             document.getElementById(campimg).src=\"images/up.png\";\n";
		print "       }else{\n";
		print "             auxorden=0;\n";
		//		print "             document.getElementById(campimg).src=\"images/down.png\";\n";
		print "       }\n";
		print "    }else{\n";
		print "       document.forms.lista.campo.value=campo;\n";
		print "        auxorden=0;\n";
		print "    }\n";
		print "   document.forms.lista.orden.value=auxorden;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.filtro.value=1;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
		print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
		//        print "   document.forms.lista.pagina.value=pagina;\n";
		print "   document.lista.submit();\n";
		//        print "   filtra();\n";
		print "}\n";

		print "function filtra(){\n";
		print "   document.lista.action='filtro_datosprop.php?i=';\n";
		print "   document.forms.lista.filtro.value=1;\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
		print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
		print "   document.forms.lista.pagina.value=1;\n";
		print "   document.lista.submit();\n";
		print "}\n";
		print "function limpiafiltro(){\n";
		print "   for(x=0;x<document.lista.elements.length;x++){\n";
		print "		document.lista.elements[x].value='';\n";
		print "   }\n";
		print "   document.lista.action='filtro_datosprop.php?i=';\n";
		print "   document.forms.lista.fid_zona.value=0;\n";
		print "   document.forms.lista.filtro.value=0;\n";
		print "   document.forms.lista.fid_loca.value=0;\n";
		print "   document.forms.lista.fid_tipo_prop.value=0;\n";
		print "   document.forms.lista.foperacion.value='Todas';\n";
		print "   document.forms.lista.pagina.value=1;\n";
		print "   document.lista.submit();\n";
		print "}\n";
		print "function marcar(prop){\n";
		print "   document.forms.lista.aux_id_prop.value=prop;\n";
		//        print "alert(document.forms.lista.id_prop.value);\n";
		print "}\n";
		print "</script>\n";

		print "<span class='pg_titulo'>Listado de Propiedades Disponibles</span><br><br>\n";

		print "<form name='lista' method='POST' action='respondeMenu.php'>";

		//        $menu=new Menu();
		//        $menu->dibujaMenu('lista');
		// Filtro
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td colspan='6' class='cd_lista_filtro' style='text-align: left; padding-left: 10px;'>Filtrar por </td>";
		print "	</tr>";
		print "<tr>\n";
		print "		<td class='cd_celda_texto' width='16%'>Zona</td>";
		print "		<td class='cd_celda_texto' width='16%'>Localidad</td>";
		print "		<td class='cd_celda_texto' width='16%'>Tipo de Prop.</td>";
		print "		<td class='cd_celda_texto' width='16%'>Operacion</td>";
		print "</tr>\n";

		if (isset($_POST['fid_zona']) && $_POST['fid_zona'] != 0) {
		$aux_zona = $_POST['fid_zona'];
		} else {
		$aux_zona = 0;
		}
		if (isset($_POST['fid_loca']) && $_POST['fid_loca'] != 0) {
		$aux_loca = $_POST['fid_loca'];
		} else {
		$aux_loca = 0;
		}
		if (isset($_POST['fid_tipo_prop']) && $_POST['fid_tipo_prop'] != 0) {
		$aux_prop = $_POST['fid_tipo_prop'];
		} else {
		$aux_prop = 0;
		}
		if (isset($_POST['foperacion']) && $_POST['foperacion'] != 'Todas') {
		$aux_operacion = $_POST['foperacion'];
		} else {
		$aux_operacion = 'Todas';
		}

		if (isset($_POST['orden']) && $_POST['orden'] != 0) {
		$aux_orden = 1;
		} else {
		$aux_orden = 0;
		}
		// orden x codigo,zona, loca, calle, nro,piso,tipoprop,suptot,supcub,cantdorm,valalq,valven

		$img1 = $img2 = $img3 = $img4 = $img5 = $img6 = $img7 = $img8 = $img9 = "spacer.gif";

		if (isset($_POST['campo']) && is_numeric($_POST['campo'])) {
		$campolocal = $_POST['campo'];
		switch ($campolocal) {
		case 1:
		$aux_campo = 'id_prop';
		if ($_POST['orden'] == 1) {
		$img1 = "up.png";
		} else {
		$img1 = "down.png";
		}
		break;
		case 2:
		$aux_campo = 'id_zona';
		if ($_POST['orden'] == 1) {
		$img2 = "up.png";
		} else {
		$img2 = "down.png";
		}
		break;
		case 3:
		$aux_campo = 'id_loca';
		if ($_POST['orden'] == 1) {
		$img3 = "up.png";
		} else {
		$img3 = "down.png";
		}
		break;
		case 4:
		$aux_campo = 'calle';
		if ($_POST['orden'] == 1) {
		$img4 = "up.png";
		} else {
		$img4 = "down.png";
		}
		break;
		case 5:
		$aux_campo = 'id_tipo_prop';
		if ($_POST['orden'] == 1) {
		$img5 = "up.png";
		} else {
		$img5 = "down.png";
		}
		break;
		case 6:
		$aux_campo = 'CAST(suptot AS DECIMAL)';
		if ($_POST['orden'] == 1) {
		$img6 = "up.png";
		} else {
		$img6 = "down.png";
		}
		break;
		case 7:
		$aux_campo = 'CAST(cantamb AS DECIMAL)';
		if ($_POST['orden'] == 1) {
		$img7 = "up.png";
		} else {
		$img7 = "down.png";
		}
		break;
		case 8:
		$aux_campo = 'CAST(valalq AS DECIMAL)';
		if ($_POST['orden'] == 1) {
		$img8 = "up.png";
		} else {
		$img8 = "down.png";
		}
		break;
		case 9:
		$aux_campo = 'CAST(valven AS DECIMAL)';
		if ($_POST['orden'] == 1) {
		$img9 = "up.png";
		} else {
		$img9 = "down.png";
		}
		break;
		default:
		break;
		}
		} else {
		$aux_campo = '';
		}


		print "<tr>\n";

		print "	<td>";
		$zona1BSN = new ZonaBSN ();
		$zona1BSN->comboZona($aux_zona, $aux_loca, 1, 'aux_zona', 'aux_loca');
		print "</td>\n";

		print "<td>";
		print "<div id='localidad'>";
		$loca = new Localidad();
		$loca->setId_loca($aux_loca);
		$loca->setId_zona($aux_zona);
		$locaBSN = new LocalidadBSN($loca);
		$locaBSN->comboLocalidad($aux_loca, $aux_zona, 1, 'aux_loca');
		print "</div>";
		print "</td>\n";

		print "<td>";
		$tipo_propBSN->comboTipoProp($aux_prop, 3, 'aux_prop', 'campos', 'filtro');
		print "</td>";

		print "<td>";
		armaTipoOperacion($aux_operacion, 1, 'aux_operacion', 0);
		print "</td>\n";

		print "</tr>\n";

		print "<tr id='filtro' name='filtro'><td colspan='4'>";
		//		echo "prop -> $aux_prop <br>";
		$dpropVW = new DatospropVW();
		$arraydp = array();
		$arrayDB = array();

		if (isset($_POST['filtro']) && $_POST['filtro'] != 0) {
		$arraydp = $dpropVW->leeFiltroDatospropVW();  // Lee los valores desde el filtro del formulario
		}

		$dpropVW->cargaFiltroDatosprop($arraydp, $aux_prop); // Carga nuevamente el filtro con los valores leidos
		$dpropBSN = new DatospropBSN();
		$arrayDB = $arraydp;
		$propsIN = $dpropBSN->armaArrayIN($arrayDB);  // armo un string con los ID de las propiedades que cumplen con los filtros de caracteristicas
		print "</td></tr>";

		print "<tr>\n";
		print " <td colspan='2'></td>\n";
		print "		<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:filtra();' value='Enviar'></td>\n";
		print "		<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></td>\n";
		print "</tr>\n";
		print "</table>\n";


		// Fin Filtro

		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='orden_tab' colspan='2'>&nbsp;</td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(1);'>C&oacute;digo<img src='images/" . $img1 . "' id='img_1' border='0'></a></td>\n";
		//print "     <td class='orden_tab'>Sucursal</td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(2);'><img src='images/" . $img2 . "' id='img_2' border='0'>&nbsp;Zona</a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(3);'><img src='images/" . $img3 . "' id='img_3' border='0'>&nbsp;Localidad</a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(4);'><img src='images/" . $img4 . "' id='img_4' border='0'>&nbsp;Calle</a></td>\n";
		print "     <td class='orden_tab'>Nro</td>\n";
		print "     <td class='orden_tab'>Piso</td>\n";
		//print "     <td class='orden_tab'>Dpto</td>\n";
		//print "     <td class='cd_listaorden_tab_titulo'>Edificio</td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(5);'><img src='images/" . $img5 . "' id='img_5' border='0'>&nbsp;Tipo</a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(6);'><img src='images/" . $img6 . "' id='img_6' border='0'>&nbsp;Sup.Total</a></td>\n";
		//        print "     <td class='orden_tab'>Sup.Cubierta</td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(7);'><img src='images/" . $img7 . "' id='img_7' border='0'>&nbsp;Dorms.</a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(8);'><img src='images/" . $img8 . "' id='img_8' border='0'>&nbsp;Val. Alquiler</a></td>\n";
		print "     <td class='orden_tab'><a href='javascript:ordenar(9);'><img src='images/" . $img9 . "' id='img_9' border='0'>&nbsp;Val. Venta</a></td>\n";
		print "	  </tr>\n";

		$evenBSN = new PropiedadBSN();
		//		$arrayEven=$evenBSN->cargaColeccionForm();
		$arrayEven = $evenBSN->cargaColeccionFiltroBuscador($aux_zona, $aux_loca, $aux_prop, $aux_operacion, 0, $propsIN, $pagina, $aux_campo, $aux_orden);

		$cantreg = $evenBSN->cantidadRegistrosFiltroBuscador($aux_zona, $aux_loca, $aux_prop, $aux_operacion, 0, $propsIN);
		//		$paginas=intval($cantreg/$registros)+1;
		$cantr = $cantreg / $registros;
		$cante = $cantreg % $registros;

		if ($cante != 0) {
		$paginas = intval($cantr + 1);
		} else {
		$paginas = $cantr;
		}

		if (sizeof($arrayEven) == 0) {
		print "No existen datos para mostrar";
		} else {
		foreach ($arrayEven as $Even) {
		if ($fila == 0) {
		$fila = 1;
		} else {
		$fila = 0;
		}

		print "<tr>\n";
		print "	 <td colspan='2' class='row" . $fila . "'>";
		//                print "    <a href='muestra_datosprop.php?i=".$Even['id_prop']."&keepThis=true&TB_iframe=true&height=600&width=800' title='Datos de la propiedad' class='thickbox'>";
		//                print "       <img src='images/magnifier.png' alt='Editar' title='Editar' border='0'></a>";

		print "<input type='radio' name='id_prop' id='id_prop' value='" . $Even['id_prop'] . "' onclick='javascript:marcar(" . $Even['id_prop'] . ");'>";
		print "</td>";


		print "  <td class='row" . $fila . "'>";
		print $Even['id_sucursal'] . str_repeat("0", 6 - strlen(strval($Even['id_prop']))) . $Even['id_prop'];
		print "</td>\n";

		//print "  <td class='row".$fila."'>";
		//print $sucursal->nombreSucursal($Even['id_sucursal']);
		//print "</td>\n";

		print "	 <td class='row" . $fila . "'>";
		$zonaBSN->setId($Even ['id_zona']);
		$zonaBSN->cargaById($Even['id_zona']);
		print $zonaBSN->getObjeto()->getNombre_zona();
		print "</td>\n";
		print "	 <td class='row" . $fila . "'>";
		$localaBSN->setId($Even['id_loca']);
		$localaBSN->cargaById($Even['id_loca']);
		print $localaBSN->getObjeto()->getNombre_loca();
		print "</td>\n";
		print "	 <td class='row" . $fila . "'>" . $Even['calle'] . "</td>\n";
		print "	 <td class='row" . $fila . "' align='center'>" . $Even['nro'] . "</td>\n";
		print "	 <td class='row" . $fila . "' align='center'>" . $Even['piso'] . "</td>\n";
		//print "	 <td class='row".$fila."'>".$Even['dpto']."</td>\n";
		//print "	 <td class='row".$fila."'>".$Even['nomedif']."</td>\n";
		print "	 <td class='row" . $fila . "'>";
		$tipopropBSN->setId($Even['id_tipo_prop']);
		$tipopropBSN->cargaById($Even['id_tipo_prop']);
		//                print $tipopropBSN->getObjeto()->getTipo_prop();
		//                if ($Even['subtipo_prop']!='') {
		//                    print "(".$Even['subtipo_prop'].")";
		//                }
		switch ($Even['id_tipo_prop']) {
		case 1:
		print "<img src='images/tipo_depto.png' border='0' title='" . $tipopropBSN->getObjeto()->getTipo_prop() . "' />";
		break;
		case 2:
		print "<img src='images/tipo_local.png' border='0' title='" . $tipopropBSN->getObjeto()->getTipo_prop() . "' />";
		break;
		case 3:
		print "<img src='images/tipo_ph.png' border='0' title='" . $tipopropBSN->getObjeto()->getTipo_prop() . "' />";
		break;
		case 6:
		print "<img src='images/tipo_terreno.png' border='0' title='" . $tipopropBSN->getObjeto()->getTipo_prop() . "' />";
		break;
		case 7:
		print "<img src='images/tipo_terreno.png' border='0' title='" . $tipopropBSN->getObjeto()->getTipo_prop() . "' />";
		break;
		case 9:
		print "<img src='images/tipo_casa.png' border='0' title='" . $tipopropBSN->getObjeto()->getTipo_prop() . "' />";
		break;
		case 11:
		print "<img src='images/tipo_oficina.png' border='0' title='" . $tipopropBSN->getObjeto()->getTipo_prop() . "' />";
		break;
		case 15:
		print "<img src='images/tipo_galpon.png' border='0' title='" . $tipopropBSN->getObjeto()->getTipo_prop() . "' />";
		break;
		}
		print "	 <td class='row" . $fila . "' align='right'>" . number_format($Even['suptot'], 2, ',', '.') . "</td>\n";
		//                print "	 <td class='row".$fila."' align='right'>".number_format($Even['supcub'], 2, ',','.')."</td>\n";
		print "	 <td class='row" . $fila . "' align='center'>" . $Even['cantamb'] . "</td>\n";
		print "	 <td class='row" . $fila . "' align='right'>" . number_format($Even['valalq'], 0, ',', '.') . "</td>\n";
		print "	 <td class='row" . $fila . "' align='right'>" . number_format($Even['valven'], 0, ',', '.') . "</td>\n";

		print "</td>\n";
		print "	</tr>\n";
		}
		//------Paginado--------------------
		print "<tr><td align='center' colspan='19' style='padding-top:10px;'>";
		if ($pagina > 1) {
		print "    <a href='javascript:paginar(1);'>";
		print "       <img src='images/resultset_first.png' alt='Inicio' title='Inicio' border='0' style='vertical-align: middle;'></a>&nbsp;";
		if ($pagina > 2) {
		print "    <a href='javascript:paginar(" . ($pagina - 1) . ");'>";
		print "       <img src='images/resultset_previous.png' alt='Anterior' title='Anterior' border='0' style='vertical-align: middle;'></a>&nbsp;-&nbsp;";
		}
		for ($x = $pagina - 5; $x < $pagina; $x++) {
		if ($x > 0) {
		print "<a href='javascript:paginar(" . $x . ");'>$x</a>&nbsp;-&nbsp;";
		}
		}
		}
		print "<span style='font-weight: bold; color:#FFF; background-color:#99BBEA;'>&nbsp;$pagina&nbsp;</span>-&nbsp;";
		if ($pagina < $paginas) {
		for ($x = $pagina + 1; $x < $pagina + 5; $x++) {
		if ($x <= $paginas) {
		print "<a href='javascript:paginar(" . $x . ");'>" . $x . "</a>&nbsp;-&nbsp;";
		}
		}
		if ($pagina < $paginas - 1) {
		print "    <a href='javascript:paginar(" . ($pagina + 1) . ");'>";
		print "       <img src='images/resultset_next.png' alt='Siguiente' title='Siguiente' border='0' style='vertical-align: middle;'></a>&nbsp;";
		}
		print "    <a href='javascript:paginar($paginas);'>";
		print "       <img src='images/resultset_last.png' alt='Ultima' title='Ultima' border='0' style='vertical-align: middle;'></a>&nbsp;";
		}
		print "</td></tr>";
		}

		print "  </table>\n";

		print "<input type='hidden' name='id_prop' id='id_prop' value=''>";
		print "<input type='hidden' name='aux_id_prop' id='aux_id_prop' value=''>";
		print "<input type='hidden' name='fid_tipo_prop' id='fid_tipo_prop' value=''>";
		print "<input type='hidden' name='fid_zona' id='fid_zona' value=''>";
		print "<input type='hidden' name='fid_loca' id='fid_loca' value=''>";
		print "<input type='hidden' name='foperacion' id='foperacion' value=''>";
		print "<input type='hidden' name='filtro' id='filtro' value='0'>";
		print "<input type='hidden' name='campo' id='campo' value='$campolocal'>";
		print "<input type='hidden' name='orden' id='orden' value='$aux_orden'>";

		print "<input type='hidden' name='pagina' id='pagina' value='$pagina'>";

		print "</form>";
		}
		*/

	public function grabaModificacion() {
		$retorno = false;
		$propiedad = new PropiedadBSN($this->propiedad);
		$retUPre = $propiedad->actualizaDB();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaPropiedad() {
		$retorno = false;
		$propiedad = new PropiedadBSN($this->propiedad);
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
		$existente = $propiedad->controlDuplicado(); //$this->propiedad->getPropiedad());
		if ($existente) {
			echo "Ya existe un propiedad con esos datos de ubicaci�n verifique los mismos.";
		} else {
			$retIPre = $propiedad->insertaDB();
			//			die();
			if ($retIPre) {
				$this->propiedad->setId_prop($propiedad->buscaID());
				echo "Se proceso la grabacion en forma correcta<br>";
				$retorno = true;
			} else {
				echo "Fallo la grabación de los datos<br>";
			}
		} // Fin control de Duplicados
		return $retorno;
	}

	public function muestraDomicilio() {
		$local = new Localidad();
		$localaBSN = new LocalidadBSN();

		$localaBSN->setId($this->arrayForm['id_loca']);
		$localaBSN->cargaById($this->arrayForm['id_loca']);

		print "<span class='pg_titulo'>Propiedad:" . $this->arrayForm['calle'] . " " . $this->arrayForm['nro'] . " " . $this->arrayForm['piso'] . " " . $this->arrayForm['dpto'] . " - ";
		print $localaBSN->getObjeto()->getNombre_loca();
		print "</span><br><br>\n";
	}

}

// fin clase
?>
