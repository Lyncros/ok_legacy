<?php
include_once("generic_class/class.PGDAO.php");
/*  `id_tasacion` int(10) unsigned NOT NULL auto_increment,  `id_prop` int(10) unsigned NOT NULL,  `valor` varchar(45) NOT NULL,  `fecha` datetime NOT NULL,  `tasador` varchar(100) NOT NULL,  `observacion` varchar(500) NOT NULL, */
class TasacionPGDAO extends PGDAO {
	
	protected $INSERT="INSERT INTO #dbName#.tasacion (id_prop,tasador,fecha,valor,estado,observacion) values (#id_prop#,'#tasador#',STR_TO_DATE('#cfecha#', '%d-%m-%Y'),'#valor#','#estado#','#observacion#')";
	
	protected $DELETE="DELETE FROM #dbName#.tasacion WHERE id_tasacion=#id_tasacion#";
	
	protected $UPDATE="UPDATE #dbName#.tasacion SET id_prop=#id_prop#,valor='#valor#',fecha=STR_TO_DATE('#cfecha#', '%d-%m-%Y'),tasador='#tasador#',estado='#estado#',observacion='#observacion#' WHERE id_tasacion=#id_tasacion# ";
	
	protected $FINDBYID="SELECT id_tasacion,id_prop,tasador,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,valor,estado,observacion FROM #dbName#.tasacion WHERE id_tasacion=#id_tasacion#";
	
	protected $FINDBYCLAVE="SELECT id_tasacion,id_prop,tasador,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha , valor,estado,observacion FROM #dbName#.tasacion WHERE valor LIKE  '#valor#'";
	
	protected $COLECCION="SELECT id_tasacion,id_prop,tasador,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,valor,estado,observacion FROM #dbName#.tasacion ORDER BY fecha";
	
	protected $COLECCIONBYPROP="SELECT id_tasacion,id_prop,tasador,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,valor,estado,observacion FROM #dbName#.tasacion WHERE id_prop=#id_prop# ORDER BY fecha";
	/* tasacion  id_tasacion INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,  id_prop INTEGER UNSIGNED NOT NULL,  valor VARCHAR(45) NOT NULL,  fecha DATETIME NOT NULL,  tasador INTEGER UNSIGNED NOT NULL, */
	
	public function ColeccionByProp(){
		$parametro=func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"X PROP");
		}
		return $resultado;
	}
} // Fin clase DAO
	?>