<?php

class Tipo_emp {

	private $id_tipo_emp;
	private $tipo_emp;
	
	public function __construct($id_tipo_emp=0,
								$tipo_emp=''
								){
								
		Tipo_emp::setId_tipo_emp($id_tipo_emp);
		Tipo_emp::setTipo_emp($tipo_emp);
	}
	
	public function seteaTipo_emp($_tipo_emp){
		$this->setId_tipo_emp($_tipo_emp->getId_tipo_emp());
		$this->setTipo_emp($_tipo_emp->getTipo_emp());
	}
	
	public function setId_tipo_emp($_id_tipo_emp){
		$this->id_tipo_emp = $_id_tipo_emp;
	}
	
	public function setTipo_emp($_tipo_emp){
		$this->tipo_emp = $_tipo_emp;
	}

	public function getId_tipo_emp(){
		return $this->id_tipo_emp;
	}
	
	public function getTipo_emp(){
		return $this->tipo_emp;
	}

	
}

?>