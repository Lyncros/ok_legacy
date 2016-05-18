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
include_once("clases/class.medioselectronicos.php");
include_once("clases/class.medioselectronicosPGDAO.php");
include_once("clases/class.tipoMedioelectronicoBSN.php");
include_once("clases/class.clienteCache.php");

class MedioselectronicosBSN extends BSN {

    protected $clase = "Medioselectronicos";
    protected $nombreId = "id_medio";
    protected $medioselectronicos;

    public function __construct($_id_medio = 0, $_medioselectronicos = '') {
        MedioselectronicosBSN::seteaMapa();
        MedioselectronicosBSN::creaObjeto();
        if ($_id_medio instanceof Medioselectronicos) {
            MedioselectronicosBSN::seteaBSN($_id_medio);
        } else {
            if (is_numeric($_id_medio)) {
                if ($_id_medio != 0) {
                    MedioselectronicosBSN::cargaById($_id_medio);
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
        return $this->medioselectronicos->getId_medio();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->medioselectronicos->setId_medio($id);
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
     * Arma un array con los medioselectronicos segun el string pasado como parametro
     * @param string $_tipocont -> Tipo de contacto U: usuario C: cliente  O: Contacto de Agenda
     * @param int $_id -> id del contacto del cual se buscan los medioselectronicos
     * @return string[][] -> array devolviendo los medioselectronicos incluidos en la lista; por cada fila incluye tipo, pais,area, numero e interno
     */
    protected function coleccionByContacto($_tipocont, $_id, $_principal) {
        $arrayRet = array();
        $telDB = new MedioselectronicosPGDAO();
        $result = $telDB->coleccionByTipoId($_tipocont, $_id, $_principal);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }

    /**
     * Retorna un string con los nros de medioselectronicos de la referencia
     * @param int $_id  -> identificacion del Cliente a buscar los medioselectronicos
     * @return string ->lista de medioselectronicos separados por / ;
     */
    public function listaMedioselectronicosByCliente($_id = 0) {
        $strRet = '';
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByCliente($_id);
        }
        if (sizeof($arrayRet) != 0) {
            $strRet = $this->armaListaMedioselectronicos($arrayRet);
        }
        return $strRet;
    }

    /**
     * Retorna un string con los nros de medioselectronicos de la referencia
     * @param int $_id  -> identificacion del Usuario a buscar los medioselectronicos
     * @return string ->lista de medioselectronicos separados por / ;
     */
    public function listaMedioselectronicosByUsuarios($_id = 0) {
        $strRet = '';
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByUsuarios($_id);
        }
        if (sizeof($arrayRet) != 0) {
            $strRet = $this->armaListaMedioselectronicos($arrayRet);
        }
        return $strRet;
    }

    /**
     * Retorna un string con los nros de medioselectronicos de la referencia
     * @param int $_id  -> identificacion del Contacto a buscar los medioselectronicos
     * @return string ->lista de medioselectronicos separados por / ;
     */
    public function listaMedioselectronicosByContactoAgenda($_id = 0) {
        $strRet = '';
        $arrayRet = array();
        if ($_id != 0) {
            $arrayRet = $this->coleccionByContactoAgenda($_id);
        }
        if (sizeof($arrayRet) != 0) {
            $strRet = $this->armaListaMedioselectronicos($arrayRet);
        }
        return $strRet;
    }

    protected function armaListaMedioselectronicos($array) {
        $strRet = '';
        $inicio = 0;
        $tipoMed = TipoMedioElectronicoBSN::getInstance();
        $arrayMedio = $tipoMed->getArrayParametros();
        foreach ($array as $medio) {
            if ($inicio == 0) {
                $inicio = 1;
            } else {
                $strRet.=' / ';
            }
            if ($medio['principal'] != '' && $medio['principal'] != '0') {
                $strRet.="<strong>";
            }
//                contacto,comentario,principal
            $strRet.=" (" . $arrayMedio[$medio['id_tipomed']] . ") ";
            $strRet.=$medio['contacto'];
            if ($medio['principal'] != '' && $medio['principal'] != '0') {
                $strRet.="</strong>";
            }
        }
        return $strRet;
    }

    public function seteaPrincipal($_id_tel) {
        $auxBSN = new MedioselectronicosBSN($_id_tel);
        $tipo = $auxBSN->getObjeto()->getTipocont();
        $cont = $auxBSN->getObjeto()->getId_cli();
        $arrayTel = $auxBSN->coleccionByContacto($tipo, $cont, 1);
        if (sizeof($arrayTel) > 0) {
            $id_prinAnt = $arrayTel[0]['id_medio'];
            $this->reseteaPrincipal($id_prinAnt);
        }
        $telDB = new MedioselectronicosPGDAO($auxBSN->getArrayTabla());
        $telDB->seteaPrincipal();
        $this->registraLog($this->getObjeto()->getId_medio(), 'Setea Principal', 'Modificado', '');
    }

    public function reseteaPrincipal($_id_tel) {
        $auxBSN = new MedioselectronicosBSN($_id_tel);
        $telDB = new MedioselectronicosPGDAO($auxBSN->getArrayTabla());
        $telDB->reseteaPrincipal();
        $this->registraLog($this->getObjeto()->getId_medio(), 'Resetea Principal', 'Modificado', '');
    }

    public function actualizaDB() {
        if ($this->getObjeto()->getPrincipal() == 1) {
            $telBSN = new MedioselectronicosBSN();
            $id_cli = $this->getObjeto()->getId_cli();
            switch ($this->getObjeto()->getTipocont()) {
                case 'U':
                    $retArray = $telBSN->principalByUsuarios($id_cli);
                    break;
                case 'C':
                    $retArray = $telBSN->principalByCliente($id_cli);
                    break;
                case 'O':
                    $retArray = $telBSN->principalByContacto($id_cli);
                    break;
            }
            $telBSN->reseteaPrincipal($retArray['id_medio']);
        }
        $retorno = parent::actualizaDB();
        if ($this->getObjeto()->getTipocont() == 'C') {
            $this->actualizaCacheCliente($id_cli, 'a');
        }
        return $retorno;
    }

    public function borraDB() {
        if ($this->getObjeto()->getTipocont() == 'C') {
            $this->actualizaCacheCliente($this->getObjeto()->getId_cli(), 'd');
        }

        $retorno = parent::borraDB();
        return $retorno;
    }

    public function insertaDB() {
        if ($this->getObjeto()->getPrincipal() == 1) {
            $telBSN = new MedioselectronicosBSN();
            $id_cli = $this->getObjeto()->getId_cli();
            switch ($this->getObjeto()->getTipocont()) {
                case 'U':
                    $retArray = $telBSN->principalByUsuarios($id_cli);
                    break;
                case 'C':
                    $retArray = $telBSN->principalByCliente($id_cli);
                    break;
                case 'O':
                    $retArray = $telBSN->principalByContacto($id_cli);
                    break;
            }
            if (sizeof($retArray) > 0) {
                $telBSN->reseteaPrincipal($retArray['id_medio']);
            }
        }
        $retorno = parent::insertaDB();
        if ($this->getObjeto()->getTipocont() == 'C') {
            $this->actualizaCacheCliente($id_cli, 'a');
        }
        
        return $retorno;
    }

    protected function actualizaCacheCliente($id, $operacion) {
        $cliC = ClienteCache::getInstance();
        $cliC->actualizaCache($id, $operacion);
    }

}

?>