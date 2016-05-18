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
include_once("clases/class.rubro.php");
include_once("clases/class.rubroPGDAO.php");

class RubroBSN extends BSN {

	protected $clase = "Rubro";
	protected $nombreId = "id_rubro";
	protected $rubro;


	public function __construct($_id_rubro=0,$_rubro=''){
		RubroBSN::seteaMapa();
		if ($_id_rubro  instanceof Rubro ){
			RubroBSN::creaObjeto();
			RubroBSN::seteaBSN($_id_rubro);
		} else {
			if (is_numeric($_id_rubro)){
				RubroBSN::creaObjeto();
				if($_id_rubro!=0){
					RubroBSN::cargaById($_id_rubro);
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
		return $this->rubro->getId_rubro();
	}

	/**
	 * Setea e ID del objeto
	 *
	 * @param unknown_type $id
	 */
	public function setId($id){
		$this->rubro->setId_rubro($id);
	}


	/**
	 * Arma un combo con losvalores levantados de la tabla correspondiente
	 *
	 * @param int $valor -> Valor actual del campo para fijar en la vista sobre la opcion actualmente seleccionada
	 * @param int $opcion -> Indica si se agrega algun tipo de opcion previo a la carga de datos 0-NO 1-Todos 2-Seleccione Opcion
	 * @param str $campo -> nombre del campo en el formulario, por omision id_rubro
	 * @param str $class -> clase de la css con la cual se presenta el dato
	 */
	public function comboRubro($valor=0,$opcion=0,$campo="id_rubro",$class="campos_btn"){
		$rubros=$this->cargaColeccionForm();
		print "<select name='".$campo."' id='".$campo."' class='" .$class."' style='width: 250px;'>\n";
		switch($opcion){
			case 1:
				print "<option value='0' SELECTED >Todos</option>\n";
				break;
			case 2:
				print "<option value='0' SELECTED >Seleccione una opcion</option>\n";
				break;
		}
		for ($pos=0;$pos<sizeof($rubros);$pos++){
			print "<option value='".$rubros[$pos]['id_rubro']."'";
			if ($rubros[$pos]['id_rubro']==$valor){
				print " SELECTED ";
			}
			print ">".$rubros[$pos]['denominacion']."</option>\n";
		}
		print "</select>\n";
	}

	/**
	 * Retorna un array con los id de los rubros y la denominacion de los misos, donde la posicion en el mismo corrsponde
	 * con el ID y el valor contenido es la denominacion
	 * return string[] 
	 */
	public function armaArrayRubros(){
		$arrayRet=array();
		$rubBSN = new RubroBSN();
		$arrayRub= $rubBSN->cargaColeccionForm();
		foreach ($arrayRub as $registro) {
			$arrayRet[$registro['id_rubro']]=$registro['denominacion'];
		}
		return $arrayRet;
	}

}

?>