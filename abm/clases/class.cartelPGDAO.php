<?php
include_once("generic_class/class.PGDAO.php");
/*  `id_cartel` int(10) unsigned NOT NULL auto_increment,  `id_prop` int(10) unsigned NOT NULL,  `estado` varchar(45) NOT NULL,  `fecha` datetime NOT NULL,  `proveedor` varchar(100) NOT NULL,  `observacion` varchar(500) NOT NULL, */
class CartelPGDAO extends PGDAO {
	
	protected $INSERT="INSERT INTO #dbName#.cartel (id_prop,estado,fecha,proveedor,observacion) values (#id_prop#,'#estado#',STR_TO_DATE('#cfecha#', '%d-%m-%Y'),'#proveedor#','#observacion#')";

	protected $DELETE="DELETE FROM #dbName#.cartel WHERE id_cartel=#id_cartel#";	protected $UPDATE="UPDATE #dbName#.cartel SET id_prop=#id_prop#,estado='#estado#',fecha=STR_TO_DATE('#cfecha#', '%d-%m-%Y'),proveedor='#proveedor#',observacion='#observacion#' WHERE id_cartel=#id_cartel# ";

	protected $FINDBYID="SELECT id_cartel,id_prop,estado,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,proveedor,observacion FROM #dbName#.cartel WHERE id_cartel=#id_cartel#";

	protected $FINDBYCLAVE="SELECT id_cartel,id_prop, estado,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha ,proveedor,observacion							FROM #dbName#.cartel							WHERE estado LIKE  '#estado#'";
	protected $COLECCION="SELECT id_cartel,id_prop,estado,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,proveedor,observacion FROM #dbName#.cartel ORDER BY fecha";

	protected $COLECCIONBYPROP="SELECT id_cartel,id_prop,estado,DATE_FORMAT(fecha,'%d-%m-%Y') as cfecha,proveedor,observacion FROM #dbName#.cartel WHERE id_prop=#id_prop# ORDER BY fecha";
	/* cartel  id_cartel INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,  id_prop INTEGER UNSIGNED NOT NULL,  estado VARCHAR(45) NOT NULL,  fecha DATETIME NOT NULL,  proveedor INTEGER UNSIGNED NOT NULL, */
	public function ColeccionByProp(){
		$parametro=func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"X PROP");
		}
		return $resultado;
	}
} // Fin clase DAO?>