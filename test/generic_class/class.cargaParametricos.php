<?php

/**
 * Lee un archivo de configuracion definido en un archivo XML.
 * Los ID de los parametros estan definidos por los id de los TAGS 
 * y sus valores asociados como el contenido del TAG en cuestion.
 * 
 * Ej:
 * <?xml version="1.0" encoding="UTF-8"?>
 * <tipos_contratos>
 *     <dato id='alquiler'>Alquiler</dato>
 *     <dato id='boleto'>Boleto Compra-Venta</dato>
 *     <dato id='fideicomiso'>Fideicomiso</dato>
 * </tipos_contratos>
 * 
 *
 */
include_once ("generic_class/class.cargaConfiguracion.php");

class CargaParametricos {

    private $indice;
    private $valores;
    private $path;

    public function __construct($_conf_file) {
        $conf_file=CargaParametricos::getArchivo($_conf_file);
        if (file_exists($conf_file)) {
            $this->leeParametros($conf_file);
        } else {
            die("No se encuentra archivo de parametros." . $conf_file);
        }
    }

    protected function getArchivo($_conf_file="") {
        $conf = CargaConfiguracion::getInstance();
        $this->path = $_SERVER['DOCUMENT_ROOT'].$conf->leeParametro('path_parametros');
        $conf_file=$this->path."/".$_conf_file;
        return $conf_file;
    }

    private function leeParametros($_archivo) {

        $contenido = "";
        $contenido = $this->leeXML($_archivo);

        $contenido = ereg_replace("�", "a", $contenido);
        $contenido = ereg_replace("�", "e", $contenido);
        $contenido = ereg_replace("�", "i", $contenido);
        $contenido = ereg_replace("�", "o", $contenido);
        $contenido = ereg_replace("�", "u", $contenido);
        $contenido = ereg_replace("�", "A", $contenido);
        $contenido = ereg_replace("�", "E", $contenido);
        $contenido = ereg_replace("�", "I", $contenido);
        $contenido = ereg_replace("�", "O", $contenido);
        $contenido = ereg_replace("�", "U", $contenido);
        $contenido = ereg_replace("�", "NI", $contenido);
        $contenido = ereg_replace("�", "ni", $contenido);

        $p = xml_parser_create();
        xml_parse_into_struct($p, $contenido, $this->valores, $this->indice);
        xml_parser_free($p);
    }

    public function getParametros(){
        foreach ($this->indice['DATO'] as $ind){
            $arrayRet[$this->valores[$ind]['attributes']['ID']]=$this->valores[$ind]['value'];
            
        }
        
        return $arrayRet;
    }
    
    /**
     * Lee el XML definido y lo carga en un string para ser parseado
     *
     * @param string $_arhivo -> nombre del archivo de configuracion
     * @return string con el XML leido
     */
    private function leeXML($_archivo) {
        $contenido = "";
        if ($da = fopen($_archivo, "r")) {
            while ($aux = fgets($da, 1024)) {
                $contenido.=$aux;
            }
            fclose($da);
        } else {
            die("Error: no se ha podido leer el archivo <strong>$_archivo</strong>");
        }
        return $contenido;
    }


    /**
     * Retorna el valor de un parametro del Archivo de Configuracion.
     * En caso que el mismo no exista o se encuentre duplicado,
     *  arroja un mensaje de error y finaliza la aplicacion.
     *
     * @param (String) nombre del parametro
     * @return (String) valor del parametro
     */
    public function leeParametro($_param) {
        $retorno = null;
        $clave = strtoupper($_param);
        if (array_key_exists($clave, $this->indice)) {
            $cant = sizeof($this->indice[$clave]);
            if ($cant == 1) {
//si el campo incluye el substring 'pass' desencripta el parametro
                if (stristr($clave, 'pass') === FALSE) {
                    $retorno = $this->valores[$this->indice[$clave][0]]['value'];
                } else {
                    $crip = new Encriptador();
                    $retorno = $crip->desencripta($this->leeParametro('key'), $this->valores[$this->indice[$clave][0]]['value']);
                }
            } else {
                die("No es un parametro de Configuracion, o es una clave Duplicada");
            }
        }
        return $retorno;
    }

}
?>