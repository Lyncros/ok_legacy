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
include_once ("generic_class/class.cargaParametricos.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.reciborendicion.php");
include_once("clases/class.reciborendicionPGDAO.php");
include_once("clases/class.propiedadBSN.php");

class ReciborendicionBSN extends BSN {

    protected $clase = "Reciborendicion";
    protected $nombreId = "id_rendicion";
    protected $reciborendicion;
    protected $arrayTipocontrato;

    public function __construct($_id_rendicion=0) {
        ReciborendicionBSN::seteaMapa();
        if ($_id_rendicion instanceof Reciborendicion) {
            ReciborendicionBSN::creaObjeto();
            ReciborendicionBSN::seteaBSN($_id_rendicion);
        } else {
            if (is_numeric($_id_rendicion)) {
                ReciborendicionBSN::creaObjeto();
                if ($_id_rendicion != 0) {
                    ReciborendicionBSN::cargaById($_id_rendicion);
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
        return $this->reciborendicion->getId_rendicion();
    }

    /**
     * Setea el ID del objeto
     *
     * @param entero $id del objeto
     */
    public function setId($id) {
        $this->reciborendicion->setId_rendicion($id);
    }

    /**
     * Carga la coleccion de recibos activos en un array
     * @param string tipo -> tipo de contrato a buscar, si se omite se retornaran todos los tipos
     * @return string[][] -> array bidimensional conteniendo en cada fila los datos de cada contrato
     * definidos para esa propiedad
     */
    public function cargaReciborendicionByContrato($id_contrato='') {
        $operaux = new ReciborendicionBSN();
        $operDB = new ReciborendicionPGDAO($operaux->getArrayTabla());
        $coleccion=$this->leeDBArray($operDB->coleccionByContrato($id_contrato));
        return $coleccion;
    }
    
    public function cargaReciborendicionByRecibo($id_recibo='') {
        $operaux = new ReciborendicionBSN();
        $operDB = new ReciborendicionPGDAO($operaux->getArrayTabla());
        $coleccion=$this->leeDBArray($operDB->coleccionByRecibo($id_recibo));
        return $coleccion;
    }
    

}

?>