<?php

include_once("generic_class/class.parametricosBSN.php");

class TipointeresesnewsBSN extends ParametricosBSN {
	
	private static $_instance;
        protected $_chkbyline=5;


	public function __construct(){
            parent::__construct('XML', 'interesesNews.xml');
        }

	
	static public function getInstance(){
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
        
}
?>
