<?php
include_once("generic_class/class.PGDAO.php");

class LoginwebuserPGDAO extends PGDAO {

	protected  $INSERT="INSERT INTO #dbName#.usuarios (id_user,usuario,clave,apellido,nombre,email,telefono,fecha_base,nueva_clave,fecha_nueva,errores,activa) values (#id_user#,'#usuario#','#clave#','#apellido#','#nombre#','#email#','#telefono#',STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),'#nueva_clave#',STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),#errores#,#activa#)";
	
	protected  $DELETE="DELETE FROM #dbName#.usuarios WHERE id_user=#id_user#";
	
	protected  $UPDATE="UPDATE #dbName#.usuarios set usuario='#usuario#',clave='#clave#',apellido='#apellido#',nombre='#nombre#',email='#email#',telefono='#telefono#',fecha_base=STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),nueva_clave='#nueva_clave#',fecha_nueva=STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),errores=#errores#,activa=#activa# WHERE id_user=#id_user# ";
	
	protected  $FINDBYCLAVE="SELECT id_user,usuario,clave,apellido,nombre,email,telefono,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa FROM #dbName#.usuarios WHERE usuario='#usuario#'";
	protected  $FINDBYNOMBRE="SELECT id_user,usuario,clave,apellido,nombre,email,telefono,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa FROM #dbName#.usuarios WHERE apellido LIKE '#apellido#' AND nombre LIKE '#nombre#'";
	
	protected  $FINDBYID="SELECT id_user,usuario,clave,apellido,nombre,email,telefono,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa FROM #dbName#.usuarios WHERE id_user=#id_user#";
	
	protected  $COLECCION="SELECT id_user,usuario,clave,apellido,nombre,email,telefono,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa FROM #dbName#.usuarios ORDER BY usuario";
	
	protected $ACTIVAR="UPDATE #dbName#.usuarios SET activa=1 WHERE id_user=#id_user#";
	
	protected $DESACTIVAR="UPDATE #dbName#.usuarios SET activa=0 WHERE id_user=#id_user#";
	
	protected $DESBLOQUEAR="UPDATE #dbName#.usuarios SET activa=1, errores=0 WHERE id_user=#id_user#";
        
	public function findByNombre(){
		$parametro = func_get_args ();
		$resultado=$this->execSql($this->FINDBYNOMBRE,$parametro);
		if (!$resultado){
			$this->onError ( 'COD_UPDATE', "FIND BY NOMBRE" );
		}
		return $resultado;
	}
	
	public function desbloquear(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DESBLOQUEAR,$parametro);
		if (!$resultado){
			$this->onError('COD_UPDATE',"Desbloqueo de Usuario");
		}
		return $resultado;				
	}

        public function activar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->ACTIVAR,$parametro);
		if (!$resultado){
			$this->onError('COD_UPDATE',"ACTIVACION de Usuario");
		}
		return $resultado;				
	}
	
	public function desactivar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DESACTIVAR,$parametro);
		if (!$resultado){
			$this->onError('COD_UPDATE',"INACTIVACION de Usuario");
		}
		return $resultado;				
	}	

	
} // Fin clase DAO
?>