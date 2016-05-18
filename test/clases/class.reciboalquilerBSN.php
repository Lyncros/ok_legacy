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
include_once("clases/class.reciboalquiler.php");
include_once("clases/class.reciboalquilerPGDAO.php");
include_once("clases/class.propiedadBSN.php");

class ReciboalquilerBSN extends BSN {

    protected $clase = "Reciboalquiler";
    protected $nombreId = "id_recibo";
    protected $reciboalquiler;
    protected $arrayTipocontrato;

    public function __construct($_id_recibo=0) {
        ReciboalquilerBSN::seteaMapa();
        if ($_id_recibo instanceof Reciboalquiler) {
            ReciboalquilerBSN::creaObjeto();
            ReciboalquilerBSN::seteaBSN($_id_recibo);
        } else {
            if (is_numeric($_id_recibo)) {
                ReciboalquilerBSN::creaObjeto();
                if ($_id_recibo != 0) {
                    ReciboalquilerBSN::cargaById($_id_recibo);
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
        return $this->reciboalquiler->getId_recibo();
    }

    /**
     * Setea el ID del objeto
     *
     * @param entero $id del objeto
     */
    public function setId($id) {
        $this->reciboalquiler->setId_recibo($id);
    }

    /**
     * Carga la coleccion de recibos activos en un array
     * @param string tipo -> tipo de contrato a buscar, si se omite se retornaran todos los tipos
     * @return string[][] -> array bidimensional conteniendo en cada fila los datos de cada contrato
     * definidos para esa propiedad
     */
    public function cargaReciboalquilerByContrato($id_contrato='') {
        $operaux = new ReciboalquilerBSN();
        $operDB = new ReciboalquilerPGDAO($operaux->getArrayTabla());
        $coleccion=$this->leeDBArray($operDB->coleccionByContrato($id_contrato));
        return $coleccion;
    }

    public function proximoRecibo(){
        $retorno=0;
        $operaux = new ReciboalquilerBSN();
        $operDB = new ReciboalquilerPGDAO($operaux->getArrayTabla());
        $coleccion=$this->leeDBArray($operDB->proximoRecibo());
        $retorno=$coleccion[0]['rec_nro'] + 1;
        return $retorno;
    }
    

}

?>