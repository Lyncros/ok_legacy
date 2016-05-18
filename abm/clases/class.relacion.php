<?php

include_once("generic_class/class.VW.php");
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("generic_class/class.PGDAO.php");
include_once ("clases/class.loginwebuserBSN.php");
include_once ("clases/class.propiedadBSN.php");
include_once ("clases/class.datospropBSN.php");
include_once ("clases/class.telefonosBSN.php");
include_once('clases/class.medioselectronicosBSN.php');
include_once ("clases/class.ubicacionpropiedadBSN.php");
include_once ("clases/class.tiporelacionBSN.php");
include_once ("clases/class.clienteBSN.php");

class Relacion {

    private $id_pc;
    private $id_sc;
    private $id_relacion;
    private $desc_pc;
    private $desc_sc;
    private $desc_rel;

    public function __construct($id_pc = 0, $id_sc = 0, $id_relacion = 0
    ) {

        Relacion::setId_pc($id_pc);
        Relacion::setId_sc($id_sc);
        Relacion::setId_relacion($id_relacion);
    }

    public function seteaRelacion($_relac) {
        $this->setId_pc($_relac->getId_pc());
        $this->setId_sc($_relac->getId_sc());
        $this->setId_relacion($_relac->getId_relacion());
    }

    public function setId_pc($_id_pc) {
        $this->id_pc = $_id_pc;
    }

    public function setId_sc($_id_sc) {
        $this->id_sc = $_id_sc;
    }

    public function setId_relacion($id_relacion) {
        $this->id_relacion = $id_relacion;
    }

    public function setDesc_pc($desc) {
        $this->desc_pc = $desc;
    }

    public function setDesc_sc($desc) {
        $this->desc_sc = $desc;
    }

    public function setDesc_rel($desc) {
        $this->desc_rel = $desc;
    }

    public function getId_pc() {
        return $this->id_pc;
    }

    public function getId_sc() {
        return $this->id_sc;
    }

    public function getId_relacion() {
        return $this->id_relacion;
    }

    public function getDesc_pc() {
        return $this->desc_pc;
    }

    public function getDesc_sc() {
        return $this->desc_sc;
    }

    public function getDesc_rel() {
        return $this->desc_rel;
    }

}

// Fin clase RELACION

class RelacionBSN extends BSN {

    protected $clase = "Relacion";
    protected $nombreId = "id_pc";
    protected $relacion;

    public function __construct($_id_pc = 0, $_id_sc = 0, $_id_rel = 0) {
        RelacionBSN::seteaMapa();
        if ($_id_pc instanceof Relacion) {
            RelacionBSN::creaObjeto();
            RelacionBSN::seteaBSN($_id_pc);
        } else {
            if (is_numeric($_id_pc) && is_numeric($_id_sc) && is_numeric($_id_rel)) {
                RelacionBSN::creaObjeto();
                if ($_id_pc != 0) {
                    RelacionBSN::setId_pc($_id_pc);
                }
                if ($_id_sc != 0) {
                    RelacionBSN::setId_sc($_id_sc);
                }
                if ($_id_rel != 0) {
                    RelacionBSN::setId_rel($_id_rel);
                }
            }
        }
    }

    protected function setId_pc($_id) {
        $this->relacion->setId_pc($_id);
    }

    protected function setId_sc($_id) {
        $this->relacion->setId_sc($_id);
    }

    protected function setId_rel($_id) {
        $this->relacion->setId_relacion($_id);
    }

    /**
     * retorna el ID del objeto
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->relacion->getId_pc();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->relacion->setId_pc($id);
    }

    /**
     * ELimina los registros de las relaciones existentes entre Cientes, Usuarios y Propiedades; de acuerdo a los
     * ID cargados en el objeto al momento de invocar el metodo.
     * El contenido en 0 o '' de uno de los ID implica la no consideracoin del mismo.
     */
    public function borraDB() {
        $relDB = new RelacionPGDAO();
        $_id_pc = $this->getObjeto()->getId_pc();
        $_id_sc = $this->getObjeto()->getId_sc();
        $_id_rel = $this->getObjeto()->getId_relacion();
        $ret = $relDB->deleteByOpcion($_id_pc, $_id_sc, $_id_rel);
    }

    /**
     * Retorna un array Bidimensional conteniendo una coleccion de datos, con los registros de las relaciones que cumplen
     * con los parametros de entrada; el contener un 0 o '' implica que dicho parametro no se tomara en cuenta para el armado
     * de la coleccion
     * @param int $_id_pc -> id del primer parametro de la relacion, basado en el criterio de tipo
     * @param int $_id_sc -> id del segundo parametro de la relacion, basado en el criterio de tipo
     * @param int $_id_rel ->  id del tipo de relacion
     * @return string[][] -> conteniendo la coleccion de registros
     */
    public function coleccionRelaciones($_id_pc = 0, $_id_sc = 0, $_id_rel = 0) {
        $arrayRet = array();
        $relDB = new RelacionPGDAO();
        $result = $relDB->coleccionRelaciones($_id_pc, $_id_sc, $_id_rel);
        $arrayRet = $this->leeDBArray($result);
        $retorno = $this->cargaDatosRelaciones($arrayRet);
        return $retorno;
    }

    /**
     * Retorna un array Bidimensional conteniendo una coleccion de datos, con los registros de las relaciones entre USUARIOS y CLIENTES 
     * que cumplen con los parametros de entrada; el contener un 0 o '' implica que dicho parametro no se tomara en cuenta
     *  para el armado de la coleccion
     * @param int $_id_pc -> id del USUARIO
     * @param int $_id_sc -> id del CLIENTE
     * @return string[][] -> conteniendo la coleccion de registros
     */
    public function coleccionRelacionesUC($_id_pc = 0, $_id_sc = 0) {
        $trelBSN = new TiporelacionBSN();
        $arrayRel = $trelBSN->coleccionRelacionByTipo('UC');
        $arrayRet = array();
        foreach ($arrayRel as $relacion) {
            $ret = $this->coleccionRelaciones($_id_pc, $_id_sc, $relacion['id_tiporel']);
            $arrayRet = array_merge($arrayRet, $ret);
        }
        return $arrayRet;
    }

    protected function cargaDatosRelaciones($arrayDatos) {
        // Recorrer el array y levantar los datos de las tablas que sea necesario segun el tipo de relacion que resulte de ID_REL
        // Cargar la informacion en los detalles de cada uno de ellos.
        $relAnt = 0;
        $pcAnt = 0;
        $scAnt = 0;

        $pPar = '';
        $pAnt = '';
        $sPar = '';
        $sAnt = '';

        $tipo = '';
        $descRel = '';
        $descPc = '';
        $descSc = '';

        $trelBSN = new TiporelacionBSN();

        $cant = sizeof($arrayDatos);
        for ($pos = 0; $pos < $cant; $pos++) {
            // Busco el tipo de relacion cada vez que sea diferente al anterio
            $tiporel = $arrayDatos[$pos]['id_relacion'];
            if ($tiporel != $relAnt) {
                $relAnt = $tiporel;
                $trelBSN->setId($tiporel);
                $trelBSN->cargaById($tiporel);
                $tipo = $trelBSN->getObjeto()->getTiporelacion();
                $descRel = $trelBSN->getObjeto()->getRelacion() . " - " . $trelBSN->getObjeto()->getGrado();
            }
            $pPar = substr($tipo, 0, 1);
            $sPar = substr($tipo, 1, 1);

            // Busco el Primer componente cada vez que difiera el ID o el tipo de Primer Componente
            $pId = $arrayDatos[$pos]['id_pc'];
            if ($pId != $pcAnt || $pPar != $pAnt) {
                $pcAnt = $pId;
                $pAnt = $pPar;
                $descPc = $this->buscaDescripcionComponente($pPar, $pId);
            }

            // Busco el Segundo componente cada vez que difiera el ID o el tipo de Segundo Componente
            $sId = $arrayDatos[$pos]['id_sc'];
            if ($sId != $scAnt || $sPar != $sAnt) {
                $scAnt = $sId;
                $sAnt = $sPar;
                $descSc = $this->buscaDescripcionComponente($sPar, $sId);
            }
            $arrayDatos[$pos]['desc_pc'] = $descPc;
            $arrayDatos[$pos]['desc_sc'] = $descSc;
            $arrayDatos[$pos]['desc_rel'] = $descRel;
        }
        return $arrayDatos;
    }

    /**
     * Busca y arma la descripcion de los componentes basado en el tipo de componente al que se hace referencia
     * @param string $tipo -> Identificacion del tipo de componente
     * @param int $id -> Id del componente
     * @return string -> descripcion del componente
     */
    protected function buscaDescripcionComponente($tipo, $id) {
        $retorno = '';
//        echo $tipo." - ".$id."<br>";
        if (is_numeric($id) && $id != 0) {
            switch ($tipo) {
                case 'U':
                    $retorno = $this->buscaDescripcionUsuario($id);
                    break;
                case 'C':
                    $retorno = $this->buscaDescripcionContacto($id);
                    break;
                case 'P':
                    $retorno = $this->buscaDescripcionPropiedad($id);
                    break;
                default:
                    break;
            }
        }
        return $retorno;
    }

    public function buscaDescripcionUsuario($id) {
        $userBSN = new LoginwebuserBSN($id);
        $desc = $userBSN->buscaDescripcionUsuario();
        return $desc;
    }

    public function buscaDescripcionPropiedad($id) {
        $propBSN = new PropiedadBSN($id);
        $desc=$propBSN->buscaDescripcionPropiedad();//$id);
        return $desc;
    }

    public function buscaDescripcionContacto($id) {
        $desc = '';
        $cliBSN = new ClienteBSN($id);
        $desc =$cliBSN->buscaDescripcionCliente();
        return $desc;
    }

    /**
     * Retorna un array con todas las relaciones existentes para un cliente determinado
     * @param type $id
     * @return type 
     */
    public function cargaRelacionesCliente($id) {
        return $this->cargaRelacionByComponente($id, 'C');
    }

    protected function cargaRelacionByComponente($id, $componente) {
        $tipos = new TiporelacionBSN();
        $listaTipos1 = $tipos->armaListaRelacionesByComponente($componente, 1);
        $listaTipos2 = $tipos->armaListaRelacionesByComponente($componente, 2);

        $relDB = new RelacionPGDAO();

        $result1 = $relDB->relacionByParte($id, 1, $listaTipos1);
        $arrayRet1 = $this->leeDBArray($result1);
        $retorno1 = $this->cargaDatosRelacionesReducida($id, $componente, 1, $arrayRet1);

        $result2 = $relDB->relacionByParte($id, 2, $listaTipos2);
        $arrayRet2 = $this->leeDBArray($result2);
        $retorno2 = $this->cargaDatosRelacionesReducida($id, $componente, 2, $arrayRet2);

        $retorno = array_merge($retorno1, $retorno2);

        return $retorno;
    }

    protected function cargaDatosRelacionesReducida($id, $componente, $parte, $arrayDatos) {
        $arrayRet = array();

        $pPar = '';
        $sPar = '';

        $tipo = '';
        $descRel = '';

        $trelBSN = new TiporelacionBSN();
        $cant = sizeof($arrayDatos);
        for ($pos = 0; $pos < $cant; $pos++) {
            // Busco el tipo de relacion cada vez que sea diferente al anterio
            $tiporel = $arrayDatos[$pos]['id_relacion'];
            $trelBSN->setId($tiporel);
            $trelBSN->cargaById($tiporel);

            $tipo = $trelBSN->getObjeto()->getTiporelacion();

            $subtipo = " - " . $trelBSN->getObjeto()->getGrado();
            if (trim($subtipo) == '-') {
                $subtipo = '';
            }
            $descRel = $trelBSN->getObjeto()->getRelacion() . $subtipo;

            $pPar = substr($tipo, 0, 1);
            $sPar = substr($tipo, 1, 1);

            // Si el tipo es diferente al primer componente de la relacion o los ID son diferentes cargo el valor en el array
            if ($parte == 1) {
                $id_comp = $arrayDatos[$pos]['id_sc'];
                $desc = $this->buscaDescripcionComponente($sPar, $id_comp);
                $orden = 2;
            } else {
                $id_comp = $arrayDatos[$pos]['id_pc'];
                $desc = $this->buscaDescripcionComponente($pPar, $id_comp);
                $orden = 1;
            }
            $arrayRet[$pos]['id_comp'] = $id_comp;
            $arrayRet[$pos]['orden'] = $orden;
            $arrayRet[$pos]['desc_comp'] = $desc;
            $arrayRet[$pos]['id_rel'] = $tiporel;
            $arrayRet[$pos]['desc_rel'] = $descRel;
        }
        return $arrayRet;
    }

    /*
      protected function cargaDatosRelacionesReducida($id,$componente,$parte,$arrayDatos) {
      $arrayRet=array();

      $pPar = '';
      $sPar = '';

      $tipo = '';
      $descRel = '';

      $trelBSN = new TiporelacionBSN();
      $cant = sizeof($arrayDatos);
      for ($pos = 0; $pos < $cant; $pos++) {
      // Busco el tipo de relacion cada vez que sea diferente al anterio
      $tiporel = $arrayDatos[$pos]['id_relacion'];
      $trelBSN->setId($tiporel);
      $trelBSN->cargaById($tiporel);

      echo "<br>";print_r($trelBSN);

      $tipo = $trelBSN->getObjeto()->getTiporelacion();

      echo "<br><br><br>".$tipo."<br>";

      $subtipo=" - " . $trelBSN->getObjeto()->getGrado();
      if(trim($subtipo)=='-'){
      $subtipo='';
      }
      $descRel = $trelBSN->getObjeto()->getRelacion() . $subtipo;

      $pPar = substr($tipo, 0, 1);
      $sPar = substr($tipo, 1, 1);

      echo $pPar." - ".$sPar."<br>";

      echo $_parte." * ".$arrayDatos[$pos]['id_pc']." * ".$id."<br>";
      // Si el tipo es diferente al primer componente de la relacion o los ID son diferentes cargo el valor en el array
      if($pPar!=$_parte || $arrayDatos[$pos]['id_pc']!=$id){
      $id_comp = $arrayDatos[$pos]['id_pc'];
      $desc = $this->buscaDescripcionComponente($pPar, $id_comp);
      $orden=1;
      }else{
      $id_comp = $arrayDatos[$pos]['id_sc'];
      $desc = $this->buscaDescripcionComponente($sPar, $id_comp);
      $orden=2;
      }
      $arrayRet[$pos]['id_comp']=$id_comp;
      $arrayRet[$pos]['orden']=$orden;
      $arrayRet[$pos]['desc_comp'] = $desc;
      $arrayRet[$pos]['id_rel']=$tiporel;
      $arrayRet[$pos]['desc_rel'] = $descRel;
      }
      return $arrayRet;
      }
     */
}

class RelacionPGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.relaciones (id_pc,id_sc,id_relacion) values (#id_pc#,#id_sc#,#id_relacion#)";
    protected $DELETEBASE = "DELETE FROM #dbName#.relaciones ";
    protected $FINDBYID = "SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones WHERE id_pc=#id_pc# AND id_sc=#id_sc# AND id_relacion=#id_relacion#";
    protected $FINDBYCLAVE = "SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones WHERE id_pc=#id_pc# AND id_sc=#id_sc# AND id_relacion=#id_relacion#";
    protected $COLECCION = "SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones ORDER BY id_pc,id_relacion";
    protected $COLECCIONBASE = "SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones";

    public function relacionByParte($id, $parte, $lista) {
        $parametro = "";
        $sqlBase = "SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones WHERE id_relacion in ($lista) ";
        if ($parte == 1) {
            $where = " AND id_pc=$id";
        } else {
            $where = " AND id_sc=$id";
        }
        $sqlStr = $sqlBase . $where;
        $resultado = $this->execSql($sqlStr, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLECTIO", "COLECCION BY PARTE " . $sqlStr);
        }
        return $resultado;
    }

    public function deleteByOpcion($_id_pc = 0, $_id_sc = 0, $_id_rel = 0) {
        $parametro = "";
        $where = $this->armaWhere($_id_pc, $_id_sc, $_id_rel);
        $resultado = $this->execSql($this->DELETEBASE . $where, $parametro);
        if (!$resultado) {
            $this->onError("COD_DELETE", "BORRADO POR OPCION " . $this->DELETEBASE . $where);
        }
        return $resultado;
    }

    public function update() {
        return true;
    }

    public function coleccionRelaciones($_id_pc = 0, $_id_sc = 0, $_id_rel = 0) {
        $parametro = func_get_args();
        $where = $this->armaWhere($_id_pc, $_id_sc, $_id_rel);
        $order = $this->armaOrden($_id_pc, $_id_sc, $_id_rel);
        $resultado = $this->execSql($this->COLECCIONBASE . $where . $order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", "RELACIONES ENTRE COMPONENTES" . $this->COLECCIONBASE . $where . $order);
        }
        return $resultado;
    }

    protected function armaWhere($_id_pc = 0, $_id_sc = 0, $_id_rel = 0) {
        $where = ' WHERE ';
        if (is_numeric($_id_pc) && $_id_pc != 0) {
            $where.=" id_pc=$_id_pc ";
        }
        if (is_numeric($_id_sc) && $_id_sc != 0) {
            if ($where != ' WHERE ') {
                $where.='AND';
            }
            $where.=" id_sc=$_id_sc ";
        }
        if (is_numeric($_id_rel) && $_id_rel != 0) {
            if ($where != ' WHERE ') {
                $where.='AND';
            }
            $where.=" id_relacion=$_id_rel ";
        }
        return $where;
    }

    protected function armaOrden($_id_pc = 0, $_id_sc = 0, $_id_rel = 0) {
        $orden = '';
        if (is_numeric($_id_rel) && $_id_rel != 0) {
            $orden = "id_relacion";
        }
        if (is_numeric($_id_pc) && $_id_pc != 0) {
            if ($orden != '') {
                $orden.=',';
            }
            $orden.=" id_pc";
        }
        if (is_numeric($_id_sc) && $_id_sc != 0) {
            if ($orden != '') {
                $orden.=',';
            }
            $orden.=" id_sc ";
        }
        if ($orden != '') {
            $orden = ' ORDER BY ' . $orden;
        }
        return $orden;
    }

}

// Fin clase DAO

class RelacionVW extends VW {

    protected $clase = "Relacion";
    protected $relacion;
    protected $nombreId = "Id_relacion";

    public function __construct($_id_pc = 0, $_id_sc = 0, $_relacion = 0) {
        RelacionVW::creaObjeto();
        if ($_id_pc instanceof Relacion) {
            RelacionVW::seteaVW($_id_pc);
        }
        if (is_numeric($_id_pc) && is_numeric($_id_sc) && is_numeric($_relacion)) {
            if ($_id_pc != 0 || $_id_sc != 0 || $_relacion != 0) {
                RelacionVW::cargaVW($_id_pc, $_id_sc, $_relacion);
            }
        }
        RelacionVW::cargaDefinicionForm();
    }

    public function cargaRelacionUsuarioCliente($_id_user = 0, $_id_contacto = 0) {
        if (is_numeric($_id_user) && is_numeric($_id_contacto)) {
            $this->cargaDatosRelacion('UC', $_id_user, $_id_contacto, 0);
        }
    }

    public function cargaRelacionUsuarioPropiedad($_id_user = 0, $_id_prop = 0) {
        if (is_numeric($_id_user) && is_numeric($_id_prop)) {
            $this->cargaDatosRelacion('UP', $_id_user, $_id_prop, 0);
        }
    }

    public function cargaRelacionUsuarioUsuario($_id_userp = 0, $_id_userc = 0) {
        if (is_numeric($_id_userp) && is_numeric($_id_userc)) {
            $this->cargaDatosRelacion('UU', $_id_user, $_id_userc, 0);
        }
    }

    public function cargaRelacionClientePropiedad($_id_contacto = 0, $_id_prop = 0) {
        if (is_numeric($_id_contacto) && is_numeric($_id_prop)) {
            $this->cargaDatosRelacion('CP', $_id_contacto, $_id_prop, 0);
        }
    }

    public function cargaRelacionClienteCliente($_id_contactop = 0, $_id_contactoc = 0) {
        if (is_numeric($_id_contactop) && is_numeric($_id_contactoc)) {
            $this->cargaDatosRelacion('CC', $_id_contactop, $_id_contactoc, 0);
        }
    }

    public function cargaDatosRelacion($tipo, $_id_pc, $_id_sc, $_id_rel) {
        $tipoRelBSN = new TiporelacionBSN();

        print "<script type='text/javascript' language='javascript'>\n";
        print "function actualizaComboRelacion(){\n";
        print "     tipo=document.getElementById('tiporelacion').value;\n";
        print "		id_pc=$_id_pc;\n";
        print "		id_sc=$_id_sc;\n";
        print "		id_rel=$_id_rel;\n";
        print "   	cargaComponentesRelacion(tipo,id_pc,id_sc,id_rel,'divComponentes');\n";
        print "}\n";
        print "function actualizaComboRelacionRemoto(campo,valor){\n";
        print "     tipo=document.getElementById('tiporelacion').value;\n";
        print "     if(campo=='id_pc'){\n";
        print "         id_pc=valor;\n";
        print "     }else{\n";
        print "         id_pc=$_id_pc;\n";
        print "     }\n";
        print "     if(campo=='id_sc'){\n";
        print "         id_sc=valor;\n";
        print "     }else{\n";
        print "         id_sc=$_id_sc;\n";
        print "     }\n";
        print "	    id_rel=$_id_rel;\n";
        print "     cargaComponentesRelacion(tipo,id_pc,id_sc,id_rel,'divComponentes');\n";
        print "}\n";

        print "function submitForm(){\n";
        print "    window.open('','ventanaRelacion','width=300,height=200');\n";
        print "}\n";

        print "</script>\n";

        print "<div id='cargaDataRelacion' name='cargaDataRelacion' style='display:none;'>";

        print "<form action='carga_Relacion.php' name='carga' id='carga' method='post' target='ventanaRelacion' onSubmit='javascript: if(ValidaRelacion(this)){submitForm();};'>\n";
        print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_titulo' colspan='2'>Carga de Relacion ";
        if (strlen($tipo) == 2) {
            print $tipoRelBSN->getDescripcionTipo($tipo);
            print "</td></tr>\n";
        } else {
            print "</td></tr>\n";
            print "<tr><td class='cd_celda_texto' width='15%'>Tipo de Relacion</td>";
            print "<td width='85%'>";
            $tipoRelBSN->comboTipoRelacion($tipo, 1);
            print "</td></tr>\n";
        }
        print "<tr><td align='center' colspan='2'>";
        print "<div id='divComponentes'>";
        if (strlen($tipo) == 2) {
            $this->cargaCamposRelacion($tipo, $_id_pc, $_id_sc, $_id_rel);
        }
        print "</div>";

        print "<br>";
        print "</td></tr>\n</table>\n";
        print "</form>\n";

        print "</div>\n";
    }

    public function cargaDatosRelacionCliente($tipo, $_id_cli) {
        $tipoRelBSN = new TiporelacionBSN();

        print "<script type='text/javascript' language='javascript'>\n";
        print "function actualizaComboRelacion(){\n";
        print "     tipo=document.getElementById('tiporelacion').value;\n";
        print "		id_cli=$_id_cli;\n";
        print "   	cargaComponentesRelacionCliente(tipo,id_cli,'divComponentes');\n";
        print "}\n";

        print "function submitForm(){\n";
        print "    window.open('','ventanaRelacionCli','width=300,height=200');\n";
        print "}\n";

        print "</script>\n";

//        print "<div id='cargaDataRelacion' name='cargaDataRelacion' style='display:none;'>";
        print "<div id='cargaDataRelacion' name='cargaDataRelacion' style='display:none; clear:both;' >";

        print "<form action='carga_RelacionCliente.php' name='carga' id='carga' method='post' target='ventanaRelacionCli' onSubmit='javascript: if(ValidaRelacion(this)){submitForm();};'>\n";
        print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_titulo' colspan='2'>Carga de Relacion ";
        print "</td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Tipo de Relacion</td>";
        print "<td width='85%'>";
        $tipoRelBSN->comboTipoRelacion($tipo, 1);
        print "</td></tr>\n";
        print "<tr><td align='center' colspan='2'>";
        print "<div id='divComponentes'>";
        print "</div>";

        print "<br>";
        print "</td></tr>\n</table>\n";
        print "</form>\n";

        print "</div>\n";
    }

    public function cargaCamposRelacion($tipo, $_id_pc, $_id_sc, $_id_rel) {
        $tipoRelBSN = new TiporelacionBSN();
        if ($tipo != '') {
            print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
            // primer componente
            if ($_id_pc != 0) {
                $this->armaDetalleComponente(substr($tipo, 0, 1), $_id_pc, 'id_pc');
            } else {
                $this->armaComboComponente(substr($tipo, 0, 1), $_id_pc, 'id_pc');
            }
            // segundo componente
            if ($_id_sc != 0) {
                $this->armaDetalleComponente(substr($tipo, 1, 1), $_id_sc, 'id_sc');
            } else {
                $this->armaComboComponente(substr($tipo, 1, 1), $_id_sc, 'id_sc');
            }

            print "<tr><td class='cd_celda_texto' width='15%'>Relacion<span class='obligatorio'>&nbsp;&bull;</span></td>";
            print "<td width='85%'>";
            if ($_id_rel == 0) {
                $tipoRelBSN->comboRelacion(0, $tipo, 'id_relacion');
            } else {
                $tipoRelBSN->cargaById($_id_rel);
                print $tipoRelBSN->getObjeto()->getRelacion() . " - " . $tipoRelBSN->getObjeto()->getGrado();
                print "<input type='hidden' name='id_relacion' id='id_relacion' value='$_id_rel'>";
            }
            print "</td></tr>\n";
            print "<input type='hidden' name='opcion' id='opcion' value=''>";
            print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td>";
            print "</tr>\n</table>\n";
        }
    }

    protected function armaDetalleComponente($tipo, $id, $campo) {
        print "<tr><td class='cd_celda_texto' width='15%'>";
        $relAux = new RelacionBSN();
        switch ($tipo) {
            case 'U':
                $label = "Usuario";
                $mostrar = $relAux->buscaDescripcionUsuario($id);
                break;
            case 'P':
                $label = "Propiedad";
                $mostrar = $relAux->buscaDescripcionPropiedad($id);
                break;
            case 'C':
                $label = "Contacto";
                $mostrar = $relAux->buscaDescripcionContacto($id);
//                $mostrar = "No existen datos para mostrar";
                break;
            default:
                ;
                break;
        }
        print "$label<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        print $mostrar;
        print "<input type='hidden' name='$campo' id='$campo' value='$id'>";
        print "</td></tr>\n";
    }

    protected function armaComboComponente($tipo, $id, $campo) {
        print "<tr><td class='cd_celda_texto' width='15%'>";
        switch ($tipo) {
            case 'U':
                $label = "Usuario";
                print "$label<span class='obligatorio'>&nbsp;&bull;</span></td>";
                print "<td width='85%'>";
                $usrBSN2 = new LoginwebuserBSN();
                $usrBSN2->cargaById($id);
                $usrBSN2->comboUsuarios($id, $campo);
                break;
            case 'P':
                $label = "Propietario";
                print "$label<span class='obligatorio'>&nbsp;&bull;</span></td>";
                print "<td width='85%'>";

                print "No se ha especificado una propiedad";
                break;
            case 'C':
                $label = "Cliente";
                print "$label<span class='obligatorio'>&nbsp;&bull;</span></td>";
                print "<td width='85%'>";
                $cliBSN = new ClienteBSN();
                $cliBSN->cargaById($id);
                $cliBSN->comboUsuarios($id, $campo);
                print "<br /><input type='button' value='Nuevo ...' onclick=\"window.open('carga_cliente.php?c=0&cpo=$campo', 'ventanaCli', 'scrollbars=yes,menubar=1,resizable=1,width=1020,height=800');\"'>";
                break;
            default:
                break;
        }
        print "</td></tr>\n";
    }

    /*     * Muestra una tabla con los datos de las relacions y una barra de herramientas o menu 
     *  donde se despliegan las opciones ingresables para cada item 
     */

    public function vistaPropietario($_id_pc = 0, $_id_sc = 0, $_id_rel = 0) {
        $fila = 0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function showHideDesc(campo,imagen){\n";
        print "  if(document.getElementById(campo).style.display=='none'){\n";
        print "   document.getElementById(campo).style.display='block';\n";
        print "   document.getElementById(imagen).src='images/fabajo.png';\n";
        print "  }else{\n";
        print "   document.getElementById(campo).style.display='none';\n";
        print "   document.getElementById(imagen).src='images/fderecha.png';\n";
        print "  }\n";
        print "}\n";
        print "</script>\n";

        print "<div style=\"margin:10px;\">\n";
        print "<div class='pg_titulo'>Listado de Relaciones</div>\n";

//        print "  <table class='cd_tabla' width='100%'>\n";
//        print "    <tr>\n";
//        print "     <td class='cd_lista_titulo'>Nombre</td>\n";
////        print "     <td class='cd_lista_titulo'>Destino</td>\n";
//        print "     <td class='cd_lista_titulo'>Relacion</td>\n";
//        print "	  </tr>\n";
        $evenBSN = new RelacionBSN();
        $arrayEven = $evenBSN->coleccionRelaciones($_id_pc, $_id_sc, $_id_rel);
        if (sizeof($arrayEven) == 0) {
            print "No existen datos para mostrar";
        } else {
            foreach ($arrayEven as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
//                print "<tr>\n";
//                print "	 <td  class='row" . $fila . "'>";
//                if ($_id_pc == 0) {
//                    $divPC = "<td>";
//                    $divPC.=$this->descripcionComponenteRelacion($Even ['id_pc'], 1, $Even ['id_relacion']);
//                    $divPC.="</td>\n";
//                } else {
//                    $imagenPC = '';
//                    $divPC = '';
//                }
                print "<div  class='row" . $fila . "'>" . $Even ['desc_pc'] . " - " . $Even ['desc_rel'] . "</div>\n";
                print "<div>" . $this->descripcionComponenteRelacion($Even ['id_pc'], 1, $Even ['id_relacion']) . "</div>\n";
                //                print "</tr>\n";
            }
        }
        //       print "  </table>\n";
        print "</div>\n";
    }

    /*     * Muestra una tabla con los datos de las relacions y una barra de herramientas o menu 
     *  donde se despliegan las opciones ingresables para cada item 
     */

    public function vistaTablaVW($_id_pc = 0, $_id_sc = 0, $_id_rel = 0) {
        $fila = 0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.perfil.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "function muestracargaDataRelacion(){\n";
        print "   document.getElementById('cargaDataRelacion').style.display='block';\n";
        print "}\n";
        print "function borra(pc,sc,relacion){\n";
        print "   window.open('carga_Relacion.php?pc='+pc+'&sc='+sc+'&r='+relacion+'&b=b', 'ventanaRelacion', 'menubar=1,resizable=1,width=950,height=550');\n";
        print "}\n";
        print "function showHideDesc(campo,imagen){\n";
        print "  if(document.getElementById(campo).style.display=='none'){\n";
        print "   document.getElementById(campo).style.display='block';\n";
        print "   document.getElementById(imagen).src='images/fabajo.png';\n";
        print "  }else{\n";
        print "   document.getElementById(campo).style.display='none';\n";
        print "   document.getElementById(imagen).src='images/fderecha.png';\n";
        print "  }\n";
        print "}\n";
        print "</script>\n";


        print "<div class='pg_titulo'>Listado de Relaciones</div>\n";

        $arrayTools = array(array('Nuevo', 'images/relacion-plus.png', 'muestracargaDataRelacion()'));
        $menu = new Menu();
        $menu->barraHerramientas($arrayTools);

        print "<form name='lista' method='POST' action='respondeMenu.php'>";
        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td class='cd_lista_titulo'>&nbsp;</td>\n";
        print "     <td class='cd_lista_titulo'>Origen</td>\n";
        print "     <td class='cd_lista_titulo'>Destino</td>\n";
        print "     <td class='cd_lista_titulo'>Relacion</td>\n";
        print "	  </tr>\n";
        $evenBSN = new RelacionBSN();
        $arrayEven = $evenBSN->coleccionRelaciones($_id_pc, $_id_sc, $_id_rel);
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
                print "	 <td  align='center' width='25' class='row" . $fila . "'>";
                print "    <a href='javascript:borra(" . $Even ['id_pc'] . ", " . $Even ['id_sc'] . ", " . $Even ['id_relacion'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
                print "  </td>\n";
                print "	 <td  class='row" . $fila . "'>";
                if ($_id_pc == 0) {
                    $imagenPC = "<img id='imgDescRelPC" . $Even ['id_pc'] . "' src='images/fderecha.png' alt='Mostrar' title='Mostrar' border=0 onclick=\"javascript:showHideDesc('descRelPC_" . $Even ['id_pc'] . "','imgDescRelPC" . $Even ['id_pc'] . "');\">";
                    $divPC = "<div id='descRelPC_" . $Even ['id_pc'] . "' style='display:none;'>";
                    $divPC.=$this->descripcionComponenteRelacion($Even ['id_pc'], 1, $Even ['id_relacion']);
                    $divPC.="</div>\n";
                } else {
                    $imagenPC = '';
                    $divPC = '';
                }
                print $imagenPC . $Even ['desc_pc'];
                print $divPC;
                print "</td>\n";
                print "	 <td  class='row" . $fila . "'>";
                if ($_id_sc == 0) {
                    $imagenSC = "<img id='imgDescRelSC" . $Even ['id_sc'] . "' src='images/fderecha.png' alt='Mostrar' title='Mostrar' border=0 onclick=\"javascript:showHideDesc('descRelSC_" . $Even ['id_sc'] . "','imgDescRelSC" . $Even ['id_sc'] . "');\">";
                    $divSC = "<div id='descRelSC_" . $Even ['id_sc'] . "' style='display:none;'>";
                    $divSC.=$this->descripcionComponenteRelacion($Even ['id_sc'], 2, $Even ['id_relacion']);
                    $divSC.="</div>\n";
                } else {
                    $imagenSC = '';
                    $divSC = '';
                }
                print $imagenSC . $Even ['desc_sc'];
                print $divSC;
                print "</td>\n";
                print "	 <td  class='row" . $fila . "'>" . $Even ['desc_rel'] . "</td>\n";
                print "	</tr>\n";
            }
        }
        print "  </table>\n";
        print "<input type='hidden' name='perfil' id='perfil' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "</form>";
    }

    protected function descripcionComponenteRelacion($id, $pos, $relacion) {
        $strRet = '';
//        if ($id != '' && $pos != '' && $relacion != '') {
        $trelBSN = new TiporelacionBSN($relacion);
        $tipo = $trelBSN->getObjeto()->getTiporelacion();
        $comp = substr($tipo, $pos - 1, 1);
        $strRet = $this->buscaDetalleComponenteVW($comp, $id);
        //      }
        return $strRet;
    }

    protected function buscaDetalleComponenteVW($tipo, $id) {
        $retorno = '';
        if (is_numeric($id) && $id != 0) {
            switch ($tipo) {
                case 'U':
                    $retorno = $this->buscaDetalleUsuario($id);
                    break;
                case 'C':
                    $retorno = $this->buscaDetalleContacto($id);
                    break;
                case 'P':
                    $retorno = $this->buscaDetallePropiedad($id);
                    break;
                default:
                    break;
            }
        }
        return $retorno;
    }

    protected function buscaDetalleUsuario($id) {
        $retorno = '';
        $usrBSN = new LoginwebuserBSN();
        $retorno = $usrBSN->buscaDetalleUsuario($id);
        return $retorno;
    }

    protected function buscaDetalleContacto($id) {
        $retorno = '';
        $cliBSN = new ClienteBSN();
        $retorno=$cliBSN->buscaDetalleCliente($id);
        return $retorno;
    }

    protected function buscaDetallePropiedad($id) {
        $retorno = '';
        $propBSN = new PropiedadBSN($id);
        $retorno=$propBSN->buscaDetallePropiedad();
        return $retorno;
    }

    public function vistaRelacionesUsuarioCliente($id_usr, $id_cli, $vista = 0) {
        $relBSN = new RelacionBSN();
        $arrayDatos = $relBSN->coleccionRelacionesUC($id_usr, $id_cli);
        $this->listaRelacionesCliente('C', $arrayDatos);
    }

    protected function listaRelacionesCliente($componente, $arrayDatos) {
        $fila = 0;

        print "<div id=\"datosRel\">\n";
        print "<div class='pg_subtitulo'>Detalle de Relaciones</div>\n";
        if (sizeof($arrayDatos) == 0) {
            print "No existen Relaciones para mostrar";
        } else {
            print "  <table class='cd_tabla' width='100%'>\n";
            print "    <tr>\n";

            foreach ($arrayDatos as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "	 <td class='row" . $fila . "'>" . $Even['desc_pc'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['desc_rel'] . "</td>\n";
                print "	</tr>\n";
            }
            print "  </table>\n";
        }
        print "  </div>\n";
    }

    /*     * Muestra una tabla con los datos de las relacions y una barra de herramientas o menu 
     *  donde se despliegan las opciones ingresables para cada item 
     */

    public function vistaRelacionesCliente($id, $vista = 0) {
        $relBSN = new RelacionBSN();
        $arrayDatos = $relBSN->cargaRelacionesCliente($id);
        if ($vista == 0) {
            $this->vistaRelaciones($id, 'C', $arrayDatos);
        } else {
            $this->vistaRelacionesExtendida($id, 'C', $arrayDatos);
        }
    }

    protected function vistaRelaciones($id, $componente, $arrayDatos) {
        $fila = 0;

        print "<div id=\"datosRel\">\n";
        print "<li class='tituloVistaRel'>";
        print "<img src='images/relacion-plus.png'border=0 onclick=\"javascript:";
        if ($componente == 'C') {
            $destino = 'carga_RelacionCliente.php?c=' . $id;
        }
        print "window.open('$destino', 'ventanaRelacion', 'menubar=1,resizable=1,width=950,height=550');\n";

        print "\"> &nbsp;";
        print "Detalle de Relaciones</li>\n";
        if (sizeof($arrayDatos) == 0) {
            print "No existen Relaciones para mostrar";
        } else {
            foreach ($arrayDatos as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "<li class='datoVistaRel'>";
                $imagenPC = "<img id='imgDescRel" . $Even ['id_comp'] . "' src='images/fderecha.png' alt='Mostrar' title='Mostrar' border=0 onclick=\"javascript:showHideDesc('descRel_" . $Even ['id_comp'] . "','imgDescRel" . $Even ['id_comp'] . "');\">";
                $divPC = "<div id='descRel_" . $Even ['id_comp'] . "' style='display:none;'>";
                $divPC.=$this->descripcionComponenteRelacion($Even ['id_comp'], $Even ['orden'], $Even ['id_rel']);
                $divPC.="</div>\n";
                print $imagenPC;
                if ($Even['orden'] == 2) {
                    print " <span class='detalleVistaRel'>" . $Even ['desc_rel'] . " </span>\n";
                    print $Even ['desc_comp'];
                } else {
                    print $Even ['desc_comp'];
                    print " (<span class='detalleVistaRel'>" . $Even ['desc_rel'] . " </span>)\n";
                }
                print "</li>";
                print $divPC;
            }
        }
        print "  </div>\n";
    }

    protected function vistaRelacionesExtendida($id, $componente, $arrayDatos) {
        $fila = 0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.perfil.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "function muestracargaDataRelacion(){\n";
        print "   document.getElementById('cargaDataRelacion').style.display='block';\n";
        print "}\n";
        print "function borra(pc,sc,relacion){\n";
        if ($componente == 'C') {
            print "   window.open('carga_RelacionCliente.php?pc='+pc+'&sc='+sc+'&r='+relacion+'&b=b', 'ventanaRelacionCli', 'scrollbars=yes,menubar=1,resizable=1,width=1000,height=550');\n";
        } else {
            print "   window.open('carga_Relacion.php?pc='+pc+'&sc='+sc+'&r='+relacion+'&b=b', 'ventanaRelacion', 'scrollbars=yes,menubar=1,resizable=1,width=1000,height=550');\n";
        }
        print "}\n";
        print "function showHideDesc(campo,imagen){\n";
        print "  if(document.getElementById(campo).style.display=='none'){\n";
        print "   document.getElementById(campo).style.display='block';\n";
        print "   document.getElementById(imagen).src='images/fabajo.png';\n";
        print "  }else{\n";
        print "   document.getElementById(campo).style.display='none';\n";
        print "   document.getElementById(imagen).src='images/fderecha.png';\n";
        print "  }\n";
        print "}\n";
        print "</script>\n";

        $arrayTools = array(array('Nuevo', 'images/relacion-plus.png', 'muestracargaDataRelacion()'));
        $menu = new Menu();
        $menu->barraHerramientas($arrayTools);

        print "<div id='vistaTablaCli' class='vistaTabla' >\n";
        print "<form name='lista' method='POST' action='respondeMenu.php'>";

        $relAux = new RelacionBSN();
        switch ($componente) {
            case 'U':
                $label = " del Usuario";
                $mostrar = $relAux->buscaDescripcionUsuario($id);
                break;
            case 'P':
                $label = " de la Propiedad";
                $mostrar = $relAux->buscaDescripcionPropiedad($id);
                break;
            case 'C':
                $label = " del Contacto";
                $mostrar = $relAux->buscaDescripcionContacto($id);
                break;
            default:
                ;
                break;
        }


        print "<div class='pg_titulo'>Detalle de Relaciones $label $mostrar</div>\n";
//        print $label.' '.$mostrar;
        if (sizeof($arrayDatos) == 0) {
            print "No existen Relaciones para mostrar";
        } else {
            print "<ul>\n";
            print "     <li class=\"li_lista_titulo\" id='relAcc' >&nbsp;</li>\n";
            print "     <li class=\"li_lista_titulo\" id='relParte' >Contraparte</li>\n";
            print "     <li class=\"li_lista_titulo\" id='relRel' >Relacion</li>\n";
            print "</ul>\n";
            print "<div style='overflow:auto; clear:both; height:250px;'>\n";

            foreach ($arrayDatos as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }

//                $onclick = "onclick=\"javascript: muestraDatos(" . $Even ['id_cli'] . ")\" ";

                print "<ul>\n";
                print "	 <li id='relAcc'>";
                if ($Even['orden'] == 2) {
                    $pc = $id;
                    $sc = $Even ['id_comp'];
                    $rel = $Even ['id_rel'];
                } else {
                    $sc = $id;
                    $pc = $Even ['id_comp'];
                    $rel = $Even ['id_rel'];
                }
                print "    <a href='javascript:borra(" . $pc . ", " . $sc . ", " . $rel . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
                print "  </li>\n";

                print "<li id='relParte'>";
                $imagenPC = "<img id='imgDescRel" . $Even ['id_comp'] . "' src='images/fderecha.png' alt='Mostrar' title='Mostrar' border=0 onclick=\"javascript:showHideDesc('descRel_" . $Even ['id_comp'] . "','imgDescRel" . $Even ['id_comp'] . "');\">";
                $divPC = "<div id='descRel_" . $Even ['id_comp'] . "' style='display:none;'>";
                $divPC.=$this->descripcionComponenteRelacion($Even ['id_comp'], $Even ['orden'], $Even ['id_rel']);
                $divPC.="</div>\n";
                print $imagenPC;
                print $Even ['desc_comp'];
                print $divPC;
                print "</li>";
                print "<li id='relRel'>";
                print " <span class='detalleVistaRel'>" . $Even ['desc_rel'] . " </span>\n";
                print "</li>";
                print "</ul>\n";
            }
        }
        print "  </div>\n";

        print "<input type='hidden' name='perfil' id='perfil' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "</form>";
        print "  </div>\n";
    }

}

?>
