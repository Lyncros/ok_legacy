<?php

/**
 * Clase Propia para la definicion de la logica de negocios.
 * Utiliza dos variables propias de la clase que las hereda llamadas
 * 		"clase" que define la base del nombre, debe tener la Primer letra en Mayuscula y responder a la base de los nombres
 * 					de los metodos propios.
 * 		"objeto" que define el nombre del objeto tipo de la clase, cuyo nombre debe ser igual al de la
 * 					clase pero todo en minuscula
 *
 * En la clase derivada se deben definir metodos que ejecuten
 * 		getId		-> Retorna el Id de la clase
 * 		getClave	-> Retorna la Clave de la clase
 *
 *
 * Ejemplo del uso del retorno de la clase para el armado de un metodo de la clase derivada.
 * 	public function muestra(){
 * 		$var='muestra'.$this->getClase();
 * 		$this->{$var}();
 * 	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.domicilio.php");
include_once("clases/class.domicilioPGDAO.php");
include_once("clases/class.clienteCache.php");

//include_once ("clases/class.logBSN.php");


class DomicilioBSN extends BSN {

    protected $clase = "Domicilio";
    protected $nombreId = "id_dom";
    protected $domicilio;
    //	protected $tarea="Domicilio";
    protected $arrayTipos = array('Particular', 'Laboral', 'Familiar', 'Mensages');

    public function __construct($_id_dom = 0) {
        DomicilioBSN::seteaMapa();
        DomicilioBSN::creaObjeto();
        if ($_id_dom instanceof Domicilio) {
            DomicilioBSN::seteaBSN($_id_dom);
        } else {
            if (is_numeric($_id_dom)) {
                //				DomicilioBSN::creaObjeto();
                if ($_id_dom != 0) {
                    DomicilioBSN::cargaById($_id_dom);
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
        return $this->domicilio->getId_dom();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->domicilio->setId_dom($id);
    }

    /**
     * Retorna el array que contien los tipos de tipo de domicilio
     */
    public function getArrayTipo() {
        return $this->arrayTipos;
    }

    /**
     * Arma un combo con los tipos posibles de domicilio
     * @param string $valor -> valor del campo en el caso que sea conocido
     * @param string $campo -> nombre del campo en el formulario
     * @param string $class -> nombre de la clase que define el estilo del combo
     */
    public function comboTipodom($valor = '', $campo = "tipodom", $class = "campos_btn") {
        $tipoDom = $this->arrayTipos;
        print "<select name='" . $campo . "' id='" . $campo . "' class='" . $class . "'>\n";
        for ($pos = 0; $pos < sizeof($tipoDom); $pos++) {
            print "<option value='" . $tipoDom[$pos] . "'";
            if ($tipoDom[$pos] == $valor) {
                print " SELECTED ";
            }
            print ">" . $tipoDom[$pos] . "</option>\n";
        }
        print "</select>\n";
    }

    /**
     * Retorna una coleccion con los valores de domicilio definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del domicilio de contacto
     */
    public function principalByContacto($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('O', $_id, 1);
        }
        if (sizeof($arrayRet) != 0) {
            $retorno = $arrayRet[0];
        } else {
            $retorno = array();
        }
        return $retorno;
    }

    /**
     * Retorna una coleccion con los valores de domicilio definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del domicilio de contacto
     */
    public function principalByUsuarios($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('U', $_id, 1);
        }
        if (sizeof($arrayRet) != 0) {
            $retorno = $arrayRet[0];
        } else {
            $retorno = array();
        }
        return $retorno;
    }

    /**
     * Retorna una coleccion con los valores de domicilio definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del domicilio de contacto
     */
    public function principalByCliente($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('C', $_id, 1);
        }
        if (sizeof($arrayRet) != 0) {
            $retorno = $arrayRet[0];
        } else {
            $retorno = array();
        }
        return $retorno;
    }

    /**
     * Retorna una coleccion con los valores de domicilio definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del domicilio de contacto
     */
    public function coleccionByUsuarios($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('U', $_id, 0);
        }
        return $arrayRet;
    }

    /**
     * Retorna una coleccion con los valores de domicilio definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del domicilio de contacto
     */
    public function coleccionByCliente($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('C', $_id, 0);
        }
        return $arrayRet;
    }

    /**
     * Retorna una coleccion con los valores de domicilio definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del domicilio de contacto
     */
    public function coleccionByContactoAgenda($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('O', $_id, 0);
        }
        return $arrayRet;
    }

    /**
     * Arma un array con los domicilio segun el string pasado como parametro
     * @param string $_tipocont -> Tipo de contacto U: usuario C: cliente
     * @param int $_id -> id del contacto del cual se buscan los domicilio
     * @return string[][] -> array devolviendo los domicilio incluidos en la lista; por cada fila incluye tipo, zona,localidad, calle y nro
     */
    protected function coleccionByContacto($_tipocont, $_id, $_principal) {
        $arrayRet = array();
        $domDB = new DomicilioPGDAO();
        $result = $domDB->coleccionByTipoId($_tipocont, $_id, $_principal);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }

    public function seteaPrincipal($_id_dom) {
        $tipo = '';
        $cont = 0;
        $auxBSN = new DomicilioBSN($_id_dom);
        $tipo = $auxBSN->getObjeto()->getTipocont();
        $cont = $auxBSN->getObjeto()->getId_cont();
        $arrayDom = $auxBSN->coleccionByContacto($tipo, $cont, 1);
        if (sizeof($arrayDom) > 0) {
            $id_prinAnt = $arrayDom[0]['id_dom'];
            $this->reseteaPrincipal($id_prinAnt);
        }
        $domDB = new DomicilioPGDAO($auxBSN->getArrayTabla());
        $domDB->seteaPrincipal();
        $this->registraLog($this->getObjeto()->getId_dom(), 'Setea Principal', 'Modificado', '');
    }

    public function reseteaPrincipal($_id_dom) {
        $auxBSN = new DomicilioBSN($_id_dom);
        $domDB = new DomicilioPGDAO($auxBSN->getArrayTabla());
        $domDB->reseteaPrincipal();
        $this->registraLog($this->getObjeto()->getId_dom(), 'Resetea Principal', 'Modificado', '');
    }

    public function listaPrincipalByCliente($id) {
        // tipodom,id_ubica,calle,nro,piso,dpto    
        $arrayDom = $this->principalByCliente($id);
        if (sizeof($arrayDom) > 1) {
            $retorno = "(" . $arrayDom['tipodom'] . ") " . $arrayDom['calle'] . " " . $arrayDom['nro'] . " " . $arrayDom['piso'] . " " . $arrayDom['dpto'];
        } else {
            $retorno = '';
        }
        return $retorno;
    }

    public function borraDB() {
        if ($this->getObjeto()->getTipocont() == 'C') {
            $this->actualizaCacheCliente($this->getObjeto()->getId_cont(), 'd');
        }

        $retorno = parent::borraDB();
        return $retorno;
    }

    public function actualizaDB() {
        $id_cont = $this->getObjeto()->getId_cont();
        if ($this->getObjeto()->getPrincipal() == 1) {
            $domBSN = new DomicilioBSN();
            switch ($this->getObjeto()->getTipocont()) {
                case 'U':
                    $retArray = $domBSN->principalByUsuarios($id_cont);
                    break;
                case 'O':
                    $retArray = $domBSN->principalByContacto($id_cont);
                    break;
                case 'C':
                    $retArray = $domBSN->principalByCliente($id_cont);
                    break;
            }
            $domBSN->reseteaPrincipal($retArray['id_dom']);
        }

        $retorno = parent::actualizaDB();
        if ($this->getObjeto()->getTipocont() == 'C') {
            $this->actualizaCacheCliente($id_cont, 'a');
        }
        return $retorno;
    }

    public function insertaDB() {
        $id_cont = $this->getObjeto()->getId_cont();
        if ($this->getObjeto()->getPrincipal() == 1) {
            $domBSN = new DomicilioBSN();
            switch ($this->getObjeto()->getTipocont()) {
                case 'U':
                    $retArray = $domBSN->principalByUsuarios($id_cont);
                    break;
                case 'O':
                    $retArray = $domBSN->principalByContacto($id_cont);
                    break;
                case 'C':
                    $retArray = $domBSN->principalByCliente($id_cont);
                    break;
            }
            if (sizeof($retArray) > 0) {
                $domBSN->reseteaPrincipal($retArray['id_dom']);
            }
        }
        $retorno = parent::insertaDB();
        if ($this->getObjeto()->getTipocont() == 'C') {
            $this->actualizaCacheCliente($id_cont, 'a');
        }
        return $retorno;
    }

    protected function actualizaCacheCliente($id, $operacion) {
        $cliC = ClienteCache::getInstance();
        $cliC->actualizaCache($id, $operacion);
    }

}

?>