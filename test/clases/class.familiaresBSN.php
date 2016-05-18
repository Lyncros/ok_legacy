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
 *		$var='muestra'.$this->getClase();
 *		$this->{$var}();
 *	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.familiares.php");
include_once("clases/class.familiaresPGDAO.php");
include_once("clases/class.clienteCache.php");

class FamiliaresBSN extends BSN {

	protected $clase = "Familiares";
	protected $nombreId = "id_fam";
	protected $familiares;

	public function __construct($_id_fam=0,$_familiares=''){
		FamiliaresBSN::seteaMapa();
		FamiliaresBSN::creaObjeto();
		if ($_id_fam  instanceof Familiares ){
			FamiliaresBSN::seteaBSN($_id_fam);
		} else {
			if (is_numeric($_id_fam)){
				if($_id_fam!=0){
					FamiliaresBSN::cargaById($_id_fam);
				}
			}
		}

	}

	/**
	 * retorna el ID del objeto
	 *
	 * @return id del objeto
	 */
	public function getId(){
		return $this->familiares->getId_fam();
	}

	/**
	 * Setea e ID del objeto
	 *
	 * @param unknown_type $id
	 */
	public function setId($id){
		$this->familiares->setId_fam($id);
	}

	/**
	 * Retorna el array que contien los tipos de nombre
	 */
	public function getArrayTipo(){
		return $this->arrayTipos;
	}

	/**
	 * Retorna una coleccion con los valores de familiar definidos  para el contacto del ID
	 * @param int $_id -> id del contacto
	 * @return string[] -> array con los datos del familiar de contacto
	 */
	public function coleccionByUsuarios($_id=0){
		$arrayRet=array();
		if($_id!=0){
			$arrayRet=$this->coleccionByContacto('U', $_id);
		}
		return $arrayRet;
	}

	/**
	 * Retorna una coleccion con los valores de familiar definidos para el contacto del ID
	 * @param int $_id -> id del contacto
	 * @return string[] -> array con los datos del familiar de contacto
	 */
	public function coleccionByCliente($_id=0){
		$arrayRet=array();
		if($_id!=0){
			$arrayRet=$this->coleccionByContacto('C', $_id);
		}
		return $arrayRet;
	}

	/**
	 * Retorna una coleccion con los valores de familiar definidos para el contacto del ID
	 * @param int $_id -> id del contacto
	 * @return string[] -> array con los datos del familiar de contacto
	 */
	public function coleccionByContactoAgenda($_id=0){
		$arrayRet=array();
		if($_id!=0){
			$arrayRet=$this->coleccionByContacto('O', $_id);
		}
		return $arrayRet;
	}

	/**
	 * Arma un array con los familiares segun el string pasado como parametro
	 * @param string $_nota -> Tipo de contacto U: usuario C: cliente  O: Contacto de Agenda
	 * @param int $_id -> id del contacto del cual se buscan los familiares
	 * @return string[][] -> array devolviendo los familiares incluidos en la lista; por cada fila incluye tipo, pais,area, apellido e id_parent
	 */
	protected function coleccionByContacto($_nota,$_id){
		$arrayRet=array();
		$telDB = new FamiliaresPGDAO();
		$result=$telDB->coleccionByTipoId($_nota, $_id);
		$arrayRet=$this->leeDBArray($result);
		return $arrayRet;
	}

        /**
         * Retorna un string con los nros de familiares de la referencia
         * @param int $_id  -> identificacion del Cliente a buscar los familiares
         * @return string ->lista de familiares separados por / ;
         */
        public function listaFamiliaresByCliente($_id=0){
            $strRet='';
            $arrayRet=array();
            if($_id!=0){
		$arrayRet=$this->coleccionByCliente($_id);
            }
            if(sizeof($arrayRet)!=0){
                $strRet=$this->armaListaFamiliares($arrayRet);
            }
            return $strRet;
        }

        /**
         * Retorna un string con los nros de familiares de la referencia
         * @param int $_id  -> identificacion del Usuario a buscar los familiares
         * @return string ->lista de familiares separados por / ;
         */
        public function listaFamiliaresByUsuarios($_id=0){
            $strRet='';
            $arrayRet=array();
            if($_id!=0){
		$arrayRet=$this->coleccionByUsuarios($_id);
            }
            if(sizeof($arrayRet)!=0){
                $strRet=$this->armaListaFamiliares($arrayRet);
            }
            return $strRet;
        }

        /**
         * Retorna un string con los nros de familiares de la referencia
         * @param int $_id  -> identificacion del Contacto a buscar los familiares
         * @return string ->lista de familiares separados por / ;
         */
        public function listaFamiliaresByContactoAgenda($_id=0){
            $strRet='';
            $arrayRet=array();
            if($_id!=0){
		$arrayRet=$this->coleccionByContactoAgenda($_id);
            }
            if(sizeof($arrayRet)!=0){
                $strRet=$this->armaListaFamiliares($arrayRet);
            }
            return $strRet;
        }
        
        protected function armaListaFamiliares($array){
            $strRet='';
            $inicio=0;
            $tipoFam=  FamiliarBSN::getInstance();
            $arrayFam=$tipoFam->getArrayParametros();
            
            foreach ($array as $familiar) {
                if($inicio==0){
                    $inicio=1;
                }else{
                    $strRet.=' / ';
                }
                $strRet.="(".$arrayFam[$familiar['id_parent']].") ";
                if($familiar['nombre']!='' && $familiar['nombre']!='0' ){
                    $strRet.=" ".$familiar['nombre']." ";
                }
                $strRet.=$familiar['apellido'];
            }
            return $strRet;
        }
        
    public function borraDB() {
        $this->actualizaCacheCliente($this->getObjeto()->getId_cli(), 'd');

        $retorno = parent::borraDB();
        return $retorno;
    }

    public function insertaDB() {
        $this->actualizaCacheCliente($this->getObjeto()->getId_cli(), 'a');

        $retorno = parent::insertaDB();
        return $retorno;
    }
    
    public function actualizaDB() {
        $this->actualizaCacheCliente($this->getObjeto()->getId_cli(), 'a');

        $retorno = parent::actualizaDB();
        return $retorno;
    }

    protected function actualizaCacheCliente($id, $operacion) {
        $cliC = ClienteCache::getInstance();
        $cliC->actualizaCache($id, $operacion);
    }
    


}

?>