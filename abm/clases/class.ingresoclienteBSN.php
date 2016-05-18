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
include_once("clases/class.ingresocliente.php");
include_once("clases/class.ingresoclientePGDAO.php");

class IngresoclienteBSN extends BSN {

    protected $clase = "Ingresocliente";
    protected $nombreId = "id_ingreso";
    protected $ingresocliente;

    public function __construct($_parametro = '') {
        IngresoclienteBSN::seteaMapa();
        if ($_parametro instanceof Ingresocliente) {
            IngresoclienteBSN::creaObjeto();
            IngresoclienteBSN::seteaBSN($_parametro);
        } else {
            IngresoclienteBSN::creaObjeto();
            if (is_numeric($_parametro) && $_parametro != 0) {
                IngresoclienteBSN::cargaById($_parametro);
            }
        }
    }

    /**
     * retorna el ID del objeto 
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->ingresocliente->getId_ingreso();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->ingresocliente->setId_ingreso($id);
    }

    /**
     * Cargo los datos del ingreso del cliente indicado
     * @param int $id
     * @return obj cliente objeto el id de cliente pasado
     */
    public function cargaByCliente($id = 0) {
        $retorno = new Ingresocliente();
        $arrayDatos = array();
        if ($id != 0) {
            $arrayDatos = $this->cargaByFiltro($id, 'C');
            $dato = $arrayDatos[0];
            foreach ($dato as $ingreso) {
                $retorno->setId_ingreso($ingreso['id_ingreso']);
                $retorno->setId_cli($ingreso['id_cli']);
                $retorno->setId_fingreso($ingreso['id_fingreso']);
                $retorno->setId_fcontacto($ingreso['id_fcontacto']);
                $retorno->setId_promo($ingreso['id_promo']);
                $retorno->setUsr_carga($ingreso['usr_carga']);
                $retorno->setFec_cont($ingreso['fec_cont']);
            }
        }
        return $retorno;
    }

    /**
     * Cargo los datos de los clientes indicado la forma de Ingreso
     * @param int $id de la forma de ingreso
     * @return array Array con los datos de los clientes segun la forma de ingreso
     */
    public function cargaByIngreso($id = 0) {
        $retorno = array();
        if ($id != 0) {
            $retorno = $this->cargaByFiltro($id, 'I');
        }
        return $retorno;
    }

    /**
     * Cargo los datos de los clientes indicado la Promocion
     * @param int $id de la promocion
     * @return array Array con los datos de los clientes segun la promocion
     */
    public function cargaByPromocion($id = 0) {
        $retorno = array();
        if ($id != 0) {
            $retorno = $this->cargaByFiltro($id, 'P');
        }
        return $retorno;
    }

    /**
     * Cargo los datos de los clientes indicado la forma de Contacto
     * @param int $id de la forma de contacto
     * @return array Array con los datos de los clientes segun la forma de contacto
     */
    public function cargaByContacto($id = 0) {
        $retorno = array();
        if ($id != 0) {
            $retorno = $this->cargaByFiltro($id, 'O');
        }
        return $retorno;
    }

    /**
     * Cargo una lista basado en cualquiera de los parametros posibles de seleccion
     * @param int $id
     * @param char $tipo C: Cliente  I: Ingreso   P: promocion   O: Contacto
     */
    protected function cargaByFiltro($id = 0, $tipo = 'C') {
        $datosDB = new IngresoclientePGDAO();
        $result=$datosDB->coleccionByFiltro($id,$tipo);
        $arrayRet=  $this->leeDBArray($result);
        return $arrayRet;
    }

}

?>
