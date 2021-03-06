<?php

/**
 * Clase de Conexion contra la base de datos de la aplicacion
 * Utiliza patron Singleton para asegurar una �nica conexion para toda 
 * aplicacion.
 */
class DB_my_connection {

	private $conector;
	private static $_instance;
	
	public function __construct($_db_host,$_db_port,$_db_name,$_db_user,$_db_pass){
		
		$string="$_db_host,$_db_user,$_db_pass,$_db_name,$_db_port";
//		$_conector=mysqli_connect($string);
		$_conector=mysql_connect($_db_host,$_db_user,$_db_pass);
//$_conector=mysql_connect('localhost','achaval','achaval');
//		mysql_selectdb($_db_name,$_conector);
		$this->conector=$_conector;
		
	}

	
	static public function getInstance($_ROOTPATH,$_db_host="",$_db_port="",$_db_name="",$_db_user="",$_db_pass=""){
		if(is_null(self::$_instance)){
			if ($_db_host==""){
				$_db_host=$db_host;
				$_db_port=$db_port;
				$_db_name=$db_name;
				$_db_user=$db_user;
				$_db_pass=$db_pass;
			}
			self::$_instance = new self($_db_host,$_db_port,$_db_name,$_db_user,$_db_pass);
		}
		return self::$_instance;
	}

	
	public function getConector(){
		return $this->conector;
	}
	
} // Fin Clase de Conexion con Postgres
?>
