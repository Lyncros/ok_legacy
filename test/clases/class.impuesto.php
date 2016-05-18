<?php

class  Impuesto {

	private $id_impuesto;
	private $denominacion;
	private $detalle;
	
	public function __construct($id_impuesto=0,
				$denominacion='',
				$detalle=''
				){
								
		Impuesto::setId_impuesto($id_impuesto);
		Impuesto::setDenominacion($denominacion);
		Impuesto::setDetalle($detalle);
	}
	
	public function seteaImpuesto($_impuesto){
		$this->setId_impuesto($_impuesto->getId_impuesto());
		$this->setDenominacion($_impuesto->getDenominacion());
		$this->setDetalle($_impuesto->getDetalle());
	}
	
	public function setId_impuesto($_id_impuesto){
		$this->id_impuesto = $_id_impuesto;
	}
	
	public function setDenominacion($denominacion){
		$this->denominacion = $denominacion;
	}

	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}
	
	public function getId_impuesto(){
		return $this->id_impuesto;
	}
	
	public function getDenominacion(){
		return $this->denominacion;
	}

	public function getDetalle(){
		return $this->detalle;
	}
	
	public function __toString() {
            $str='';
            $str.='id_impuesto: '.$this->id_impuesto."; ";
            $str.='denominacion: '.$this->denominacion."; ";
            $str.='detalle: '.$this->detalle."; ";
            return $str;;
            }
}

?>