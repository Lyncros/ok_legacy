<?php
include_once("generic_class/class.PGDAO.php");

class PerfileswebuserPGDAO extends PGDAO {
	
	protected $INSERT="INSERT INTO #dbName#.usuarios_web_perfiles (id_user, perfil) values (#id_user#, '#perfil#')";
	protected $DELETE="DELETE FROM #dbName#.usuarios_web_perfiles WHERE id_user=#id_user# AND perfil='#perfil#'";
	protected $PERFIL_UW_BY_UW="SELECT perfil FROM #dbName#.usuarios_web_perfiles WHERE id_user=#id_user# ORDER BY perfil";
	protected $FINDBYID = "SELECT perfil FROM #dbName#.usuarios_web_perfiles WHERE id_user=#id_user#";
	protected $COLECCION = "SELECT u.perfil,u.id_user,concat(l.nombre,' ',l.apellido) as usuario FROM #dbName#.usuarios_web_perfiles as u INNER JOIN #dbName#.usuarios as l ON u.id_user = l.id_user WHERE perfil = '#perfil#'";
	
	public function coleccionUW_Perfiles_byUW(){
		$parametro = func_get_args ();		
		$resultado=$this->execSql($this->PERFIL_UW_BY_UW,$parametro);
		if(!$resultado){
			$this->onError ( $COD_UPDATE, "FIND PERFILES x USUARIO" );
		}
		return $resultado;
	}
	
	
} // Fin clase DAO
?>