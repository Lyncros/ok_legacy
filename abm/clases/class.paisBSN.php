<?php
include_once("generic_class/class.parametricosBSN.php");

class PaisBSN extends ParametricosBSN {
	
	private static $_instance;
        private static $strSql='SELECT iso,name from #dbName#.paises';
        
	public function __construct(){
            parent::__construct('SQL', self::$strSql);
        }

	
	static public function getInstance(){
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
}


?>
