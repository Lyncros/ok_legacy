<?php
include_once("generic_class/class.PGDAO.php");


class OperacionPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.propiedad_operacion (id_prop,operacion,fecha,intervino,comentario) values (#id_prop#,'#operacion#',STR_TO_DATE('#cfecha#', '%d-%m-%Y'),#intervino#,'#comentario#')";
	protected $DELETE="DELETE FROM #dbName#.propiedad_operacion WHERE id_oper=#id_oper#";
	protected $UPDATE="UPDATE #dbName#.propiedad_operacion SET id_prop=#id_prop#,operacion='#operacion#',fecha=STR_TO_DATE('#cfecha#', '%d-%m-%Y'),inteervino=#intervino#,comentario='#comentario#' WHERE id_oper=#id_oper# ";

	protected $FINDBYID="SELECT id_oper,id_prop,operacion,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,intervino,comentario FROM #dbName#.propiedad_operacion WHERE id_oper=#id_oper#";

	protected $FINDBYCLAVE="SELECT id_oper,id_prop, operacion,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha ,intervino,comentario
							FROM #dbName#.propiedad_operacion
							WHERE operacion LIKE  '#operacion#'";
	
	protected $COLECCION="SELECT id_oper,id_prop,operacion,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,intervino,comentario FROM #dbName#.propiedad_operacion ORDER BY fecha";

	protected $COLECCIONBYPROP="SELECT id_oper,id_prop,operacion,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,intervino,comentario FROM #dbName#.propiedad_operacion WHERE id_prop=#id_prop# ORDER BY fecha";
	
	protected $COLECCIONOVEDADES="SELECT id_oper,id_prop,operacion,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,intervino,comentario FROM #dbName#.propiedad_operacion WHERE fecha >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY AND fecha < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY ORDER BY fecha DESC";
	
/* propiedad_operacion
  id_oper INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  id_prop INTEGER UNSIGNED NOT NULL,
  operacion VARCHAR(45) NOT NULL,
  fecha DATETIME NOT NULL,
  intervino INTEGER UNSIGNED NOT NULL,
 */

	public function ColeccionByProp(){
		$parametro=func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"X PROP");
		}
		return $resultado;
	}

	public function ColeccionNovedades(){
		$parametro=func_get_args();
		$resultado=$this->execSql($this->COLECCIONOVEDADES,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"X PROP");
		}
		return $resultado;
	}
	
	
} // Fin clase DAO
?>