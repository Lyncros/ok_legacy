<?php

class Cartel {

/*
  `id_cartel` int(10) unsigned NOT NULL auto_increment,
  `id_prop` int(10) unsigned NOT NULL,
  `estado` varchar(45) NOT NULL,
  `fecha` datetime NOT NULL,
  `proveedor` varchar(100) NOT NULL,
  `observacion` varchar(500) NOT NULL,
 */
	private $id_cartel;
	private $id_prop;
	private $estado;
	private $cfecha;
	private $proveedor;
	private $observacion;

	public function __construct($id_cartel=0,
								$id_prop=0,
								$estado='',
								$cfecha='',
								$proveedor='',
								$observacion=''
								){
								
		Cartel::setId_cartel($id_cartel);
		Cartel::setEstado($estado);
		Cartel::setId_prop($id_prop);
		Cartel::setCfecha($cfecha);
		Cartel::setProveedor($proveedor);
		Cartel::setObservacion($observacion);
	}

	
	public function seteaCartel($_estado){
		$this->setId_cartel($_estado->getId_cartel());
		$this->setEstado($_estado->getEstado());
		$this->setId_prop($_estado->getId_prop());
		$this->setCfecha($_estado->getCfecha());
		$this->setProveedor($_estado->getProveedor());		
		$this->setObservacion($_estado->getObservacion());
	}
	
	
	public function setId_cartel($_id_cartel){
		$this->id_cartel = $_id_cartel;
	}

	public function setId_prop($_id_prop){
		$this->id_prop = $_id_prop;
	}
	
	public function setEstado($_estado){
		$this->estado = $_estado;
	}

	public function setCfecha($_cfecha){
		$this->cfecha=$_cfecha;
	}
	
	public function setProveedor($_inter){
		$this->proveedor=$_inter;
	}
	
	public function setObservacion($_comen){
		$this->observacion=$_comen;
	}

	public function getId_cartel(){
		return $this->id_cartel;
	}
	
	public function getId_prop(){
		return $this->id_prop;
	}
	
	public function getEstado(){
		return $this->estado;
	}
	
	public function getCfecha(){
		return $this->cfecha;
	}
	
	public function getProveedor(){
		return $this->proveedor;
	}

	public function getObservacion(){
		return $this->observacion;
	}
	
}

?>