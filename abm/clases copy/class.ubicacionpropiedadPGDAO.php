<?php
include_once ("generic_class/class.PGDAO.php");
class UbicacionpropiedadPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.ubicacionpropiedad (id_padre, nombre_ubicacion) values (#id_padre#, '#nombre_ubicacion#')";
	protected $DELETE = "DELETE FROM #dbName#.ubicacionpropiedad WHERE id_ubica=#id_ubica#";
	protected $UPDATE = "UPDATE #dbName#.ubicacionpropiedad SET id_padre=#id_padre#, nombre_ubicacion='#nombre_ubicacion#' WHERE id_ubica=#id_ubica# ";
	protected $FINDBYID = "SELECT id_ubica, id_padre, nombre_ubicacion FROM #dbName#.ubicacionpropiedad WHERE id_ubica=#id_ubica#";
	protected $FINDBYCLAVE = "SELECT id_ubica, id_padre, nombre_ubicacion FROM #dbName#.ubicacionpropiedad WHERE nombre_ubicacion LIKE  '#nombre_ubicacion#' AND id_padre=#id_padre#";
	protected $COLECCION = "SELECT id_ubica, id_padre, nombre_ubicacion FROM #dbName#.ubicacionpropiedad ORDER BY nombre_ubicacion";

	public function coleccionHijos($padre=0){
		$parametro = func_get_args();
		$COLECIONHIJOS ="SELECT id_ubica, id_padre, nombre_ubicacion FROM #dbName#.ubicacionpropiedad WHERE id_padre IN ($padre) ORDER BY id_padre,nombre_ubicacion";
		$resultado=$this->execSql($COLECIONHIJOS,$parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","Subzonas de $padre ".$COLECIONHIJOS);
		}
		return $resultado;
	}
	
	
	
	
}
 // Fin clase DAO
 ?>