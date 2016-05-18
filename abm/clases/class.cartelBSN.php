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
include_once("clases/class.cartel.php");
include_once("clases/class.cartelPGDAO.php");


class CartelBSN extends BSN {
	
	protected $clase = "Cartel";
	protected $nombreId = "id_cartel";
	protected $operacion;

	
	public function __construct($_id_cartel=0,$_operacion=''){
		CartelBSN::seteaMapa();
		if ($_id_cartel  instanceof Cartel  ){
			CartelBSN::creaObjeto();
			CartelBSN::seteaBSN($_id_cartel);
		} else {
			if (is_numeric($_id_cartel)){
				CartelBSN::creaObjeto();
				if($_id_cartel!=0){
					CartelBSN::cargaById($_id_cartel);
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
		return $this->cartel->getId_cartel();
	}
	
/**
 * Setea el ID del objeto
 *
 * @param entero $id del objeto
 */
	public function setId($id){
		$this->cartel->setId_cartel($id);
	}

	public function cargaCartelByPropiedad($_id){
		$operaux=new CartelBSN();
		$operaux->cartel->setId_prop($_id);
		
		$operDB = new CartelPGDAO($operaux->getArrayTabla());
		$this->seteaArray($operDB->ColeccionByProp());
	}
	
	public function cargaColeccionFormByPropiedad($_id){
		$this->cargaCartelByPropiedad($_id);
		$array=$this->{$this->getNombreObjeto()};
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}	
	
}

?>