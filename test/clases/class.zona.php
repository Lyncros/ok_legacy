<?php

class Zona {

	private $id_zona;
	private $nombre_zona;
	
	public function __construct($id_zona=0,
								$nombre_zona=''
								){
								
		Zona::setId_zona($id_zona);
		Zona::setNombre_zona($nombre_zona);
	}
	
	public function seteaZona($_nombre_zona){
		$this->setId_zona($_nombre_zona->getId_zona());
		$this->setNombre_zona($_nombre_zona->getNombre_zona());
	}
	
	public function setId_zona($_id_zona){
		$this->id_zona = $_id_zona;
	}
	
	public function setNombre_zona($_nombre_zona){
		$this->nombre_zona = $_nombre_zona;
	}

	public function getId_zona(){
		return $this->id_zona;
	}
	
	public function getNombre_zona(){
		return $this->nombre_zona;
	}

	
}

?>