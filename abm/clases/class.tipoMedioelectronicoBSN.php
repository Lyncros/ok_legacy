<?php

include_once("generic_class/class.parametricosBSN.php");

class TipoMedioElectronicoBSN extends ParametricosBSN {
	
	private static $_instance;

	public function __construct(){
            parent::__construct('XML', 'tiposMedioelectronico.xml');
        }

	
	static public function getInstance(){
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
}
?>
