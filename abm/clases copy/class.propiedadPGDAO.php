<?php
include_once("generic_class/class.PGDAO.php");
class PropiedadPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.propiedad (id_zona,id_loca,calle,entre1,entre2,nro,descripcion,id_tipo_prop,subtipo_prop,intermediacion,id_inmo,operacion,comentario,video,piso,dpto,id_cliente,goglat,goglong,activa,id_sucursal,id_emp,nomedif, plano1, plano2, plano3, id_parcela, id_comercial, compartir,publicaprecio) values ('#id_zona#','#id_loca#','#calle#','#entre1#','#entre2#','#nro#','#descripcion#',#id_tipo_prop#,'#subtipo_prop#','#intermediacion#',#id_inmo#,'#operacion#','#comentario#','#video#','#piso#','#dpto#',#id_cliente#,#goglat#,#goglong#,#activa#,'#id_sucursal#',#id_emp#,'#nomedif#', '#plano1#', '#plano2#', '#plano3#', '#id_parcela#', '#id_comercial#', #compartir#,#publicaprecio#)";

	protected $DELETE="DELETE FROM #dbName#.propiedad WHERE id_prop=#id_prop#";

	protected $UPDATE="UPDATE #dbName#.propiedad SET id_zona='#id_zona#',id_loca='#id_loca#',calle='#calle#',entre1='#entre1#',entre2='#entre2#',nro='#nro#',descripcion='#descripcion#',id_tipo_prop=#id_tipo_prop#,subtipo_prop='#subtipo_prop#',intermediacion='#intermediacion#',id_inmo=#id_inmo#,operacion='#operacion#',comentario='#comentario#',video='#video#',piso='#piso#',dpto='#dpto#',id_cliente=#id_cliente#,goglat=#goglat#,goglong=#goglong#,activa=#activa#,id_sucursal='#id_sucursal#',id_emp=#id_emp#,nomedif='#nomedif#',plano1='#plano1#',plano2='#plano2#',plano3='#plano3#',id_parcela='#id_parcela#',id_comercial='#id_comercial#',compartir=#compartir#,publicaprecio=#publicaprecio# WHERE id_prop=#id_prop# ";

	protected $FINDBYID="SELECT p.id_prop,p.id_zona,p.id_loca,p.calle,p.entre1,p.entre2,p.nro,p.descripcion,p.id_tipo_prop,p.subtipo_prop,p.intermediacion,p.id_inmo,p.operacion,p.comentario,p.video,p.piso,p.dpto,p.id_cliente,p.goglat,p.goglong,p.activa,p.id_sucursal,p.id_emp,p.nomedif,p.plano1,p.plano2,p.plano3,p.id_parcela,p.id_comercial,p.compartir,p.publicaprecio FROM #dbName#.propiedad as p  WHERE p.id_prop=#id_prop#";

	protected $FINDBYCLAVE="SELECT id_prop,id_zona,id_loca,calle,entre1,entre2,nro,descripcion,id_tipo_prop,subtipo_prop,intermediacion,id_inmo,operacion,comentario,video,piso,dpto,id_cliente,goglat,goglong,activa,id_sucursal,id_emp,nomedif,plano1,plano2,plano3,id_parcela,id_comercial,compartir,publicaprecio FROM #dbName#.propiedad WHERE calle LIKE  '#calle#' and nro='#nro#' and piso like '#piso#' and dpto like '#dpto#' and nomedif = '#nomedif#'" ;

	protected $COLECCION="SELECT id_prop,id_zona,id_loca,calle,entre1,entre2,nro,descripcion,id_tipo_prop,subtipo_prop,intermediacion,id_inmo,operacion,comentario,video,piso,dpto,id_cliente,goglat,goglong,activa,id_sucursal,id_emp,nomedif,plano1,plano2,plano3,id_parcela,id_comercial,compartir,publicaprecio  FROM #dbName#.propiedad ORDER BY id_zona,id_loca,calle,nro";

	protected $ACTIVAR="UPDATE #dbName#.propiedad SET activa=1 WHERE id_prop=#id_prop#";

	protected $DESACTIVAR="UPDATE #dbName#.propiedad SET activa=0 WHERE id_prop=#id_prop#";

	protected $PUBLICARPRECIO="UPDATE #dbName#.propiedad SET publicaprecio=1 WHERE id_prop=#id_prop#";

	protected $DESPUBLICARPRECIO="UPDATE #dbName#.propiedad SET publicaprecio=0 WHERE id_prop=#id_prop#";

	protected $CANTREGBASE="SELECT COUNT(p.id_prop) as id_prop FROM #dbName#.propiedad as p ";

	protected $COLECCIONBASE="SELECT p.id_prop,p.id_zona,p.id_loca,p.calle,p.entre1,p.entre2,p.nro,p.descripcion,p.id_tipo_prop,p.subtipo_prop,p.intermediacion,p.id_inmo,p.operacion,p.comentario,p.video,p.piso,p.dpto,p.id_cliente,p.goglat,p.goglong,p.activa,p.id_sucursal,p.id_emp,p.nomedif, p.plano1, p.plano2, p.plano3, p.id_parcela, p.id_comercial, p.compartir,p.publicaprecio FROM #dbName#.propiedad as p ";

	protected $JOIN = " INNER JOIN (SELECT q.id_prop,q.operacion FROM #dbName#.propiedad_operacion as q inner join ( select id_prop,max(fecha) as fecha from  #dbName#.propiedad_operacion group by id_prop) as j on (q.id_prop=j.id_prop and q.fecha=j.fecha)) as o ON p.id_prop=o.id_prop ";

	protected $COLECCIONBASEBUSCADOR="SELECT p.id_prop,p.id_zona,p.id_loca,p.calle,p.nro,p.id_tipo_prop,p.subtipo_prop,p.intermediacion,p.id_inmo,p.operacion,p.piso,p.dpto,p.id_cliente,p.activa,p.id_sucursal,p.id_emp, p.compartir,p.publicaprecio,p.goglat,p.goglong,z.nombre_zona,l.nombre_loca,t.tipo_prop,st.suptot,sca.cantamb,smal.monalq,sal.valalq,smve.monven,sve.valven FROM (
    SELECT id_prop,id_zona,id_loca,calle,nro,id_tipo_prop,subtipo_prop,intermediacion,id_inmo,operacion,piso,dpto,id_cliente,activa,id_sucursal,id_emp,compartir,publicaprecio,goglat,goglong FROM #dbName#.propiedad ";


	protected $JOINBUSCADOR = " INNER JOIN #dbName#.zona as z ON p.id_zona = z.id_zona INNER JOIN #dbName#.localidad as l ON p.id_loca = l.id_loca INNER JOIN #dbName#.tipoprop as t ON p.id_tipo_prop = t.id_tipo_prop ";


	protected $JOINCARAC = "  LEFT JOIN
(SELECT id_prop,contenido as suptot FROM #dbName#.propiedad_caracteristicas WHERE id_carac=198) as st ON p.id_prop=st.id_prop 
 LEFT JOIN 
(SELECT id_prop,contenido as cantamb FROM #dbName#.propiedad_caracteristicas WHERE id_carac=208) as sca ON p.id_prop=sca.id_prop
LEFT JOIN
(SELECT id_prop,contenido as monalq FROM #dbName#.propiedad_caracteristicas WHERE id_carac=166) as smal ON p.id_prop=smal.id_prop
LEFT JOIN
(SELECT id_prop,contenido as valalq FROM #dbName#.propiedad_caracteristicas WHERE id_carac=164) as sal ON p.id_prop=sal.id_prop
LEFT JOIN
(SELECT id_prop,contenido as monven FROM #dbName#.propiedad_caracteristicas WHERE id_carac=165) as smve ON p.id_prop=smve.id_prop
LEFT JOIN
(SELECT id_prop,contenido as valven FROM #dbName#.propiedad_caracteristicas WHERE id_carac=161) as sve ON p.id_prop=sve.id_prop
 ";


	//    protected $ORDENBASE = " ORDER BY p.id_zona,p.id_loca,calle,nro";
	protected $ORDENBASE = " ORDER BY z.nombre_zona,l.nombre_loca,calle,nro";

	/*
	 public function coleccionByFiltro($zona,$localidad,$tipo_prop,$operacion,$publicadas,$sucursal,$pagina=1,$registros=0) {
	 $parametro = func_get_args();
	 $FILTRO = array();
	 $where='';

	 if($zona!=0) {
	 $FILTRO[]="p.id_zona='#id_zona#'";
	 }

	 if($localidad!=0) {
	 $FILTRO[]="p.id_loca='#id_loca#'";
	 }
	 if($tipo_prop!=0) {
	 //            $FILTRO[]="id_tipo_prop ='#id_tipo_prop#'";
	 $FILTRO[]="p.id_tipo_prop IN (#id_tipo_prop#)";
	 echo $tipo_prop."<br>";
	 }
	 if($operacion!='Todas') {
	 $FILTRO[]="p.operacion='#operacion#'";
	 }
	 if($publicadas!='Todas') {
	 $FILTRO[]="activa=#activa#";
	 }
	 if($sucursal!='Todas') {
	 $FILTRO[]="p.id_sucursal='#id_sucursal#'";
	 }
	 if(sizeof($FILTRO)>0) {
	 for($x=0; $x < sizeof($FILTRO)-1;$x++) {
	 $where.= ($FILTRO[$x] . " AND ");

	 }
	 $where = " WHERE ".$where.$FILTRO[$x];
	 }
	 if($registros>0 ) {
	 $limite = ' LIMIT '.($pagina-1)*$registros.','.$registros;
	 }else {
	 $limite='';
	 }
	 //		print_r($parametro);
	 $resultado=$this->execSql($this->COLECCIONBASE.$this->JOIN.$where.$this->ORDENBASE.$limite,$parametro);
	 if (!$resultado) {
	 $this->onError("COD_COLLECION","PROPIEDADES FILTRADAS");
	 }
	 return $resultado;
	 }
	 */

	/*
	 public function coleccionByFiltroBuscador($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina=1,$registros=0,$campo='',$orden=0) {
	 $parametro = func_get_args();
		$auxwhere='';
		$where='';

		$ORDENLOCAL = " ORDER BY id_zona,id_loca,calle,nro ";
		if($campo!='') {
		$ORDENLOCAL = " ORDER BY ".$campo;
		if($orden!=0) {
		$ORDENLOCAL .= " DESC ";
		}
		}

		$FILTRO = array();
		if($codigo!=0) {
		$FILTRO[]="p.id_prop=$codigo";
		}
		if($calle!='') {
		$FILTRO[]="p.calle LIKE '%$calle%'";
		}
		if($zona!=0) {
		$FILTRO[]="p.id_zona='#id_zona#'";
		}
		if($id_emp!=0) {
		$FILTRO[]="id_emp=#id_emp#";
		}

		if($localidad!=0) {
		$FILTRO[]="p.id_loca in ($localidad)";
		}
		if($tipo_prop!=0) {
		$FILTRO[]="p.id_tipo_prop in($tipo_prop)";
		}
		if($operacion!='') {

		$FILTRO[]="p.operacion in (".str_replace("\\\"","'",$operacion).")";
		}
		if(sizeof($FILTRO)>0) {
		for($x=0; $x < sizeof($FILTRO);$x++) {
		if($x > 0){
		$auxwhere.=" AND ";
		}
		$auxwhere.= $FILTRO[$x];
		}
		$where .= (" WHERE ".$auxwhere);
		}
		if($in!='' && $in != '0') {
		if($where!='') {
		$where .= " AND ";
		}else{
		$where = " WHERE ";
		}
		$where.="  p.id_prop IN ($in)";
		}

		if($registros > 0) {
		if($pagina > 0) {
		$regin =($pagina-1)*$registros;
		}else {
		$regin=0;
		}
		$limite = ' LIMIT '.$regin.','.$registros;
		}else {
		$limite='';
		}

		$resultado=$this->execSql($this->COLECCIONBASEBUSCADOR.$this->JOINBUSCADOR.$this->JOINCARAC.$where.$ORDENLOCAL.$limite,$parametro);
		if (!$resultado) {
		$this->onError("COD_COLLECION","PROPIEDADES FILTRADAS x BUSCADOR");
		}
		return $resultado;
		}
		*/

	public function coleccionByFiltroBuscadorMapa($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina=1,$registros=0,$campo='',$orden=0,$aux_vistaestado='',$aux_vistazona='',$publicadas=-1) {
		$parametro = func_get_args();
		$auxwhere='';
		$where='';

		$ORDENLOCAL = " ORDER BY id_zona,id_loca,calle,nro ";
		if($campo!='') {
			$ORDENLOCAL = " ORDER BY ".$campo;
			if($orden!=0) {
				$ORDENLOCAL .= " DESC ";
			}
		}

		$FILTRO = array();
		if($codigo!=0) {
			$FILTRO[]="id_prop=$codigo";
		}
		if($calle!='') {
			$FILTRO[]="calle LIKE '%$calle%'";
		}
		if($zona!=0) {
			$FILTRO[]="id_zona='#id_zona#'";
		}
		if($id_emp!=0) {
			$FILTRO[]="id_emp in ($id_emp) ";
			//        	$FILTRO[]="id_emp=#id_emp#";
		}

		if($localidad!=0) {
			$FILTRO[]="id_loca in ($localidad)";
		}
		if($tipo_prop!=0) {
			$FILTRO[]="id_tipo_prop in($tipo_prop)";
		}
		if($operacion!='') {
			$FILTRO[]="operacion in (".str_replace("\\\"","'",$operacion).")";
		}else{
			if($aux_vistaestado!=''){
				$FILTRO[]="operacion in (".$aux_vistaestado.")";
			}
		}
		if($aux_vistazona!='') {
			$FILTRO[]="id_sucursal ='".$aux_vistazona."'";
		}
		if($publicadas==1 || $publicadas==0){
			$FILTRO[]= " activa=".$publicadas ;
		}

		if(sizeof($FILTRO)>0) {
			for($x=0; $x < sizeof($FILTRO);$x++) {
				if($x > 0){
					$auxwhere.=" AND ";
				}
				$auxwhere.= $FILTRO[$x];
			}
			$where .= (" WHERE ".$auxwhere);
		}
		if($in!='' && $in != '0') {
			if($where!='') {
				$where .= " AND ";
			}else{
				$where .= " WHERE ";
			}
			$where.="  id_prop IN ($in)";
		}
		//        echo
		if($registros > 0) {
			if($pagina > 0) {
				$regin =($pagina-1)*$registros;
			}else {
				$regin=0;
			}
			$limite = ' LIMIT '.$regin.','.$registros;
		}else {
			$limite='';
		}
		//        echo $this->COLECCIONBASEBUSCADOR.$where." ) as p ".$this->JOINBUSCADOR.$this->JOINCARAC.$ORDENLOCAL . $limite."<br>";
		$resultado=$this->execSql($this->COLECCIONBASEBUSCADOR.$where." ) as p ".$this->JOINBUSCADOR.$this->JOINCARAC.$ORDENLOCAL . $limite,$parametro);
		if (!$resultado) {
			$this->onError("COD_COLLECION","PROPIEDADES FILTRADAS x BUSCADOR");
		}
		return $resultado;
	}


	public function cantRegistrosFiltroBuscador($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in) {
		$parametro = func_get_args();
		$FILTRO = array();

		if($codigo!=0) {
			$FILTRO[]="p.id_prop=$codigo";
		}
		if($calle!='') {
			$FILTRO[]="p.calle LIKE '%$calle%'";
		}
		if($zona!=0) {
			$FILTRO[]="p.id_zona=$zona";
		}
		if($id_emp!=0) {
			$FILTRO[]="p.id_emp=$id_emp";
		}

		if($localidad!=0) {
			$FILTRO[]="p.id_loca in ($localidad)";
		}
		if($tipo_prop!=0) {
			$FILTRO[]="p.id_tipo_prop in($tipo_prop)";
		}
		if($operacion!='') {
			$FILTRO[]="p.operacion in (".str_replace("\\","",$operacion).")";

		}

		if(sizeof($FILTRO)>0) {
			for($x=0; $x < sizeof($FILTRO);$x++) {
				if($x > 0){
					$auxwhere.=" AND ";
				}
				$auxwhere.= $FILTRO[$x];
			}
			$where .= (" WHERE ".$auxwhere);
		}
		if($in!='' && $in != '0') {
			if($where!='') {
				$where .= " AND ";
			}else{
				$where = " WHERE ";
			}
			$where.="  p.id_prop IN ($in)";
		}

//        $resultado=$this->execSql($this->CANTREGBASE.$this->JOINBUSCADOR.$this->JOINCARAC.$where.$this->ORDENBASE,$parametro);
        $resultado=$this->execSql($this->CANTREGBASE.$where,$parametro);
//        echo $this->CANTREGBASE.$where."<br>"; 
        if (!$resultado) {
//			$resultado=0;
            $this->onError("COD_COLLECION","CANTIDAD DE PROPIEDADES FILTRADAS x BUSCADOR");
        }
		return $resultado;
	}

	/*
	 public function cantidadRegistrosFiltro($zona,$localidad,$tipo_prop,$operacion,$publicadas,$sucursal) {
	 $parametro = func_get_args();
	 $FILTRO = array();
	 $where='';

	 if($zona!=0) {
	 $FILTRO[]="p.id_zona=$zona";
	 }

	 if($localidad!=0) {
	 $FILTRO[]="p.id_loca=$localidad";
	 }
	 if($tipo_prop!=0) {
	 //            $FILTRO[]="id_tipo_prop=$tipo_prop";
	 $FILTRO[]="p.id_tipo_prop IN ($tipo_prop)";

	 }
	 if($operacion!='Todas') {
	 $FILTRO[]="p.operacion='$operacion'";
	 }
	 if($publicadas!=-1) {
	 $FILTRO[]="activa=$publicadas";
	 }
	 if($sucursal!='Todas') {
	 $FILTRO[]="p.id_sucursal='$sucursal'";
	 }
	 if(sizeof($FILTRO)>0) {
	 for($x=0; $x < sizeof($FILTRO)-1;$x++) {
	 $where.= ($FILTRO[$x] . " AND ");

	 }
	 $where = " WHERE ".$where.$FILTRO[$x];
	 }
	 //		print_r($parametro);
	 $resultado=$this->execSql($this->CANTREGBASE.$this->JOIN.$where.$this->ORDENBASE,$parametro);
	 if (!$resultado) {
	 $this->onError("COD_COLLECION","PROPIEDADES FILTRADAS");
	 }
	 return $resultado;
	 }
	 */

	public function activar() {
		$parametro = func_get_args();
		$resultado=$this->execSql($this->ACTIVAR,$parametro);
		if (!$resultado) {
			$this->onError($COD_UPDATE,"ACTIVACION de propiedad");
		}
		return $resultado;
	}

	public function desactivar() {
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DESACTIVAR,$parametro);
		if (!$resultado) {
			$this->onError($COD_UPDATE,"INACTIVACION de propiedad");
		}
		return $resultado;
	}

	public function publicarPrecio() {
		$parametro = func_get_args();
		$resultado=$this->execSql($this->PUBLICARPRECIO,$parametro);
		if (!$resultado) {
			$this->onError($COD_UPDATE,"Publicacion de precio de propiedad");
		}
		return $resultado;
	}

	public function despublicarPrecio() {
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DESPUBLICARPRECIO,$parametro);
		if (!$resultado) {
			$this->onError($COD_UPDATE,"Despublicar precio de propiedad");
		}
		return $resultado;
	}


} // Fin clase DAO
?>