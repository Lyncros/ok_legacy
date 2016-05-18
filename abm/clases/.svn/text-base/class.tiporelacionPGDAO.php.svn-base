<?php
include_once("generic_class/class.PGDAO.php");


class TiporelacionPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.tiporelacion (tiporelacion,relacion,grado,observacion) values ('#tiporelacion#','#relacion#','#grado#','#observacion#')";
	protected $DELETE="DELETE FROM #dbName#.tiporelacion WHERE id_tiporel=#id_tiporel#";
	protected $UPDATE="UPDATE #dbName#.tiporelacion SET tiporelacion='#tiporelacion#',relacion='#relacion#',grado='#grado#',observacion='#observacion#' WHERE id_tiporel=#id_tiporel# ";

	protected $FINDBYID="SELECT id_tiporel,tiporelacion,relacion,grado,observacion FROM #dbName#.tiporelacion WHERE id_tiporel=#id_tiporel#";

	protected $FINDBYCLAVE="SELECT id_tiporel, tiporelacion, relacion ,grado,observacion
							FROM #dbName#.tiporelacion
							WHERE tiporelacion LIKE  '#tiporelacion#' AND relacion like '#relacion#' AND grado like '#grado#'";
	
	protected $COLECCION="SELECT id_tiporel,tiporelacion,relacion,grado,observacion FROM #dbName#.tiporelacion ORDER BY tiporelacion,relacion";
	
	protected $COLECCIONBYTIPO="SELECT id_tiporel,tiporelacion,relacion,grado,observacion FROM #dbName#.tiporelacion WHERE tiporelacion='#tiporelacion#' ORDER BY relacion,grado";	

	public function coleccionByTipo(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYTIPO,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"RELACIONES POR TIPO");
		}
		return $resultado;
	}		
	
	
	
} // Fin clase DAO
?>