<?php

include_once("generic_class/class.cargaParametricos.php");
include_once("generic_class/class.PGDAO.php");

/**
 * Clase generica para la implementacion de combos y arrays de datos parametricos
 * Toma acceso desde XML o Tablas de la base de datos para el armado de los parametros
 * Para ello se debe especificar el tipo de origen XML o SQL y el string que define el mismo.
 * Dicho string es elnombre del archivo sin path, si es XML; o el SELECT correspondiente si es SQL
 * Para el SQL se toma la primer columna como ID y la segunda como Detalle
 */
class ParametricosBSN {

    protected $arrayParametros;
    private $tipoOrigen;
    private $strOrigen;

    function __construct($tipo = 'XML', $origen = '') {
        $error = 0;
        if ($origen == '') {
            $error = 1;
        } else {
            ParametricosBSN::setOrigen($origen);
        }
        switch ($tipo) {
            case 'XML':
                ParametricosBSN::origenXml();
                break;
            case 'SQL':
                ParametricosBSN::origenSql();
                break;
            default:
                $error = 1;
        }
        if ($error == 1) {
            die('Fallo la aplicacion.');
        } else {
            ParametricosBSN::cargaParametros();
        }
    }

    private function setOrigen($origen) {
        $this->strOrigen = $origen;
    }

    private function origenXml() {
        $this->tipoOrigen = 'XML';
    }

    private function origenSql() {
        $this->tipoOrigen = 'Sql';
    }

    /* 	
      static public function getInstance(){
      if(is_null(self::$_instance)){
      self::$_instance = new self();
      }
      return self::$_instance;
      }
     */

    /*
     * Retorna la descripcion del elemento basado en el ID pasado como parametro
     */

    public function getDescripcionById($id) {
        return $this->arrayParametros[$id];
    }

    /**
     * Retorna el array con los datos de los parametros cargados 
     * @return string[] : arrayen el cual el ID de la fila contiene el ID del tipo de estado
     *  y el valor es la descripcion del mismo
     */
    public function getArrayParametros() {
        return $this->arrayParametros;
    }

    public function getJsonParametros() {
        $arrRet = array();
        $arrayClaves = array_keys($this->arrayParametros);
        foreach ($arrayClaves as $clave) {
            $arrRet[] = array($clave, $this->arrayParametros[$clave]);
        }
        return json_encode($arrRet);
    }

    protected function cargaParametros() {
        if (trim(strtoupper($this->tipoOrigen)) == 'XML') {
            $this->cargaParametrosXML();
        } else {
            $this->cargaParametrosSQL();
        }
    }

    protected function cargaParametrosXML() {
        $params = new CargaParametricos($this->strOrigen);
        $this->arrayParametros = $params->getParametros();
    }

    protected function cargaParametrosSQL() {
        $dao = new PGDAO();
        $result = $dao->execSql($this->strOrigen, array());

        $conf = CargaConfiguracion::getInstance();
        $tipoDB = $conf->leeParametro("tipodb");
        if ($tipoDB == "my") {
            $fetch = "mysql_fetch_array";
        } else {
            $fetch = "pg_fetch_array";
        }
        while ($row = $fetch($result)) {
            $this->arrayParametros[$row[0]] = $row[1];
        }
    }

    public function comboParametros($valor = '', $opcion = 0, $campo = "id", $class = "campos_btn", $js = '') {
        $arrayDato = $this->arrayParametros;
        $claveDato = array_keys($arrayDato);
        print "<select name='" . $campo . "' id='" . $campo . "' class='" . $class . "'";
        if ($js != '') {
            print "onclick=\"javascript:" . $js . "; \"";
        }
        print ">\n";
        print "<option value='0'";
        if ($valor == '') {
            print " SELECTED ";
        }
        switch ($opcion) {
            case 0:
                $opcBase = 'Seleccione una opcion';
                break;
            case 1:
                $opcBase = 'Todos';
                break;
        }
        print ">$opcBase</option>\n";

        for ($pos = 0; $pos < sizeof($arrayDato); $pos++) {
            print "<option value='" . $claveDato[$pos] . "'";
            if ($claveDato[$pos] == $valor) {
                print " SELECTED ";
            }
            print ">" . $arrayDato[$claveDato[$pos]] . "</option>\n";
        }
        print "</select>\n";
    }

    public function comboParametros2string($valor = '', $opcion = 0, $campo = "id", $class = "campos_btn", $js = '') {
        $arrayDato = $this->arrayParametros;
        $claveDato = array_keys($arrayDato);
        $str = '';
        $str.= "<select name='" . $campo . "' id='" . $campo . "' class='" . $class . "'";
        if ($js != '') {
            $str.= " onclick=\"javascript:" . $js . "; \"";
        }
        $str.= ">\n";
        $str.= "<option value='0'";
        if ($valor == '') {
            $str.= " SELECTED ";
        }
        switch ($opcion) {
            case 0:
                $opcBase = 'Seleccione una opcion';
                break;
            case 1:
                $opcBase = 'Todos';
                break;
        }
        $str.= ">$opcBase</option>\n";

        for ($pos = 0; $pos < sizeof($arrayDato); $pos++) {
            $str.= "<option value='" . $claveDato[$pos] . "'";
            if ($claveDato[$pos] == $valor) {
                $str.= " SELECTED ";
            }
            $str.= ">" . $arrayDato[$claveDato[$pos]] . "</option>\n";
        }
        $str.= "</select>\n";
        return $str;
    }

    /**
     * Arma una tabla con checkbox para cada opcion del array de parametros, identificando cada chkbx con el parametro campo + _ + id del parametro.
     * se utiliza el valor $_chkbyline para identificar la cantidad de campos por renglon en la tabla
     * @param string $valor -> cadena de valores de los id seleccionados separados por ','
     * @param string $campo -> identificador del prefijo del campo
     * @param string $class -> identificador de la clase dentro de la css que define el estilo de cada campo
     */
    public function checkboxParametros($valor = '', $campo = 'chkPar', $class = 'campos_chk') {
        $arrayValores = explode(',', $valor);
        $xlinea = $this->_chkbyline;
        $comLi = 0;
        $arrayDato = $this->arrayParametros;
        $claveDato = array_keys($arrayDato);
        print "<div>";
        for ($pos = 0; $pos < sizeof($arrayDato); $pos++) {
            if ($comLi == 0) {
                $comLi = 1;
            }
            print "<div class=\"$class\" style=\"float: left; padding-left:5px;\"><input type=\"checkbox\" name=\"" . $campo . "_" . $claveDato[$pos] . "\" name=\"" . $campo . "_" . $claveDato[$pos] . "\" ";
            if ($valor != 0 && array_search($claveDato[$pos], $arrayValores) !== false) {
                print " checked ";
            }
            print "/>" . $arrayDato[$claveDato[$pos]] . "</div>\n";
//            if($pos!=0 && ($pos%$xlinea)==0){
//                $comLi=0;
//                print "</tr>";
//            }
        }
        print "</div>";
        print " <div id=\"clearfix\"></div>\n";
    }

    /**
     * Lee la lista de posts y determina los valores de los campos seleccionados, retornando una lista de los mismos separados po ','
     * @param array $arrayPost -> array conteniendo los POST desde la pagina
     * @param string $campo -> prefijo del nombre del campo
     * @return string -> lista de ids separados por ','
     */
    public function leeCheckboxParametros($arrayPost, $campo = 'chkPar') {
        $retorno = '';
        $arrayClaves = array_keys($arrayPost);
        foreach ($arrayClaves as $clave) {
            if (substr($clave, 0, strlen($campo)) == $campo) {
                $indice = substr($clave, strlen($campo) + 1);
                $retorno.=$indice . ",";
            }
        }
        if (strlen($retorno) != 0) {
            $retorno = substr($retorno, 0, -1);
        } else {
            $retorno = 0;
        }
        return $retorno;
    }

}

?>
