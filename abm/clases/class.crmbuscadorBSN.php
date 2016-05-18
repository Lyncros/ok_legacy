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
include_once("clases/class.crmbuscador.php");
include_once("clases/class.crmbuscadorPGDAO.php");


class CrmbuscadorBSN extends BSN {
	
	protected $clase = "Crmbuscador";
	protected $nombreId = "idcrm";
	protected $crmbuscador;

	
	public function __construct($_idcrm=0){
		CrmbuscadorBSN::seteaMapa();
		if ($_idcrm  instanceof Crmbuscador  ){
			CrmbuscadorBSN::creaObjeto();
			CrmbuscadorBSN::seteaBSN($_idcrm);
		} else {
			if (is_string($_idcrm)){
				CrmbuscadorBSN::creaObjeto();
				if($_idcrm!=''){
					CrmbuscadorBSN::cargaById($_idcrm);
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
		return $this->crmbuscador->getIdcrm();
	}
	
/**
 * Setea el ID del objeto
 *
 * @param entero $id del objeto
 */
	public function setId($id){
		$this->crmbuscador->setIdcrm($id);
	}

}

?>