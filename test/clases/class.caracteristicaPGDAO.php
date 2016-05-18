<?php
include_once ("generic_class/class.PGDAO.php");

class CaracteristicaPGDAO extends PGDAO {

	protected $INSERT = "INSERT INTO #dbName#.caracteristica (id_tipo_carac,titulo, tipo,maximo,comentario,lista,orden,publica,tasacion) values (#id_tipo_carac#,'#titulo#','#tipo#',#maximo#,'#comentario#','#lista#',#orden#,#publica#,#tasacion#)";
	
	protected $INSERTBUSCADOR = "INSERT INTO  #dbName#.caracteristica_buscador (id_carac,comparacion) values (#id_carac#,'#comparacion#')";
	
	protected $DELETE = "DELETE FROM #dbName#.caracteristica WHERE id_carac=#id_carac#";
	
	protected $UPDATE = "UPDATE #dbName#.caracteristica SET id_tipo_carac=#id_tipo_carac#, titulo='#titulo#', tipo='#tipo#',maximo=#maximo#,comentario='#comentario#',lista='#lista#',orden=#orden#,publica=#publica#,tasacion=#tasacion# WHERE id_carac=#id_carac# ";
	
	protected $FINDBYID = "SELECT id_carac,id_tipo_carac,titulo, tipo,maximo,comentario,lista,orden,publica,tasacion FROM #dbName#.caracteristica WHERE id_carac=#id_carac#";
	
	protected $FINDBYCLAVE = "SELECT id_carac,id_tipo_carac,titulo, tipo,maximo,comentario,lista,orden,publica,tasacion FROM #dbName#.caracteristica WHERE titulo LIKE  '#titulo#'";
	
	protected $COLECCIONBYTIPO = "SELECT id_carac,id_tipo_carac,titulo, tipo,maximo,comentario,lista,orden,publica,tasacion FROM #dbName#.caracteristica WHERE id_tipo_carac=#id_tipo_carac# ORDER BY id_tipo_carac,orden";
	
	protected $COLECCION = "SELECT id_carac,id_tipo_carac,titulo, tipo,maximo,comentario,lista,orden,publica,tasacion  FROM #dbName#.caracteristica ORDER BY id_tipo_carac,orden";
	
	protected $COLECCIONBUSCADOR = "SELECT id_carac,comparacion FROM #dbName#.caracteristica_buscador ORDER BY id_carac";
	
	protected $COLECCIONTASACION = "SELECT id_carac,id_tipo_carac,titulo, tipo,maximo,comentario,lista,orden,publica,tasacion  FROM #dbName#.caracteristica WHERE tasacion=1 ORDER BY id_tipo_carac,orden";
	
	protected $DELETEBUSCADOR = "DELETE FROM #dbName#.caracteristica_buscador WHERE id_carac=#id_carac#";
	
	protected $SUBIR = "UPDATE #dbName#.caracteristica SET orden=orden-1 WHERE id_carac=#id_carac#";
	
	protected $BAJAR = "UPDATE #dbName#.caracteristica SET orden=orden+1 WHERE id_carac=#id_carac#";

    protected $INSERTAORDEN = "UPDATE #dbName#.caracteristica SET orden=orden+1 WHERE orden>=#orden#";
	
	protected $COLECCIONBYPOSICION = "SELECT id_carac FROM #dbName#.caracteristica WHERE orden=#orden# and id_tipo_carac=#id_tipo_carac#";
	
	protected $COLECCIONXBUSCADOR = "SELECT a.id_carac,a.id_tipo_carac,a.titulo, a.tipo,a.maximo,a.comentario,a.lista,a.orden  FROM #dbName#.caracteristica as a INNER JOIN #dbName#.caracteristica_buscador as b ON a.id_carac=b.id_carac ";
	protected $ORDENXBUSCADOR =" ORDER BY id_tipo_carac,orden";
	
	protected $MAYORORDEN="SELECT MAX(orden) as max FROM #dbName#.caracteristica WHERE id_tipo_carac=#id_tipo_carac#";
	
	public function deleteBuscador(){
		$parametro = func_get_args();		
		$resultado=$this->execSql($this->DELETEBUSCADOR,$parametro);
		if (!$resultado){
			$this->onError("COD_DELETE",$this->DELETEBUSCADOR);
		}
		return $resultado;		
	}
	
	public function insertaOrden(){
		$parametro = func_get_args();		
		$resultado=$this->execSql($this->INSERTAORDEN,$parametro);
		if (!$resultado){
			$this->onError("COD_UPDARTE",$this->INSERTAORDEN);
		}
		return $resultado;		
	}

    public function insertaBuscador(){
		$parametro = func_get_args();
		$resultado = $this->execSql($this->INSERTBUSCADOR,$parametro);
		if(!$resultado){
			$this->onError("COD_INSERT",$this->INSERTBUSCADOR);
		}
		return $resultado;
	
	}
	
	public function cargaBuscadorById($id) {
//		$parametro = func_get_args ();
		$parametro='';
		$BUSCADORXCARAC = "SELECT comparacion FROM #dbName#.caracteristica_buscador WHERE id_carac=$id";

		$resultado = $this->execSql ( $BUSCADORXCARAC, $parametro );
		if (! $resultado) {
			$this->onError ( "COD_COLLECION", $BUSCADORXCARAC );
		}
		return $resultado;
	}
	
	public function coleccionBuscador() {
		$parametro = func_get_args ();
		$resultado = $this->execSql ( $this->COLECCIONBUSCADOR, $parametro );
		if (! $resultado) {
			$this->onError ( "COD_COLLECION", $this->COLECCIONBUSCADOR );
		}
		return $resultado;
	}
	
	
	public function subirCarac() {
		$parametro = func_get_args ();
		$resultado = $this->execSql ( $this->SUBIR, $parametro );
		if (! $resultado) {
			$this->onError ( "COD_UPDATE", "SUBIR ORDEN" );
		}
		return $resultado;
	}
	
	public function bajarCarac() {
		$parametro = func_get_args ();
		$resultado = $this->execSql ( $this->BAJAR, $parametro );
		if (! $resultado) {
			$this->onError ( "COD_UPDATE", "BAJAR ORDEN" );
		}
		return $resultado;
	}
	
	public function coleccionCaracBuscador($in=''){
		$parametro = func_get_args ();
		$sqlStr = $this->COLECCIONXBUSCADOR;
		if($in!=''){
			$sqlStr .= " WHERE b.id_carac IN ($in) ";
		}
		$sqlStr .= $this->ORDENXBUSCADOR;
		
		$resultado = $this->execSql ( $sqlStr, $parametro );
		if (! $resultado) {
			$this->onError ( "COD_COLLECION", "Caracteristicas para Buscador" );
		}
		return $resultado;
	}
	
	public function coleccionByPosicion() {
		$parametro = func_get_args ();
		$resultado = $this->execSql ( $this->COLECCIONBYPOSICION, $parametro );
		if (! $resultado) {
			$this->onError ( "COD_COLLECION", "NOTICIAS POR POSICION" );
		}
		return $resultado;
	}
	
	public function coleccionByTipo(){
		$parametro = func_get_args();
//		print_r($parametro);
		$resultado=$this->execSql($this->COLECCIONBYTIPO,$parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","CARACTERISTICAS x TIPO CARAC");
		}
		return $resultado;
	}
        
	public function coleccionTasacion(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONTASACION,$parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","CARACTERISTICAS para TASACION");
		}
		return $resultado;
	}
        
}
// Fin clase DAO
?>