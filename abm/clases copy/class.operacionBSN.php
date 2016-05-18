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
include_once("clases/class.operacion.php");
include_once("clases/class.operacionPGDAO.php");


class OperacionBSN extends BSN {
	
	protected $clase = "Operacion";
	protected $nombreId = "id_oper";
	protected $operacion;

	
	public function __construct($_id_oper=0,$_operacion=''){
		OperacionBSN::seteaMapa();
		if ($_id_oper  instanceof Operacion ){
			OperacionBSN::creaObjeto();
			OperacionBSN::seteaBSN($_id_oper);
		} else {
			if (is_numeric($_id_oper)){
				OperacionBSN::creaObjeto();
				if($_id_oper!=0){
					OperacionBSN::cargaById($_id_oper);
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
		return $this->operacion->getId_oper();
	}
	
/**
 * Setea e ID del objeto
 *
 * @param unknown_type $id
 */
	public function setId($id){
		$this->operacion->setId_oper($id);
	}

	public function cargaOperacionByPropiedad($_id){
		$operaux=new OperacionBSN();
		$operaux->operacion->setId_prop($_id);
		
		$operDB = new OperacionPGDAO($operaux->getArrayTabla());
		$this->seteaArray($operDB->ColeccionByProp());
	}
	
	public function cargaColeccionFormByPropiedad($_id){
		$this->cargaOperacionByPropiedad($_id);
		$array=$this->{$this->getNombreObjeto()};
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}	
	
	
}

?>