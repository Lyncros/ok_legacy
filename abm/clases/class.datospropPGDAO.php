<?php
include_once("generic_class/class.PGDAO.php");
include_once("clases/class.caracteristicaBSN.php");

/*DROP TABLE IF EXISTS `achaval`.`propiedad_caracteristicas`;
 CREATE TABLE  `achaval`.`propiedad_caracteristicas` (
 `id_prop_carac` int(10) unsigned NOT NULL auto_increment,
 `id_prop` int(10) unsigned NOT NULL,
 `id_carac` int(10) unsigned NOT NULL,
 `contenido` varchar(500) NOT NULL,
 `comentario` varchar(500) NOT NULL,
 PRIMARY KEY  (`id_prop_carac`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 */

class DatospropPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.propiedad_caracteristicas (id_prop,id_carac,contenido,comentario) values (#id_prop#,#id_carac#,'#contenido#','#comentario#')";
	protected $DELETE="DELETE FROM #dbName#.propiedad_caracteristicas WHERE id_prop=#id_prop#";
	protected $UPDATE="UPDATE #dbName#.propiedad_caracteristicas SET id_prop=#id_prop#,id_carac=#id_carac#,contenido='#contenido#',comentario='#comentario#' WHERE id_prop_carac=#id_prop_carac# ";

	protected $FINDBYID="SELECT id_prop_carac,id_prop,id_carac,contenido,comentario FROM #dbName#.propiedad_caracteristicas WHERE id_prop_carac=#id_prop_carac#";

	protected $FINDBYCLAVE="SELECT id_prop_carac,id_prop,id_carac,contenido,comentario FROM #dbName#.propiedad_caracteristicas WHERE id_prop=#id_prop# and  id_carac=#id_carac#";

	protected $COLECCION="SELECT p.id_prop_carac,p.id_prop,p.id_carac,p.contenido,p.comentario
							FROM #dbName#.propiedad_caracteristicas as p 
							WHERE p.id_prop=#id_prop# and p.id_carac=#id_carac#";

	protected $COLECCIONCARACPROP="SELECT t.orden as orden_tipo,c.orden as orden,c.publica as publica,t.publica as tipo_publica,t.id_tipo_carac as id_tipo,p.id_prop,p.id_carac,t.tipo_carac,c.titulo
							,p.contenido,p.comentario 
							FROM #dbName#.propiedad_caracteristicas as p INNER JOIN #dbName#.caracteristica as c
							ON p.id_carac=c.id_carac INNER JOIN #dbName#.tipocarac as t ON c.id_tipo_carac=t.id_tipo_carac
							WHERE id_prop=#id_prop# ORDER BY t.orden,t.tipo_carac,c.orden";

	protected $COLECCIONCARACPUBLIPROP="SELECT t.orden as orden_tipo,c.orden as orden,c.publica as publica,t.publica as tipo_publica,t.id_tipo_carac as id_tipo,p.id_prop,p.id_carac,t.tipo_carac,c.titulo
							,p.contenido,p.comentario 
							FROM #dbName#.propiedad_caracteristicas as p INNER JOIN #dbName#.caracteristica as c
							ON p.id_carac=c.id_carac INNER JOIN #dbName#.tipocarac as t ON c.id_tipo_carac=t.id_tipo_carac
							WHERE id_prop=#id_prop# AND c.publica=1 AND t.publica=1 ORDER BY t.orden,t.tipo_carac,c.orden";
	
	protected $COLECCIONCARACCOLECPROP="SELECT t.orden as orden_tipo,c.orden as orden,p.id_prop,p.id_carac,t.tipo_carac,c.titulo
							,p.contenido,p.comentario 
							FROM #dbName#.propiedad_caracteristicas as p INNER JOIN #dbName#.caracteristica as c
							ON p.id_carac=c.id_carac INNER JOIN #dbName#.tipocarac as t ON c.id_tipo_carac=t.id_tipo_carac";

	protected $COLECCION_BY_PROP_CARAC_ID_BULK = "SELECT p.id_prop_carac,p.id_prop,p.id_carac,p.contenido,p.comentario
							FROM #dbName#.propiedad_caracteristicas as p 
							WHERE p.id_prop=#id_prop# and p.id_carac IN (#ids_carac#)";




	public function coleccionPropiedadCarac($id_carac,$contenido,$tipo,$in){
		$COLECCIONID="SELECT id_prop FROM #dbName#.propiedad_caracteristicas WHERE id_carac=$id_carac";
		switch ($tipo){
			case 'c':
				$COLECCIONID .= " AND contenido='$contenido'";
				break;
			case 'l':
				$COLECCIONID .= " AND contenido='$contenido'";
				break;
			case 't':
				$COLECCIONID .= " AND contenido like '%$contenido%'";
				break;
			case 'b':
				$COLECCIONID .= " AND contenido like '%$contenido%'";
				break;
			case 'w':
				$COLECCIONID .= " AND contenido like '%$contenido%'";
				break;
			case 'n':
				$carac=new CaracteristicaBSN();
				$compara=$carac->cargaComparacionCarac($id_carac);
				if($compara=='><'){
					$COLECCIONID .= " AND contenido BETWEEN $contenido";
				}else {
					$COLECCIONID .= " AND contenido $compara $contenido";
				}
				break;
		}

		if($in != '0')	{
			$COLECCIONID .= " and id_prop IN ($in)";
		}
		$retorno=$this->execSql($COLECCIONID);
		return $retorno;
	}

	public function coleccionCaracteristicasByPropiedad($inprop,$incarac){
		$COLECCIONID=$this->COLECCIONCARACCOLECPROP;
		if($inprop != '0')	{
			$COLECCIONID .= " WHERE p.id_prop IN ($inprop)";
		}
		if($incarac != '0')	{
			if($inprop != '0')	{
				$COLECCIONID .= " AND ";
			}else {
				$COLECCIONID .= " WHERE ";
			}
				
			$COLECCIONID .= "  p.id_carac IN ($incarac)";
		}

		$COLECCIONID .= " ORDER BY p.id_prop,t.orden,c.orden";

		$retorno=$this->execSql($COLECCIONID);
		return $retorno;
	}


	public function coleccionCaracteristicasByProp(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONCARACPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"CARACTERISTICAS DE PROPIEDAD");
		}
		return $resultado;
	}

	public function coleccionCaracteristicasPublicasByProp(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONCARACPUBLIPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"CARACTERISTICAS DE PROPIEDAD");
		}
		return $resultado;
	}
	
	public function coleccionPropiedadCaracAvanzada($id_carac,$condicion,$in){
		$COLECCIONID="SELECT id_prop FROM #dbName#.propiedad_caracteristicas WHERE id_carac=$id_carac";
		if(strpos($condicion,' AND ')!==false){
			$COLECCIONID .= " AND contenido BETWEEN $condicion";
		}elseif (strpos($condicion,'=')!==false){
			$COLECCIONID .= " AND contenido $condicion";
		}elseif (strpos($condicion,',')!==false){
			$COLECCIONID .= " AND contenido in ($condicion)";
		}elseif (strpos($condicion,'$')!==false){
			$COLECCIONID .= " AND contenido = '$condicion'";
		}
		if($id_carac==43){
			$COLECCIONID .= " AND contenido = '$condicion'";
		}
		if($in != '0')	{
			$COLECCIONID .= " and id_prop IN ($in)";
		}
		$retorno=$this->execSql($COLECCIONID);
		return $retorno;
	}

	public function clonacionCaracteristicasByProp($propOrig,$propDestino){
		$parametro = func_get_args();
		$sqlstr= "INSERT INTO #dbName#.propiedad_caracteristicas (id_prop,id_carac,contenido,comentario)";
  		$sqlstr.=" SELECT $propDestino,p.id_carac,p.contenido,p.comentario ";
  		$sqlstr.=" FROM #dbName#.propiedad_caracteristicas as p WHERE p.id_prop=$propOrig";

		$resultado=$this->execSql($sqlstr,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"CLONACION DE CARACTERISTICAS DE PROPIEDAD");
		}
		return $resultado;		
	}

	public function coleccionPropCaracBulk($id_prop, $ids_carac) {
		$ids_carac_param = implode($ids_carac, ',');
		$params = array(
			'id_prop' => $id_prop,
			'ids_carac' => $ids_carac_param,
		);
		$this->seteaDato($params);
		$resultado = $this->execSql($this->COLECCION_BY_PROP_CARAC_ID_BULK, $parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"COLECCION DE CARACTERISTICAS POR PROPIEDAD bulk por lista de IDs");
		}
		return $resultado;	
	}

	
} // Fin clase DAO
?>