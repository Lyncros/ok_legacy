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
include_once("clases/class.evento.php");
include_once("clases/class.eventoPGDAO.php");

class EventoBSN extends BSN {

    protected $clase = "Evento";
    protected $nombreId = "id_evento";
    protected $evento;

    public function __construct($_parametro = '') {
        EventoBSN::seteaMapa();
        if ($_parametro instanceof Evento) {
            EventoBSN::creaObjeto();
            EventoBSN::seteaBSN($_parametro);
        } else {
            EventoBSN::creaObjeto();
            if (is_numeric($_parametro) && $_parametro != 0) {
                EventoBSN::cargaById($_parametro);
            }
        }
    }

    /**
     * retorna el ID del objeto 
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->evento->getid_evento();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->evento->setid_evento($id);
    }

    /**
     * Activa la evento para ser publicada
     *
     * @return estado de la finalizacion de la operacion
     */
    public function publicarEvento() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $eventoDB = new EventoPGDAO($arrayTabla);
        $retorno = $eventoDB->activarEvento();
        return $retorno;
    }

    /**
     * Desactiva el evento para ser publicada
     *
     * @return estado de la finalizacion de la operacion
     */
    public function quitarEvento() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $eventoDB = new EventoPGDAO($arrayTabla);
        $retorno = $eventoDB->desactivarEvento();
        return $retorno;
    }

    public function cargaColeccionFormFecha($fecha) {
        $this->evento->setFecha_even($fecha);
        $localclass = $this->getClase() . 'PGDAO';
        $datoDB = new $localclass($this->getArrayTabla());
        $this->seteaArray($datoDB->coleccionEventoFecha());

        $array = $this->{$this->getNombreObjeto()};
        $arrayform = array();
        foreach ($array as $registro) {
            $arrayform[] = $this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    /**
     * Retorna un array bidimencional con los datos de los registros correspondientes al filtro especificado
     * @param char $tipo -> tipo de referencia P: Propiedad   C: Cliente  U: Usuario   O: Contacto   F: Fecha
     * @param mixed $id -> id de la referencia , en el caso que tipo=F se presentara una fecha.
     */
    public function cargaColeccionByTipo($tipo = 'C', $id = 0) {
        $arrayRet=array();
        if(isset($tipo) && isset($id)){
            if($tipo=='F'){
                $this->evento->setFecha_even($fecha);
                $datoDB=new EventoPGDAO($this->getArrayTabla());
                $arrayRet=  $this->leeDBArray($datoDB->coleccionEventoFecha());
            }else{
                $datoDB=new EventoPGDAO();
                $arrayRet=  $this->leeDBArray($datoDB->coleccionByTipo($tipo, $id));
            }
        }
        return $arrayRet;
    }

}

?>
