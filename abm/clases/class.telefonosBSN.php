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
include_once("clases/class.telefonos.php");
include_once("clases/class.telefonosPGDAO.php");
include_once("clases/class.clienteCache.php");

class TelefonosBSN extends BSN {

    protected $clase = "Telefonos";
    protected $nombreId = "id_telefono";
    protected $telefonos;
    protected $arrayTipos = array('Particular', 'Cel Particular', 'Laboral', 'Cel Laboral', 'Familiar', 'Mensages', 'Fax');

    public function __construct($_id_telefono = 0, $_telefonos = '') {
        TelefonosBSN::seteaMapa();
        TelefonosBSN::creaObjeto();
        if ($_id_telefono instanceof Telefonos) {
            TelefonosBSN::seteaBSN($_id_telefono);
        } else {
            if (is_numeric($_id_telefono)) {
                if ($_id_telefono != 0) {
                    TelefonosBSN::cargaById($_id_telefono);
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
        return $this->telefonos->getId_telefono();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->telefonos->setId_telefono($id);
    }

    /**
     * Retorna el array que contien los tipos de codarea
     */
    public function getArrayTipo() {
        return $this->arrayTipos;
    }

    /**
     * Arma un combo con los tipos posibles de codareaes
     * @param string $valor -> valor del campo en el caso que sea conocido
     * @param string $campo -> nombre del campo en el formulario
     * @param string $class -> nombre de la clase que define el estilo del combo
     */
    public function comboTipotel($valor = '', $campo = "tipotel", $class = "cd_celda_input") {
        $tipoRel = $this->arrayTipos;
        print "<select name='" . $campo . "' id='" . $campo . "' class='campos_btn'>\n";
        for ($pos = 0; $pos < sizeof($tipoRel); $pos++) {
            print "<option value='" . $tipoRel[$pos] . "'";
            if ($tipoRel[$pos] == $valor) {
                print " SELECTED ";
            }
            print ">" . $tipoRel[$pos] . "</option>\n";
        }
        print "</select>\n";
    }

    /**
     * Retorna una coleccion con los valores de telefono definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del telefono de contacto
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
     * Retorna una coleccion con los valores de telefono definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del telefono de contacto
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
     * Retorna una coleccion con los valores de telefono definido como principal para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del telefono de contacto
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
     * Retorna una coleccion con los valores de telefono definidos  para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del telefono de contacto
     */
    public function coleccionByUsuarios($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('U', $_id, 0);
        }
        return $arrayRet;
    }

    /**
     * Retorna una coleccion con los valores de telefono definidos para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del telefono de contacto
     */
    public function coleccionByCliente($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('C', $_id, 0);
        }
        return $arrayRet;
    }

    /**
     * Retorna una coleccion con los valores de telefono definidos para el contacto del ID
     * @param int $_id -> id del contacto
     * @return string[] -> array con los datos del telefono de contacto
     */
    public function coleccionByContactoAgenda($_id = 0) {
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContacto('O', $_id, 0);
        }
        return $arrayRet;
    }

    /**
     * Arma un array con los telefonos segun el string pasado como parametro
     * @param string $_tipocont -> Tipo de contacto U: usuario C: cliente  O: Contacto de Agenda
     * @param int $_id -> id del contacto del cual se buscan los telefonos
     * @return string[][] -> array devolviendo los telefonos incluidos en la lista; por cada fila incluye tipo, pais,area, numero e interno
     */
    protected function coleccionByContacto($_tipocont, $_id, $_principal) {
        $arrayRet = array();
        $telDB = new TelefonosPGDAO();
        $result = $telDB->coleccionByTipoId($_tipocont, $_id, $_principal);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }

    /**
     * Retorna un string con los nros de telefonos de la referencia
     * @param int $_id  -> identificacion del Cliente a buscar los telefonos
     * @return string ->lista de telefonos separados por / ;
     */
    public function listaTelefonosByCliente($_id = 0) {
        $strRet = '';
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByCliente($_id);
        }
        if (sizeof($arrayRet) != 0) {
            $strRet = $this->armaListaTelefonos($arrayRet);
        }
        return $strRet;
    }

    /**
     * Retorna un string con los nros de telefonos de la referencia
     * @param int $_id  -> identificacion del Usuario a buscar los telefonos
     * @return string ->lista de telefonos separados por / ;
     */
    public function listaTelefonosByUsuarios($_id = 0) {
        $strRet = '';
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByUsuarios($_id);
        }
        if (sizeof($arrayRet) != 0) {
            $strRet = $this->armaListaTelefonos($arrayRet);
        }
        return $strRet;
    }

    /**
     * Retorna un string con los nros de telefonos de la referencia
     * @param int $_id  -> identificacion del Contacto a buscar los telefonos
     * @return string ->lista de telefonos separados por / ;
     */
    public function listaTelefonosByContactoAgenda($_id = 0) {
        $strRet = '';
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContactoAgenda($_id);
        }
        if (sizeof($arrayRet) != 0) {
            $strRet = $this->armaListaTelefonos($arrayRet);
        }
        return $strRet;
    }

    protected function armaListaTelefonos($array) {
        $strRet = '';
        $inicio = 0;
        foreach ($array as $telefono) {
            if ($inicio == 0) {
                $inicio = 1;
            } else {
                $strRet.=' / ';
            }
            if ($telefono['principal'] != '' && $telefono['principal'] != '0') {
                $strRet.="<strong>";
            }
//                codpais,codarea,numero,interno,principal
            if ($telefono['codpais'] != '' && $telefono['codpais'] != '0') {
                $strRet.="(" . $telefono['codpais'] . ")";
            }
            if ($telefono['codarea'] != '' && $telefono['codarea'] != '0') {
                $strRet.=" " . $telefono['codarea'] . " ";
            }
            $strRet.=$telefono['numero'];
            if ($telefono['interno'] != '' && $telefono['interno'] != '0') {
                $strRet.=" - " . $telefono['interno'];
            }
            if ($telefono['principal'] != '' && $telefono['principal'] != '0') {
                $strRet.="<strong>";
            }
        }
        return $strRet;
    }

    public function seteaPrincipal($_id_tel) {
        $tipo = '';
        $cont = 0;
        $auxBSN = new TelefonosBSN($_id_tel);
        $tipo = $auxBSN->getObjeto()->getTipocont();
        $cont = $auxBSN->getObjeto()->getId_cont();
        $arrayTel = $auxBSN->coleccionByContacto($tipo, $cont, 1);
        if (sizeof($arrayTel) > 0) {
            $id_prinAnt = $arrayTel[0]['id_telefono'];
            $this->reseteaPrincipal($id_prinAnt);
        }
        $telDB = new TelefonosPGDAO($auxBSN->getArrayTabla());
        $telDB->seteaPrincipal();
        $this->registraLog($this->getObjeto()->getId_telefono(), 'Setea Principal', 'Modificado', '');
    }

    public function reseteaPrincipal($_id_tel) {
        $auxBSN = new TelefonosBSN($_id_tel);
        $telDB = new TelefonosPGDAO($auxBSN->getArrayTabla());
        $telDB->reseteaPrincipal();
        $this->registraLog($this->getObjeto()->getId_telefono(), 'Resetea Principal', 'Modificado', '');
    }

    public function borraDB() {
        if ($this->getObjeto()->getTipocont() == 'C') {
            $this->actualizaCacheCliente($this->getObjeto()->getId_cont(), 'd');
        }

        $retorno = parent::borraDB();
        return $retorno;
    }

    public function actualizaDB() {
        if ($this->getObjeto()->getPrincipal() == 1) {
            $telBSN = new TelefonosBSN();
            $id_cont = $this->getObjeto()->getId_cont();
            switch ($this->getObjeto()->getTipocont()) {
                case 'U':
                    $retArray = $telBSN->principalByUsuarios($id_cont);
                    break;
                case 'C':
                    $retArray = $telBSN->principalByCliente($id_cont);
                    break;
                case 'O':
                    $retArray = $telBSN->principalByContacto($id_cont);
                    break;
            }
            $telBSN->reseteaPrincipal($retArray['id_telefono']);
        }
        $retorno = parent::actualizaDB();
        if ($this->getObjeto()->getTipocont() == 'C') {
            $this->actualizaCacheCliente($id_cont, 'a');
        }

        return $retorno;
    }

    public function insertaDB() {
        if ($this->getObjeto()->getPrincipal() == 1) {
            $telBSN = new TelefonosBSN();
            $id_cont = $this->getObjeto()->getId_cont();
            switch ($this->getObjeto()->getTipocont()) {
                case 'U':
                    $retArray = $telBSN->principalByUsuarios($id_cont);
                    break;
                case 'C':
                    $retArray = $telBSN->principalByCliente($id_cont);
                    break;
                case 'O':
                    $retArray = $telBSN->principalByContacto($id_cont);
                    break;
            }
            if (sizeof($retArray) > 0) {
                $telBSN->reseteaPrincipal($retArray['id_telefono']);
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