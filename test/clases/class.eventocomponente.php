<?php

include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.VW.php");
include_once("generic_class/class.maper.php");
include_once ("clases/class.loginwebuserBSN.php");
include_once ("clases/class.propiedadBSN.php");
include_once ("clases/class.clienteBSN.php");
include_once ("clases/class.tipoEventocomponenteBSN.php");

class Eventocomponente {

    private $id_evencomp;
    private $id_evento;
    private $id;
    private $tipo_comp;

    public function __construct($id_evencomp = 0, $id_evento = 0, $id = 0, $tipo_comp = 'C') {
        Eventocomponente::setId_evencomp($id_evencomp);
        Eventocomponente::setId_evento($id_evento);
        Eventocomponente::setId($id);
        Eventocomponente::setTipo_comp($tipo_comp);
    }

    public function seteaEventocomponente($compo) {
        $this->setId_evencomp($compo->getId_evencomp());
        $this->setId_evento($compo->getId_evento());
        $this->setId($compo->getId());
        $this->setTipo_comp($compo->getTipo_comp());
    }

    public function getId_evencomp() {
        return $this->id_evencomp;
    }

    public function setId_evencomp($id_evencomp) {
        $this->id_evencomp = $id_evencomp;
    }

    public function getId_evento() {
        return $this->id_evento;
    }

    public function setId_evento($id_evento) {
        $this->id_evento = $id_evento;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTipo_comp() {
        return $this->tipo_comp;
    }

    public function setTipo_comp($tipo_comp) {
        $this->tipo_comp = $tipo_comp;
    }

    public function __toString() {
        $str = 'Id componente: ' . $this->getId_evencomp();
        $str = ', Evento: ' . $this->getId_evento();
        $str.=', Id: ' . $this->getId();
        $str.=', Tipo: ' . $this->getTipo_comp();
        return $str;
    }

}

class EventocomponenteBSN extends BSN {

    protected $clase = "Eventocomponente";
    protected $nombreId = "id_evencomp";
    protected $eventocomponente;

    public function __construct($_id_evencomp = 0, $_eventocomponente = '') {
        EventocomponenteBSN::seteaMapa();
        if ($_id_evencomp instanceof Eventocomponente) {
            EventocomponenteBSN::creaObjeto();
            EventocomponenteBSN::seteaBSN($_id_evencomp);
        } else {
            if (is_numeric($_id_evencomp)) {
                EventocomponenteBSN::creaObjeto();
                if ($_id_evencomp != 0) {
                    EventocomponenteBSN::cargaById($_id_evencomp);
                }
            }
        }
    }

    /**
     * retorna el ID del objeto
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->eventocomponente->getId_evencomp();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->eventocomponente->setId_evencomp($id);
    }

    /**
     * Arma un string con los ID de los eventos que corresponden con los tipo y id especificados
     * @param char $tipo -> tipo de componente
     * @param int $id -> id del componente
     * @return string
     */
    public function armaListaIdEventoByTipo($tipo = 'C', $id = 0) {
        $strRet = '';
        $arrayElem = $this->cargaColeccionEventosByTipo($tipo, $id);
        foreach ($arrayElem as $elemento) {
            $strRet.=($elemento['id_evento'] . ',');
        }
        if (strlen($strRet) > 1) {
            $strRet = substr($strRet, 0, -1);
        }
        return $strRet;
    }

    /**
     * Arma un array bidimensional con los datos de los componentes de los eventos que corresponden con los tipo y id especificados
     * @param char $tipo -> tipo de componente
     * @param int $id -> id del componente
     * @return string[][]
     */
    public function cargaColeccionEventosByTipo($tipo = 'C', $id = 0) {
        $arrayRet = array();
        if ($tipo != '' && $id != 0) {
            $dao = new EventocomponentePGDAO();
            $arrayRet = $this->leeDBArray($dao->coleccionEventosByTipo($tipo, $id));
        }
        return $arrayRet;
    }

    /**
     * Arma un array bidimensional con los datos de los componentes de los eventos que corresponden con el evento especificado
     * @param int $id -> id del evento
     * @return string[][]
     */
    public function cargaColeccionComponentesByEvento($id = 0) {
        $arrayRet = array();
        if ($id != 0) {
            $dao = new EventocomponentePGDAO();
            $arrayRet = $this->leeDBArray($dao->coleccionComponentesByEvento($id));
        }
        return $arrayRet;
    }

    /**
     * Arma un array bidimensional con los datos de los componentes de los eventos que corresponden con el evento especificado
     * @param int $id -> id del evento
     * @return string[][] -> primer componente contiene la descripcion, segundo componente contiene el detalle
     */
    public function cargaColeccionDetalleComponentesByEvento($id = 0) {
        $arrayRet = array();
        if ($id != 0) {
            $arrayDatos = $this->cargaColeccionComponentesByEvento($id);
            foreach ($arrayDatos as $dato) {
                $arrayRet[] = array($this->cargaDescripcionComponente($dato['tipo_comp'], $dato['id']), $this->cargaDetalleComponente($dato['tipo_comp'], $dato['id']),$dato['id'],$dato['tipo_comp']);
            }
        }
        return $arrayRet;
    }

    public function cargaDetalleComponente($tipo, $id) {
        switch ($tipo) {
            case 'C':
                $retorno = $this->cargaDetalleCliente($id);
                break;
            case 'P':
                $retorno = $this->cargaDetallePropiedad($id);
                break;
            case 'U':
                $retorno = $this->cargaDetalleUsuario($id);
                break;
            default:
                $retorno='';
        }
        return $retorno;
    }

    protected function cargaDetalleCliente($id) {
        $cliBSN = new ClienteBSN($id);
        $retorno = $cliBSN->buscaDetalleCliente($id);
        return $retorno;
    }

    protected function cargaDetalleUsuario($id) {
        $usrBSN = new LoginwebuserBSN();
        $retorno = $usrBSN->buscaDetalleUsuario($id);
        return $retorno;
    }

    protected function cargaDetallePropiedad($id) {
        $propBSN = new PropiedadBSN($id);
        $retorno = $propBSN->buscaDetallePropiedad();
        return $retorno;
    }

    public function cargaDescripcionComponente($tipo, $id) {
        $retorno='';
        switch ($tipo) {
            case 'C':
                $retorno = $this->cargaDescripcionCliente($id);
                break;
            case 'P':
                $retorno = $this->cargaDescripcionPropiedad($id);
                break;
            case 'U':
                $retorno = $this->cargaDescripcionUsuario($id);
                break;
        }
        return $retorno;
    }

    protected function cargaDescripcionCliente($id) {
        $cliBSN = new ClienteBSN($id);
        $retorno = $cliBSN->buscaDescripcionCliente($id);
        return $retorno;
    }

    protected function cargaDescripcionUsuario($id) {
        $usrBSN = new LoginwebuserBSN($id);
//        $retorno = $usrBSN->buscaDescripcionUsuario($id);
        $retorno = $usrBSN->buscaDescripcionUsuario();
        return $retorno;
    }

    protected function cargaDescripcionPropiedad($id) {
        $propBSN = new PropiedadBSN($id);
        $retorno = $propBSN->buscaDescripcionPropiedad();
        return $retorno;
    }

}

class EventocomponentePGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.evento_componente (id_evento,tipo_comp,id) values (#id_evento#,'#tipo_comp#',#id#)";
    protected $DELETE = "DELETE FROM #dbName#.evento_componente WHERE id_evencomp=#id_evencomp#";
    protected $UPDATE = "UPDATE #dbName#.evento_componente SET id_evento='#id_evento#',tipo_comp='#tipo_comp#',id=#id# WHERE id_evencomp=#id_evencomp# ";
    protected $FINDBYID = "SELECT id_evencomp,id_evento,id,tipo_comp FROM #dbName#.eventos WHERE id_evencomp=#id_evencomp#";
    protected $FINDBYCLAVE = "SELECT id_evencomp,id_evento,id,tipo_comp FROM #dbName#.evento_componente
							WHERE id=#id# AND id_evento=#id_evento# AND tipo_comp=#tipo_comp#";
    protected $COLECCION = "SELECT id_evencomp,id_evento,id,tipo_comp FROM #dbName#.eventos ORDER BY id_evento,tipo_comp,id";
    protected $COLECBASE = "SELECT id_evencomp,id_evento,id,tipo_comp FROM #dbName#.evento_componente";

    public function coleccionEventosByTipo($tipo, $id) {
        $parametro = func_get_args();
        $where = " where tipo_comp='" . $tipo . "' AND id=" . $id;
        $order = " order by id_evento,tipo_comp,id";
        $resultado = $this->execSql($this->COLECBASE . $where . $order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", "EVENTOS x COMPONENTES");
        }
        return $resultado;
    }

    public function coleccionComponentesByEvento($id_evento) {
        $parametro = func_get_args();
        $where = " where id_evento=" . $id_evento;
        $order = " order by id_evento,tipo_comp,id";
        $resultado = $this->execSql($this->COLECBASE . $where . $order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", "COMPONENTES DE EVENTOS x ID");
        }
        return $resultado;
    }

}

class EventocomponenteVW extends VW {

    protected $clase = "Eventocomponente";
    protected $ingresocliente;
    protected $nombreId = "Id_evencomp";

    public function __construct($_id = 0, $tipo = 'C') {
        EventocomponenteVW::creaObjeto();
        if ($_id instanceof Eventocomponente) {
            EventocomponenteVW::seteaVW($_id);
        }
        if ($_id != 0 && $tipo == 'C') { // Ingreso el Id de un cliente
        } else {
            if (is_numeric($_id)) {
                if ($_id != 0) {
                    EventocomponenteVW::cargaVW($_id);
                }
            }
        }

        EventocomponenteVW::cargaDefinicionForm();
    }

// Cargo los datos del ingreso de un cliente segun su ID    
    protected function cargaByCliente($id) {
        
    }

    public function cargaDatosVW($div, $id) {
        print "<div id='cargaData' name='cargaData' style='display:none;'>";
        print "<form action='carga_EventoComponente.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaEvento(this);'>\n";

        print "<div class='pg_titulo'>Componentes relacionados al evento</div>\n";

        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Tipo componente<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $evento = 0;
        if ($this->arrayForm ['id_evento'] != '') {
            $evento = $this->arrayForm ['id_evento'];
        } else {
            $evento = $id;
        }
        $tEvnCBSN = TipoEventocomponenteBSN::getInstance();
        $tEvnCBSN->comboParametros($this->arrayForm ['tipo_comp'], 0, 'tipo_comp', 'campos_btn', 'comboTipocomponente(\'tipo_comp\',' . $evento . ',\'divComboComponente\')');
        print "</ br>";
        print "<div id='divComboComponente' name='divComboComponente'>";
        print "</div>";

        print "<div id='divCompoCli' name='divCompoCli' style='display:none'>";
        print "<br>Indique el Cliente<br>";
        print "<input type='text' size='50' name='buscaCli' id='buscaCli'>";
        print "<br> * Pon al menos 3 letras para que salgan opciones.";
        print "<input type='hidden' size='50' name='aux_id' id='aux_id'>";
        print "</div>";
        print "<div id='divCompoProp' name='divCompoProp' style='display:none'>";
        print "<br>Indique la Propiedad<br>";
        print "<input type='text' size='50' name='buscaProp' id='buscaProp'>";
        print "<br> * Pon al menos 3 letras para que salgan opciones.";
        print "<input type='hidden' size='50' name='aux_prop' id='aux_prop'>";
        print "</div>";
        print " </td></tr>\n";
        print "<br>";
        print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";

        print "<input type='hidden' name='id_evencomp' id='id_evencomp' value='" . $this->arrayForm['id_evencomp'] . "'>\n";
        print "<input type='hidden' name='id_evento' id='id_evento' value='" . $evento . "'>\n";

        print "<br>";
        print "</form>";
        print "</div>";
    }

    /**
     * Despliega una tabla con los datos relativos a las tareas programadas para el tipo e id indicado
     * @param char $tipo -> tipo de referencia P: Propiedad   C: Cliente  U: Usuario   O: Contacto   F: Fecha
     * @param mixed $id -> id de la referencia , en el caso que tipo=F se presentara una fecha.
     * @param char $opcion -> identifica se la lista presenta opciones de menu o es exclusivamente de vista  o: operaciones    v: vistas.
     */
    public function vistaTablaVW($id = 0, $opcion = 'o') {
        // Cantidad de elementos en la botonera de acciones de cada registro
        print "<script type='text/javascript' language='javascript'>\n";
        print "function mostrarDetalle(elem){\n";
        print "   est=document.getElementById(elem).style.display;\n";
        print "   if(est=='none'){\n";
        print "      document.getElementById(elem).style.display='block';\n";
        print "   }else{\n";
        print "      document.getElementById(elem).style.display='none';\n";
        print "   }\n";
        print "}\n";
        print "</script>\n";
        if ($opcion == 'o') {
            print "<form name='lista' method='POST' action='respondeMenu.php'>";

            print "<script type='text/javascript' language='javascript'>\n";
            print "function envia(nameForm,opcion,id){\n";
            print "   document.forms.lista.id_evento.value=id;\n";
            print "   submitform(nameForm,opcion);\n";
            print "}\n";
            print "function modifica(id){\n";
            print "     document.getElementById('opcion').value='m';\n";
            print "     document.location.href='carga_EventoComponente.php?c='+id+'&ev=" . $id . "';\n";
            print "}\n";
            print "function borra(cartel){\n";
            print "     document.getElementById('opcion').value='b';\n";
            print "     document.location.href='carga_EventoComponente.php?c='+cartel+'&b=b&ev=" . $id . "';\n";
            print "}\n";
            print "function muestraCargaData(){\n";
            print "   document.getElementById('cargaData').style.display='block';\n";
            print "}\n";

            print "</script>\n";

            print "<div class='pg_subtitulo'>Datos asociados a la tarea</div>\n";

            $arrayTools = array(array('Nuevo', 'images/building_edit.png', 'muestraCargaData()'), array('Regresar', 'images/ui-button-navigation-back.png', 'KillMe()'));
            $menu = new Menu();
            $menu->barraHerramientas($arrayTools);

            $endForm = "</form>";
        } else {
            print "<div class='pg_subtitulo'>Datos asociados a la tarea</div>\n";
            $endForm = "";
        }

        $objBSN = new EventocomponenteBSN();

        $arrayDatos = $objBSN->cargaColeccionComponentesByEvento($id);
        $this->despliegaTabla($arrayDatos, $opcion);


        print "<input type='hidden' name='id_evencomp' id='id_evencomp' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";

        print $endForm;
    }

    public function despliegaTabla($arrayDatos, $opcion) {
        if ($opcion == 'o') {
            $elemBotonera = 2;
        } else {
            $elemBotonera = 1;
        }
        $fila = 0;
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {
            $tipoBSN = TipoEventocomponenteBSN::getInstance();
            $arrayTipo = $tipoBSN->getArrayParametros();
            $compEv = new EventocomponenteBSN();

            print "  <table class='cd_tabla' width='100%'>\n";
            print "    <tr>\n";
            print "     <td class='cd_lista_titulo' colspan='$elemBotonera'>&nbsp;</td>\n";
            print "     <td class='cd_lista_titulo'>Tipo</td>\n";
            print "     <td class='cd_lista_titulo'>Detalle</td>\n";
            // agregar los titulos de las columnas a mostrar
            print "	  </tr>\n";

            foreach ($arrayDatos as $datosCont) {
                $onclick = "onclick=\"javascript: mostrarDetalle('detEven_" . $datosCont['id_evencomp'] . "');\" ";
                $descComp = $compEv->cargaDescripcionComponente($datosCont['tipo_comp'], $datosCont['id']);
                $detComp = $compEv->cargaDetalleComponente($datosCont['tipo_comp'], $datosCont['id']);
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }

                print "<tr>\n";
                // Botonera de acciones por registro
                print "	 <td class='row" . $fila . "'>";
                if ($opcion == 'o') {
                    print "    <a href=\"javascript:modifica(" . $datosCont['id_evencomp'] . ");\" border='0'>";
                    print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a>";
                    print "  </td>\n";
                    print "	 <td class='row" . $fila . "'>";
                    print "    <a href=\"javascript:borra(" . $datosCont['id_evencomp'] . ");\" border=0>";
                    print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
                } else {
                    print "&nbsp;";
                }
                print "  </td>\n";
                // Fin Botonera de acciones
                print "	 <td class='row" . $fila . "' $onclick>" . $arrayTipo[$datosCont['tipo_comp']] . "</td>\n";
                print "	 <td class='row" . $fila . "' $onclick>" . $descComp;
                print "     <div id='detEven_" . $datosCont['id_evencomp'] . "' style='display:none;'>";
                print $detComp . "<br>";
                print "     </div>";
                print "</td>\n";
                // agregar las columnas a mostrar

                print "	</tr>\n";
            }

            print "  </table>\n";
        }
    }

}

?>
