<?php
include_once("generic_class/class.PGDAO.php");

class FotoempPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.fotosemp (id_emp_carac,foto,posicion) values (#id_emp_carac#,'#foto#',#posicion#)";
	protected $DELETE="DELETE FROM #dbName#.fotosemp WHERE id_foto=#id_foto#";
	protected $UPDATE="UPDATE #dbName#.fotosemp SET id_emp_carac=#id_emp_carac#, posicion='#posicion#' WHERE id_foto=#id_foto# ";
	
	protected $FINDBYID="SELECT * FROM #dbName#.fotosemp WHERE id_foto=#id_foto#";
	protected $FINDBYCLAVE="SELECT * FROM #dbName#.fotosemp WHERE foto='#foto#'";
	protected $COLECCIONBYPROP="SELECT * FROM #dbName#.fotosemp WHERE id_emp_carac=#id_emp_carac# ORDER BY posicion";
	
	protected $SUBIRFOTO="UPDATE #dbName#.fotosemp SET posicion=posicion-1 WHERE id_foto=#id_foto#";
	protected $BAJARFOTO="UPDATE #dbName#.fotosemp SET posicion=posicion+1 WHERE id_foto=#id_foto#";
	protected $COLECCIONBYPOSICION="SELECT id_foto FROM #dbName#.fotosemp WHERE posicion=#posicion#  and id_emp_carac=#id_emp_carac#";	

	protected $MAYORORDEN="SELECT MAX(posicion) as max FROM #dbName#.fotosemp WHERE id_emp_carac=#id_emp_carac#";

	public function ColeccionByCarac(){
		$parametro=func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"X PROP");
		}
		return $resultado;
	}

	public function subirFotoemp(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->SUBIRFOTO,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"SUBIR ORDEN de FOTO");
		}
		return $resultado;				
	}

	public function bajarFotoemp(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->BAJARFOTO,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"BAJAR ORDEN de FOTO");
		}
		return $resultado;				
	}

} // Fin clase DAO
?>