<?php

class Crmbuscador {

	private $idcrm;
	private $crmpar;
	private $crmtxt;
	private $adjuntos;

	public function __construct($idcrm='',
								$crmpar='',
								$crmtxt='',
								$adjuntos=''
								){
								
		Crmbuscador::setIdcrm($idcrm);
		Crmbuscador::setCrmtxt($crmtxt);
		Crmbuscador::setCrmpar($crmpar);
		Crmbuscador::setAdjuntos($adjuntos);
	}

	
	public function seteaCrmbuscador($_crm){
		$this->setIdcrm($_crm->getIdcrm());
		$this->setCrmtxt($_crm->getCrmtxt());
		$this->setCrmpar($_crm->getCrmpar());
		$this->setAdjuntos($_crm->getAdjuntos());
	}
	
	
	public function setIdcrm($_idcrm){
		$this->idcrm = $_idcrm;
	}

	public function setCrmpar($_crmpar){
		$this->crmpar = $_crmpar;
	}
	
	public function setCrmtxt($_crmtxt){
		$this->crmtxt = $_crmtxt;
	}

	public function setAdjuntos($_adjuntos){
		$this->adjuntos=$_adjuntos;
	}
	
	public function getIdcrm(){
		return $this->idcrm;
	}
	
	public function getCrmpar(){
		return $this->crmpar;
	}
	
	public function getCrmtxt(){
		return $this->crmtxt;
	}
	
	public function getAdjuntos(){
		return $this->adjuntos;
	}
	
}

?>