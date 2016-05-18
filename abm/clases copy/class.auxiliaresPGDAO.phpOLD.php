<?php
include_once("generic_class/class.PGDAO.php");

class AuxiliaresPGDAO extends PGDAO {
	

	protected $COLECCIONTIPOPROPASIGNADA = "SELECT t.id_tipo_prop, t.tipo_prop FROM achaval.tipoprop as t 
												INNER JOIN achaval.caracteristica_tipoprop as c ON t.id_tipo_prop=c.id_tipo_prop WHERE c.id_carac=";
	
	protected $COLECCIONTIPOPROPSINASIGNAR = "SELECT id_tipo_prop, tipo_prop FROM achaval.tipoprop a t 
													WHERE id_tipo_prop NOT IN (SELECT id_tipo_prop FROM achaval.caracteristica_tipoprop WHERE id_carac=";
	
	 
	 
	protected $INSERTATIPOPROPASIGNADA = "INSERT INTO achaval.caracteristica_tipoprop (id_carac,id_tipo_prop) values (";
	
	protected $BORRATIPOPROPASIGNADA = "DELETE FROM achaval.caracteristica_tipoprop  WHERE id_carac=";
	
//	Fallo la aplicacion con codigo COD_COLECCION en SELECT t.id_tipo_prop, t.tipo_prop FROM achaval.tipoprop as t INNER JOIN achaval.caracteristica_tipoprop as c ON t.id_tipo_prop=c.id_tipo_prop WHERE c.id_carac=
	
	public function coleccionTipopropSinAsignar($_id_carac=0){
		if(!is_numeric($_id_carac)){
			$_id_carac=0;
		}
		return  $this->coleccionAux($this->COLECCIONTIPOPROPSINASIGNAR.$_id_carac);
	}
	
	public function coleccionTipopropAsignada($_id_carac=0){
		if(!is_numeric($_id_carac)){
			$_id_carac=0;
		}
		return  $this->coleccionAux($this->COLECCIONTIPOPROPASIGNADA.$_id_carac);
	}

	public function coleccionTipopropCarac($_id_tipo_prop=0){
		$COLECCIONTIPOPROPCARAC = "SELECT a.id_carac,t.tipo_carac,t.columnas FROM  achaval.caracteristica as a
											INNER JOIN achaval.tipocarac as t ON a.id_tipo_carac=t.id_tipo_carac
											INNER JOIN achaval.caracteristica_tipoprop as c ON c.id_carac=a.id_carac 
											WHERE c.id_tipo_prop=$_id_tipo_prop ORDER BY t.orden, a.orden ";

		if(!is_numeric($_id_tipo_prop)){
			$_id_tipo_prop=0;
		}
		return  $this->coleccionAux($COLECCIONTIPOPROPCARAC);
	}
	
/**
 * Arma una coleccion de caracteristicas segun el siguiente criterio
 * para PUBLICAS -1:  Todas   0: Privadas  1: Publicas
 *
 * @param int $_id_tipo_prop
 * @param int $_publicas
 * @return array conteniendo la coleccion con una fila por cada registro
 */
	public function coleccionTipopropCaracPublicas($_id_tipo_prop=0,$_publicas=-1){
		$COLECCIONTIPOPROPCARAC = "SELECT a.id_carac,t.tipo_carac,t.columnas,t.id_tipo_carac FROM  achaval.caracteristica as a
											INNER JOIN achaval.tipocarac as t ON a.id_tipo_carac=t.id_tipo_carac
											INNER JOIN achaval.caracteristica_tipoprop as c ON c.id_carac=a.id_carac 
											WHERE c.id_tipo_prop=$_id_tipo_prop";
		switch ($_publicas) {
			case 0:
			 	$COLECCIONTIPOPROPCARAC .= " AND (a.publica= $_publicas OR t.publica=$_publicas) ";
				break;
			case 1:
			 	$COLECCIONTIPOPROPCARAC .= " AND (a.publica= $_publicas AND t.publica=$_publicas) ";
				break;
			default:
				break;
		}

		$COLECCIONTIPOPROPCARAC .= " ORDER BY t.orden, a.orden ";

		if(!is_numeric($_id_tipo_prop)){
			$_id_tipo_prop=0;
		}
		return  $this->coleccionAux($COLECCIONTIPOPROPCARAC);
	}

	
/**
 * Arma una coleccion con los Id de las caracteristicas que cumplen pertenecen a un tipo de propiedad determinada
 * y que estan incluidas dentro de una lista de caracteristicas, en el caso de no pasar la lista de caracteristicas
 * se toma como base a todas las existentes
 *
 * @param int $_id_tipo_prop -> Id del tipo de propiedad
 * @param string $in -> lista de caracteristicas a utilizar como filtro; las mismas deben estar separadas por ,
 * @return string conteniendo los ID de las caracteristicas que cumplen con estas condiciones, separados por ,.
 */
	public function coleccionTipopropCaracBuscador($_id_tipo_prop=0,$in=''){
//		echo "Prop Axu -> $id_tipo_prop<br>";
		$COLECCIONTIPOPROPCARAC = "SELECT id_carac FROM achaval.caracteristica_tipoprop WHERE id_tipo_prop=$_id_tipo_prop  ";
		if($in!='' && $in!='0'){
			$COLECCIONTIPOPROPCARAC .="AND id_carac IN ($in)";
		}
		if(!is_numeric($_id_tipo_prop)){
			$_id_tipo_prop=0;
		}
//		echo "sql -> TipopropCarac -> $COLECCIONTIPOPROPCARAC <br>" ;
		return  $this->arrayIN($this->coleccionAux($COLECCIONTIPOPROPCARAC));
	}

	
	public function arrayIN($array){
		$retorno='';
		foreach ($array as $elemento){
			$retorno.=$elemento['id_carac'].",";
		}
		return substr($retorno,0,strlen($retorno)-1);
	}
	
	
	public  function insertaTipopropAsignada($id_carac,$id_tipo_prop){
		$col=$this->INSERTATIPOPROPASIGNADA.$id_carac.",".$id_tipo_prop.")";
		$resultado=$this->execSql($col);
		if (!$resultado){
			$this->onError("COD_INSERT",$col);
		}
				
	}
	
	public function borraTipopropAsignada($id_carac){
		if(!is_numeric($id_carac)){
			$id_carac=0;
		}
		$resultado=$this->execSql($this->BORRATIPOPROPASIGNADA.$id_carac);
		if (!$resultado){
			$this->onError("COD_DELETE",$this->BORRATIPOPROPASIGNADA.$id_carac);
		}
		
	}
	
	protected function coleccionAux($col){
		$resultado=$this->execSql($col);
		if (!$resultado){
			$this->onError("COD_COLECCION",$col);
		}
		$array=$this->armaArray($resultado);
		return $array;
	}

	protected function armaArray($result) {
		$array=array();
		$conf=new CargaConfiguracion();
		$tipoDB=$conf->leeParametro("tipodb");
		if($tipoDB=="my"){
			$fetch="mysql_fetch_array";
		} else {
			$fetch="pg_fetch_array";
		}
		while ($row=$fetch($result)) {
//				$array[]=array($row[0],$row[1]);
				$array[]=$row;
		}
		return $array;
	}

} // Fin clase auxiliares
?>
