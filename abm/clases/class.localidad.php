<?php

class Localidad {

	private $id_loca;
	private $id_zona;
	private $nombre_loca;
	
	public function __construct($id_loca=0,
								$id_zona=0,
								$nombre_loca=''
								){
								
		Localidad::setId_loca($id_loca);
		Localidad::setId_zona($id_zona);
		Localidad::setNombre_loca($nombre_loca);
	}
	
	public function seteaLocalidad($_nombre_loca){
		$this->setId_loca($_nombre_loca->getId_loca());
		$this->setId_zona($_nombre_loca->getId_zona());
		$this->setNombre_loca($_nombre_loca->getNombre_loca());
	}
	
	public function setId_loca($_id_loca){
		$this->id_loca = $_id_loca;
	}
	
	public function setId_zona($_id_zona){
		$this->id_zona = $_id_zona;
	}
	
	public function setNombre_loca($_nombre_loca){
		$this->nombre_loca = $_nombre_loca;
	}

	public function getId_loca(){
		return $this->id_loca;
	}
	
	public function getId_zona(){
		return $this->id_zona;
	}
	
	public function getNombre_loca(){
		return $this->nombre_loca;
	}

	
}

?>