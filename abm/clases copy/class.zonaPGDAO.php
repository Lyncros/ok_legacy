<?php
include_once ("generic_class/class.PGDAO.php");
class ZonaPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.zona (nombre_zona) values ('#nombre_zona#')";
	protected $DELETE = "DELETE FROM #dbName#.zona WHERE id_zona=#id_zona#";
	protected $UPDATE = "UPDATE #dbName#.zona SET nombre_zona='#nombre_zona#' WHERE id_zona=#id_zona# ";
	protected $FINDBYID = "SELECT id_zona,nombre_zona FROM #dbName#.zona WHERE id_zona=#id_zona#";
	protected $FINDBYCLAVE = "SELECT id_zona, nombre_zona FROM #dbName#.zona WHERE nombre_zona LIKE  '#nombre_zona#'";
	protected $COLECCION = "SELECT id_zona,nombre_zona FROM #dbName#.zona ORDER BY nombre_zona";
}
 // Fin clase DAO
 ?>