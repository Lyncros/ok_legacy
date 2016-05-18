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
include_once("clases/class.impuesto.php");
include_once("clases/class.impuestoPGDAO.php");

class ImpuestoBSN extends BSN {

    protected $clase = "Impuesto";
    protected $nombreId = "id_impuesto";
    protected $impuesto;

    public function __construct($_id_impuesto=0, $_impuesto='') {
        ImpuestoBSN::seteaMapa();
        if ($_id_impuesto instanceof Impuesto) {
            ImpuestoBSN::creaObjeto();
            ImpuestoBSN::seteaBSN($_id_impuesto);
        } else {
            if (is_numeric($_id_impuesto)) {
                ImpuestoBSN::creaObjeto();
                if ($_id_impuesto != 0) {
                    ImpuestoBSN::cargaById($_id_impuesto);
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
        return $this->impuesto->getId_impuesto();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->impuesto->setId_impuesto($id);
    }

    /**
     * Arma un combo con los valores levantados de la tabla correspondiente
     *
     * @param int $valor -> Valor actual del campo para fijar en la vista sobre la opcion actualmente seleccionada
     * @param int $opcion -> Indica si se agrega algun tipo de opcion previo a la carga de datos 0-NO 1-Todos 2-Seleccione Opcion
     * @param str $campo -> nombre del campo en el formulario, por omision id_
     * @param str $class -> clase de la css con la cual se presenta el dato
     */
    public function comboImpuesto($valor=0, $opcion=0, $campo="id_impuesto", $class="campos_btn") {
        $impuestos = $this->cargaColeccionForm();
        print "<select name='" . $campo . "' id='" . $campo . "' class='campos'>\n";
        switch ($opcion) {
            case 1:
                print "<option value='0' SELECTED >Todos</option>\n";
                break;
            case 2:
                print "<option value='0' SELECTED >Seleccione una opcion</option>\n";
                break;
        }
        for ($pos = 0; $pos < sizeof($impuestos); $pos++) {
            print "<option value='" . $impuestos[$pos]['id_impuesto'] . "'";
            if ($impuestos[$pos]['id_impuesto'] == $valor) {
                print " SELECTED ";
            }
            print ">" . $impuestos[$pos]['denominacion'] . "</option>\n";
        }
        print "</select>\n";
    }

    /**
     * Retorna un array con los id de los impuestos y la denominacion de los misos, donde la posicion en el mismo corrsponde
     * con el ID y el valor contenido es la denominacion
     * return string[] 
     */
    public function armaArrays() {
        $arrayRet = array();
        $rubBSN = new BSN();
        $arrayRub = $rubBSN->cargaColeccionForm();
        foreach ($arrayRub as $registro) {
            $arrayRet[$registro['id_impuesto']] = $registro['denominacion'];
        }
        return $arrayRet;
    }

}

?>