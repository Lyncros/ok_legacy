<?php
include_once("generic_class/class.PGDAO.php");

class FotoPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.fotos (id_prop,foto,posicion) values (#id_prop#,'#foto#',#posicion#)";
	protected $DELETE="DELETE FROM #dbName#.fotos WHERE id_foto=#id_foto#";
	protected $UPDATE="UPDATE #dbName#.fotos SET id_prop=#id_prop#, posicion='#posicion#' WHERE id_foto=#id_foto# ";
	
	protected $FINDBYID="SELECT * FROM #dbName#.fotos WHERE id_foto=#id_foto#";
	protected $FINDBYCLAVE="SELECT * FROM #dbName#.fotos WHERE foto='#foto#'";
	protected $COLECCIONBYPROP="SELECT * FROM #dbName#.fotos WHERE id_prop=#id_prop# ORDER BY posicion";
	
	protected $SUBIRFOTO="UPDATE #dbName#.fotos SET posicion=posicion-1 WHERE id_foto=#id_foto#";
	protected $BAJARFOTO="UPDATE #dbName#.fotos SET posicion=posicion+1 WHERE id_foto=#id_foto#";
	protected $COLECCIONBYPOSICION="SELECT id_foto FROM #dbName#.fotos WHERE posicion=#posicion# and id_prop=#id_prop#";	

	protected $MAYORORDEN="SELECT MAX(posicion) as max FROM #dbName#.fotos WHERE id_prop=#id_prop#";
	
	public function ColeccionByProp(){
		$parametro=func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"X PROP");
		}
		return $resultado;
	}

	public function subirFoto(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->SUBIRFOTO,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"SUBIR ORDEN de FOTO");
		}
		return $resultado;				
	}

	public function bajarFoto(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->BAJARFOTO,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"BAJAR ORDEN de FOTO");
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