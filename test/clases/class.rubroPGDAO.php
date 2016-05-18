<?php
include_once ("generic_class/class.PGDAO.php");
class RubroPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.rubros (denominacion,detalle) values ('#denominacion#','#detalle#')";
	protected $DELETE = "DELETE FROM #dbName#.rubros WHERE id_rubro=#id_rubro#";
	protected $UPDATE = "UPDATE #dbName#.rubros SET denominacion='#denominacion#',detalle='#detalle#' WHERE id_rubro=#id_rubro# ";
	protected $FINDBYID = "SELECT id_rubro,denominacion,detalle FROM #dbName#.rubros WHERE id_rubro=#id_rubro#";
	protected $FINDBYCLAVE = "SELECT id_rubro, denominacion,detalle FROM #dbName#.rubros WHERE denominacion LIKE  '#denominacion#'";
	protected $COLECCION = "SELECT id_rubro,denominacion,detalle FROM #dbName#.rubros ORDER BY denominacion";
}
 // Fin clase DAO
 ?>