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
include_once("clases/class.promocion.php");
include_once("clases/class.promocionPGDAO.php");

class PromocionBSN extends BSN {

    protected $clase = "Promocion";
    protected $nombreId = "id_promo";
    protected $promocion;

    public function __construct($_parametro = '') {
        PromocionBSN::seteaMapa();
        if ($_parametro instanceof Promocion) {
            PromocionBSN::creaObjeto();
            PromocionBSN::seteaBSN($_parametro);
        } else {
            PromocionBSN::creaObjeto();
            if (is_numeric($_parametro) && $_parametro != 0) {
                PromocionBSN::cargaById($_parametro);
            }
        }
    }

    /**
     * retorna el ID del objeto 
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->promocion->getId_promo();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->promocion->setId_promo($id);
    }

    public function cambiarEstadoPromociones() {
        $objDB = new PromocionPGDAO($this->getArrayTabla());
        if ($this->promocion->getActiva() == 1) {
            $retorno = $objDB->desactivar();
        } else {
            $retorno = $objDB->activar();
        }
        return $retorno;
    }

    public function cargaColeccionActivas() {
        $objDB = new PromocionPGDAO($this->getArrayTabla());
        $arrayRet = $this->leeDBArray($objDB->coleccionActivas());
        return $arrayRet;
    }
    
    public function cargaColeccionFiltro($param) {
        $objDB = new PromocionPGDAO($this->getArrayTabla());
        $arrayRet = $this->leeDBArray($objDB->coleccionFiltro($param));
        return $arrayRet;
    }
    

    /**
     * Arma un combo con los valores de las promociones activas
     * @param int $valor -> Valor actual del campo para fijar en la vista sobre la opcion actualmente seleccionada
     * @param str $nombre -> nombre del campo en el formulario
     * @param str $clase -> clase de la css con la cual se presenta el dato
     * @param int $opcion -> Indica si se agrega algun tipo de opcion previo a la carga de datos 0-NO 1-Todos 2-Seleccione Opcion 
     */
    public function comboColeccion($valor = 0, $nombre = 'id_promo', $clase = 'campos_btn', $opcion = 0) {
        $arrayDatos = $this->cargaArrayCombo();
        $this->armaCombo($arrayDatos, $valor, $nombre, $clase, $opcion);
    }

    protected function cargaArrayCombo() {
        $objDB = new PromocionPGDAO($this->getArrayTabla());
        $arrayRet = $this->leeDBArray($objDB->coleccionCombo());
        return $arrayRet;
    }

}

?>
