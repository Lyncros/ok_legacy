<?php
include_once ("generic_class/class.PGDAO.php");
class LocalidadPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.localidad (id_zona, nombre_loca) values (#id_zona#, '#nombre_loca#')";
	protected $DELETE = "DELETE FROM #dbName#.localidad WHERE id_loca=#id_loca#";
	protected $UPDATE = "UPDATE #dbName#.localidad SET id_zona=#id_zona#, nombre_loca='#nombre_loca#' WHERE id_loca=#id_loca# ";
	protected $FINDBYID = "SELECT id_loca, id_zona, nombre_loca FROM #dbName#.localidad WHERE id_loca=#id_loca#";
	protected $FINDBYCLAVE = "SELECT id_loca, id_zona, nombre_loca FROM #dbName#.localidad WHERE nombre_loca LIKE  '#nombre_loca#'";
	protected $COLECCION = "SELECT id_loca, id_zona, nombre_loca FROM #dbName#.localidad ORDER BY nombre_loca";
	protected $COLECCIONBYZONA = "SELECT id_loca, id_zona, nombre_loca FROM #dbName#.localidad WHERE id_zona=#id_zona# ORDER BY nombre_loca";

	public function coleccionByZona(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYZONA,$parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","Localidad x Zona");
		}
		return $resultado;
	}

}
 // Fin clase DAO
 ?>