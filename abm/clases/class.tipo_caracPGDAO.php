<?php
include_once("generic_class/class.PGDAO.php");


class Tipo_caracPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.tipocarac (tipo_carac,orden,columnas,publica) values ('#tipo_carac#',#orden#,#columnas#,#publica#)";
	protected $DELETE="DELETE FROM #dbName#.tipocarac WHERE id_tipo_carac=#id_tipo_carac#";
	protected $UPDATE="UPDATE #dbName#.tipocarac SET tipo_carac='#tipo_carac#',orden=#orden#,columnas=#columnas#,publica=#publica# WHERE id_tipo_carac=#id_tipo_carac# ";

	protected $FINDBYID="SELECT id_tipo_carac,tipo_carac,orden,columnas,publica FROM #dbName#.tipocarac WHERE id_tipo_carac=#id_tipo_carac#";

	protected $FINDBYCLAVE="SELECT id_tipo_carac, tipo_carac, orden ,columnas,publica
							FROM #dbName#.tipocarac
							WHERE tipo_carac LIKE  '#tipo_carac#'";
	
	protected $COLECCION="SELECT id_tipo_carac,tipo_carac,orden,columnas,publica FROM #dbName#.tipocarac ORDER BY orden";
	
	protected $SUBIR="UPDATE #dbName#.tipocarac SET orden=orden-1 WHERE id_tipo_carac=#id_tipo_carac#";
	protected $BAJAR="UPDATE #dbName#.tipocarac SET orden=orden+1 WHERE id_tipo_carac=#id_tipo_carac#";
	
	protected $COLECCIONBYPOSICION="SELECT id_tipo_carac FROM #dbName#.tipocarac WHERE orden=#orden#";	

	protected $MAYORORDEN="SELECT MAX(orden) as max FROM #dbName#.tipocarac";

	
	public function subirTipoCarac(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->SUBIR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"SUBIR ORDEN");
		}
		return $resultado;				
	}

	public function bajarTipoCarac(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->BAJAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"BAJAR ORDEN");
		}
		return $resultado;				
	}

	public function coleccionByPosicion(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYPOSICION,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"NOTICIAS POR POSICION");
		}
		return $resultado;
	}		
	
	
	
} // Fin clase DAO
?>