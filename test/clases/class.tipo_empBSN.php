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
include_once("clases/class.tipo_emp.php");
include_once("clases/class.tipo_empPGDAO.php");


class Tipo_empBSN extends BSN {
	
	protected $clase = "Tipo_emp";
	protected $nombreId = "id_tipo_emp";
	protected $tipo_emp;

	
	public function __construct($_id_tipo_emp=0,$_tipo_emp=''){
		Tipo_empBSN::seteaMapa();
		if ($_id_tipo_emp  instanceof Tipo_emp ){
			Tipo_empBSN::creaObjeto();
			Tipo_empBSN::seteaBSN($_id_tipo_emp);
		} else {
			if (is_numeric($_id_tipo_emp)){
				Tipo_empBSN::creaObjeto();
				if($_id_tipo_emp!=0){
					Tipo_empBSN::cargaById($_id_tipo_emp);
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
		return $this->tipo_emp->getId_tipo_emp();
	}
	
/**
 * Setea e ID del objeto
 *
 * @param unknown_type $id
 */
	public function setId($id){
		$this->tipo_emp->setId_tipo_emp($id);
	}

	
/**
 * Arma un combo con losvalores levantados de la tabla correspondiente
 *
 * @param int $valor -> Valor actual del campo para fijar en la vista sobre la opcion actualmente seleccionada
 * @param int $opcion -> Indica si se agrega algun tipo de opcion previo a la carga de datos 0-NO 1-Todos 2-Seleccione Opcion 
 * @param str $campo -> nombre del campo en el formulario, por omision id_tipo_emp
 * @param str $class -> clase de la css con la cual se presenta el dato
 */
	public function comboTipoEmp($valor=0,$opcion=0,$campo="id_tipo_emp",$class="cd_celda_input"){
		$tipoEmp=$this->cargaColeccionForm();
		print "<select name='".$campo."' id='".$campo."' class='campos_btn'>\n";
		switch ($opcion) {
			case 1:
				print "<option value='0'";
				if ($valor==0){
					print " SELECTED ";
				}
				print ">Todas</option>\n";
				break;
			case 2:
				print "<option value='0'";
				if ($valor==0){
					print " SELECTED ";
				}
				print ">Seleccione una opcion</option>\n";
				break;
		
			default:
				break;
		}
		for ($pos=0;$pos<sizeof($tipoEmp);$pos++){
			print "<option value='".$tipoEmp[$pos]['id_tipo_emp']."'";
			if ($tipoEmp[$pos]['id_tipo_emp']==$valor){
				print " SELECTED ";
			}
			print ">".$tipoEmp[$pos]['tipo_emp']."</option>\n";
		}
		print "</select>\n";
	}

}

?>