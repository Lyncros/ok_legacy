<?php
include_once ("generic_class/class.PGDAO.php");
class Tipo_empPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.tipoemp (tipo_emp) values ('#tipo_emp#')";
	protected $DELETE = "DELETE FROM #dbName#.tipoemp WHERE id_tipo_emp=#id_tipo_emp#";
	protected $UPDATE = "UPDATE #dbName#.tipoemp SET tipo_emp='#tipo_emp#' WHERE id_tipo_emp=#id_tipo_emp# ";
	protected $FINDBYID = "SELECT id_tipo_emp,tipo_emp FROM #dbName#.tipoemp WHERE id_tipo_emp=#id_tipo_emp#";
	protected $FINDBYCLAVE = "SELECT id_tipo_emp, tipo_emp FROM #dbName#.tipoemp WHERE tipo_emp LIKE  '#tipo_emp#'";
	protected $COLECCION = "SELECT id_tipo_emp,tipo_emp FROM #dbName#.tipoemp ORDER BY tipo_emp";
}
 // Fin clase DAO
 ?>