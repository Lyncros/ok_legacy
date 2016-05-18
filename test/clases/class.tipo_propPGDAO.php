<?php
include_once ("generic_class/class.PGDAO.php");
class Tipo_propPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.tipoprop (tipo_prop,subtipo_prop) values ('#tipo_prop#','#subtipo_prop#')";
	protected $DELETE = "DELETE FROM #dbName#.tipoprop WHERE id_tipo_prop=#id_tipo_prop#";
	protected $UPDATE = "UPDATE #dbName#.tipoprop SET tipo_prop='#tipo_prop#',subtipo_prop='#subtipo_prop#' WHERE id_tipo_prop=#id_tipo_prop# ";
	protected $FINDBYID = "SELECT id_tipo_prop,tipo_prop,subtipo_prop FROM #dbName#.tipoprop WHERE id_tipo_prop=#id_tipo_prop#";
	protected $FINDBYCLAVE = "SELECT id_tipo_prop, tipo_prop,subtipo_prop FROM #dbName#.tipoprop WHERE tipo_prop LIKE  '#tipo_prop#'";
	protected $COLECCION = "SELECT id_tipo_prop,tipo_prop,subtipo_prop FROM #dbName#.tipoprop ORDER BY tipo_prop";
}
 // Fin clase DAO
 ?>