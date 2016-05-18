<?php
include_once ("generic_class/class.PGDAO.php");
class PerfilesPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.perfiles (perfil,descripcion) values ('#perfil#','#descripcion#')";
	protected $DELETE = "DELETE FROM #dbName#.perfiles WHERE perfil='#perfil#'";
	protected $UPDATE = "UPDATE #dbName#.perfiles SET descripcion='#descripcion#' WHERE perfil='#perfil#' ";
	protected $FINDBYID = "SELECT perfil,descripcion FROM #dbName#.perfiles WHERE perfil='#perfil#'";
	protected $FINDBYCLAVE = "SELECT perfil, descripcion FROM #dbName#.perfiles WHERE perfil='#perfil#'";
	protected $COLECCION = "SELECT perfil,descripcion FROM #dbName#.perfiles ORDER BY perfil";
}
 // Fin clase DAO
 ?>