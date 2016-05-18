<?php

class Tiporelacion {

	
 
	private $id_tiporel;
	private $tiporelacion;
	private $relacion;
	private $grado;
	private $observacion;
	
	public function __construct($id_tiporel=0,
								$tiporelacion='',
								$relacion='',
								$grado='',
								$observacion=''
								){
								
		Tiporelacion::setId_tiporel($id_tiporel);
		Tiporelacion::setTiporelacion($tiporelacion);
		Tiporelacion::setRelacion($relacion);
		Tiporelacion::setGrado($grado);
		Tiporelacion::setObservacion($observacion);
	}

	
	public function seteaTiporelacion($_tiporelacion){
		$this->setId_tiporel($_tiporelacion->getId_tiporel());
		$this->setTiporelacion($_tiporelacion->getTiporelacion());
		$this->setRelacion($_tiporelacion->getRelacion());
		$this->setGrado($_tiporelacion->getGrado());
		$this->setObservacion($_tiporelacion->getObservacion());
	}
	
	
	public function setObservacion($_publ){
		$this->observacion = $_publ;
	}
	
	public function getObservacion(){
		return $this->observacion;
	}
	
	public function setId_tiporel($_id_tiporel){
		$this->id_tiporel = $_id_tiporel;
	}
	
	public function setTiporelacion($_tiporelacion){
		$this->tiporelacion = $_tiporelacion;
	}
	
	public function setRelacion($relacion){
		$this->relacion=$relacion;
	}
	
	public function setGrado($col){
		$this->grado=$col;
	}


	public function getId_tiporel(){
		return $this->id_tiporel;
	}
	
	public function getTiporelacion(){
		return $this->tiporelacion;
	}

	public function getRelacion(){
		return $this->relacion;
	}
	
	public function getGrado(){
		return $this->grado;
	}
}

?>