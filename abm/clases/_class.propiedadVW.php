<?php

include_once ('generic_class/class.VW.php');
include_once("generic_class/class.menu.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedad.php");
include_once("clases/class.tipo_propBSN.php");
include_once ("clases/class.ubicacionpropiedadBSN.php");
include_once ("clases/class.emprendimientoBSN.php");
include_once("inc/funciones.inc");
include_once("inc/funciones_gmap.inc");
include_once("clases/class.sucursal.php");
include_once("clases/class.datospropVW.php");
include_once('generic_class/class.upload.php');
include_once("generic_class/class.cargaConfiguracion.php");
include_once("clases/class.perfilesBSN.php");
include_once("clases/class.grupo_tipoprop.php");
include_once ("clases/class.contactoBSN.php");
include_once ("clases/class.tasacionBSN.php");
include_once ("clases/class.casoexito.php");
include_once ("clases/class.propiedadcontratoBSN.php");
include_once ("clases/class.crmbuscadorBSN.php");

class PropiedadVW extends VW {

    protected $clase = "Propiedad";
    protected $propiedad;
    protected $nombreId = "Id_prop";
    //	private $arrayForm;
    private $path;
    private $pathC;
    private $anchoG;
    private $anchoC;

    public function __construct($_propiedad = 0) {
        //		PropiedadVW::creaPropiedad();
        PropiedadVW::creaObjeto();
        if ($_propiedad instanceof Propiedad) {
            PropiedadVW::seteaVW($_propiedad);
        }
        if (is_numeric($_propiedad)) {
//            if ($_propiedad != 0) {
            //				PropiedadVW::cargaPropiedad($_propiedad);
            PropiedadVW::cargaVW($_propiedad);
//            }
        }
        PropiedadVW::cargaDefinicionForm();
        $conf = CargaConfiguracion::getInstance();
        $this->path = $conf->leeParametro('path_fotos');
        $this->pathC = $conf->leeParametro('path_fotos_chicas');
        $this->anchoC = $conf->leeParametro('ancho_foto_chica');
        $this->anchoG = $conf->leeParametro('ancho_foto_grande');
    }

    public function getIdTipoProp() {
        return $this->propiedad->getId_tipo_prop();
    }

    public function getOperacion() {
        return $this->propiedad->getOperacion();
    }

    public function cargaDatosPropiedad($id_cli = 0) {
        $ubiBSN = new UbicacionpropiedadBSN();
        //		$zonaBSN = new ZonaBSN ();
        //		$locaBSN = new LocalidadBSN();
        $tipo_propBSN = new Tipo_propBSN();
        $sucursal = new Sucursal();
        $perf = new PerfilesBSN();

        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

        $gtp = new Grupo_tipoprop();
        if ($perfGpo == 'SUPERUSER' || strtoupper($perfGpo) == 'LECTURA' || $perfGpo == 'admin' || $perfGpo == 'GRAL' || strtoupper($perfGpo) == 'STAFF' || strtoupper($perfGpo) == 'GERENCIA') {
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

        //$menu = new Menu();
        //$menu->dibujaMenu('carga');

        print "<form action='carga_propiedad.php' name='carga' id='carga' enctype='multipart/form-data' method='post' onSubmit='javascript: return ValidaPropiedad(this);'>\n";

        print "<table width='100%' align='center' bgcolor='#f1f1f1'>\n";
        if ($this->arrayForm['id_prop'] != 0) {
            $titulo = "Edici&oacute;n de la  Propiedad: " . $this->arrayForm['id_prop'];
        } else {
            $titulo = "Carga de Propiedades";
        }
        print "<tr><td class='pg_titulo'>$titulo</td></tr>\n";

        print "<tr><td align='center'>";


        if ((($listaTPG == 0 || strpos($listaTPG, $this->arrayForm['id_tipo_prop']) !== false) && ($perfSuc == $this->arrayForm['id_sucursal'] || $perfSuc == 'Todas')) || $this->arrayForm['id_prop'] == '' || $this->arrayForm['id_prop'] == 0) {

            print "<table width='100%' align='center'>\n";

            print "<tr><td class='cd_celda_texto'>Sucursal<span class='obligatorio'>&nbsp;&bull;</span><br />";
            $sucursal->comboSucursal($this->arrayForm['id_sucursal']);
            print "</td>\n";

            print "<td class='cd_celda_texto'>Zona<span class='obligatorio'>&nbsp;&bull;</span><br />";
            if ($this->arrayForm['id_ubica'] == '') {
                $this->arrayForm['id_ubica'] = 0;
                $textoUbica = 'Seleccione una Zona';
            } else {
                $textoUbica = $ubiBSN->armaNombreZona($this->arrayForm['id_ubica']);
            }
            $id_padre = $ubiBSN->definePrincipalByHijo($this->arrayForm['id_ubica']);
            $ubiBSN->comboUbicacionpropiedadPrincipal($id_padre, 0);
            print "</td>\n";
            print "<td><input type='hidden' id='id_ubica' name='id_ubica' value='" . $this->arrayForm['id_ubica'] . "'>";
            print "<input type='button' value='Despliegue Subzonas' onclick=\"window.open('seleccionaZona.php?v='+document.getElementById('id_ubica').value+'&z='+document.getElementById('id_ubicaPrincipal').value+'&t=r', 'ventanaDom', 'menubar=1,resizable=1,width=700,height=550');\">";
            print "<div id='txtUbica'>$textoUbica</div>";
            print "</td>\n";
            print "</tr>\n";

            print "<tr><td class='cd_celda_texto' colspan='3'>Publica " . $this->arrayForm['id_prop'] . "Direccion en Web<br />\n";
            print "<input type='checkbox' name='publicadir' id='publicadir' ";
            if ($this->arrayForm['publicadir'] == 0 && $this->arrayForm['id_prop'] != '') {
                print ">";
            } else {
                print " checked >";
            }
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
            $empre->setId_ubica($this->arrayForm['id_ubica']);
            $empBSN = new EmprendimientoBSN($empre);
            $empBSN->comboEmprendimiento($this->arrayForm['id_emp'], 3, $this->arrayForm['id_ubica']);
            print "</div>";
            print "</td>\n";
            print "<td class='cd_celda_texto' colspan='2'> Descripcion <br />";
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
            //			print "$verMax;' class='cd_celda_texto' colspan='2'>Inmobiliaria<br />";
            print "$verMax;' class='cd_celda_texto' >Inmobiliaria<br />";
            armaInmo($this->arrayForm['id_inmo']);
            print "</td>\n";
            print "</tr>\n";

            print "<tr>\n";
            print "<td class='cd_celda_texto'>Publica Precio en WEB<br />\n";
            print "<input type='checkbox' name='publicaprecio' id='publicaprecio' ";
            if ($this->arrayForm['publicaprecio'] == 1) {
                print "checked>";
            } else {
                print ">";
            }
            print "</td>\n";

            print "<td class='cd_celda_texto'>Destacada<br />\n";
            print "<input type='checkbox' name='destacado' id='destacado' ";
            if ($this->arrayForm['destacado'] == 1) {
                print "checked>";
            } else {
                print ">";
            }
            print "</td>\n";

            print "<td class='cd_celda_texto'>Oportunidad<br />\n";
            print "<input type='checkbox' name='oportunidad' id='oportunidad' ";
            if ($this->arrayForm['oportunidad'] == 1) {
                print "checked>";
            } else {
                print ">";
            }
            print "</td>\n";

            print "</tr>\n";

            print "<tr><td colspan='4' class='separador'><hr /></td></tr>";

            print "<tr><td class='cd_celda_texto'>Plano 1<br />";
            print "<input type='hidden' name='plano1' id='plano1' value='" . $this->arrayForm['plano1'] . "'>" . $this->arrayForm['plano1'] . " <input type='checkbox' name='bplano1' id='bplano1' > Marque la casilla para eliminar el Plano";
            print "<input class='campos' type='file' name='hplano1' id='hplano1' maxlength='250' size='28' ></td>\n";
            print "<td class='cd_celda_texto'>Plano 2<br />";
            print "<input type='hidden' name='plano2' id='plano2' value='" . $this->arrayForm['plano2'] . "'>" . $this->arrayForm['plano2'] . " <input type='checkbox' name='bplano2' id='bplano2' > Marque la casilla para eliminar el Plano";
            print "<input class='campos' type='file' name='hplano2' id='hplano2' maxlength='250' size='28' ></td>\n";
            print "<td class='cd_celda_texto'>Plano3<br />";
            print "<input type='hidden' name='plano3' id='plano3' value='" . $this->arrayForm['plano2'] . "'>" . $this->arrayForm['plano3'] . " <input type='checkbox' name='bplano3' id='bplano3' > Marque la casilla para eliminar el Plano";
            print "<input class='campos' type='file' name='hplano3' id='hplano3' maxlength='250' size='28'></td>\n";
            print "</tr>\n";
            print "<tr>\n";
            print "<td class='cd_celda_texto' colspan='2'>Video<br />";
            print "<input class='campos' type='text' name='video' id='video' value='" . $this->arrayForm['video'] . "' maxlength='250' size='80'></td>\n";

            print "<td class='cd_celda_texto'><br /><input type='button' class='campos_btn' value=\"Muestra mapa de ubicacion\" id='ver' name='ver' onclick='javascript: popupMapa(\"p\");' style='cursor:pointer;'></td>\n";
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
        print "<input type='hidden' name='opcion' id='opcion' value='' />";

        print "</td></tr>\n</table>\n";
        print "</form>\n";
    }

    public function cargaDatosPropiedadDiv($id_cli = 0, $div = 'propiedad') {
        $ubiBSN = new UbicacionpropiedadBSN();
        $tipo_propBSN = new Tipo_propBSN();
        $contactoBSN = new ContactoBSN();
        $sucursal = new Sucursal();
        $perf = new PerfilesBSN();

        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

        $gtp = new Grupo_tipoprop();
        if ($perfGpo == 'SUPERUSER' || strtoupper($perfGpo) == 'LECTURA' || $perfGpo == 'admin' || $perfGpo == 'GRAL' || strtoupper($perfGpo) == 'STAFF' || strtoupper($perfGpo) == 'GERENCIA') {
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
        print "function validaTipo(){\n";
        print "     if(document.forms.carga.id_tipo_prop.value==7){;\n";
        print "			activa('trtipo');\n";
        print "		}else{\n";
        print "			desactiva('trtipo');\n";
        print "		}\n";
        print "}\n";
        print "function activa(campo){\n";
        print "     document.getElementById(campo).style.display='table-row';\n";
        print "}\n";
        print "function desactiva(campo){\n";
        print "     document.getElementById(campo).style.display='none';\n";
        print "}\n";
        print "function submitForm(){\n";
        print "    window.open('','ventanaProp','width=300,height=200');\n";
        print "}\n";

        print "$(document).ready(function(){\n";
        print "    $(\"#calle\").autocomplete(\"autocompletar.php?arg=2\");\n";
        print "});\n";

        print "</script>\n";

        mapaGmap();

        print "<div id='$div' name='$div'>\n";

        print "<table width='100%' align='center' bgcolor='#f1f1f1'>\n";
        if ($this->arrayForm['id_prop'] != 0) {
            $titulo = "Edición de datos de la Propiedad: " . $this->arrayForm['id_prop'];
        } else {
            $titulo = "Carga de datos de Propiedades";
        }
        print "<tr><td class='pg_titulo'>$titulo</td></tr>\n";

        print "<tr><td align='center'>";


        if ((($listaTPG == 0 || strpos($listaTPG, $this->arrayForm['id_tipo_prop']) !== false) && ($perfSuc == $this->arrayForm['id_sucursal'] || $perfSuc == 'Todas')) || $this->arrayForm['id_prop'] == '' || $this->arrayForm['id_prop'] == 0) {

            print "<table width='100%' align='center'>\n";

            if ($this->arrayForm['id_prop'] == 0) {
                print "<tr><td class='cd_celda_texto'>Operacion<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
                armaTipoOperacion($this->arrayForm['operacion'], 2);
                print "</td>\n";
            } else {
                print "<tr><td class='cd_celda_texto'>Operacion<span class='obligatorio'>&nbsp;&bull;</span><br /><span class='campos'>" . $this->arrayForm['operacion'] . "</span>\n";

                print "<input type='hidden' name='operacion' id='operacion' value='" . $this->arrayForm['operacion'] . "'>\n";
                print "<input type='hidden' name='comentario' id='comentario' value='" . $this->arrayForm['comentario'] . "'>\n";
            }


            print "<td class='cd_celda_texto'>Tipo de Propiedad<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
            $tipo_propBSN->comboTipoProp($this->arrayForm['id_tipo_prop'], 2, 'id_tipo_prop', 'campos_btn', 'subtipo', $this->arrayForm['subtipo_prop'], 'datosprop');
            print "</td>\n";
            print "<td class='cd_celda_texto'>Subtipo Propiedad<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
            print "<div id='subtipo'>";
            $tipo_propBSN->comboSubtipoProp($this->arrayForm['subtipo_prop'], $this->arrayForm['id_tipo_prop'], 2);
            print "</div>";
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

            print "<tr>\n";
            print "<td><input type='checkbox' name='activa' id='activa' ";
            if ($this->arrayForm['activa'] == 1 /* || !isset($this->arrayForm['id_prop']) */) {
                print "checked>";
            } else {
                print ">";
            }
            print "  <span  class='cd_celda_texto' >Publica Propiedad en Web</span></td>\n";
            print "<td>\n";
            print "<input type='checkbox' name='publicadir' id='publicadir' ";
            if ($this->arrayForm['publicadir'] == 1 /* || !isset($this->arrayForm['id_prop']) */) {
                print "checked>";
            } else {
                print ">";
            }
            print "  <span class='cd_celda_texto' >Publica Direccion en Web</span></td>\n";
            print "<td>\n";
            print "<input type='checkbox' name='compartir' id='compartir' ";
            if ($this->arrayForm['compartir'] == 1) {
                print "checked>";
            } else {
                if ($this->arrayForm['id_ubica'] == 0) {
                    print "checked>\n";
                } else {
                    print ">";
                }
            }
            print "  <span class='cd_celda_texto'>Compartir</span></td>\n";
            print "</tr>\n";

            //print "<tr><td colspan='3' class='separador'><hr /></td></tr>";

            print "<tr>\n";
            print "<td>\n";
            print "<input type='checkbox' name='publicaprecio' id='publicaprecio' ";
            if ($this->arrayForm['publicaprecio'] == 1) {
                print "checked>";
            } else {
                print ">";
            }
            print " <span class='cd_celda_texto'>Publica Precio en WEB</span></td>\n";

            print "<td>\n";
            print "<input type='checkbox' name='destacado' id='destacado' ";
            if ($this->arrayForm['destacado'] == 1) {
                print "checked>";
            } else {
                print ">";
            }
            print "<span class='cd_celda_texto'>Destacada</span></td>\n";

            print "<td>\n";
            print "<input type='checkbox' name='oportunidad' id='oportunidad' ";
            if ($this->arrayForm['oportunidad'] == 1) {
                print "checked>";
            } else {
                print ">";
            }
            print " <span class='cd_celda_texto'>Oportunidad</span></td>\n";
            print "</tr>\n";

            print "<tr><td colspan='3' class='separador'><hr /></td></tr>";

            print "<tr><td class='cd_celda_texto'>Sucursal<span class='obligatorio'>&nbsp;&bull;</span><br />";
            $sucursal->comboSucursal($this->arrayForm['id_sucursal']);
            print "</td>\n";

            print "<td class='cd_celda_texto'>Zona<span class='obligatorio'>&nbsp;&bull;</span><br />";
            if ($this->arrayForm['id_ubica'] == '') {
                $this->arrayForm['id_ubica'] = 0;
                $textoUbica = 'Seleccione una Zona';
            } else {
                $textoUbica = $ubiBSN->armaNombreZonaGMap($this->arrayForm['id_ubica']);
            }
            $id_padre = $ubiBSN->definePrincipalByHijo($this->arrayForm['id_ubica']);
            $ubiBSN->comboUbicacionpropiedadPrincipal($id_padre, 0);
            print "</td>\n";
            if ($this->arrayForm['id_emp'] == '') {
                $this->arrayForm['id_emp'] = 0;
            }
            print "<td><div id='txtUbica'>$textoUbica</div>\n";
            print "<input type='hidden' id='id_ubica' name='id_ubica' value='" . $this->arrayForm['id_ubica'] . "'>";
            print "<input type='button' value='Despliegue Subzonas' onclick=\"window.open('seleccionaZona.php?v='+document.getElementById('id_ubica').value+'&z='+document.getElementById('id_ubicaPrincipal').value+'&t=r&cemp=emprendimiento&emp=" . $this->arrayForm['id_emp'] . "', 'ventanaDom', 'menubar=1,resizable=1,width=700,height=550');\">";

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

            /*            print "<tr><td class='cd_celda_texto'>Nombre de Edificio <br />";
              print "<input class='campos' type='text' name='nomedif' id='nomedif' value='" . $this->arrayForm['nomedif'] . "' maxlength='250' size='80'></td>\n";
              print "<td class='cd_celda_texto'>Tipo de Propiedad<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
              $tipo_propBSN->comboTipoProp($this->arrayForm['id_tipo_prop'], 2, 'id_tipo_prop', 'campos_btn', 'subtipo', $this->arrayForm['subtipo_prop'], 'datosprop');
              print "</td>\n";
              print "<td class='cd_celda_texto'>Subtipo Propiedad<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
              print "<div id='subtipo'>";
              $tipo_propBSN->comboSubtipoProp($this->arrayForm['subtipo_prop'], $this->arrayForm['id_tipo_prop'], 2);
              print "</div>";
              print "</td>\n";
              print "</tr>\n";
             */
            print "<tr>\n";
            print "<td class='cd_celda_texto'>Nombre de Edificio <br />";
            print "<input class='campos' type='text' name='nomedif' id='nomedif' value='" . $this->arrayForm['nomedif'] . "' maxlength='250' size='80'></td>\n";
            print "<td class='cd_celda_texto'>Emprendimiento<span class='obligatorio'>&nbsp;&bull;</span><br />";
            print "<div id='emprendimiento'>";
            $empre = new Emprendimiento();
            $empre->setId_ubica($this->arrayForm['id_ubica']);
            $empBSN = new EmprendimientoBSN($empre);
            $empBSN->comboEmprendimiento($this->arrayForm['id_emp'], 3, $this->arrayForm['id_ubica']);
            print "</div>";
            print "</td>\n";
            print "<td class='cd_celda_texto'> Descripcion <br />";
            print "<input class='campos' type='text' name='descripcion' id='descripcion' value='" . $this->arrayForm['descripcion'] . "' maxlength='250' size='80'></td>\n";
            print "</tr>\n";

            /*            if ($this->arrayForm['id_prop'] == 0) {
              print "<tr><td class='cd_celda_texto' colspan='2'>Operacion<span class='obligatorio'>&nbsp;&bull;</span><br />\n";
              armaTipoOperacion($this->arrayForm['operacion'], 2);
              print "</td>\n";
              print "<td class='cd_celda_texto'>Compartir<br />\n";
              print "<input type='checkbox' name='compartir' id='compartir' ";
              if ($this->arrayForm['compartir'] == 1) {
              print "checked>";
              } else {
              if($this->arrayForm['id_ubica'] == 0){
              print "checked>\n";
              }else{
              print ">";
              }
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
             */

            print "<tr id='trtipo' style='display:";
            if ($this->arrayForm['id_tipo_prop'] == 7) {
                $verMax = 'table-row';
            } else {
                $verMax = 'none';
            }
            print "$verMax' width='100%'>";

            print "<td class='cd_celda_texto'>ID Parcela<br />";
            print "<input class='campos' type='text' name='id_parcela' id='id_parcela' value='" . $this->arrayForm['id_parcela'] . "' maxlength='250' size='80'></td>\n";
            print "<td class='cd_celda_texto'>ID Comercial <br />";
            print "<input class='campos' type='text' name='id_comercial' id='id_comercial' value='" . $this->arrayForm['id_comercial'] . "' maxlength='250' size='80'></td>\n";
            print "<td></td>\n";
            print "</tr>\n";

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
            //			print "$verMax;' class='cd_celda_texto' colspan='2'>Inmobiliaria<br />";
            print "$verMax;' class='cd_celda_texto' >Inmobiliaria<br />";
            $contactoBSN->comboContactoInmobiliaria($this->arrayForm['id_inmo']);
            print "</td>\n";
            print "</tr>\n";

            print "<tr><td colspan='4' class='separador'><hr /></td></tr>";

            print "<tr><td class='cd_celda_texto'>Plano 1<br />";
            print "<input type='hidden' name='plano1' id='plano1' value='" . $this->arrayForm['plano1'] . "'>" . $this->arrayForm['plano1'] . " <input type='checkbox' name='bplano1' id='bplano1' > Marque la casilla para eliminar el Plano";
            print "<input class='campos' type='file' name='hplano1' id='hplano1' maxlength='250' size='28' ></td>\n";
            print "<td class='cd_celda_texto'>Plano 2<br />";
            print "<input type='hidden' name='plano2' id='plano2' value='" . $this->arrayForm['plano2'] . "'>" . $this->arrayForm['plano2'] . " <input type='checkbox' name='bplano2' id='bplano2' > Marque la casilla para eliminar el Plano";
            print "<input class='campos' type='file' name='hplano2' id='hplano2' maxlength='250' size='28' ></td>\n";
            print "<td class='cd_celda_texto'>Plano3<br />";
            print "<input type='hidden' name='plano3' id='plano3' value='" . $this->arrayForm['plano2'] . "'>" . $this->arrayForm['plano3'] . " <input type='checkbox' name='bplano3' id='bplano3' > Marque la casilla para eliminar el Plano";
            print "<input class='campos' type='file' name='hplano3' id='hplano3' maxlength='250' size='28'></td>\n";
            print "</tr>\n";
            print "<tr>\n";
            print "<td class='cd_celda_texto' colspan='2'>Video<br />";
            print "<input class='campos' type='text' name='video' id='video' value='" . $this->arrayForm['video'] . "' maxlength='250' size='80'></td>\n";

            print "<td class='cd_celda_texto'><br /><input type='button' class='campos_btn' value=\"Muestra mapa de ubicacion\" id='ver' name='ver' onclick='javascript: popupMapa(\"p\");' style='cursor:pointer;' /></td>\n";
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
        print "<input type='hidden' name='opcion' id='opcion' value='' />";

        print "</td></tr>\n</table>\n";
        print "</div>\n";
    }

    public function cargaDatosClonacion($rep = 1, $actual = 0) {
        //		$menu = new Menu();
        //		$menu->dibujaMenu('carga');

        print "<form action='clona_Propiedad.php' name='carga' id='carga' enctype='multipart/form-data' method='post' onSubmit='javascript: return ValidaPropiedad(this);'>\n";
        //		print "<form action='clona_Propiedad.php' name='carga' id='carga' enctype='multipart/form-data' method='post'>\n";
        print "<input type='hidden' name='actual' id='actual' value='" . $actual . "'>\n";
        print "<input type='hidden' name='rep' id='rep' value='" . $rep . "'>\n";
        print "<input type='hidden' name='opcion' id='opcion' value='' />";


        if ($rep == 1 && $actual == 1) {
            print "<script type='text/javascript'>\n";
            print "var answer = prompt ('Cantidad de clonaciones?','5');\n";
            print "document.getElementById('rep').value=answer;\n";
            print "if(answer==null){\n";
            print "   submitform(289);\n";
            print "}\n";
            print "</script>\n";
        }

        print "<table width='100%' align='center' bgcolor='#f1f1f1'>\n";
        $titulo = "Edición de la Propiedades Clonadas";
        print "<tr><td class='pg_titulo'>$titulo</td></tr>\n";

        print "<tr><td align='center'>";
        print "<table width='100%' align='center'>\n";

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

        if ($this->arrayForm['id_sucursal'] == "NOR") {
            print "<tr><td class='cd_celda_texto'>ID Parcela<br />";
            print "<input class='campos' type='text' name='id_parcela' id='id_parcela' value='" . $this->arrayForm['id_parcela'] . "' maxlength='250' size='80'></td>\n";
            print "<td class='cd_celda_texto'>ID Ccomercial <br />";
            print "<input class='campos' type='text' name='id_comercial' id='id_comercial' value='" . $this->arrayForm['id_comercial'] . "' maxlength='250' size='80'></td>\n";
            print "<td></td>\n";
            print "</tr>\n";
        }

        print "<br>";
        print "<tr><td colspan='4' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "<input type='hidden' name='id_ubica' id='id_ubica' value='" . $this->arrayForm['id_ubica'] . "'>\n";
        print "<input type='hidden' name='descripcion' id='descripcion' value='" . $this->arrayForm['descripcion'] . "'>\n";
        print "<input type='hidden' name='id_tipo_prop' id='id_tipo_prop' value='" . $this->arrayForm['id_tipo_prop'] . "'>\n";
        print "<input type='hidden' name='subtipo_prop' id='subtipo_prop' value='" . $this->arrayForm['subtipo_prop'] . "'>\n";
        print "<input type='hidden' name='intermediacion' id='intermediacion' value='" . $this->arrayForm['intermediacion'] . "'>\n";
        print "<input type='hidden' name='id_inmo' id='id_inmo' value='" . $this->arrayForm['id_inmo'] . "'>\n";
        print "<input type='hidden' name='operacion' id='operacion' value='" . $this->arrayForm['operacion'] . "'>\n";
        print "<input type='hidden' name='comentario' id='comentario' value='" . $this->arrayForm['comentario'] . "'>\n";
        print "<input type='hidden' name='video' id='video' value='" . $this->arrayForm['video'] . "'>\n";
        print "<input type='hidden' name='id_cliente' id='id_cliente' value='" . $this->arrayForm['id_cliente'] . "'>\n";
        print "<input type='hidden' name='goglat' id='goglat' value='" . $this->arrayForm['goglat'] . "'>\n";
        print "<input type='hidden' name='goglong' id='goglong' value='" . $this->arrayForm['goglong'] . "'>\n";
        print "<input type='hidden' name='activa' id='activa' value='" . $this->arrayForm['activa'] . "'>\n";
        print "<input type='hidden' name='id_sucursal' id='id_sucursal' value='" . $this->arrayForm['id_sucursal'] . "'>\n";
        print "<input type='hidden' name='id_emp' id='id_emp' value='" . $this->arrayForm['id_emp'] . "'>\n";
        print "<input type='hidden' name='nomedif' id='nomedif' value='" . $this->arrayForm['nomedif'] . "'>\n";
        print "<input type='hidden' name='plano1' id='plano1' value='" . $this->arrayForm['plano1'] . "'>\n";
        print "<input type='hidden' name='plano2' id='plano2' value='" . $this->arrayForm['plano2'] . "'>\n";
        print "<input type='hidden' name='plano3' id='plano3' value='" . $this->arrayForm['plano3'] . "'>\n";
        print "<input type='hidden' name='compartir' id='compartir' value='" . $this->arrayForm['compartir'] . "'>\n";
        print "<input type='hidden' name='publicaprecio' id='publicaprecio' value='" . $this->arrayForm['publicaprecio'] . "'>\n";
        print "<input type='hidden' name='publicadir' id='publicadir' value='" . $this->arrayForm['publicadir'] . "'>\n";
        print "<input type='hidden' name='destacado' id='destacado' value='" . $this->arrayForm['destacado'] . "'>\n";
        print "<input type='hidden' name='oportunidad' id='oportunidad' value='" . $this->arrayForm['oportunidad'] . "'>\n";
        print "<input type='hidden' name='id_prop' id='id_prop' value='" . $this->arrayForm['id_prop'] . "'>\n";
        print "<input type='hidden' name='id_cliente' id='id_cliente' value='" . $this->arrayForm['id_cliente'] . "'>\n";

        print "</td></tr>\n</table>\n";
        print "</form>\n";
    }

    /**
     * Lee desde un formulario los datos cargados para el propiedad.
     * Los registra en un objeto del tipo propiedad propiedad de esta clase
     *
     */
    public function leeDatosPropiedadVW() {
        $propiedad = new PropiedadBSN();
        if (array_key_exists('publicaprecio', $_POST) && $_POST['publicaprecio'] == "on") {
            $_POST['publicaprecio'] = 1;
        }
        if (array_key_exists('publicadir', $_POST) && $_POST['publicadir'] == "on") {
            $_POST['publicadir'] = 1;
        }
        if (array_key_exists('destacado', $_POST) && $_POST['destacado'] == "on") {
            $_POST['destacado'] = 1;
        }
        if (array_key_exists('oportunidad', $_POST) && $_POST['oportunidad'] == "on") {
            $_POST['oportunidad'] = 1;
        }
        if (array_key_exists('activa', $_POST) && ($_POST['activa'] == "on" || $_POST['activa'] == 1)) {
            $_POST['activa'] = 1;
        } else {
            $_POST['activa'] = 0;
        }
        if (array_key_exists('bplano1', $_POST) && $_POST['bplano1'] == "on") {
            $this->borraPlano($_POST['plano1']);
            $nombre1 = "";
        } else {
            if ($_FILES['hplano1']['type'] == 'image/jpeg' || $_FILES['hplano1']['type'] == 'image/gif' || $_FILES['hplano1']['type'] == 'image/png') {
                $imgplano1 = true;
            }
            if (trim($_FILES['hplano1']['name']) == '' || !isset($_FILES['hplano1']['name']) || !$imgplano1) {
                $nombre1 = $_POST['plano1'];
            } else {
                $nombre1 = $this->UploadPlanos('hplano1');
            }
        }
        $_POST['plano1'] = $nombre1;

        if (array_key_exists('bplano2', $_POST) && $_POST['bplano2'] == "on") {
            $this->borraPlano($_POST['plano2']);
            $nombre2 = "";
        } else {
            if ($_FILES['hplano2']['type'] == 'image/jpeg' || $_FILES['hplano2']['type'] == 'image/gif' || $_FILES['hplano2']['type'] == 'image/png') {
                $imgplano2 = true;
            }
            if (trim($_FILES['hplano2']['name']) == '' || !isset($_FILES['hplano2']['name']) || !$imgplano2) {
                $nombre2 = $_POST['plano2'];
            } else {
                $nombre2 = $this->UploadPlanos('hplano2');
            }
        }
        $_POST['plano2'] = $nombre2;

        if (array_key_exists('bplano3', $_POST) && $_POST['bplano3'] == "on") {
            $this->borraPlano($_POST['plano3']);
            $nombre3 = "";
        } else {
            if ($_FILES['hplano3']['type'] == 'image/jpeg' || $_FILES['hplano3']['type'] == 'image/gif' || $_FILES['hplano3']['type'] == 'image/png') {
                $imgplano3 = true;
            }
            if (trim($_FILES['hplano3']['name']) == '' || !isset($_FILES['hplano3']['name']) || !$imgplano3) {
                $nombre3 = $_POST['plano3'];
            } else {
                $nombre3 = $this->UploadPlanos('hplano3');
            }
        }
        $_POST['plano3'] = $nombre3;

        $this->propiedad = $propiedad->leeDatosForm($_POST);

        if ($this->propiedad->getCompartir() == "on") {
            $this->propiedad->setCompartir(1);
        } else {
            $this->propiedad->setCompartir(0);
        }
        /*
          if ($this->propiedad->getActiva() == "on") {
          $this->propiedad->setActiva(1);
          } else {
          $this->propiedad->setActiva(0);
          }
         */
        // Si se activa lo de publicacion desde el Menu, quitar el comentario anterior y comentariar la linea siguiente
        //        $this->propiedad->setActiva(0);
    }

    public function UploadPlanos($plano) {
        if ($_FILES[$plano]['type'] == 'image/jpeg' || $_FILES[$plano]['type'] == 'image/gif' || $_FILES[$plano]['type'] == 'image/png') {
            $planoup = new Upload($_FILES[$plano], 'es_ES');
            $nom = remover_acentos($_FILES[$plano]['name']);
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

    protected function borraPlano($_nombre) {
        $nombre = $this->path . "/" . $_nombre;
        if (file_exists($nombre)) {
            unlink($nombre);
        }
        $nombre = $this->pathC . "/" . $_nombre;
        if (file_exists($nombre)) {
            unlink($nombre);
        }
    }

    /*     * ****************
     *
     * Vista datos propiedad
     */

    public function vistaDatosPropiedad($id_cli = 0) {
        //		$zonaBSN = new ZonaBSN ();
        //		$locaBSN = new LocalidadBSN();
//        $ubiBSN = new UbicacionpropiedadBSN();
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
        $ubiBSN = new UbicacionpropiedadBSN($this->arrayForm['id_ubica']);
        echo $ubiBSN->armaNombreZona($this->arrayForm['id_ubica']);
        print "</td>\n";
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

    public function vistaDatosPropiedadBuscador($id_cli = 0) {
        $tipo_propBSN = new Tipo_propBSN();
        $sucursal = new Sucursal();

        if ($this->arrayForm['id_cliente'] == 0 || $this->arrayForm['id_cliente'] == '') {
            $this->arrayForm['id_cliente'] = $id_cli;
        }
        $ubiBSN = new UbicacionpropiedadBSN($this->arrayForm['id_ubica']);
        //		$zonaBSN = new ZonaBSN($this->arrayForm['id_zona']);
        //		$locaBSN = new LocalidadBSN($this->arrayForm['id_loca']);
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
        //	print "                                            <td height=\"63\" style=\"font-size:12pt; color:#646419; font-weight:bold; padding-left:15px;\">" . $zonaBSN->getObjeto()->getNombre_zona() . "</td>\n";
        print "                                            <td height=\"63\" style=\"font-size:12pt; color:#646419; font-weight:bold; padding-left:15px;\">" . $ubiBSN->armaNombreZona($this->arrayForm['id_ubica']) . "</td>\n";
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
        //		print "                                                        <td style=\"font-size:12pt; color:#646419; font-weight:bold;\">" . $locaBSN->getObjeto()->getNombre_loca() . " - " . $this->arrayForm['operacion'] . "</td>\n";
        $tipo_propBSN = new Tipo_propBSN($this->arrayForm['id_tipo_prop']);
        print "                                                        <td style=\"font-size:12pt; color:#646419; font-weight:bold;\">" . $tipo_propBSN->getObjeto()->getTipo_prop() . " - " . $this->arrayForm['operacion'] . "</td>\n";
        //print "                                                        <td style=\"font-size:12pt; color:#646419;\" align=\"right\">" . strtoupper($this->arrayForm['id_sucursal']) . str_repeat("0", 5 - strlen(strval($this->getId()))) . $this->getId() . "</td>\n";
        print "                                                        <td style=\"font-size:12pt; color:#646419;\" align=\"right\">ID " . str_repeat("0", 5 - strlen(strval($this->getId()))) . $this->getId() . "</td>\n";
        print "                                                    </tr>\n";
        print "                                                    <tr>\n";
        print "                                                        <td colspan=\"2\" style=\"font-size:10pt; color:#646419;\">" . $this->arrayForm['calle'] . " " . $this->arrayForm['nro'] . " " . $this->arrayForm['piso'] . " " . $this->arrayForm['depto'] . "</td>\n";
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
        $datosProp2->setId_prop($this->getId());
        switch ($this->arrayForm['operacion']) {
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
        switch ($this->arrayForm['operacion']) {
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

        if ($this->arrayForm['id_tipo_prop'] == 6 || $this->arrayForm['id_tipo_prop'] == 16) {
            $unidad = "Ha.";
        } else {
            $unidad = "m2";
        }

        print $arrayDatos[0]['contenido'] . $unidad . "</td>\n";
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
        print "                                                        <td valign=\"top\" width=\"30%\" align=\"center\"><a href=\"mail_cta_form.php?id=" . $this->getId() . "&TB_iframe=true&height=350&width=400&modal=false\" class=\"thickbox\"><img src=\"images/enviar_mail.gif\" width=\"92\" height=\"14\" border=\"0\" /></a></td>\n";
        print "                                                        <td valign=\"top\" width=\"20%\" align=\"center\"><!--<a href=\"javascript: ventana('imprimir_prop.php?id=" . $this->getId() . "','Impresión de Ficha de la propiedad', 'location=no,status=1,scrollbars=1, width=970,height=600');\"><img src=\"images/imprimir.gif\" width=\"55\" height=\"15\" border=\"0\" /></a>--></td>\n";
        print "                                                        <td valign=\"top\" width=\"25%\" align=\"center\"><a href=\"ficha_prop_pdf.php?id=" . $this->getId() . "\"><img src=\"images/ficha_pdf.gif\" width=\"82\" height=\"14\" border=\"0\" /></a></td>\n";
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
        print "                                                        <td valign=\"top\" class=\"legales_ficha\">Toda la información comercial, descripción,  precios, planos, imágenes, medidas y superficies que se proporcionan en esta web se basa en información que consideramos fiable y que es proporcionada por terceros. No podemos asegurar que sea exacta ni completa, representa material preliminar al solo efecto informativo e ilustrativo de las caracter√≠sticas del inmueble, pudiendo estar sujeta a errores, omisiones y cambios, incluyendo el precio o la retirada de oferta sin previo aviso.<br />\n";
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

    public function vistaPlanosPropiedad($id_cli = 0) {
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
        $crmpar = '';
        foreach ($arraykey as $clave) {
            if ($clave != 'crmpar' && $clave != 'crmtxt' && $clave != 'adjuntos') {
                $crmpar.=($clave . "->" . $_POST[$clave] . "|");
            }
        }
        return substr($crmpar, 0, strlen($crmpar) - 1);
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
    public function vistaTablaPropiedad($pagina = 1, $idcrm = '') {
        $ubiBSN = new UbicacionpropiedadBSN();
        $tipopropBSN = new Tipo_propBSN();
        $tipo_propBSN = new Tipo_propBSN();
        $sucursal = new Sucursal();
        $config = CargaConfiguracion::getInstance();
        $registros = $config->leeParametro('regprod_adm');
        $anchoPag = $config->leeParametro("ancho_pagina");
        $perf = new PerfilesBSN();
        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

        $crmtxt = '';
        $identificador = '';
        if ($idcrm != '') {
            $identificador = $idcrm;
            $_SESSION['idcrm'] = $idcrm;
        } else {
            if (isset($_SESSION['idcrm'])) {
                $identificador = $_SESSION['idcrm'];
            }
        }
        $gtp = new Grupo_tipoprop();
        if ($perfGpo == 'SUPERUSER' || strtoupper($perfGpo) == 'LECTURA' || $perfGpo == 'admin' || $perfGpo == 'GRAL' || strtoupper($perfGpo) == 'STAFF' || strtoupper($perfGpo) == 'GERENCIA') {
            $listaTPG = 0;
        } else {
            $listaTPG = $gtp->listaTipopropGrupo($perfGpo);
        }

        $_SESSION['perfSuc'] = $perfSuc;
        $_SESSION['perfGpo'] = $perfGpo;
        $_SESSION['listaTPG'] = $listaTPG;

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
        print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
        print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n";
        print "   document.forms.lista.seloperacion.value=document.forms.lista.aux_seloperacion.value;\n";
        print "   document.forms.lista.pagina.value=pagina;\n";
        print "   document.lista.submit();\n";
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
        print "   document.forms.lista.filtro.value=1;\n";
        print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
        print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n";
        print "   document.forms.lista.seloperacion.value=document.forms.lista.aux_seloperacion.value;\n";
        print "   document.lista.submit();\n";
        print "}\n";

        print "function filtra(){\n";
        print "   document.lista.action='lista_propiedad.php?i=';\n";
        print "   document.forms.lista.filtro.value=1;\n";
        print "   document.forms.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
        print "   document.forms.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
        print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
        print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n";
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
        print "   document.forms.lista.filtro.value=0;\n";
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
        print "   document.forms.lista.aux_id_prop.value=prop;\n";
        print "		document.getElementById('toolbar').style.display='block';\n";
        print "}\n";
        print "function popupTipoprop() {\n";
        print "	    elemProp=document.getElementById('aux_prop').value;\n";
        print "	    window.open('muestraSelectorTipoprop.php?v='+elemProp,'Seleccion de Tipo de Propiedad', 'location=0,status=0,scrollbars=0, width=420,height=200');\n";
        print "}\n";
        print "function popupEmprendimiento() {\n";
        print " 	elemZona=document.getElementById('fid_ubica').value;\n";
        print "	    elemEmp=document.getElementById('aux_emp').value;\n";
        print "	    window.open('muestraSelectorEmprendimiento.php?v='+elemEmp+'&z='+elemZona,'Seleccion de Emprendimientos', 'location=0,status=0,scrollbars=1, width=450,height=470');\n";
        print "}\n";
        print "function popupOperacion() {\n";
        print "	    elemOper=document.getElementById('aux_operacion').value;\n";
        print "	    window.open('muestraSelectorOperacion.php?v='+elemOper,'Seleccion de Tipo de Operacion', 'location=0,status=0,scrollbars=1, width=370,height=250');\n";
        print "}\n";

        print "function agregarSeleccion(i){\n";
        print "     var adj = new Array();\n";
        print "     var existe=-1;\n";
        print "     adj = document.getElementById('adjuntos').value.split(',');\n";
        print "     for(x=0,len=adj.length;x<len;x++){\n";
        print "         if(adj[x].indexOf(String(i))!=-1){\n";
        print "              existe=x;\n";
        print "              x=len+1;\n";
        print "         }\n";
        print "     }\n";
//        print "     existe = adj.indexOf(String(i));\n";
        print "     if(existe == -1){\n";
        print "         if(adj[0] == ''){\n";
        print "             adj[0] = i+':1'\n";
        print "         }else{\n";
        print "             adj.push(i+':1');\n";
        print "         }\n";
        print "         document.getElementById('seleccionar'+i).src = 'images/basket_delete.png';\n";
        print "         document.getElementById('seleccion').style.display = 'block'\n";
//        print "         if(document.getElementById('adjuntos').value != ''){\n";
        print "             document.getElementById('adjuntos').value += (','+i+':1')\n";
//        print "         }else{\n";
//        print "             document.getElementById('adjuntos').value += (i+':1')\n";
//        print "         }\n";
        print "     }else{\n";
//        print "         adj.splice(Number(existe),1);\n";
        print "         estado=adj[Number(existe)];\n";
        print "         preEstado=estado.substr(0,estado.indexOf(':'));\n";
        print "         postEstado=estado.substr(estado.indexOf(':')+1);\n";
        print "         adj[Number(existe)]=preEstado+':1'+postEstado;\n";
        print "         document.getElementById('seleccionar'+i).src = 'images/basket_down.png';\n";
        print "         document.getElementById('adjuntos').value =document.getElementById('adjuntos').value.replace(','+estado,','+adj[Number(existe)]); \n";
        print "			generaTareaCambioEstado(i,'1'+postEstado);\n";
        print "     }\n";
        print "     var txt=document.getElementById('cuantos')\n";
        print "     if(adj.length > 0){\n";
        print "         txt.innerHTML = '('+adj.length+')';\n";
        print "     }else{\n";
        print "         txt.innerHTML = '';\n";
        print "     }\n";
        print "		grabarSeleccion();\n";
        print "}\n";

/*
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
*/

        print "function cambiaEstadoSeleccion(i,est){\n";
        print "     var adj = new Array();\n";
        print "     var existe=-1;\n";
        print "     adj = document.getElementById('adjuntos').value.split(',');\n";
        print "     for(x=0,len=adj.length;x<len;x++){\n";
        print "         if(adj[x].indexOf(String(i))!=-1){\n";
        print "              existe=x;\n";
        print "              x=len+1;\n";
        print "         }\n";
        print "     }\n";
//        print "     existe = adj.indexOf(String(i));\n";
        print "     if(existe == -1){\n";
        print "         if(adj[0] == ''){\n";
        print "             adj[0] = i+':'+est\n";
        print "         }else{\n";
        print "             adj.push(i+':'+est);\n";
        print "         }\n";
        print "         switch(est){\n";
        print "             case 1:\n";
        print "                 img='images/basket_delete.png';\n";
        print "                 break;\n";
        print "             case 2:\n";
        print "                 img='images/basket_mail.png';\n";
        print "                 break;\n";
        print "             case 3:\n";
        print "                 img='images/basket_lupa.png';\n";
        print "                 break;\n";
        print "             case 4:\n";
        print "                 img='images/basket_gente.png';\n";
        print "                 break;\n";
        print "         }\n";
        print "         document.getElementById('seleccionar'+i).src = img;\n";
        print "         document.getElementById('seleccion').style.display = 'block'\n";
        print "         document.getElementById('adjuntos').value += (','+i+':'+est)\n";
        print "     }else{\n";
        print "         estado=adj[Number(existe)];\n";
        print "         preEstado=estado.substr(0,estado.indexOf(':'));\n";
        print "         postEstado=estado.substr(estado.indexOf(':')+1);\n";
        print "         adj[Number(existe)]=preEstado+':'+est;\n";
        print "         switch(est){\n";
        print "             case 1:\n";
        print "                 img='images/basket_delete.png';\n";
        print "                 break;\n";
        print "             case 2:\n";
        print "                 img='images/basket_mail.png';\n";
        print "                 break;\n";
        print "             case 3:\n";
        print "                 img='images/basket_lupa.png';\n";
        print "                 break;\n";
        print "             case 4:\n";
        print "                 img='images/basket_gente.png';\n";
        print "                 break;\n";
        print "         }\n";
        print "         document.getElementById('seleccionar'+i).src = img;\n";
        print "         document.getElementById('adjuntos').value =document.getElementById('adjuntos').value.replace(','+estado,','+adj[Number(existe)]); \n";
        print "     }\n";
        print "         var txt=document.getElementById('cuantos')\n";
        print "         if(adj.length > 0){\n";
        print "             txt.innerHTML = '('+adj.length+')';\n";
        print "         }else{\n";
        print "             txt.innerHTML = '';\n";
        print "         }\n";
        print "		generaTareaCambioEstado(i,est);\n";
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
//        print "     document.getElementById('seleccion').style.display = 'none'\n";
        print "     document.getElementById('adjuntos').value = ''\n";
        print "     grabarSeleccion();\n";
        print "         document.lista.action='lista_propiedad.php?i=';\n";
        print "         document.lista.submit();\n";
        print "}\n";

        print "function verSeleccion(){\n";
        print "     adj = document.getElementById('adjuntos').value;\n";
        print "     if(adj != ''){\n";
        print "         document.lista.action='lista_propiedad.php?i=';\n";
        print "         document.getElementById('vistasel').value = 1;\n";
        print "         document.forms.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
        print "         document.forms.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
        print "         document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
        print "         document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
        print "         document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "         document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
        print "         document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n";
        print "         document.forms.lista.seloperacion.value=document.forms.lista.aux_seloperacion.value;\n";
        print "         document.forms.lista.pagina.value=1;\n";
        print "         document.lista.submit();\n";
        print "     }else{\n";
        print "         alert('No ha seleccionado ninguna propiedad')\n";
        print "     }\n";
        print "}\n";

        print "function enviarSeleccion(){\n";
        print "     adj = document.getElementById('adjuntos').value;\n";
        print "     if(adj != ''){\n";
        print "         document.lista.target='nueva';\n";
        print "         document.lista.action='enviarSeleccion.php?i=';\n";
        //print "     document.lista.onsubmit=\"window.open('','envioMail', 'menubar=0,resizable=1, scrollbars=1,width=500,height=500');\"\n";

        print "         document.getElementById('vistasel').value = 1;\n";
        print "		document.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
        print "		document.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
        print "		document.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
        print "		document.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
        print "		document.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "		document.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
        print "		document.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n";
        print "		document.lista.seloperacion.value=document.forms.lista.aux_seloperacion.value;\n";
        print "         document.lista.pagina.value=1;\n";
        print "		ventana('', 'nueva', 500, 300);\n";
        print "         document.lista.submit();\n";
        print "     }else{\n";
        print "         alert('No ha seleccionado ninguna propiedad')\n";
        print "     }\n";
        print "}\n";

        print "function verLista(){\n";
        print "     document.lista.action='lista_propiedad.php?i=';\n";
        print "     document.getElementById('vistasel').value = 0;\n";
        print "     document.forms.lista.pagina.value=1;\n";
        print "   document.forms.lista.fid_codigo.value=document.forms.lista.aux_codigo.value;\n";
        print "   document.forms.lista.fid_calle.value=document.forms.lista.aux_calle.value;\n";
        print "   document.forms.lista.fid_emp.value=document.forms.lista.aux_emp.value;\n";
        print "   document.forms.lista.fid_selemp.value=document.forms.lista.aux_selemp.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.fid_seltipo_prop.value=document.forms.lista.aux_selprop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.value;\n";
        print "     document.lista.submit();\n";
        print "}\n";
        print "function grabarSeleccion(){\n";
        print "  if(document.getElementById('identificador').value!=''){\n";
        print "     pasaDatosCrm('identificador','crmpar','crmtxt','adjuntos');\n";
        print "  }\n";
        print "}\n";
        print "function generaTareaCambioEstado(i,est){\n";
        print "  if(document.getElementById('identificador').value!=''){\n";
        print "     generaTarea('identificador',i,est,".$_SESSION['UserId'].");\n";
        print "  }\n";
        print "}\n";

        print "function limpiaLocalidad(zonaant){\n";
        print "if(zonaant!=document.getElementById('aux_zona').value){\n";
        print "document.getElementById('aux_loca').value='';\n";
        print "}\n";
        print "}\n";

        print "function ActTotal(cant){\n";
        print "     var txt=document.getElementById('total');\n";
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
        print "function menuTools(opcion){\n";
        print " 	id_prop=document.forms.lista.aux_id_prop.value;\n";
        print "		if(isNaN(id_prop)){\n";
        print " 			alert('Debe seleccionar una propiedad para poder operar');\n";
        print "		}else{\n";
        print " 	switch(opcion){\n";
        print "		  case 1:\n";
        print "   		window.open('carga_propiedad.php?i=0', 'ventanaProp', 'menubar=0,resizable=1, scrollbars=1,width=1000,height=650');\n";
        print "			break;\n";
        print "		  case 2:\n";
//		print "   		window.open('carga_propiedad.php?i='+id_prop, 'ventanaProp', 'menubar=1,resizable=1, scrollbars=1,width=1000,height=550');\n";
        print "   		window.open('carga_propiedadTabs.php?i='+id_prop, 'ventanaProp', 'menubar=1,resizable=1, scrollbars=1,width=1200,height=800');\n";
        print "			break;\n";
        print "		  case 3:\n";
        //		print "   		window.open('borra_propiedad.php?i='+id_prop, 'ventanaProp', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "			var conf =confirm('Esta seguro de eliminar este registro');\n";
        print "			if(conf){\n";
        print "				window.location = 'borra_propiedad.php?i='+id_prop;\n";
        print "			}\n";
        print "			break;\n";
        /*
          print "		  case 4:\n";
          print "   		window.open('carga_Foto.php?i='+id_prop+'&f=0', 'ventanaFoto', 'menubar=1,resizable=1,width=1000,height=550');\n";
          print "			break;\n";
          print "		  case 5:\n";
          print "   		window.open('carga_Operacion.php?i='+id_prop+'&o=0', 'ventanaOperacion', 'menubar=1,resizable=1,width=1000,height=550');\n";
          print "			break;\n";
          print "		  case 6:\n";
          print "   		window.open('carga_Cartel.php?i='+id_prop+'&o=0', 'ventanaCartel', 'menubar=1,resizable=1,width=1000,height=550');\n";
          print "			break;\n";
          print "		  case 7:\n";
          print "   		window.open('carga_Tasacion.php?i='+id_prop+'&o=0', 'ventanaTasacion', 'menubar=1,resizable=1,width=1000,height=550');\n";
          print "			break;\n";
          print "		  case 8:\n";
          print "   		window.open('carga_Relacion.php?i='+id_prop, 'ventanaRelacion', 'menubar=1,resizable=1,width=1000,height=550');\n";
          print "			break;\n";
         */
        print "		  case 9:\n";
        print "   		window.open('clona_Propiedad.php?i='+id_prop+'&rep=0', 'ventanaClonar', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "			break;\n";
        print "		  case 10:\n";
//		print "   		window.open('destaca_Propiedad.php?i='+id_prop, 'ventanaDestacar', 'menubar=1,resizable=1,width=500,height=450');\n";
        print "			window.location = 'destaca_propiedad.php?i='+id_prop;\n";
        print "			break;\n";
        print "		  case 11:\n";
        print "			window.location = 'oportunidad_Propiedad.php?i='+id_prop+'&e=-1';\n";
        //print "   		window.open('oportunidad_Propiedad.php?i='+id_prop+'&e=-1', 'ventanaClonar', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "			break;\n";
        print "		  case 12:\n";
        print "   		window.open('casoExito.php?i='+id_prop+'&t=p', 'ventanaExito', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "			break;\n";
        print "		  case 13:\n";
        print "   		window.open('imprimirFicha.php?i='+id_prop, 'ventanaExito', 'menubar=0,resizable=0,width=300,height=200');\n";
        print "			break;\n";
        print "		  case 14:\n";
        print "   		window.open('lista_Comentario.php?i='+id_prop+'&o=0', 'ventanaListaCom', 'menubar=1,resizable=1,width=1000,height=700');\n";
        print "			break;\n";
        print "		  case 15:\n";
        print "   		window.open('zpPublicaciones.php?i='+id_prop, 'ventanaZP', 'menubar=0,resizable=0,width=450,height=300');\n";
        print "			break;\n";
        print "		  case 16:\n";
        print "   		window.open('datosPropietario.php?i='+id_prop, 'ventanaZP', 'menubar=0,resizable=0,width=450,height=300');\n";
        print "			break;\n";
        print "		  case 17:\n";
        print "   		window.open('listar_zonaprop.php','_blank'); win.focus();\n";
        print "			break;\n";
        print "		  case 18:\n";
        print "   		window.open('apPublicaciones.php?i='+id_prop, 'ventanaAP', 'menubar=0,resizable=0,width=450,height=300');\n";
        print "			break;\n";
        print "		  case 19:\n";
        print "   		window.open('http://www.argenprop.com/Abonos/AvisosDestacables?key=69d82dcf875d39aeb583e7d964ed0156','_blank');\n";
        print "			break;\n";
        print "		  case 20:\n";
        print "   		window.open('cuPublicaciones.php?i='+id_prop, 'ventanaCU', 'menubar=0,resizable=0,width=450,height=300');\n";
        print "			break;\n";
        print "		}\n";
        print "		}\n";
        print "}\n";
        print "function cambioVistas(idTab) {\n";
        print "	var contVistas = document.getElementById(idTab);\n";
        print "	var tituVistos = document.getElementById('titu_'+idTab);\n";
        print "	if(contVistas.style.display == '' || contVistas.style.display == 'block') {\n";
        print "    	contVistas.style.display = 'none';\n";
        print "		tituVistos.style.backgroundImage = 'url(images/fderecha.png)';\n";
        print "  }else {\n";
        print "		contVistas.style.display = 'block';\n";
        print "		tituVistos.style.backgroundImage = 'url(images/fAbajo.png)';\n";
        print "	}\n";
        print "}\n";
        print "</script>\n";

        $arrayTools = array();
        //$arrayTools[]=array('Nueva','images/building--plus.png','menuTools(1)');
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {

//            $arrayTools[] = array('Modificar', 'images/building--pencil.png', 'menuTools(2)');
        $arrayTools[] = array('Modificar', 'images/building--pencil.png', 'menuTools(2)');
//	    if(strtoupper($perfGpo) != 'STAFF'){ $arrayTools[] = array('Modificar', 'images/building--pencil.png', 'menuTools(2)');}
            if (strtoupper($perfGpo) != 'STAFF') {
                $arrayTools[] = array('Borrar', 'images/building--minus.png', 'menuTools(3)');
            }
            //		$arrayTools[]=array('Fotos','images/building-photo.png','menuTools(4)');
            //		$arrayTools[]=array('Operacion','images/building-operacion.png','menuTools(5)');
            //		$arrayTools[]=array('Cartel','images/building-cartel.png','menuTools(6)');
            //		$arrayTools[]=array('Tasacion','images/building-tasacion.png','menuTools(7)');
            //		$arrayTools[]=array('Relacion','images/building-relacion.png','menuTools(8)');
            $arrayTools[] = array('Clonar', 'images/building-clone.png', 'menuTools(9)');
            $arrayTools[] = array('Destacar', 'images/building--exclamation.png', 'menuTools(10)');
            $arrayTools[] = array('Oportunidad', 'images/building-oportunidad.png', 'menuTools(11)');
        }
        $arrayTools[] = array('Imprimir', 'images/printer.png', 'menuTools(13)');
        $arrayTools[] = array('Comentarios', 'images/balloon--plus.png', 'menuTools(14)');
        $arrayTools[] = array('Propietario', 'images/user--arrow.png', 'menuTools(16)');
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
            $arrayTools[] = array('ZonaProp', 'images/zp.png', 'menuTools(15)');
            $arrayTools[] = array('Listar ZonaProp', 'images/listar_zp.png', 'menuTools(17)');
            //$arrayTools[]=array('Caso de Exito','images/building--exclamation.png','menuTools(12)');
            $arrayTools[] = array('ArgenProp', 'images/logo_argenprop.jpg', 'menuTools(18)');
            $arrayTools[] = array('CUCICBA', 'images/cucicba.png', 'menuTools(20)');
            $arrayTools[] = array('Listar ArgenProp', 'images/listarAP.png', 'menuTools(19)');
        }


        print "<div class='pg_titulo'>Listado de Propiedades Disponibles</div>\n";

        print "<div style='height: 25px; padding-top:2px; margin-bottom: 3px;width: " . $anchoPag . "px;'>\n";
        //print "<div width='$anchoPag' id='toolbar' style='float: right; padding:5px; display:none;'>\n";
        print "	<div id='toolbar' style='width:720px;float: left;'>\n";
        $menu = new Menu();
        $menu->barraHerramientas($arrayTools);
        if (!isset($cantSel)) {
            $cantSel = '';
        }
        print "	</div>\n";
        print "	<div id='seleccion' style='float: right; width: 250px;padding-top:5px;'>\n";
        print "		<span class='row' align='left' style='padding-right:3px; font-weight: bold;font-size:14px;'>Selecci&oacute;n&nbsp;</span>\n";
        print "		<img src='images/trash.png' onclick='javascript:limpiarAdjuntos();' title='Limpiar Selecci&oacute;n' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;\n";
        print "     <img src='images/basket_edit.png' onclick='javascript:verSeleccion();' title='Ver Selecci&oacute;n' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;\n";
        print "     <img src='images/email.png' onclick='javascript:enviarSeleccion();' title='Enviar Selecci&oacute;n' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;<span id='cuantos'>" . $cantSel . "</span>\n";
//        print "     <img src='images/page_save.png' onclick='javascript://grabarSeleccion();' title='Grabar Selecci&oacute;n' style='vertical-align: middle; padding: 2px; cursor: pointer;'>&nbsp;\n";
        print "     <img src='images/eye.png' onclick='javascript:verLista();' title='Ver Lista' style='vertical-align: middle; padding: 2px; cursor: pointer;'>\n";
        print "	</div>\n";
        print "<div style='display:none;' id='grabado'></div>\n";
        print "</div>\n";
        //		print "<br>\n";

        print "<div style='width:1000px;'>\n";
        print "<form name='lista' id='lista' method='post' action='respondeMenu.php'>";

        //		$menu = new Menu();
        //		$menu->dibujaMenu('lista');

        if (isset($_POST) && sizeof($_POST) > 0) {
            if (isset($_POST['crmpar']) && $_POST['crmpar'] != '' && !isset($_SESSION['crmpar'])) {
                $this->armaPOSTCRM();
            }
            $_SESSION['filtro'] = $_POST;
            if(isset($_SESSION['adjuntos'])){
                $_SESSION['filtro']['adjuntos']=$_SESSION['adjuntos'];
            }
            $_SESSION['crmpar'] = $this->armaCRMParam();
        }else{
            if(isset($_SESSION['crmpar'])){
                $this->armaPOSTCRM();
                $_SESSION['filtro']=$_POST;
            }
            if(isset($_SESSION['adjuntos'])){
                $_SESSION['filtro']['adjuntos']=$_SESSION['adjuntos'];
            }
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
        if (isset($_SESSION['filtro']['fid_ubica']) && $_SESSION['filtro']['fid_ubica'] != 0) {
            $aux_ubica = $_SESSION['filtro']['fid_ubica'];
            $crmtxt.= ( "Zona: " . $ubiBSN->armaNombreZonaAbr($aux_ubica) . "|");
        } else {
            $aux_ubica = 0;
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
            $vista_filaEstado = 'none';  // desactivo la vista de la seleccion de Muestra por
        } else {
            $aux_operacion = '';
            $vista_filaEstado = 'inherit';  // activo la vista de la seleccion de Muestra por
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
        
        // ***************  DEFINICION DE ADJUNTOS 
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
            $aux_vistaestado = 6;
        }
        if (isset($_SESSION['filtro']['vistazona']) && $_SESSION['filtro']['vistazona'] != '') {
            $aux_vistazona = $_SESSION['filtro']['vistazona'];
        } else {
            $aux_vistazona = 0;
        }

        //---------------------------   INCLUIR MENU de MUESTRA DE DATOS
        print "<div id='menu_buscador'>\n";
        print "<div id='total' class='total_filtro'></div>\n";

        print "<div class='titu_filtro' id=\"titu_filaEstado\" onClick=\"cambioVistas('filaEstado');\" style=\"background-image: url(../images/fderecha.png);\">Mostrar</div>\n";
        print "	<div id=\"filaEstado\" name=\"filaEstado\" style=\"display:none;\">\n";

        print "<div class='cd_celda_filtro' style=\"text-align: left;\">TASACIONES</div>\n";
        print "		<div style=\"float:left; width:50%;\">En Proceso<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='1' onClick='filtra();'";
        if ($aux_vistaestado == 1) {
            print " checked ";
        }
        print "	    ></div>\n";
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
            print "		<div style=\"float:right; width:50%;\">Tasadas<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='2' onClick='filtra();'";
            if ($aux_vistaestado == 2) {
                print " checked ";
            }
            print "	    ></div>\n";
        }
        print "<div id=\"clearfix\"></div>\n";

        print "<div class='cd_celda_filtro' style=\"margin-top:5px;padding-top:3px; border-top: thin solid #999;text-align: left;\">ALQUILERES</div>\n";
        print "		<div style=\"float:left; width:33%;\">En Proceso<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='3' onClick='filtra();' ";
        if ($aux_vistaestado == 3) {
            print " checked ";
        }
        print "	    ></div>\n";
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
            print "		<div style=\"float:left; width:33%;\">Activos<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='4' onClick='filtra();' ";
            if ($aux_vistaestado == 4) {
                print " checked ";
            }
            print "	    ></div>\n";
            print "		<div style=\"float:right; width:33%;\">Inactivos<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='5' onClick='filtra();' ";
            if ($aux_vistaestado == 5) {
                print " checked ";
            }
            print "	    ></div>\n";
        }
        print "<div id=\"clearfix\"></div>\n";

        print "<div class='cd_celda_filtro' style=\"margin-top:5px;padding-top:3px; border-top: thin solid #999;text-align: left;\">VENTAS</div>\n";
        print "		<div style=\"float:left; width:50%;\">En Proceso<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='6' onClick='filtra();' ";
        if ($aux_vistaestado == 6) {
            print " checked ";
        }
        print "	    ></div>\n";
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
            print "		<div style=\"float:left; width:50%;\">Vendidas<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='7' onClick='filtra();' ";
            if ($aux_vistaestado == 7) {
                print " checked ";
            }
            print "	    ></div>\n";
        }
        print "<div id=\"clearfix\"></div>\n";

        print "<div class='cd_celda_filtro' style=\"margin-top:5px;padding-top:3px; border-top: thin solid #999;text-align: left;\">ESTADOS</div>\n";
        print "		<div style=\"float:left; width:33%;\">Novedad<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='8' onClick='filtra();' ";
        if ($aux_vistaestado == 8) {
            print " checked ";
        }
        print "	    ></div>\n";
        print "		<div style=\"float:left; width:33%;\">Oportunidad<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='9' onClick='filtra();' ";
        if ($aux_vistaestado == 9) {
            print " checked ";
        }
        print "	    ></div>\n";
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
            print "		<div style=\"float:right; width:33%;\">Casos &eacute;xito<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='10' onClick='filtra();' ";
            if ($aux_vistaestado == 10) {
                print " checked ";
            }
            print "	    ></div>\n";
        }
        print "<div id=\"clearfix\"></div>\n";
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
            print "		<div style=\"float:left; width:33%;\">Inactivas<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='11' onClick='filtra();' ";
            if ($aux_vistaestado == 11) {
                print " checked ";
            }
            print "	    ></div>\n";
            print "		<div style=\"float:left; width:33%;\">Reservadas<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='12' onClick='filtra();' ";
            if ($aux_vistaestado == 12) {
                print " checked ";
            }
            print "	    ></div>\n";
            print "		<div style=\"float:right; width:33%;\">TODAS<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='0' onClick='filtra();' ";
            if ($aux_vistaestado == 0) {
                print " checked ";
            }
            print "	    ></div>\n";
            print "<div id=\"clearfix\" style=\"height:3px;\"></div>\n";
        }
        print "</div>\n";

        // ---------------------------  FIN MUESTRA DE DATOS
        print "<div class='titu_filtro' id=\"titu_filaFiltrarpor\" onClick=\"cambioVistas('filaFiltrarpor');\">Filtrar por</div>\n";
        print "	<div id=\"filaFiltrarpor\" name=\"filaFiltrarpor\">\n";

        print "  <table class='cd_tabla' width='100%'>\n";
        print "<tr>\n";
        print "		<td class='cd_celda_texto'><input type='button' class='campos_btn' value=\"Tipo de Propiedad\" id='ver' name='ver' onclick='javascript: popupTipoprop();'>";
        print "             <div id='selTipoprop' class='buscado'>" . str_replace(",", "<br />", $aux_selprop) . "</div>";
        print "     <input class='campos' name='aux_prop' id='aux_prop' type='hidden' value='$aux_prop'>\n<input class='campos' name='aux_selprop' id='aux_selprop' type='hidden' value='$aux_selprop'></td>\n";
        print "	</tr>";

        print "<tr>\n";
        print "		<td class='cd_celda_texto'><input type='button' class='campos_btn' value=\"Tipo de Operaci&oacute;n\" id='ver' name='ver' onclick='javascript: popupOperacion();'>";
        print "             <div id='operacion' class='buscado'>" . str_replace(",", "<br />", $aux_seloperacion) . "</div>";
        print "     <input class='campos' name='aux_operacion' id='aux_operacion' type='hidden' value='$aux_operacion'>\n<input class='campos' name='aux_seloperacion' id='aux_seloperacion' type='hidden' value='$aux_seloperacion'></td>\n";
        print "</tr>\n";

        print "<tr>\n";
        print "		<td class='cd_celda_filtro'>Zona<br />";

        $ubi1BSN = new UbicacionpropiedadBSN();
        if ($aux_ubica != 0) {
            $textoUbica = $ubi1BSN->armaNombreZonaAbr($aux_ubica);
            $idsZonas = $aux_ubica;
        }else{
            $textoUbica = '';
            $idsZonas = 0;
            
        }
        $id_padre = $ubi1BSN->definePrincipalByHijo($aux_ubica);
        $ubi1BSN->comboUbicacionpropiedadPrincipal($id_padre, 1, 'id_ubicaPrincipal', 'fid_ubica');
        print "<input type='button' class='campos_btn' value='Seleccione Subzonas' onclick=\"window.open('seleccionaZona.php?v='+document.getElementById('fid_ubica').value+'&z='+document.getElementById('id_ubicaPrincipal').value+'&ncu=fid_ubica', 'ventanaEmp', 'menubar=1,resizable=1,width=700,height=550');\">";
        print "<div id='txtUbica'>$textoUbica</div>";

        print "</td>\n";
        print "	</tr>";

        print "<tr>\n";
        print "		<td class='cd_celda_filtro'>Calle<br /><input class='campos' name='aux_calle' id='aux_calle' type='text' value='$aux_calle'>\n";
        print "</td>";
        print "	</tr>";

        print "<tr>\n";
        print "		<td class='cd_celda_texto'><input type='button' class='campos_btn' value=\"Emprendimiento\" id='ver' name='ver' onclick='javascript: popupEmprendimiento();'>\n";
        print "             <div id='emprendimiento' class='buscado'>" . str_replace(",", "<br />", $aux_selemp) . "</div>\n";
        print "     <input class='campos' name='aux_emp' id='aux_emp' type='hidden' value='$aux_emp'>\n<input class='campos' name='aux_selemp' id='aux_selemp' type='hidden' value='$aux_selemp'></td>\n";
        print "	</tr>";

        print "<tr>\n";
        print "		<td class='cd_celda_filtro'>C&oacute;digo<br /><input class='campos' name='aux_codigo' id='aux_codigo' type='text' value='$aux_codigo' onkeypress='//handleKeyPress(event,this.form);'></td>";
        print "	</tr>";
        print "</table>\n";

        if (isset($_SESSION['filtro']['campo']) && is_numeric($_SESSION['filtro']['campo'])) {
            $campolocal = $_SESSION['filtro']['campo'];
            switch ($campolocal) {
                case 1:
                    $aux_campo = 'id_prop';
                    break;
                case 2:
                    $aux_campo = 'id_ubica';
                    break;
                case 3:
                    $aux_campo = 'id_loca';
                    break;
                case 4:
                    $aux_campo = 'calle';
                    break;
                case 5:
                    $aux_campo = 'id_tipo_prop';
                    break;
                case 6:
                    $aux_campo = 'CAST(suptot AS DECIMAL)';
                    break;
                case 7:
                    $aux_campo = 'CAST(cantamb AS DECIMAL)';
                    break;
                case 9:
                    $aux_campo = 'CAST(valven AS DECIMAL)';
                    break;
                default:
                    break;
            }
        } else {
            $aux_campo = '';
        }

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



        if (array_key_exists('vistasel', $_POST) && $_POST['vistasel'] != '1') {
            $propsIN = $dpropBSN->armaArrayIN($arrayDB);  // armo un string con los ID de las propiedades que cumplen con los filtros de caracteristicas
        } else {
            // Cambio para el manejo de los adjuntos como id:estado
            $adjuntosPuros='';
            if($aux_adjuntos!=''){
                $sel = split(',', $aux_adjuntos);
                foreach ($sel as $elemAdj){
                    $elemPuros= split(':', $elemAdj);
                    $adjuntosPuros.=(','.$elemPuros[0]);
                }
                while(strpos($adjuntosPuros, ',')==0){
                    $adjuntosPuros=  substr($adjuntosPuros, 1);
                }
            }
            $propsIN = $adjuntosPuros;
        }
        print "</div>";

        print "<div style=\"margin-top:3px;\">\n		<div style='float: left;'><input class='boton_form_filtro' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></div><div style='float: right;'><input class='boton_form_filtro' type='button' onclick='javascript:filtra();' value='Enviar'></div><div id=\"clearfix\"></div></div>\n";
        if ($aux_adjuntos == '') {
            $display = 'none';
        } else {
            $display = 'block';
        }
        print "</div>";
        // Fin Filtro
        // Armo Coleccion de datos a mostrar
        $evenBSN = new PropiedadBSN();


        switch ($aux_vistaestado) {
            case 1:
                $tasBSN = new TasacionBSN();
                $propsIN = $tasBSN->armaListaIds('Pendiente');
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa(0, '', 0, 0, '', 0, $propsIN, $pagina);
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador(0, '', 0, 0, '', 0, $propsIN,$aux_vistaestado);
                break;
            case 2:
                $tasBSN = new TasacionBSN();
                $propsIN = $tasBSN->armaListaIds("'Tasado','Retirado'");
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa(0, '', 0, 0, '', 0, $propsIN, $pagina); 
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador(0, '', 0, 0, '', 0, $propsIN,$aux_vistaestado);
                break;
            case 8:
                $arrayDatos = $evenBSN->cargaColeccionNovedad($aux_operacion);
                $totalregs = sizeof($arrayDatos);
                break;
            case 9:
                $arrayDatos = $evenBSN->cargaColeccionOportunidad($aux_prop, $aux_operacion);
                $totalregs = sizeof($arrayDatos);
                break;
            case 4:
                $contBSN = new PropiedadcontratoBSN();
                $propsIN = $contBSN->armaListaIds('alquiler', 'a');
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa(0, '', 0, 0, '', 0, $propsIN, $pagina); 
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador(0, '', 0, 0, '', 0, $propsIN);
                break;
            case 5:
                $contBSN = new PropiedadcontratoBSN();
                $propsIN = $contBSN->armaListaIds('alquiler', 'v');
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa(0, '', 0, 0, '', 0, $propsIN, $pagina); 
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador(0, '', 0, 0, '', 0, $propsIN);
                break;
            case 10:
                $casoExitoBSN = new CasoexitoBSN();
                $propsIN = $casoExitoBSN->armaListaIds('p');
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa(0, '', 0, 0, '', 0, $propsIN, $pagina);
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador(0, '', 0, 0, '', 0, $propsIN);
                break;


/////////////////////////////////////////////////

            case 6:
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN,$aux_vistaestado);
                break;
            case 7:
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN,$aux_vistaestado);
                break;
            case 3:
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN,$aux_vistaestado);
                break;
            case 11:
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN,$aux_vistaestado);
                break;
            case 12:
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN,$aux_vistaestado);
                break;
////////////////////////////////////////////////////////////
            default:
//                $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
//                $totalregs=$evenBSN->cantidadRegistrosFiltroBuscador($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN);
                break;
        }
        if ($aux_vistaestado != 8 && $aux_vistaestado != 9) {
            $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
            $totalregs = $evenBSN->cantidadRegistrosFiltroBuscador($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $aux_vistaestado);
        }

//echo "PREVIO <br>";print_r($arrayDatos);
        //--------------------------------- Mostrar Resultados
        print "<div id='resultado'>";
        $this->deslpiegaTabla($arrayDatos, $registros, $totalregs, $pagina,$sel);
        print "</div>";
        print "<input type='hidden' name='aux_id_prop' id='aux_id_prop' value='' />\n";
        print "<input type='hidden' name='fid_tipo_prop' id='fid_tipo_prop' value='' />\n";
        print "<input type='hidden' name='fid_seltipo_prop' id='fid_seltipo_prop' value='' />\n";
        print "<input type='hidden' name='fid_codigo' id='fid_codigo' value='0' />\n";
        print "<input type='hidden' name='fid_calle' id='fid_calle' value='' />\n";
        print "<input type='hidden' name='fid_ubica' id='fid_ubica' value='$aux_ubica' />";
        print "<input type='hidden' name='fid_selloca' id='fid_selloca' value='' />\n";
        print "<input type='hidden' name='fid_emp' id='fid_emp' value='' />\n";
        print "<input type='hidden' name='fid_selemp' id='fid_selemp' value='' />\n";
        print "<input type='hidden' name='foperacion' id='foperacion' value='' />\n";
        print "<input type='hidden' name='seloperacion' id='seloperacion' value='' />\n";
        print "<input type='hidden' name='filtro' id='filtro' value='0' />\n";
        print "<input type='hidden' name='campo' id='campo' value='$campolocal' />\n";
        print "<input type='hidden' name='orden' id='orden' value='$aux_orden' />\n";
        print "<input type='hidden' name='adjuntos' id='adjuntos' value='$aux_adjuntos' />\n";
        print "<input type='hidden' name='vistasel' id='vistasel' value='0' />\n";

        print "<input type='hidden' name='crmtxt' id='crmtxt' value='" . $_SESSION['crmtxt'] . "' />\n";
        print "<input type='hidden' name='crmpar' id='crmpar' value='" . $_SESSION['crmpar'] . "' />\n";
        print "<input type='hidden' name='identificador' id='identificador' value='$identificador' />\n";

        print "<input type='hidden' name='pagina' id='pagina' value='$pagina'>\n";

        print "</form>\n";

        print "</div>\n";
    }

    public function deslpiegaTabla($arrayDatos, $registros, $totalregs, $pagina,$sel) {
        $perfGpo = $_SESSION['perfGpo'];
        $perfSuc = $_SESSION['perfSuc'];
        $listaTPG = $_SESSION['listaTPG'];
        $ubi1BSN = new UbicacionpropiedadBSN();
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
                    $aux_campo = 'id_ubica';
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


        print "<div id='vistaTabla' class='vistaTabla' style=\"width:825px;\">\n";  // DIV general de la tabla
//        print "    <ul>\n";
//        print "     <li class='li_lista_titulo' id='propAcc'>&nbsp;</td>\n";
//        print "     <li class='li_lista_titulo' id='propCod'  onclick='javascript:ordenar(1);'>C&oacute;digo<img src='images/" . $img1 . "' id='img_1' border='0' /></li>\n";
//        print "     <li class='li_lista_titulo' id='propZona' onclick='javascript:ordenar(2);'>Zona<img src='images/" . $img2 . "' id='img_2' border='0' /></li>\n";
//        print "     <li class='li_lista_titulo' id='propDir'  onclick='javascript:ordenar(4);'>Direcci&oacute;n<img src='images/" . $img4 . "' id='img_4' border='0' /></li>\n";
//        print "     <li class='li_lista_titulo' id='propTipo' onclick='javascript:ordenar(5);'>Tipo<img src='images/" . $img5 . "' id='img_5' border='0' /></li>\n";
//        print "     <li class='li_lista_titulo' id='propSup'  onclick='javascript:ordenar(6);'>Sup.<img src='images/" . $img6 . "' id='img_6' border='0' /></li>\n";
//        print "     <li class='li_lista_titulo' id='propAmb'  onclick='javascript:ordenar(7);'>Amb.<img src='images/" . $img7 . "' id='img_7' border='0' /></li>\n";
//        print "     <li class='li_lista_titulo' id='propVal'  onclick='javascript:ordenar(9);'>Valor<img src='images/" . $img9 . "' id='img_9' border='0' /></li>\n";
//        print "	  </ul>\n";

        print "     <div class='div_lista_titulo' id='propAcc'>&nbsp;</div>\n";
        print "     <div class='div_lista_titulo' id='propCod' title='Ordenar por código' onclick='javascript:ordenar(1);'>C&oacute;digo<img src='images/" . $img1 . "' id='img_1' border='0' /></div>\n";
        print "     <div class='div_lista_titulo' id='propZona' title='Ordenar por zona' onclick='javascript:ordenar(2);'>Zona<img src='images/" . $img2 . "' id='img_2' border='0' /></div>\n";
        print "     <div class='div_lista_titulo' id='propDir' title='Ordenar por dirección'  onclick='javascript:ordenar(4);'>Direcci&oacute;n<img src='images/" . $img4 . "' id='img_4' border='0' /></div>\n";
        print "     <div class='div_lista_titulo' id='propTipo' title='Ordenar por tipo propiedad' onclick='javascript:ordenar(5);'>Tipo<img src='images/" . $img5 . "' id='img_5' border='0' /></div>\n";
        print "     <div class='div_lista_titulo' id='propSup' title='Ordenar por superficie'  onclick='javascript:ordenar(6);' style='text-align: center;'>Sup<img src='images/" . $img6 . "' id='img_6' border='0' /></div>\n";
        print "     <div class='div_lista_titulo' id='propAmb' title='Ordenar por ambientes'  onclick='javascript:ordenar(7);' style='text-align: center;'>Amb<img src='images/" . $img7 . "' id='img_7' border='0' /></div>\n";
        print "     <div class='div_lista_titulo' id='propVal' title='Ordenar por valor'  onclick='javascript:ordenar(9);' style='text-align: center;'>Valor<img src='images/" . $img9 . "' id='img_9' border='0' /></div>\n";
        print "<div id=\"clearfix\"></div>\n";


        $cantreg = $totalregs;
        $cantr = $cantreg / $registros;
        $cante = $cantreg % $registros;

        print "<script type='text/javascript' language='javascript'>\n";
        print "ActTotal(" . $cantreg . ")\n";
        print "</script>\n";

        if ($cante != 0) {
            $paginas = intval($cantr + 1);
        } else {
            $paginas = $cantr;
        }

        // **************************  DEFINO NUEVO ARRAY DE SELECCION, DESPLIEGO EN DOS, ARRAY ID_PROP y ARRAY ESTADO
        $selId=array();
        $selEst=array();
        if(sizeof($sel)>0){
            foreach ($sel as $elemSel){
                $datoSel=  split(':', $elemSel);
//                $selId[]=$datoSel[0];
                $selEst[$datoSel[0]]=$datoSel[1];
            }
        }
        
        
//        $arrayEven = array_slice($arrayDatos, (($pagina - 1) * $registros), $registros);   // AGREGADO PARA UNIFICAR BUSQUEDA
        $arrayEven = $arrayDatos;
        print "<div style='overflow:auto; clear:both; height:600px; width:795px;'>\n";
        if (sizeof($arrayEven) == 0) {
            print "No existen datos para mostrar";
        } else {
            $zonaAnt = 0;
            foreach ($arrayEven as $Even) {

                // Defino informacion 
                $linkprop = 'href="muestra_datosprop.php?i=' . $Even['id_prop'] . '"';
                $editprop = 'href="carga_propiedadTabs.php?i=' . $Even['id_prop'] . '"';
                $onclick = " onclick=\"javascript: cambiaEstadoSeleccion(" . $Even['id_prop'] . ",3);ventana('muestra_datosprop.php?i=" . $Even['id_prop'] . "', 'Datos de la propiedad', 980, 900);\"";

                if ($zonaAnt != $Even['id_ubica']) {
                    $txtZona = $ubi1BSN->armaNombreZonaAbr($Even['id_ubica']);
                    $zonaAnt = $Even['id_ubica'];
                }

                switch ($Even['id_tipo_prop']) {
                    case 1:
                        $tipoProp = "<img src='images/tipo_depto.png' height='16' border='0' title='Departamento' />";
                        break;
                    case 2:
                        $tipoProp = "<img src='images/tipo_local.png' height='16' border='0' title='Local' />";
                        break;
                    case 3:
                        $tipoProp = "<img src='images/tipo_ph.png' height='16' border='0' title='PH' />";
                        break;
                    case 6:
                        $tipoProp = "<img src='images/tipo_terreno.png' height='16' border='0' title='Terreno' />";
                        break;
                    case 7:
                        $tipoProp = "<img src='images/tipo_terreno.png' height='16' border='0' title='" . $Even['tipo_prop'] . "' />";
                        break;
                    case 9:
                        $tipoProp = "<img src='images/tipo_casa.png' height='16' border='0' title='Casa' />";
                        break;
                    case 11:
                        $tipoProp = "<img src='images/tipo_oficina.png' height='16' border='0' title='Oficina' />";
                        break;
                    case 15:
                        $tipoProp = "<img src='images/tipo_galpon.png' height='16' border='0' title='Galp&oacute;n' />";
                        break;
                }

                // Fin de definicion de informacion

                if ($Even['destacado'] == 1) {
                    $fila = 'Destacado';
                } else {
                    if ($Even['oportunidad']) {
                        $fila = 'Oportunidad';
                    } else {
                        if ($Even['operacion'] == 'Reservado') {
                            $fila = 'Reservado';
                        } else {
                            if ($fila == 0) {
                                $fila = 1;
                            } else {
                                $fila = 0;
                            }
                        }
                    }
                }

                //print "<ul>\n";

                print "<div class=\"div_lista_" . $fila . "\">";
                print "	 <div id='propAcc'>";

//                if (in_array($Even['id_prop'], $sel)) {
//                $idPos=array_search($Even['id_prop'], $selId);
//                if ($idPos!==FALSE) {
                if(array_key_exists($Even['id_prop'], $selEst)){
                    switch($selEst[$Even['id_prop']]){
                        case 1:
                            $basket = "basket_delete.png";
                            break;
                        case 2:
                            $basket = "basket_mail.png";
                            break;
                        case 3:
                            $basket = "basket_lupa.png";
                            break;
                        case 4:
                            $basket = "basket_gente.png";
                            break;
                        case 11:
                        case 12:
                        case 13:
                        case 14:
                            $basket = "basket_down.png";
                            break;
                    }
                } else {
                    $basket = "basket_add.png";
                }
                print "&nbsp;<img src='images/" . $basket . "' id='seleccionar" . $Even['id_prop'] . "' title='Seleccionar' border='0' style='cursor: pointer;' onclick=\"javascript: agregarSeleccion(" . $Even['id_prop'] . ");\">";

                if ($listaTPG == 0 || strpos($listaTPG, $Even['id_tipo_prop']) !== false) {
                    if ($perfSuc == $Even['id_sucursal'] || $perfSuc == 'Todas') {
                        if ($Even['activa'] == 0) {
                            print "&nbsp;&nbsp;<img src='images/web_no.png' alt='Publicar la propiedad' title='Publicar la propiedad' border='0' style='cursor: pointer;' onclick=\"javascript: publicar(" . $Even['id_prop'] . ");\">";
                        } else {
                            print "&nbsp;&nbsp;<img src='images/web.png' alt='Publicar la propiedad' title='Publicar la propiedad' border='0' style='cursor: pointer;' onclick=\"javascript: publicar(" . $Even['id_prop'] . ");\">";
                        }
                    }
                }

                if ($listaTPG == 0 || strpos($listaTPG, $Even['id_tipo_prop']) !== false) {
                    if ($perfSuc == $Even['id_sucursal'] || $perfSuc == 'Todas') {
                        print "&nbsp;<input type='radio' name='id_prop' id='id_prop' value='" . $Even['id_prop'] . "' onclick='javascript:marcar(" . $Even['id_prop'] . ");'>";
                    }
                }

                print "</div>";

                print "  <div id='propCod'><a class='edit' target='_blank' $editprop><img src='images/building--pencil.png' alt='Modificar' title='Modificar'></a> <a target='_blank' $linkprop>";
//                print $Even['id_sucursal'] . str_repeat("0", 5 - strlen(strval($Even['id_prop']))) . $Even['id_prop'];
                print $Even['id_prop'];
                print "</a></div>\n";

                print "  <div id='propZona' $onclick>";
                print $txtZona;
                print "</div>\n";

                print "  <div id='propDir' $onclick>";
                print $Even['calle'] . "&nbsp;" . $Even['nro'] . "&nbsp;" . $Even['piso'] . "&nbsp;" . $Even['dpto'];
                print "</div>\n";

                print "  <div id='propTipo' $onclick>";
                print (isset($tipoProp))?$tipoProp:'';
                print "</div>\n";

                print "  <div id='propSup' $onclick>";
                print number_format($Even['suptot'], 0, ',', '.');
                print "</div>\n";

                print "  <div id='propAmb' $onclick>";
                if ($Even['cantamb'] != "Sin definir") {
                    print number_format($Even['cantamb'], 0);
                }
                print "</div>\n";

                print "  <div id='propVal' $onclick>";
                if ($Even['monven'] != "Sin definir" && $Even['valven'] != 0) {
                    print $Even['monven'] . " " . number_format($Even['valven'], 0, ',', '.');
                } else {
                    if ($Even['monalq'] != "Sin definir") {
                        print $Even['monalq'] . " " . number_format($Even['valalq'], 0, ',', '.');
                    }
                }
                print "</div>\n";
                print "<div id=\"clearfix\"></div>\n";
                print "	</div>\n";
            }
            print "  </div>\n";
            //------Paginado--------------------
            print "<div >\n";

            print "<ul>\n";
            print "<li><a class='boton_form_filtro' style='padding-top:5px;' href=\"javascript:ventana('lista_propiedadGoogle.php', 'Busqueda en GoogleMaps', 980, 900);\">Ver en GoogleMaps</a></li>\n";

            print "		<li align='right' colspan='7' style='padding-top:10px; padding-bottom:10px;'>";
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
            print "</li>";
            print "</ul>";
            print "  </div>\n";
        }

        print "  </div>\n";
    }

    /**    OK
     * Muestra muestra la los resultados en GoogleMpas
     *
     *
     */
    public function vistaGoogleMaps() {
        // MAPA DE GOOGLE MPAS---------------------------
        $evenBSN = new PropiedadBSN();
        //		$arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $aux_zona, $aux_loca, $aux_prop, $aux_operacion, 0, $propsIN, $pagina, $aux_campo, $aux_orden);
        $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $aux_ubica, $aux_prop, $aux_operacion, 0, $propsIN, $pagina, $aux_campo, $aux_orden);

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
            echo "Ya existe un propiedad con esos datos de ubicaci&oacute;n verifique los mismos.";
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
        /*
          $local = new Localidad();
          $localaBSN = new LocalidadBSN();

          $localaBSN->setId($this->arrayForm['id_loca']);
          $localaBSN->cargaById($this->arrayForm['id_loca']);
         */
        $ubiBSN = new UbicacionpropiedadBSN();
        print "<div class='pg_subtitulo'>Propiedad:" . $this->arrayForm['calle'] . " " . $this->arrayForm['nro'] . " " . $this->arrayForm['piso'] . " " . $this->arrayForm['dpto'] . " - ";
        //		print $localaBSN->getObjeto()->getNombre_loca();
        print $ubiBSN->armaNombreZona($this->arrayForm['id_ubica']);
        print "</div>\n";
    }

    public function armaMailSeleccion() {
        include_once('clases/class.loginwebuserBSN.php');
        include_once ("clases/class.fotoBSN.php");
        include_once("clases/class.clienteBSN.php");
        include_once("inc/class.mail.php");

        $id_cli = $_POST['id_cli'];
        $msgMail = nl2br($_POST['msgMail']) . "<br /><br />";

        $_SESSION ['filtro'] = $_POST;

        if(!isset($_POST['adjuntos']) ){
            if(isset($_SESSION ['filtro'] ['adjuntos'])){
                $aux_adjuntos = $_SESSION ['filtro'] ['adjuntos'];
            }else{
                $aux_adjuntos='';
            }
        }else{
            $aux_adjuntos=$_POST['adjuntos'];
        }
        $sel = split(',', $aux_adjuntos);
        $cantSel = "(" . count($sel) . ")";
        if (array_key_exists('vistasel', $_POST) && $_POST ['vistasel'] != '1') {
            $propsIN = $dpropBSN->armaArrayIN($arrayDB); // armo un string con los ID de las propiedades que cumplen con los filtros de caracteristicas
        } else {
//  Cambio para el manejo de los adjuntos como id_estado
            // envio por mail solo cuando el estado es 1 o 3 ( seleccionado  o visto )
            $sel = split(',', $aux_adjuntos);
            $adjuntosPuros='';
            foreach ($sel as $elemAdj){
                $elemPuros= split(':', $elemAdj);
                if(trim($elemPuros[1])=='1' || trim($elemPuros[1])=='3'){
                    $adjuntosPuros.=(','.$elemPuros[0]);
                }
            }
            while(strpos($adjuntosPuros, ',')===0){
                $adjuntosPuros=  substr($adjuntosPuros, 1);
            }
            $propsIN = $adjuntosPuros;
//            $propsIN = $aux_adjuntos;
        }
        if(isset($_POST['identificador']) && $_POST['identificador']!=0 && $_POST['identificador']!=''){
            $idCrm=$_POST['identificador'];
        }else{
            $idCrm=0;
        }
        $evenBSN = new PropiedadBSN ();
        $arrayDatos = $evenBSN->cargaColeccionFiltroBuscadorMapa($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
        $totalregs = $evenBSN->cantidadRegistrosFiltroBuscador($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $aux_vistaestado);

        //print_r($arrayDatos);
        //die();
        //---------ARMADO de HTML--------------------------------
        $mensaje = "<div style=\"margin-botton:20px; font-family: Arial,Helvetica,sans-serif;color:#414042;\">" . $msgMail . "</div>\n";
        foreach ($arrayDatos as $propiedad) {
            $id_prop = $propiedad ['id_prop'];
            // BUSCA FOTOS ----------
            $fotoBSN = new FotoBSN ();
            $arrayFoto = $fotoBSN->cargaColeccionFormByPropiedad($id_prop);
            //print_r($arrayFoto);
            //die();
            //-----------------------
            $ubiBSN = new UbicacionpropiedadBSN($propiedad ['id_ubica']);
            $ubi = $ubiBSN->armaNombreZona($propiedad ['id_ubica']);

            $tipo_propBSN = new Tipo_propBSN($propiedad ['id_tipo_prop']);
            $tipo_prop = $tipo_propBSN->getObjeto()->getTipo_prop();

            if (is_null($propiedad ['cantamb'])) {
                $ambientes = "-";
            } else {
                $ambientes = intval($propiedad ['cantamb']);
            }
            if ($propiedad ['suptot'] == "") {
                $superficie = "-";
            } else {
                $superficie = intval($propiedad ['suptot']);
            }
//			if ($propiedad ['publicaprecio'] == 0) {
//				$moneda = "";
//				$valor = "Consulte";
//			} else {
            if ($propiedad ['operacion'] == "Venta") {
                if (is_null($propiedad ['monven']) || $propiedad ['monven'] == "Sin definir") {
                    $moneda = "";
                } else {
                    $moneda = $propiedad ['monven'];
                }
                if (is_null($propiedad ['valven']) || $propiedad ['valven'] == 0) {
                    $valor = "Consulte";
                } else {
                    $valor = number_format($propiedad ['valven'], 0, ",", ".");
                }
            } else {
                if (is_null($propiedad ['monalq']) || $propiedad ['monalq'] == "Sin definir") {
                    $moneda = "";
                } else {
                    $moneda = $propiedad ['monalq'];
                }
                if (is_null($propiedad ['valalq']) || $propiedad ['valalq'] == 0) {
                    $valor = "Consulte";
                } else {
                    $valor = number_format($propiedad ['valalq'], 0, ",", ".");
                    ;
                }
            }
//			}

            $datosProp2 = new Datosprop ();
            $datosProp2->setId_prop($id_prop);
            $datosProp2->setId_carac(257);
            $datosPropBSN = new DatospropBSN ();
            $datosPropBSN->seteaBSN($datosProp2);
            $arrayDatos = $datosPropBSN->cargaColeccionForm();

            $mensaje .= "  <table width=\"820\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\" style=\"border-top: thin solid #CCC; margin:5px 0px;\">\n";
            $mensaje .= "  <tr>\n";
            $mensaje .= "    <td><table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\">\n";
            $mensaje .= "  <tr>\n";
            $mensaje .= "    <td width=\"680\" style=\"font-weight: bold; font-size:12pt;color:#008046;\"><a href=\"http://www.okeefe.com.ar/detalleProp.php?iddest=".$id_prop."\" style=\"color:#008046; tesxt-decoration: none;\">" . substr($arrayDatos [0] ['contenido'], 0, 100) . "</a></td>\n";
            $mensaje .= "    <td width=\"80\" align=\"center\" style=\"font-weight: bold; padding:5px; border: thin solid #008046;border-radius: 5px;-ms-border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;\">ID " . str_repeat("0", 5 - strlen(strval($id_prop))) . $id_prop . "</td>\n";
            $mensaje .= "    <td width=\"80\" align=\"center\" style=\"font-weight: bold; padding:5px; color:#FFF; background-color: #008046; border-radius: 5px;-ms-border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;\">" . $propiedad ['operacion'] . "</td>\n";
            $mensaje .= "  </tr>\n";
            $mensaje .= "</table>\n";
            $mensaje .= "</td>\n";
            $mensaje .= "  </tr>\n";
            $mensaje .= "  <tr>\n";
            $mensaje .= "    <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
            $mensaje .= "  <tr>\n";
            $mensaje .= "    <td width=\"350\"><table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\">\n";
            $mensaje .= "  <tr>\n";
            $mensaje .= "    <td colspan=\"2\"><a href=\"http://www.okeefe.com.ar/detalleProp.php?iddest=".$id_prop."\">\n";
            if ($arrayFoto [0] ['hfoto'] != "") {
                $mensaje .= "      <img src=\"http://abm.okeefe.com.ar/fotos_th/" . $arrayFoto [0] ['hfoto'] . "\" width=\"330\" border=\"0\" />\n";
            } else {
                $mensaje .= "      <img src=\"http://abm.okeefe.com.ar/images/noDisponible.gif\" width=\"330\" />\n";
            }
            $mensaje .= "      </a></td>\n";
            $mensaje .= "  </tr>\n";
            $mensaje .= "  <tr>\n";
            $mensaje .= "    <td width=\"50%\"><a href=\"http://www.okeefe.com.ar/detalleProp.php?iddest=".$id_prop."\">\n";
            if ($arrayFoto [1] ['hfoto'] != "") {
                $mensaje .= "      <img src=\"http://abm.okeefe.com.ar/fotos_th/" . $arrayFoto [1] ['hfoto'] . "\" width=\"157\" border=\"0\" />\n";
            }
            $mensaje .= "    </a></td>\n";
            $mensaje .= "    <td><a href=\"http://www.okeefe.com.ar/detalleProp.php?iddest=".$id_prop."\">\n";
            if ($arrayFoto [2] ['hfoto'] != "") {
                $mensaje .= "      <img src=\"http://abm.okeefe.com.ar/fotos_th/" . $arrayFoto [2] ['hfoto'] . "\" width=\"157\" border=\"0\" />\n";
            }
            $mensaje .= "   </a></td>\n";
            $mensaje .= "  </tr>\n";
            $mensaje .= "  <tr>\n";
            $mensaje .= "    <td colspan=\"2\"><br />\n";
            $mensaje .= "<span style=\"font-weight:bold; font-size:12pt;padding:5px; border: solid thin #008046;border-radius: 5px;-ms-border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;\">Precio:" . $moneda . " " . $valor . "</span>";
            $mensaje .= "   </td>\n";
            $mensaje .= "  </tr>\n";
            $mensaje .= "  <tr>\n";
            $mensaje .= "    <td colspan=\"2\" style=\"font-weight:bold; font-size:10pt;padding:5px;margin-top:10px;\">\n";

            $datosProp2->setId_carac(172);
            $datosPropBSN = new DatospropBSN ();
            $datosPropBSN->seteaBSN($datosProp2);
            $arrayDatos = $datosPropBSN->cargaColeccionForm();
            /*
            $direccion = $propiedad['calle'] . " " . $propiedad['nro'];
            if($propiedad['piso'] != ""){
                $direccion .= " - " .$propiedad['piso'] . " ".$propiedad['dpto'];
            }
            
            $direccion .= " - " . $ubi; 
			*/
            $direccion .= $ubi; 
            $mensaje .= "<span>Forma de Pago:<br />" . $arrayDatos [0] ['contenido'] . "</span>\n";
            $mensaje .= "   </td>\n";
            $mensaje .= "  </tr>\n";
            $mensaje .= "</table>\n";
            $mensaje .= "</td>\n";
            $mensaje .= "    <td valign=\"top\" style=\"color:#333;\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
            $mensaje .= "      <tr>\n";
            $mensaje .= "        <td><div><span style=\"font-weight:bold;\">Direcci&oacute;n:</span> <span class=\"caracResul\">" . $direccion . "</span><br />\n";
            $mensaje .= "<span style=\"font-weight:bold;\">Superficie:</span> <span class=\"caracResul\">\n";

            $datosProp2->setId_carac(198);
            $datosPropBSN = new DatospropBSN ();
            $datosPropBSN->seteaBSN($datosProp2);
            $arrayDatos = $datosPropBSN->cargaColeccionForm();

            $mensaje .= $arrayDatos [0] ['contenido'];

            if ($propiedad ['id_tipo_prop'] == 6 || $propiedad ['id_tipo_prop'] == 16) {
                $mensaje .= "Ha.";
            } else {
                $mensaje .= "m2";
            }
            $mensaje .= "</span><br />\n";

            switch ($propiedad ['id_tipo_prop']) {
                case 6 :
                case 7 :
                case 16 :
                    $buscar = 303;
                    $tituBuscar = "Aptitud";
                    //$valor = busca_valor ( $propiedad ['id_prop'], 303, $carac );
                    break;
                default :
                    $buscar = 208;
                    //$valor = $ambientes;
                    $tituBuscar = "Ambientes";
                    break;
            }
            $datosProp2->setId_carac($buscar);

            $datosPropBSN = new DatospropBSN ();
            $datosPropBSN->seteaBSN($datosProp2);
            $arrayDatos = $datosPropBSN->cargaColeccionForm();

            $mensaje .= "          <span style=\"font-weight:bold;\">" . $tituBuscar . ": </span><span class=\"caracResul\">" . $arrayDatos [0] ['contenido'] . "</span><br />\n";
            $mensaje .= "          <span style=\"font-weight:bold;\">Categoría:</span> <span class=\"caracResul\">" . $tipo_prop . "</span><br />\n";
            $mensaje .= "        </div></td>\n";
            $mensaje .= "      </tr>\n";
            $mensaje .= "      <tr>\n";
            $mensaje .= "        <td><br /><div><span style=\"font-weight:bold;\">Descripción:</span><br />\n";
            $mensaje .= "          <span>\n";

            $datosProp2->setId_carac(255);

            $datosPropBSN = new DatospropBSN ();
            $datosPropBSN->seteaBSN($datosProp2);
            $arrayDatos = $datosPropBSN->cargaColeccionForm();

            $desc = $arrayDatos [0] ['contenido'];
            $MaxLENGTH = 1300;
            if (strlen($desc) > $MaxLENGTH) {
                $desc = substr(strip_tags($desc), 0, strrpos(substr(strip_tags($desc), 0, $MaxLENGTH), " "));
                $desc .= '...';
            }
            $mensaje .= $desc;


            $mensaje .= "            </span>\n";
            $mensaje .= "            </div></td>\n";
            $mensaje .= "      </tr>\n";
            $mensaje .= "    </table></td>\n";
            $mensaje .= "  </tr>\n";
            $mensaje .= "</table>\n";
            $mensaje .= "</td>\n";
            $mensaje .= "  </tr>\n";
            $mensaje .= "</table>\n";
        }
//		$mensaje .= "<div style=\"border-top: thick solid #F16221;border-bottom: thick solid #00491B;padding:10px; text-align:center; color:#333;font-size:10pt;\">".$usrNombre ." " . $usrApellido . " - <a href=\"mailto:".$usrMail."\" style=\"color:#333;\">" . $usrMail. "</a> - " .$usrTel ."</div>\n";
//		echo $mensaje;
//		die();

        $usrBSN = new LoginwebuserBSN();
        $usrBSN->cargaById($_SESSION['UserId']);
        $usrId = $usrBSN->getObjeto()->getId_user();
        $usrNombre = $usrBSN->getObjeto()->getNombre();
        $usrApellido = $usrBSN->getObjeto()->getApellido();
        $usrMail = $usrBSN->getObjeto()->getEmail();
        $usrTel = $usrBSN->getObjeto()->getTelefono();

        if (strlen($usrId) == 1) {
            $pie = "pie0" . $usrId . ".jpg";
        } else {
            $pie = "pie" . $usrId . ".jpg";
        }

        $mensaje .= "<br /><br /><img src=\"http://abm.okeefe.com.ar/images/piesMails/" . $pie . "\" width=\"800\" />";


        $de = $usrMail;
        $deNombre = $usrNombre . ' ' . $usrApellido;

        $Subject = 'Inmobiliaria O\'Keefe responde a tu busqueda.';

        $cliBSN = new ClienteBSN($id_cli);
        $cliNombre = $cliBSN->getObjeto()->getNombre();
        $cliApellido = $cliBSN->getObjeto()->getApellido();
        $medCli = new MedioselectronicosBSN();
        $arrMed = $medCli->coleccionByCliente($id_cli);
        $cliMail = "";
        foreach ($arrMed as $medio) {
            if ($medio['id_tipomed'] == 1) {
                $cliMail.=$medio['contacto'] . ';';
            }
        }
//        $cliMail=$cliBSN->getObjeto()->getEmail();

        if ($cliMail != "") {
            $cliMail=substr($cliMail, 0, -1);
            $para = $cliMail;
            $paraNombre = $cliNombre . ' ' . $cliApellido;
        } else {
            $para = $de;
            $paraNombre = $deNombre;
        }

        //so we use the MD5 algorithm to generate a random hash
        $random_hash = md5(date('r', time()));
        //define the headers we want passed. Note that they are separated with \r\n
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "To: " . $paraNombre . " <" . $para . ">\r\n";
        $headers .= "Cc: " . $deNombre . " <" . $de . ">\r\n";
        $headers .= "From: " . $deNombre . " <" . $de . ">\r\nReply-To: " . $deNombre . " <" . $de . ">\r\n";
        //add boundary string and mime type specification
        //$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";


        if (mail($para, $Subject, $mensaje, $headers)) {
            if($idCrm!=0){
                $crmBSN=new CrmbuscadorBSN($idCrm);
                foreach ($arrayDatos as $propiedad) {
                    $crmBSN->actualizaEstadoAdjunto($propiedad['id_prop'], 2);
                }
            }
            
            header("location:mailEnviado.html");
        } else {
            echo "Error al enviar";
        }

        //-------------------------------------------
    }

    public function armaBuscadorPropHorizontal() {
        $perf = new PerfilesBSN();
//        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);
  
        $aux_vistaestado=0;
        $aux_selTprop='';
        $aux_Tprop=0;
        $aux_seloperacion='';
        $aux_operacion='';
        $aux_ubica=0;
        $aux_calle='';
        $aux_selemp='';
        $aux_emp=0;

        print "<div id='menuBuscadorPropiedad' style='display:none;'>";

       if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
            print "		<div style=\"float:left; width:33%;\">Activas<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='4' onClick='filtra();' ";
            if ($aux_vistaestado == 4) {
                print " checked ";
            }
            print "	    ></div>\n";
            print "		<div style=\"float:right; width:33%;\">Inactivas<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='5' onClick='filtra();' ";
            if ($aux_vistaestado == 5) {
                print " checked ";
            }
            print "	    ></div>\n";
        }
        print "		<div style=\"float:right; width:33%;\">TODAS<br />\n<input type='radio' name='vistaestado' id='vistaestado' value='0' onClick='filtra();' ";
        if ($aux_vistaestado == 0) {
            print " checked ";
        }
        print "	    ></div>\n";
        print "<div id=\"clearfix\" style=\"height:3px;\"></div>\n";

        // ---------------------------  FIN MUESTRA DE DATOS
        print "<div class='titu_filtro' id=\"titu_filaFiltrarpor\" onClick=\"cambioVistas('filaFiltrarpor');\">Filtrar por</div>\n";
        print "	<div id=\"filaFiltrarpor\" name=\"filaFiltrarpor\">\n";

        print "  <table class='cd_tabla' width='100%'>\n";
        print "<tr>\n";
        print "		<td class='cd_celda_texto'><input type='button' class='boton_form' value=\"Tipo de Propiedad\" id='ver' name='ver' onclick=\"javascript: popupTipoprop('div_selTipoprop','aux_Tprop','aux_selTprop');\">";
        print "             <div id='div_selTipoprop' class='buscado'>" . str_replace(",", "<br />", $aux_selTprop) . "</div>";
        print "     <input class='campos' name='aux_Tprop' id='aux_Tprop' type='hidden' value='$aux_Tprop'>\n";
        print "     <input class='campos' name='aux_selTprop' id='aux_selTprop' type='hidden' value='$aux_selTprop'></td>\n";

        print "		<td class='cd_celda_texto'><input type='button' class='boton_form' value=\"Tipo de Operaci&oacute;n\" id='ver' name='ver' onclick=\"javascript: popupOperacion('div_selOperacion','aux_operacion','aux_seloperacion');\">";
        print "             <div id='div_selOperacion' class='buscado'>" . str_replace(",", "<br />", $aux_seloperacion) . "</div>";
        print "     <input class='campos' name='aux_operacion' id='aux_operacion' type='hidden' value='$aux_operacion'>\n<input class='campos' name='aux_seloperacion' id='aux_seloperacion' type='hidden' value='$aux_seloperacion'></td>\n";

        print "		<td class='cd_celda_filtro'>Zona<br />";

        $ubi1BSN = new UbicacionpropiedadBSN();
        if ($aux_ubica != 0) {
            $textoUbica = $ubi1BSN->armaNombreZonaAbr($aux_ubica);
            $idsZonas = $aux_ubica;
        }
        $id_padre = $ubi1BSN->definePrincipalByHijo($aux_ubica);
        $ubi1BSN->comboUbicacionpropiedadPrincipal($id_padre, 1, 'id_ubicaPrincipal', 'fid_ubica','txtUbica','','datoCampo');
        print "<input class='campos' name='fid_ubica' id='fid_ubica' type='hidden' value='$aux_ubica'>\n";
        print "<input type='button' class='boton_form' value='Seleccione Subzonas' onclick=\"window.open('seleccionaZona2.php?v='+document.getElementById('fid_ubica').value+'&z='+document.getElementById('id_ubicaPrincipal').value+'&ncu=fid_ubica', 'ventanaEmp', 'menubar=1,resizable=1,width=700,height=550');\">";
        print "<div id='txtUbica'>$textoUbica</div>";

        print "</td>\n";

        print "		<td class='cd_celda_filtro'>Calle<br /><input class='datoCampo' name='aux_calle' id='aux_calle' type='text' value='$aux_calle'>\n";
        print "</td>";

        print "		<td class='cd_celda_texto'><input type='button' class='boton_form' value=\"Emprendimiento\" id='ver' name='ver' onclick=\"popupEmprendimiento('div_selEmprendimiento','aux_emp','aux_selemp','fid_ubica');\">\n";
        print "             <div id='div_selEmprendimiento' class='buscado'>" . str_replace(",", "<br />", $aux_selemp) . "</div>\n";
        print "     <input class='campos' name='aux_emp' id='aux_emp' type='hidden' value='$aux_emp'>\n<input class='campos' name='aux_selemp' id='aux_selemp' type='hidden' value='$aux_selemp'></td>\n";
        print "	</tr>";

        print "</table>\n";
        print "</div>";
        print "</div>";
    }

}

// fin clase
?>
