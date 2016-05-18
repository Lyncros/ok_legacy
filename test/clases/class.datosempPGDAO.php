<?php
include_once("generic_class/class.PGDAO.php");

/*DROP TABLE IF EXISTS `achaval`.`emprendimiento_caracteristicas`;
CREATE TABLE  `achaval`.`emprendimiento_caracteristicas` (
  `id_emp_carac` int(10) unsigned NOT NULL auto_increment,
  `id_emp` int(10) unsigned NOT NULL,
  `id_carac` int(10) unsigned NOT NULL,
  `contenido` varchar(500) NOT NULL,
  `comentario` varchar(500) NOT NULL,
  PRIMARY KEY  (`id_emp_carac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
*/

class DatosempPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.emprendimiento_caracteristicas (id_emp,id_carac,contenido,comentario,activa) values (#id_emp#,#id_carac#,'#contenido#','#comentario#',1)";
	protected $DELETE="DELETE FROM #dbName#.emprendimiento_caracteristicas WHERE id_emp=#id_emp#";
	protected $UPDATE="UPDATE #dbName#.emprendimiento_caracteristicas SET id_emp=#id_emp#,id_carac=#id_carac#,contenido='#contenido#',comentario='#comentario#' WHERE id_emp_carac=#id_emp_carac# ";

	protected $FINDBYID="SELECT id_emp_carac,id_emp,id_carac,contenido,comentario,activa FROM #dbName#.emprendimiento_caracteristicas WHERE id_emp_carac=#id_emp_carac#";

	protected $FINDBYCLAVE="SELECT id_emp_carac,id_emp,id_carac,contenido,comentario,activa FROM #dbName#.emprendimiento_caracteristicas WHERE id_emp=#id_emp# and  id_carac=#id_carac#";
	
	protected $COLECCION="SELECT p.id_emp_carac,p.id_emp,p.id_carac,p.contenido,p.comentario,p.activa 
							FROM #dbName#.emprendimiento_caracteristicas as p 
							WHERE p.id_emp=#id_emp#";
	
	protected $COLECCIONACTIVAS="SELECT p.id_emp_carac,p.id_emp,p.id_carac,p.contenido,p.comentario,p.activa 
							FROM #dbName#.emprendimiento_caracteristicas as p 
							WHERE p.id_emp=#id_emp# and p.activa=1";

	protected $BASECOLECCIONCARACEMP = "SELECT p.id_emp_carac,c.orden,p.id_emp,p.id_carac,c.titulo,p.contenido,p.comentario,p.activa FROM #dbName#.emprendimiento_caracteristicas as p INNER JOIN #dbName#.caracteristicaemp as c
							ON p.id_carac=c.id_carac WHERE p.id_emp=#id_emp# ";
	protected $ORDERCOLECCIONCARACEMP = " ORDER BY c.orden";


	protected $ACTIVAR="UPDATE #dbName#.emprendimiento_caracteristicas SET activa=1 WHERE id_emp_carac=#id_emp_carac#";
	
	protected $DESACTIVAR="UPDATE #dbName#.emprendimiento_caracteristicas SET activa=0 WHERE id_emp_carac=#id_emp_carac#";

	
	public function coleccionCaracteristicasByEmp($activa){
		$parametro = func_get_args();
		$sql = $this->BASECOLECCIONCARACEMP;
		if($activa==1){
			$sql.=" AND activa=1 ";
		}
		$sql.=$this->ORDERCOLECCIONCARACEMP;
		$resultado=$this->execSql($sql,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"CARACTERISTICAS DE EMPRENDIMIENTOS");
		}
		return $resultado;		
	}

	public function coleccionCaracteristicasEmpActivas(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONACTIVAS,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"CARACTERISTICAS DE EMPRENDIMIENTOS ACTIVAS");
		}
		return $resultado;		
	}

	public function activar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->ACTIVAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"ACTIVACION de CARACTERISTICAS DE EMPRENDIMIENTO");
		}
		return $resultado;				
	}
	
	public function desactivar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DESACTIVAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"INACTIVACION de CARACTERISTICAS DE EMPRENDIMIENTO");
		}
		return $resultado;				
	}	
	

} // Fin clase DAO

?>