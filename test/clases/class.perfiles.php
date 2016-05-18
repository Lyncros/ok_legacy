<?php

class Perfiles {

	private $perfil;
	private $descripcion;
	
	public function __construct($perfil='',
								$descripcion=''
								){
								
		Perfiles::setPerfil($perfil);
		Perfiles::setDescripcion($descripcion);
	}
	
	public function seteaPerfiles($_perfil){
		$this->setPerfil($_perfil->getPerfil());
		$this->setDescripcion($_perfil->getDescripcion());
	}
	
	public function setPerfil($_perfil){
		$this->perfil = $_perfil;
	}
	
	public function setDescripcion($_desc){
		$this->descripcion = $_desc;
	}

	public function getPerfil(){
		return $this->perfil;
	}
	
	public function getDescripcion(){
		return $this->descripcion;
	}

	
}

?>