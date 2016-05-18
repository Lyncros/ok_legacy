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
include_once("clases/class.tiporelacion.php");
include_once("clases/class.tiporelacionPGDAO.php");


class TiporelacionBSN extends BSN {

	protected $clase = "Tiporelacion";
	protected $nombreId = "id_tiporel";
	protected $tiporelacion;
	protected $arrayTipos=array('UP','UU','CP','CC','UC');
	protected $arrayDescTipos=array('Usuario - Propiedad','Usuario - Usuario','Cliente - Propiedad','Cliente - Cliente','Usuario - Cliente');

	public function __construct($_id_tiporel=0,$_tiporelacion=''){
		TiporelacionBSN::seteaMapa();
		if ($_id_tiporel  instanceof Tiporelacion ){
			TiporelacionBSN::creaObjeto();
			TiporelacionBSN::seteaBSN($_id_tiporel);
		} else {
			if (is_numeric($_id_tiporel)){
				TiporelacionBSN::creaObjeto();
				if($_id_tiporel!=0){
					TiporelacionBSN::cargaById($_id_tiporel);
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
		return $this->tiporelacion->getId_tiporel();
	}

	/**
	 * Setea e ID del objeto
	 *
	 * @param unknown_type $id
	 */
	public function setId($id){
		$this->tiporelacion->setId_tiporel($id);
	}

	/**
	 * Retorna el array que contien los tipos de relacion
	 */
	public function getArrayTipo(){
		return $this->arrayTipos;
	}

	/**
	 * Retorna el array con el detalle de los tipos de relacion
	 */
	public function getArrayDetalle(){
		return $this->arrayDescTipos;
	}

	/**
	 * Arma un combo con los tipos posibles de relaciones
	 * @param string $valor -> valor del campo en el caso que sea conocido
	 * @param string $campo -> nombre del campo en el formulario
	 * @param string $class -> nombre de la clase que define el estilo del combo
	 */
	public function comboTipoRelacion($valor='',$opcion=0,$campo="tiporelacion",$class="cd_celda_input"){
		$tipoRel=$this->arrayTipos;
		if(strlen($valor)==1){
			$parte=$valor;
		}else{
			$parte='';
		}
		print "<select name='".$campo."' id='".$campo."' class='campos_btn'";
		if($opcion==1){
			print "onchange='actualizaComboRelacion();'";
		}
		print ">\n";
		print "<option value=''";
		if ($valor=='') {
			print " SELECTED ";
			$all=1;
		}
		print ">Seleccione una opcion</option>\n";
		for ($pos=0;$pos<sizeof($tipoRel);$pos++){
			if(strpos($tipoRel[$pos],$parte)!==false || $parte==''){
				print "<option value='".$tipoRel[$pos]."'";
				if ($tipoRel[$pos]==$valor){
					print " SELECTED ";
				}
				print ">".$this->arrayDescTipos[$pos]."</option>\n";
			}
		}
		print "</select>\n";
	}

	/**
	 * Arma un combo con las posibles de relaciones definidas para el tipo de relacion indicado
	 * @param string $valor -> valor del campo en el caso que sea conocido
	 * @param string $tiporel -> valor del tipo de relacion
	 * @param string $campo -> nombre del campo en el formulario
	 * @param string $class -> nombre de la clase que define el estilo del combo
	 */
	public function comboRelacion($valor=0,$tiporel='UP',$campo="id_tiporel",$class="cd_celda_input"){
		if(in_array($tiporel, $this->arrayTipos)){
			$tipoRel=$this->coleccionRelacionByTipo($tiporel);
			print "<select name='".$campo."' id='".$campo."' class='campos_btn' >\n";
			for ($pos=0;$pos<sizeof($tipoRel);$pos++){
				print "<option value='".$tipoRel[$pos]['id_tiporel']."'";
				if ($tipoRel[$pos]['id_tiporel']==$valor){
					print " SELECTED ";
				}
				print ">".$tipoRel[$pos]['relacion']." - ".$tipoRel[$pos]['grado']."</option>\n";
			}
			print "</select>\n";
		}
	}

	/**
	 * Arma un array con las relaciones posibles segun el tipo de relacion pasada como parametro
	 * @param string $_tipo -> tipo de relacion
	 * @return string[][] -> array devolviendo las relaciones posibles
	 */
	public function coleccionRelacionByTipo($_tipo=''){
		$arrayRet=array();
		if(in_array($_tipo, $this->arrayTipos)){
			$tipo=new Tiporelacion();
			$tipo->setTiporelacion($_tipo);
			$arrayTabla=$this->mapa->objTOtabla($tipo);
			$tipoDB = new TiporelacionPGDAO($arrayTabla);
			$result=$tipoDB->coleccionByTipo();
			$arrayRet=$this->leeDBArray($result);
		}
		return $arrayRet;
	}

	public function getDescripcionTipo($tipo){
		$pos=array_search($tipo, $this->arrayTipos);
		$retorno='';
		if($pos!==false){
			$retorno=$this->arrayDescTipos[$pos];
		}
		return $retorno;
	}

        /**
         * Retorna un array con los tipos de relaciones en cuyo forma exista uno de los componentes del tipo especificado
         *  @param char $componente -> char que identifica el componente a buscar en la relacion
         *  @param int $_parte -> int que identifica la parte buscada en la relacion
         */
        public function cargaRelacionByComponente($componente,$_tipo){
		$arrayRet=array();
//			$tipo=new Tiporelacion();
//			$tipo->setTiporelacion($componente);
//			$arrayTabla=$this->mapa->objTOtabla($tipo);
//			$tipoDB = new TiporelacionPGDAO($arrayTabla);
			$tipoDB = new TiporelacionPGDAO();
			$result=$tipoDB->coleccionByComponente($componente,$_tipo);
			$arrayRet=$this->leeDBArray($result);
		return $arrayRet;
        }
        
        /**
         * Arma una lista separada por , con los ID de las relaciones que contienen el componente indicado en la parte especificada
         *  @param char $componente -> char que identifica el componente a buscar en la relacion
         *  @param int $_parte -> int que identifica la parte buscada en la relacion
         */
        public function armaListaRelacionesByComponente($componente,$_parte){
            $retorno='';
            $arrayRel = $this->cargaRelacionByComponente($componente,$_parte);
            if(sizeof($arrayRel)!=0){
                foreach ($arrayRel as $registro){
                    $retorno.=($registro['id_tiporel'].',');
                }
                $retorno=  substr($retorno, 0,-1);
            }
            return $retorno;
        }
}

?>