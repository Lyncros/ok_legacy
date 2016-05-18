<?php
include_once("generic_class/class.PGDAO.php");

class EmprendimientoPGDAO extends PGDAO {
	protected $INSERT="INSERT INTO #dbName#.emprendimiento (id_ubica,nombre,ubicacion,descripcion,id_tipo_emp,estado,logo,comentario,foto,goglat,goglong,activa) values (#id_ubica#,'#nombre#','#ubicacion#','#descripcion#',#id_tipo_emp#,'#estado#','#logo#','#comentario#','#foto#',#goglat#,#goglong#,#activa#)";
	protected $DELETE="DELETE FROM #dbName#.emprendimiento WHERE id_emp=#id_emp#";
	protected $UPDATE="UPDATE #dbName#.emprendimiento SET id_ubica=#id_ubica#,nombre='#nombre#',ubicacion='#ubicacion#',descripcion='#descripcion#',id_tipo_emp=#id_tipo_emp#,estado='#estado#',logo='#logo#',comentario='#comentario#',foto='#foto#',goglat=#goglat#,goglong=#goglong#,activa=#activa# WHERE id_emp=#id_emp# ";

	protected $FINDBYID="SELECT id_emp,nombre,id_ubica,ubicacion,descripcion,id_tipo_emp,estado,logo,comentario,foto,goglat,goglong,activa FROM #dbName#.emprendimiento WHERE id_emp=#id_emp#";

	protected $FINDBYCLAVE="SELECT id_emp,nombre,id_ubica,ubicacion,descripcion,id_tipo_emp,estado,logo,comentario,foto,goglat,goglong,activa
							FROM #dbName#.emprendimiento
							WHERE nombre LIKE  '#nombre#'" ;

	protected $COLECCION="SELECT id_emp,nombre,id_ubica,ubicacion,descripcion,id_tipo_emp,estado,logo,comentario,foto,goglat,goglong,activa  FROM #dbName#.emprendimiento ORDER BY id_ubica,nombre";

	protected $COLECCIONACTIVAS="SELECT id_emp,nombre,id_ubica,ubicacion,descripcion,id_tipo_emp,estado,logo,comentario,foto,goglat,goglong,activa  FROM #dbName#.emprendimiento WHERE activa=1 ORDER BY id_ubica,nombre";

	protected $COLECCIONBASE="SELECT id_emp,nombre,id_ubica,ubicacion,descripcion,id_tipo_emp,estado,logo,comentario,foto,goglat,goglong,activa  FROM #dbName#.emprendimiento ";
	protected $ORDENBASE = " ORDER BY trim(nombre),id_ubica";

	protected $ACTIVAR="UPDATE #dbName#.emprendimiento SET activa=1 WHERE id_emp=#id_emp#";

	protected $DESACTIVAR="UPDATE #dbName#.emprendimiento SET activa=0 WHERE id_emp=#id_emp#";

	protected $CANTREGBASE="SELECT COUNT(id_emp) as id_emp FROM #dbName#.emprendimiento ";


	public function coleccionByFiltro($id_ubica,$tipo_emp,$estado,$activa,$pagina=1,$registros=0){
		$parametro = func_get_args();
		$FILTRO = array();
		$where='';

		if($id_ubica!=0 && $id_ubica!=''){
			$FILTRO[]="id_ubica in ($id_ubica)";
		}
		if($tipo_emp!=0){
			$FILTRO[]="id_tipo_emp IN ($tipo_emp)";
//			$FILTRO[]="id_tipo_emp='$tipo_emp'";
		}
		if($estado!='' && $estado!=-1){
			$FILTRO[]="estado = '$estado'";
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
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$this->ORDENBASE.$limite,$parametro);
		if (!$resultado){
			$this->onError("COD_COLLECION","PROPIEDADES FILTRADAS");
		}
		return $resultado;
	}

	public function cantidadRegistrosFiltro($id_ubica,$tipo_emp,$estado,$activa){
		$parametro = func_get_args();
		$FILTRO = array();
		$where='';

		if($id_ubica!=0 && $id_ubica!=''){
			$FILTRO[]="id_ubica in ($id_ubica)";
		}
		if($tipo_emp!=0){
			$FILTRO[]="id_tipo_emp IN ($tipo_emp)";
//			$FILTRO[]="id_tipo_emp='$id_tipo_emp'";
		}
		if($estado!='' && $estado!=-1){
			$FILTRO[]="estado = '$estado'";
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