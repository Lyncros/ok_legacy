<?php

class Foto {

	private $id_foto;
	private $id_prop;
	private $foto;
	private $posicion;
	private $publica_web;
	
	public function __construct($id_foto=0,
								$id_prop=0,
								$foto='',
								$posicion=0,
								$publica_web = 1 ){
								
		Foto::setId_foto($id_foto);
		Foto::setId_prop($id_prop);
		Foto::setFoto($foto);
		Foto::setPosicion($posicion);
		Foto::setPublica_web($publica_web);
	}

	
	public function seteaFoto($_foto){
		$this->setId_foto($_foto->getId_foto());
		$this->setId_prop($_foto->getId_prop());
		$this->setFoto($_foto->getFoto());
		$this->setPosicion($_foto->getPosicion());
	}
	
	
	public function setId_foto($_id_foto){
		$this->id_foto = $_id_foto;
	}
	
	public function setId_prop($id_prop){
		$this->id_prop = $id_prop;
	}

	public function setFoto($_foto){
		$this->foto = $_foto;
	}

	public function setPosicion($_posicion){
		$this->posicion = $_posicion;
	}

	public function setPublica_web($_publica_web){
		$this->publica_web = $_publica_web;
	}
	
	public function getId_foto(){
		return $this->id_foto;
	}
	
	public function getId_prop(){
		return $this->id_prop;
	}
	
	public function getFoto(){
		return $this->foto;
	}
	
	public function getPosicion(){
		return $this->posicion;
	}
	
	public function getPublica_web(){
		return $this->publica_web;
	}
}

?>