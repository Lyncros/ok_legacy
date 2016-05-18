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
include_once("clases/class.datosprop.php");
include_once("clases/class.datospropPGDAO.php");

//include_once ("clases/class.logBSN.php");


class DatospropBSN extends BSN {

    protected $clase = "Datosprop";
    protected $nombreId = "Id_prop_carac";
    protected $datosprop;
    protected $tarea = "Caracteristicas Propiedad";

    public function __construct($_Id_prop_carac = 0, $_datosprop = '') {
        DatospropBSN::seteaMapa();
        if ($_Id_prop_carac instanceof Datosprop) {
            DatospropBSN::creaObjeto();
            DatospropBSN::seteaBSN($_Id_prop_carac);
        } else {
            if (is_numeric($_Id_prop_carac)) {
                DatospropBSN::creaObjeto();
                if ($_Id_prop_carac != 0) {
                    DatospropBSN::cargaById($_Id_prop_carac);
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
        return $this->datosprop->getId_prop_carac();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->datosprop->setId_prop_carac($id);
    }

    public function grabaCaracteristica_Prop($id_prop, $arrayCarac) {
        $datos = new Datosprop();
        $datos->setId_prop($id_prop);
        $datosBSN = new DatospropBSN($datos);
        $retorno = $datosBSN->borraDB();
        if ($retorno) {
//			$logBSN=new LogBSN();
            $claves = array_keys($arrayCarac);
            foreach ($claves as $id_carac) {
                if (array_key_exists('contenido', $arrayCarac[$id_carac])) {
                    $cont = $arrayCarac[$id_carac]['contenido'];
                } else {
                    $cont = '';
                }
                if ($cont != '' && trim($cont) != '0' && strtolower(trim($cont)) != 'sin definir') {
                    $datos->setId_carac($id_carac);
                    $datos->setContenido($arrayCarac[$id_carac]['contenido']);
                    if (array_key_exists('comentario', $arrayCarac[$id_carac])) {
                        $coment = $arrayCarac[$id_carac]['comentario'];
                    } else {
                        $coment = '';
                    }

//                    $datos->setComentario($arrayCarac[$id_carac]['comentario']);
                    $obs = $datos->__toString();
                    $this->registraLog($this->tarea, $datos->getId_prop(), 'Nueva', 'Carga', $obs);
                    $datosBSN->seteaBSN($datos);
                    $retorno = $datosBSN->insertaDB();
                    if (!$retorno) {
                        break;
                    }
                }
            }
        }
        return $retorno;
    }

    /**
     * Arma un string con los ID de las propiedades que se corresponden con las caracteristicas pasadas, separando los valores con ,
     *
     * @param array con los datos de las caracteristicas a cumplir. El indice del array es el ID de la caracteristica y contiene un array donde el primer valor es el tipo y el segundo el valor el contenido de la misma.
     * @return String con los datos de los ID de las propiedades
     *
     */
    public function armaArrayIN($array) {
        $retorno = '';
        $propin = '0';
        if (sizeof($array) > 0) {
            $datosDB = new DatospropPGDAO();
            $claves = array_keys($array);
            foreach ($claves as $id_cara) {
                if ($propin != '') {
                    if ($array[$id_cara][1] != '') {
                        $resultprop = $this->leeDBArray($datosDB->coleccionPropiedadCarac($id_cara, $array[$id_cara][1], $array[$id_cara][0], $propin));
                        $propin = $this->armaStringId($resultprop);
                    }
                }
            }
            if ($propin != '') {
                $retorno = $propin;
            } else {
                $retorno = -1;
            }
        }
        return $retorno;
    }

    public function armaStringId($array) {
        $retorno = '';
        foreach ($array as $elemArray) {
            $retorno.=$elemArray['id_prop'] . ',';
        }
        if (sizeof($retorno) > 0) {
            $retorno = substr($retorno, 0, strlen($retorno) - 1);
        }
        return $retorno;
    }

    /**
     * Retorna las propiedades que cumplen en sus caracteristicas con los parametros de filtrado para el Nuevo buscador
     *
     * @param array $array -> array bidimencional donde en cada fila se encuentran la caracteristica y el valor de filtro a aplicar
     */
    public function armaArrayINAvanzado($array) {
        //Array ( [0] => Array ( [0] => pepe [1] => 1 ) [1] => Array ( [0] => opcPrecioVenta [1] => 150000 AND 200000 ) )
        $retorno = '';
        $propin = '0';
        if (sizeof($array) > 0) {
            $datosDB = new DatospropPGDAO();
            foreach ($array as $opcion) {
                if ($propin != '') {
                    switch ($opcion[0]) {
                        case 'opcAmbientes':
                            $id_carac = 208;
                            break;
                        //						case 'opcDespachos':
                        //							$id_carac=208;
                        //							break;
                        case 'opcMonedaVenta':
                            $id_carac = 165;
                            break;
                        case 'opcPrecioVenta':
                            $id_carac = 161;
                            break;
                        case 'opcMonedaAlquiler':
                            $id_carac = 166;
                            break;
                        case 'opcPrecioAlquiler':
                            $id_carac = 164;
                            break;
                        case 'opcSupTotal':
                            $id_carac = 198;
                            break;
                        case 'opcOrientacion':
                            $id_carac = 43;
                            break;
                        default:
                            $id_carac = 0;
                            break;
                    }
                    if ($id_carac != 0) {
                        $resultprop = $this->leeDBArray($datosDB->coleccionPropiedadCaracAvanzada($id_carac, $opcion[1], $propin));
                        $propin = $this->armaStringId($resultprop);
                    }
                }
            }
            if ($propin != '') {
                $retorno = $propin;
            } else {
                $retorno = -1;
            }
        }
        return $retorno;
    }

    public function coleccionCaracteristicasProp($id_prop, $publicas = 0) {
        $datos = new Datosprop();
        $datos->setId_prop($id_prop);
        $datosaux = new DatospropBSN($datos);
        $datosDB = new DatospropPGDAO($datosaux->getArrayTabla());
        if ($publicas == 0) {
            $this->seteaArray($datosDB->coleccionCaracteristicasByProp());
        } else {
            $this->seteaArray($datosDB->coleccionCaracteristicasPublicasByProp());
        }
        $array = $this->datosprop;
        $retorno = array();
        foreach ($array as $reg) {
            $retorno[] = $this->mapa->tablaTOform($reg);
        }
        return $retorno;
    }

    public function coleccionCaracteristicasByGrupoProp($inprop, $incarac) {
        $datosaux = new DatospropBSN();
        $datosDB = new DatospropPGDAO();
        $this->seteaArray($datosDB->coleccionCaracteristicasByPropiedad($inprop, $incarac));
        $array = $this->datosprop;
        $retorno = array();
        foreach ($array as $reg) {
            $retorno[] = $this->mapa->tablaTOform($reg);
        }
        return $retorno;
    }

    public function clonaCaracteristicasByPropiedad($propOrig = 0, $propDest = 0) {
        $datosDB = new DatospropPGDAO();
        $retorno = $datosDB->clonacionCaracteristicasByProp($propOrig, $propDest);
        return $retorno;
    }

}

?>