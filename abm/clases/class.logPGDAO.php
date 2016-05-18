<?php
include_once ("generic_class/class.PGDAO.php");

class LogPGDAO extends PGDAO {
	protected $INSERT = "INSERT INTO #dbName#.log (fecha,id_user,tarea,id,proceso,estado,observacion) values ('#fecha#',#id_user#,'#tarea#',#id#,'#proceso#','#estado#','#observacion#')";
//	protected $DELETE = "DELETE FROM #dbName#.log WHERE id_log='#id_log#'";
	protected $FINDBYID = "SELECT id_log,fecha,id_user,tarea,id,proceso,estado,observacion FROM #dbName#.log WHERE id_log=#id_log#";
	
	protected $FINDBYCLAVE = "SELECT id_log, fecha,id_user,tarea,id,proceso,estado,observacion  FROM #dbName#.log WHERE fecha=#fecha# AND tarea=#tarea# AND id=#id#";
	protected $COLECCIONBASE = "SELECT id_log, fecha,id_user,tarea,id,proceso,estado,observacion  FROM #dbName#.log ";
	
	protected $COLECCION = "SELECT id_log,fecha,tarea,id_user,id,proceso,estado,observacion  FROM #dbName#.log ORDER BY fecha";

	/**
	 *
	 * Retorna la coleccion delos datos del log en las diferentes relaciones que posee con respecto a otras estructuras de datos
	 * Basandose en los parametros
	 * @param vartype $id_relacion -> id del elemento con el cual se relaciona
	 * @param unknown_type $relacion -> identificador del dato con el cual tiene relacion: default 'g': registro  'q':qcpprocess
	 */
	public function colecionRelacionLog($id_relacion=0,$relacion='g'){
		//'r': perfil    'p': proyecto   'g': registro  'c': procesos   'q': qcoprocess
		$parametro = func_get_args ();
		$sqlStr=$this->COLECCIONBASE;
		if($id_relacion!=0 && $id_relacion!=''){
		 switch ($relacion){
		 	case 'g':
		 		$sqlStr.= " WHERE tarea='Registro' AND id=$id_relacion ";
		 		break;
		 	case 'q':
		 		$sqlStr.= " WHERE tarea='QCProcess' AND id=$id_relacion ";
		 		break;
		 	default:
		 		break;
		 }
		 	
		 $sqlStr.= " ORDER BY fecha ";
		}
		$resultado=$this->execSql($sqlStr,$parametro);
		if (!$resultado){
			$this->onError ( $COD_COLECCION, "COLECCION DE LOGS EN RELACIONES ".$sqlStr );
		}
		return $resultado;
	}
}
 // Fin clase DAO 
 ?>