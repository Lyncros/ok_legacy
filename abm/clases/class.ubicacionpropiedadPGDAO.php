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
	
	public function coleccionFiltro($nombre=''){
		$parametro = func_get_args();
		$COLECIONFILTRO ="select id_ubica,nombre_ubicacion from (select u.id_ubica,concat(p.nombre_ubicacion,' - ',u.nombre_ubicacion) as nombre_ubicacion";
		$COLECIONFILTRO.=" from #dbName#.ubicacionpropiedad as u inner join #dbName#.ubicacionpropiedad as p on u.id_padre=p.id_ubica) as p where nombre_ubicacion like '%$nombre%' order by nombre_ubicacion";
		$resultado=$this->execSql($COLECIONFILTRO,$parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","Zonas filtradas ".$COLECIONFILTRO);
		}
		return $resultado;
	}

	public function coleccionHijosRecursiva($id_padre) {
		$parametro = array(
			'id_padre1' => $id_padre,
			'id_padre2' => $id_padre,
			'id_padre3' => $id_padre
		);
		$this->seteaDato($parametro);
		$COLECCION_HIJOS_RECURSIVA = "SELECT id_ubica FROM #dbName#.ubicacionpropiedad " .
								"WHERE id_ubica = #id_padre1# OR id_padre = #id_padre2# OR id_padre IN " .
								"(SELECT id_ubica FROM #dbName#.ubicacionpropiedad WHERE id_padre = #id_padre3#)";
		$resultado = $this->execSql($COLECCION_HIJOS_RECURSIVA, $parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","Subzonas recursivas de $id_padre " . $COLECCION_HIJOS_RECURSIVA);
		}
		return $resultado;
	}
	
	
}
