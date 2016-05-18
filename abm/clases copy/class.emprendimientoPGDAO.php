<?php
include_once("generic_class/class.PGDAO.php");

/*
  id_emp int(10) unsigned NOT NULL auto_increment,
  id_tipo_emp int(10) unsigned default NULL,
  id_zona int(10) unsigned NOT NULL default '0',
  id_loca int(10) unsigned NOT NULL default '0',
  ubcacion varchar(500) NOT NULL,
  descripcion varchar(5000) NOT NULL,
  logo varchar(500) NOT NULL,
  foto varchar(500) NOT NULL,
  comentario varchar(1000) default NULL,
  goglat double default NULL,
  goglong double default NULL,

*/


//  id_emp,id_zona,id_loca,ubicacion,entre1,entre2,nro,descripcion,id_tipo_emp

class EmprendimientoPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.emprendimiento (id_zona,nombre,id_loca,ubicacion,descripcion,id_tipo_emp,logo,comentario,foto,goglat,goglong,activa) values (#id_zona#,'#nombre#',#id_loca#,'#ubicacion#','#descripcion#',#id_tipo_emp#,'#logo#','#comentario#','#foto#',#goglat#,#goglong#,#activa#)";
	protected $DELETE="DELETE FROM #dbName#.emprendimiento WHERE id_emp=#id_emp#";
	protected $UPDATE="UPDATE #dbName#.emprendimiento SET id_zona=#id_zona#,nombre='#nombre#',id_loca=#id_loca#,ubicacion='#ubicacion#',descripcion='#descripcion#',id_tipo_emp=#id_tipo_emp#,logo='#logo#',comentario='#comentario#',foto='#foto#',goglat=#goglat#,goglong=#goglong#,activa=#activa# WHERE id_emp=#id_emp# ";

	protected $FINDBYID="SELECT id_emp,nombre,id_zona,id_loca,ubicacion,descripcion,id_tipo_emp,logo,comentario,foto,goglat,goglong,activa FROM #dbName#.emprendimiento WHERE id_emp=#id_emp#";

	protected $FINDBYCLAVE="SELECT id_emp,nombre,id_zona,id_loca,ubicacion,descripcion,id_tipo_emp,logo,comentario,foto,goglat,goglong,activa
							FROM #dbName#.emprendimiento
							WHERE nombre LIKE  '#nombre#'" ;
	
	protected $COLECCION="SELECT id_emp,nombre,id_zona,id_loca,ubicacion,descripcion,id_tipo_emp,logo,comentario,foto,goglat,goglong,activa  FROM #dbName#.emprendimiento ORDER BY id_zona,id_loca,nombre";
	
	protected $COLECCIONACTIVAS="SELECT id_emp,nombre,id_zona,id_loca,ubicacion,descripcion,id_tipo_emp,logo,comentario,foto,goglat,goglong,activa  FROM #dbName#.emprendimiento WHERE activa=1 ORDER BY id_zona,id_loca,nombre";
	
	protected $COLECCIONBASE="SELECT id_emp,nombre,id_zona,id_loca,ubicacion,descripcion,id_tipo_emp,logo,comentario,foto,goglat,goglong,activa  FROM #dbName#.emprendimiento ";
	protected $ORDENBASE = " ORDER BY id_zona,id_loca,nombre";

	protected $ACTIVAR="UPDATE #dbName#.emprendimiento SET activa=1 WHERE id_emp=#id_emp#";
	
	protected $DESACTIVAR="UPDATE #dbName#.emprendimiento SET activa=0 WHERE id_emp=#id_emp#";

	protected $CANTREGBASE="SELECT COUNT(id_emp) as id_emp FROM #dbName#.emprendimiento ";
	
	
	public function coleccionByFiltro($zona,$localidad,$tipo_emp,$activa,$pagina=1,$registros=0){
		$parametro = func_get_args();
		$FILTRO = array();
		$where='';
		
		if($zona!=0){
			$FILTRO[]="id_zona='#id_zona#'";
		}
		if($localidad!=0){
//			$FILTRO[]="id_loca='#id_loca#'";
			$FILTRO[]="id_loca in (".$localidad.")";
		}
		if($tipo_emp!=0){
			$FILTRO[]="id_tipo_emp='#id_tipo_emp#'";
		}
		if(sizeof($FILTRO)>0){
			for($x=0; $x < sizeof($FILTRO)-1;$x++){
				$where.= ($FILTRO[$x] . " AND ");
			}
			$where = " WHERE ".$where.$FILTRO[$x];
		}
		if($activa==1){
			if($where==''){
				$where = " WHERE ";
			}else{
				$where.= " AND ";
			}
			$where.= " activa=1 ";
		}

		if($registros>0 && $pagina >0 ){
			$limite = ' LIMIT '.($pagina-1)*$registros.','.$registros;
		}else{
			$limite='';
		}
			
		
//		print_r($parametro);
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$this->ORDENBASE.$limite,$parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","PROPIEDADES FILTRADAS");
		}
		return $resultado;
	}

		public function cantidadRegistrosFiltro($zona,$localidad,$tipo_emp,$activa){
		$parametro = func_get_args();
		$FILTRO = array();
		$where='';
		
		if($zona!=0){
			$FILTRO[]="id_zona='#id_zona#'";
		}
		if($localidad!=0){
			$FILTRO[]="id_loca='#id_loca#'";
		}
		if($tipo_emp!=0){
			$FILTRO[]="id_tipo_emp='#id_tipo_emp#'";
		}
		if(sizeof($FILTRO)>0){
			for($x=0; $x < sizeof($FILTRO)-1;$x++){
				$where.= ($FILTRO[$x] . " AND ");
			}
			$where = " WHERE ".$where.$FILTRO[$x];
		}
		if($activa==1){
			if($where==''){
				$where = " WHERE ";
			}else{
				$where.= " AND ";
			}
			$where.= " activa=1 ";
		}
		//		print_r($parametro);
		$resultado=$this->execSql($this->CANTREGBASE.$where,$parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","PROPIEDADES FILTRADAS");
		}
		return $resultado;
	}	
	
	
	public function coleccionEmprendimientosActivos(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONACTIVAS,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"EMPRENDIMIENTOS ACTIVAS");
		}
		return $resultado;		
	}

	public function activar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->ACTIVAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"ACTIVACION de EMPRENDIMIENTOS");
		}
		return $resultado;				
	}
	
	public function desactivar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DESACTIVAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"INACTIVACION de EMPRENDIMIENTOS");
		}
		return $resultado;				
	}	
	
} // Fin clase DAO
?>