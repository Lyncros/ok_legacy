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
include_once("clases/class.caracteristicaemp.php");
include_once("clases/class.caracteristicaempPGDAO.php");


class CaracteristicaempBSN extends BSN {
	
	protected $clase = "Caracteristicaemp";
	protected $nombreId = "id_carac";
	protected $caracteristicaemp;

	
	public function __construct($_id_carac=0){
		CaracteristicaempBSN::seteaMapa();
		if ($_id_carac  instanceof Caracteristicaemp ){
			CaracteristicaempBSN::creaObjeto();
			CaracteristicaempBSN::seteaBSN($_id_carac);
		} else {
			if (is_numeric($_id_carac)){
				CaracteristicaempBSN::creaObjeto();
				if($_id_carac!=0){
					CaracteristicaempBSN::cargaById($_id_carac);
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
		return $this->caracteristicaemp->getId_carac();
	}
	
/**
 * Setea e ID del objeto
 *
 * @param unknown_type $id
 */
	public function setId($id){
		$this->caracteristicaemp->setId_carac($id);
	}

	
	public function subirCarac(){
		$orden=$this->caracteristicaemp->getOrden();
//		$id_tipo_carac=$this->caracteristicaemp->getId_tipo_carac();
		if($orden>1){
			$caracDB=new CaracteristicaempPGDAO($this->getArrayTabla());
			$arrayOrden=$this->armaArrayOrden($orden-1);
			foreach ($arrayOrden as $id){
				$carac=new Caracteristicaemp($id);
				$arrayTabla=$this->mapa->objTOtabla($carac);
				$caracDB2=new CaracteristicaempPGDAO($arrayTabla);
				
				$caracDB2->bajarCarac($this->caracteristicaemp->getId_carac());
			}
			$caracDB->subirCarac();
		}
	}
	
	public function bajarCarac(){
		$orden=$this->caracteristicaemp->getOrden();
//		$id_tipo_carac=$this->caracteristicaemp->getId_tipo_carac();
		$caracDB=new CaracteristicaempPGDAO($this->getArrayTabla());
		$arrayOrden=$this->armaArrayOrden($orden+1);
		if(sizeof($arrayOrden)!=0){
			foreach ($arrayOrden as $id){
				$carac=new Caracteristicaemp($id);
				$arrayTabla=$this->mapa->objTOtabla($carac);
				$caracDB2=new CaracteristicaempPGDAO($arrayTabla);
				$caracDB2->subirCarac($this->caracteristicaemp->getId_carac());
			}
			$caracDB->bajarCarac();
		}
	}	
	
	protected function armaArrayOrden($_orden){
		$array=array();
		$carac=new Caracteristicaemp();
		$carac->setOrden($_orden);
//		$carac->setId_tipo_carac($id_tipo_carac);
		$arrayTabla=$this->mapa->objTOtabla($carac);
		$caracDB=new CaracteristicaempPGDAO($arrayTabla);
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
	

}

?>