<?php
include_once ("generic_class/class.PGDAO.php");
class CaracteristicaempPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.caracteristicaemp (titulo, tipo,maximo,comentario,lista,orden) values ('#titulo#','#tipo#',#maximo#,'#comentario#','#lista#',#orden#)";
	protected $DELETE = "DELETE FROM #dbName#.caracteristicaemp WHERE id_carac=#id_carac#";
	protected $UPDATE = "UPDATE #dbName#.caracteristicaemp SET  titulo='#titulo#', tipo='#tipo#',maximo=#maximo#,comentario='#comentario#',lista='#lista#',orden=#orden# WHERE id_carac=#id_carac# ";
	protected $FINDBYID = "SELECT id_carac,titulo, tipo,maximo,comentario,lista,orden FROM #dbName#.caracteristicaemp WHERE id_carac=#id_carac#";
	protected $FINDBYCLAVE = "SELECT id_carac,titulo, tipo,maximo,comentario,lista,orden FROM #dbName#.caracteristicaemp WHERE titulo LIKE  '#titulo#'";
	protected $COLECCION = "SELECT id_carac,titulo, tipo,maximo,comentario,lista,orden  FROM #dbName#.caracteristicaemp ORDER BY orden";
	protected $SUBIR = "UPDATE #dbName#.caracteristicaemp SET orden=orden-1 WHERE id_carac=#id_carac#";
	protected $BAJAR = "UPDATE #dbName#.caracteristicaemp SET orden=orden+1 WHERE id_carac=#id_carac#";
	protected $COLECCIONBYPOSICION = "SELECT id_carac FROM #dbName#.caracteristicaemp WHERE orden=#orden#";
	
	protected $MAYORORDEN="SELECT MAX(orden) as max FROM #dbName#.caracteristicaemp";
		
	public function subirCarac() {
		$parametro = func_get_args ();
		$resultado = $this->execSql ( $this->SUBIR, $parametro );
		if (! $resultado) {
			$this->onError ( $COD_UPDATE, "SUBIR ORDEN" );
		}
		return $resultado;
	}
	public function bajarCarac() {
		$parametro = func_get_args ();
		$resultado = $this->execSql ( $this->BAJAR, $parametro );
		if (! $resultado) {
			$this->onError ( $COD_UPDATE, "BAJAR ORDEN" );
		}
		return $resultado;
	}
	public function coleccionByPosicion() {
		$parametro = func_get_args ();
		$resultado = $this->execSql ( $this->COLECCIONBYPOSICION, $parametro );
		if (! $resultado) {
			$this->onError ( $COD_COLLECION, "NOTICIAS POR POSICION" );
		}
		return $resultado;
	}
	
}
// Fin clase DAO
?>