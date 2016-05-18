<?php

class Rubro {

	private $id_rubro;
	private $denominacion;
	private $detalle;
	
	public function __construct($id_rubro=0,
								$denominacion='',
								$detalle=''
								){
								
		Rubro::setId_rubro($id_rubro);
		Rubro::setDenominacion($denominacion);
		Rubro::setDetalle($detalle);
	}
	
	public function seteaRubro($_rubro){
		$this->setId_rubro($_rubro->getId_rubro());
		$this->setDenominacion($_rubro->getDenominacion());
		$this->setDetalle($_rubro->getDetalle());
	}
	
	public function setId_rubro($_id_rubro){
		$this->id_rubro = $_id_rubro;
	}
	
	public function setDenominacion($denominacion){
		$this->denominacion = $denominacion;
	}

	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}
	
	public function getId_rubro(){
		return $this->id_rubro;
	}
	
	public function getDenominacion(){
		return $this->denominacion;
	}

	public function getDetalle(){
		return $this->detalle;
	}
	
	
}

?>