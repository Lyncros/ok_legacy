<?php

class Tipo_prop {

	private $id_tipo_prop;
	private $tipo_prop;
	private $subtipo_prop;
	private $grupo;
	
	public function __construct($id_tipo_prop=0,
								$tipo_prop='',
								$subtipo_prop=''
								){
								
		Tipo_prop::setId_tipo_prop($id_tipo_prop);
		Tipo_prop::setTipo_prop($tipo_prop);
		Tipo_prop::setSubtipo_prop($subtipo_prop);
	}
	
	public function seteaTipo_prop($_tipo_prop){
		$this->setId_tipo_prop($_tipo_prop->getId_tipo_prop());
		$this->setTipo_prop($_tipo_prop->getTipo_prop());
		$this->setSubtipo_prop($_tipo_prop->getSubtipo_prop());
	}
	
	public function setId_tipo_prop($_id_tipo_prop){
		$this->id_tipo_prop = $_id_tipo_prop;
	}
	
	public function setTipo_prop($_tipo_prop){
		$this->tipo_prop = $_tipo_prop;
	}

	public function setSubtipo_prop($subtipo_prop){
		$this->subtipo_prop = $subtipo_prop;
	}
	
	public function getId_tipo_prop(){
		return $this->id_tipo_prop;
	}
	
	public function getTipo_prop(){
		return $this->tipo_prop;
	}

	public function getSubtipo_prop(){
		return $this->subtipo_prop;
	}
	
	
}

?>