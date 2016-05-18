<?php
include_once ("generic_class/class.PGDAO.php");
class ImpuestoPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.impuestos (denominacion,detalle) values ('#denominacion#','#detalle#')";
	protected $DELETE = "DELETE FROM #dbName#.impuestos WHERE id_impuesto=#id_impuesto#";
	protected $UPDATE = "UPDATE #dbName#.impuestos SET denominacion='#denominacion#',detalle='#detalle#' WHERE id_impuesto=#id_impuesto# ";
	protected $FINDBYID = "SELECT id_impuesto,denominacion,detalle FROM #dbName#.impuestos WHERE id_impuesto=#id_impuesto#";
	protected $FINDBYCLAVE = "SELECT id_impuesto, denominacion,detalle FROM #dbName#.impuestos WHERE denominacion LIKE  '#denominacion#'";
	protected $COLECCION = "SELECT id_impuesto,denominacion,detalle FROM #dbName#.impuestos ORDER BY denominacion";
}
 // Fin clase DAO
 ?>