<?php
include_once($ROOT_PATH."generic_class/class.PGDAO.php");

class LoginPGDAO extends PGDAO {
	
	protected $INSERT="INSERT INTO #dbName#.usuarios (usuario,clave,fecha_base,nueva_clave,fecha_nueva,errores) values ('#usuario#','#clave#',STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),'#nueva_clave#',STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),#errores#)";
	protected $DELETE="DELETE FROM #dbName#.usuarios WHERE id_user=#id_user#";
	protected $UPDATE="UPDATE #dbName#.usuarios set  usuario='#usuario#',clave='#clave#',fecha_base=STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),nueva_clave='#nueva_clave#',fecha_nueva=STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),errores=#errores# WHERE id_user=#id_user# ";
	protected $FINDBYNOMBRE="SELECT id_user,usuario,clave,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores FROM #dbName#.usuarios WHERE usuario = '#usuario#'";
	
	protected $COLECCION="SELECT id_user,usuario,clave,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores FROM #dbName#.usuarios ORDER BY usuario";

	public function findByNombre(){
		$parametro = func_get_args ();
		$resultado=$this->execSql($this->FINDBYNOMBRE,$parametro);
		if (!$resultado){
			$this->onError ( $COD_COLECCION, "FIND BY NOMBRE" );
		}
		return $resultado;
	}

	
} // Fin clase DAO
?>