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
include_once("clases/class.tasacion.php");
include_once("clases/class.tasacionPGDAO.php");


class TasacionBSN extends BSN {
	
	protected $clase = "Tasacion";
	protected $nombreId = "id_tasacion";
	protected $operacion;

	
	public function __construct($_id_tasacion=0,$_operacion=''){
		TasacionBSN::seteaMapa();
		if ($_id_tasacion  instanceof Tasacion  ){
			TasacionBSN::creaObjeto();
			TasacionBSN::seteaBSN($_id_tasacion);
		} else {
			if (is_numeric($_id_tasacion)){
				TasacionBSN::creaObjeto();
				if($_id_tasacion!=0){
					TasacionBSN::cargaById($_id_tasacion);
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
		return $this->tasacion->getId_tasacion();
	}
	
/**
 * Setea el ID del objeto
 *
 * @param entero $id del objeto
 */
	public function setId($id){
		$this->tasacion->setId_tasacion($id);
	}

	public function cargaTasacionByPropiedad($_id){
		$operaux=new TasacionBSN();
		$operaux->tasacion->setId_prop($_id);
		
		$operDB = new TasacionPGDAO($operaux->getArrayTabla());
		$this->seteaArray($operDB->ColeccionByProp());
	}
	
	public function cargaColeccionFormByPropiedad($_id){
		$this->cargaTasacionByPropiedad($_id);
		$array=$this->{$this->getNombreObjeto()};
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}	
	
}

?>