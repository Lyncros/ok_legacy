<?php
include_once("generic_class/class.PGDAO.php");

class ClientePGDAO extends PGDAO {

	protected  $INSERT="INSERT INTO #dbName#.clientes (id_cli,usuario,clave,apellido,nombre,email,observacion,fecha_base,nueva_clave,fecha_nueva,errores,activa) values (#id_cli#,'#usuario#','#clave#','#apellido#','#nombre#','#email#','#observacion#',STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),'#nueva_clave#',STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),#errores#,#activa#)";
	
	protected  $DELETE="DELETE FROM #dbName#.clientes WHERE id_cli=#id_cli#";
	
	protected  $UPDATE="UPDATE #dbName#.clientes set usuario='#usuario#',clave='#clave#',apellido='#apellido#',nombre='#nombre#',email='#email#',observacion='#observacion#',fecha_base=STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),nueva_clave='#nueva_clave#',fecha_nueva=STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),errores=#errores#,activa=#activa# WHERE id_cli=#id_cli# ";
	
	protected  $FINDBYCLAVE="SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa FROM #dbName#.clientes WHERE usuario='#usuario#'";
	protected  $FINDBYNOMBRE="SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa FROM #dbName#.clientes WHERE apellido LIKE '#apellido#' AND nombre LIKE '#nombre#'";
	
	protected  $FINDBYID="SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa FROM #dbName#.clientes WHERE id_cli=#id_cli#";
	
	protected  $COLECCION="SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa FROM #dbName#.clientes ORDER BY usuario";
	
	protected $ACTIVAR="UPDATE #dbName#.clientes SET activa=1 WHERE id_cli=#id_cli#";
	
	protected $DESACTIVAR="UPDATE #dbName#.clientes SET activa=0 WHERE id_cli=#id_cli#";
	
	public function findByNombre(){
		$parametro = func_get_args ();
		$resultado=$this->execSql($this->FINDBYNOMBRE,$parametro);
		if (!$resultado){
			$this->onError ( $COD_UPDATE, "FIND BY NOMBRE" );
		}
		return $resultado;
	}
	
	public function activar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->ACTIVAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"ACTIVACION de Usuario");
		}
		return $resultado;				
	}
	
	public function desactivar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DESACTIVAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"INACTIVACION de Usuario");
		}
		return $resultado;				
	}	

	
} // Fin clase DAO
?>