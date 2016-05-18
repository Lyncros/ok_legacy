<?php

include_once("generic_class/class.parametricosBSN.php");

class EstadoasuntoBSN extends ParametricosBSN {
	
	private static $_instance;

	public function __construct(){
            parent::__construct('XML', 'estadoAsunto.xml');
        }

	
	static public function getInstance(){
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
}
?>
