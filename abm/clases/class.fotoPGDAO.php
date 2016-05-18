<?php
include_once("generic_class/class.PGDAO.php");

class FotoPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.fotos (id_prop,foto,posicion) values (#id_prop#,'#foto#',#posicion#)";
	protected $DELETE="DELETE FROM #dbName#.fotos WHERE id_foto=#id_foto#";
	protected $UPDATE="UPDATE #dbName#.fotos SET id_prop=#id_prop#, posicion='#posicion#' WHERE id_foto=#id_foto# ";
	
	protected $FINDBYID="SELECT * FROM #dbName#.fotos WHERE id_foto=#id_foto#";
	protected $FINDBYCLAVE="SELECT * FROM #dbName#.fotos WHERE foto='#foto#'";
	protected $COLECCIONBYPROP="SELECT * FROM #dbName#.fotos WHERE id_prop=#id_prop# ORDER BY posicion";
	protected $COLECCIONPUBLICASBYPROP="SELECT * FROM #dbName#.fotos WHERE id_prop=#id_prop# AND publica_web=1 ORDER BY posicion";
	
	protected $SUBIRFOTO="UPDATE #dbName#.fotos SET posicion=posicion-1 WHERE id_foto=#id_foto#";
	protected $BAJARFOTO="UPDATE #dbName#.fotos SET posicion=posicion+1 WHERE id_foto=#id_foto#";
	protected $MOSTRAR_PUBLICA_WEB="UPDATE #dbName#.fotos SET publica_web=1 WHERE id_foto=#id_foto#";
	protected $OCULTAR_PUBLICA_WEB="UPDATE #dbName#.fotos SET publica_web=0 WHERE id_foto=#id_foto#";
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

	public function ColeccionPublicasByProp() {
		$parametro=func_get_args();
		$resultado=$this->execSql($this->COLECCIONPUBLICASBYPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"PUBLICAS X PROP");
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

	public function mostrarFoto($id_foto) {
		$this->cambiaPublicaWeb($this->MOSTRAR_PUBLICA_WEB, $id_foto);
	}

	public function ocultarFoto($id_foto) {
		$this->cambiaPublicaWeb($this->OCULTAR_PUBLICA_WEB, $id_foto);
	}

	private function cambiaPublicaWeb($sql, $id_foto) {
		$resultado=$this->execSql($sql, $id_foto);
		if (!$resultado){
			$this->onError($COD_UPDATE,"CAMBIAR PUBLICA WEB de FOTO");
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