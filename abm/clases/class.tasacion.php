<?php

class Tasacion {

/*
  `id_tasacion` int(10) unsigned NOT NULL auto_increment,
  `id_prop` int(10) unsigned NOT NULL,
  `valor` varchar(45) NOT NULL,
  `fecha` datetime NOT NULL,
  `tasador` varchar(100) NOT NULL,
  `observacion` varchar(500) NOT NULL,
 */
	private $id_tasacion;
	private $id_prop;
	private $valor;
	private $cfecha;
	private $tasador;
	private $observacion;
	private $estado;

	public function __construct($id_tasacion=0,
								$id_prop=0,
								$valor='',
								$estado='',
								$cfecha='',
								$tasador='',
								$observacion=''
								){
								
		Tasacion::setId_tasacion($id_tasacion);
		Tasacion::setValor($valor);
		Tasacion::setId_prop($id_prop);
		Tasacion::setCfecha($cfecha);
		Tasacion::setTasador($tasador);
		Tasacion::setObservacion($observacion);
		Tasacion::setEstado($estado);
	}

	
	public function seteaTasacion($_valor){
		$this->setId_tasacion($_valor->getId_tasacion());
		$this->setValor($_valor->getValor());
		$this->setId_prop($_valor->getId_prop());
		$this->setCfecha($_valor->getCfecha());
		$this->setTasador($_valor->getTasador());		
		$this->setObservacion($_valor->getObservacion());
		$this->setEstado($_valor->getEstado());
	}
	
	
	public function setId_tasacion($_id_tasacion){
		$this->id_tasacion = $_id_tasacion;
	}

	public function setId_prop($_id_prop){
		$this->id_prop = $_id_prop;
	}
	
	public function setValor($_valor){
		$this->valor = $_valor;
	}

	public function setEstado($_estado){
		$this->estado = $_estado;
	}

	public function setCfecha($_cfecha){
		$this->cfecha=$_cfecha;
	}
	
	public function setTasador($_inter){
		$this->tasador=$_inter;
	}
	
	public function setObservacion($_comen){
		$this->observacion=$_comen;
	}

	public function getId_tasacion(){
		return $this->id_tasacion;
	}
	
	public function getId_prop(){
		return $this->id_prop;
	}
	
	public function getValor(){
		return $this->valor;
	}
	
	public function getEstado(){
		return $this->estado;
	}
	
	public function getCfecha(){
		return $this->cfecha;
	}
	
	public function getTasador(){
		return $this->tasador;
	}

	public function getObservacion(){
		return $this->observacion;
	}
	
}

?>