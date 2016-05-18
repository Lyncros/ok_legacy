<?php

class Ubicacionpropiedad {

	private $id_ubica;
	private $id_padre;
	private $nombre_ubicacion;
	
	public function __construct($id_ubica=0,
								$id_padre=0,
								$nombre_ubicacion=''
								){
								
		Ubicacionpropiedad::setId_ubica($id_ubica);
		Ubicacionpropiedad::setId_padre($id_padre);
		Ubicacionpropiedad::setNombre_ubicacion($nombre_ubicacion);
	}
	
	public function seteaUbicacionpropiedad($_nombre_ubicacion){
		$this->setId_ubica($_nombre_ubicacion->getId_ubica());
		$this->setId_padre($_nombre_ubicacion->getId_padre());
		$this->setNombre_ubicacion($_nombre_ubicacion->getNombre_ubicacion());
	}
	
	public function setId_ubica($_id_ubica){
		$this->id_ubica = $_id_ubica;
	}
	
	public function setId_padre($_id_padre){
		$this->id_padre = $_id_padre;
	}
	
	public function setNombre_ubicacion($_nombre_ubicacion){
		$this->nombre_ubicacion = $_nombre_ubicacion;
	}

	public function getId_ubica(){
		return $this->id_ubica;
	}
	
	public function getId_padre(){
		return $this->id_padre;
	}
	
	public function getNombre_ubicacion(){
		return $this->nombre_ubicacion;
	}

	public function __toString(){
		$str='';
		$str.='id: '.$this->id_ubica.', ';
		$str.='nombre: '.$this->nombre_ubicacion.', ';
		$str.='id: '.$this->id_padre.', ';
		return $str;
	}
	
}

?>