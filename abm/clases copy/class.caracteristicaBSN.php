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
include_once("clases/class.caracteristica.php");
include_once("clases/class.caracteristicaPGDAO.php");
include_once("clases/class.tipo_propBSN.php");


class CaracteristicaBSN extends BSN {
	
	protected $clase = "Caracteristica";
	protected $nombreId = "id_carac";
	protected $caracteristica;

	
	public function __construct($_id_carac=0){
		CaracteristicaBSN::seteaMapa();
		if ($_id_carac  instanceof Caracteristica ){
			CaracteristicaBSN::creaObjeto();
			CaracteristicaBSN::seteaBSN($_id_carac);
		} else {
			if (is_numeric($_id_carac)){
				CaracteristicaBSN::creaObjeto();
				if($_id_carac!=0){
					CaracteristicaBSN::cargaById($_id_carac);
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
		return $this->caracteristica->getId_carac();
	}
	
/**
 * Setea e ID del objeto
 *
 * @param unknown_type $id
 */
	public function setId($id){
		$this->caracteristica->setId_carac($id);
	}

	
	public function insertaBuscador($id_carac,$comparacion=''){
		$this->setId($id_carac);
		if($comparacion=='eq'){
			$comparacion='=';
		}
		$this->caracteristica->setComparacion(trim($comparacion));
		$caracDB = new CaracteristicaPGDAO($this->getArrayTabla());
		$retorno=$caracDB->insertaBuscador();
		if($retorno){
			$retorno=true;
		}
		return $retorno;
	}

	public function borraBuscador($id_carac){
		$this->setId($id_carac);
		$caracDB = new CaracteristicaPGDAO($this->getArrayTabla());
		$retorno=$caracDB->deleteBuscador();
		return $retorno;
	}

	public function cargaColeccionBuscador(){
		$array=array();
		$datoDB = new CaracteristicaPGDAO();
		$array = $this->leeDBArray($datoDB->coleccionBuscador());
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$registro[0];
		}
		return $arrayform;
	}

/**
 * Lee el tipo de comparacion y retorna el mismo, tomando como base el id de la caracteristica del objeto actual
 *
 * @return string con el comparador
 */
	public function cargaComparacionCarac($id_carac){
		$array=array();
		$datoDB = new CaracteristicaPGDAO();
		$array = $this->leeDBArray($datoDB->cargaBuscadorById($id_carac));
		return $array[0][0];
	}
	
	public function coleccionCaracteristicasBuscador($tipo_prop=0){
		$array=array();
		$aux = new AuxiliaresPGDAO();
		if($tipo_prop == 0){

			$propBSN = new Tipo_propBSN();
			$arrayProp=$propBSN->cargaColeccionForm();
			$in='0';
			// Busco en la tabla de caracteristicas por buscador, las que coinciden con todos los tipos de propiedades
			foreach ($arrayProp as $prop){
				if($in!=''){
					$in=$aux->coleccionTipopropCaracBuscador($prop['id_tipo_prop'],$in);
				}
			}
			
		}else{
			$in=$aux->coleccionTipopropCaracBuscador($tipo_prop);
		}
		
		$datoDB = new CaracteristicaPGDAO();
		$array = $this->leeDBArray($datoDB->coleccionCaracBuscador($in));
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;

	}
	
	public function subirCarac(){
		$orden=$this->caracteristica->getOrden();
		$id_tipo_carac=$this->caracteristica->getId_tipo_carac();
		if($orden>1){
			$caracDB=new CaracteristicaPGDAO($this->getArrayTabla());
			$arrayOrden=$this->armaArrayOrden($orden-1,$id_tipo_carac);
			foreach ($arrayOrden as $id){
				$carac=new Caracteristica($id);
				$arrayTabla=$this->mapa->objTOtabla($carac);
				$caracDB2=new CaracteristicaPGDAO($arrayTabla);
				
				$caracDB2->bajarCarac($this->caracteristica->getId_carac());
			}
			$caracDB->subirCarac();
		}
	}
	
	public function bajarCarac(){
		$orden=$this->caracteristica->getOrden();
		$id_tipo_carac=$this->caracteristica->getId_tipo_carac();
		$caracDB=new CaracteristicaPGDAO($this->getArrayTabla());
		$arrayOrden=$this->armaArrayOrden($orden+1,$id_tipo_carac);
		if(sizeof($arrayOrden)!=0){
			foreach ($arrayOrden as $id){
				$carac=new Caracteristica($id);
				$arrayTabla=$this->mapa->objTOtabla($carac);
				$caracDB2=new CaracteristicaPGDAO($arrayTabla);
				$caracDB2->subirCarac($this->caracteristica->getId_carac());
			}
			$caracDB->bajarCarac();
		}
	}	
	
	protected function armaArrayOrden($_orden,$id_tipo_carac){
		$array=array();
		$carac=new Caracteristica();
		$carac->setOrden($_orden);
		$carac->setId_tipo_carac($id_tipo_carac);
		$arrayTabla=$this->mapa->objTOtabla($carac);
		$caracDB=new CaracteristicaPGDAO($arrayTabla);
		$result=$caracDB->coleccionByPosicion();
		
		$conf=CargaConfiguracion::getInstance();
		$tipodb=$conf->leeParametro("tipodb");
		if($tipodb=="my"){
			$nrows="mysql_numrows";
			$fetch="mysql_fetch_array";
		} else {
			$nrows="pg_numrows";
			$fetch="pg_fetch_array";
		}

		if($nrows($result) != 0 ){
			while ($row = $fetch($result)){
				$array[]=$row["id_carac"];
			}
		}
		return $array;
	}	
	
	public function cargaColeccionTipoCarac($id_tipo_carac){
//		$localclass=$this->getClase().'PGDAO';
		$array=array();
		$carac=new Caracteristica();
		$carac->setId_tipo_carac($id_tipo_carac);		
		$arrayTabla=$this->mapa->objTOtabla($carac);
		$datoDB = new CaracteristicaPGDAO($arrayTabla);
		$array = $this->leeDBArray($datoDB->coleccionByTipo());
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}

}

?>