<?php

class Operacion {

/*  id_oper INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  id_prop INTEGER UNSIGNED NOT NULL,
  operacion VARCHAR(45) NOT NULL,
  fecha DATETIME NOT NULL,
  intervino INTEGER UNSIGNED NOT NULL,
 */
	private $id_oper;
	private $id_prop;
	private $operacion;
	private $cfecha;
	private $intervino;
	private $comentario;

	public function __construct($id_oper=0,
								$id_prop=0,
								$operacion='',
								$cfecha='',
								$intervino=0,
								$comentario=''
								){
								
		Operacion::setId_oper($id_oper);
		Operacion::setOperacion($operacion);
		Operacion::setId_prop($id_prop);
		Operacion::setCfecha($cfecha);
		Operacion::setIntervino($intervino);
		Operacion::setComentario($comentario);
	}

	
	public function seteaOperacion($_operacion){
		$this->setId_oper($_operacion->getId_oper());
		$this->setOperacion($_operacion->getOperacion());
		$this->setId_prop($_operacion->getId_prop());
		$this->setCfecha($_operacion->getCfecha());
		$this->setIntervino($_operacion->getIntervino());		
		$this->setComentario($_operacion->getComentario());
	}
	
	
	public function setId_oper($_id_oper){
		$this->id_oper = $_id_oper;
	}

	public function setId_prop($_id_prop){
		$this->id_prop = $_id_prop;
	}
	
	public function setOperacion($_operacion){
		$this->operacion = $_operacion;
	}

	public function setCfecha($_cfecha){
		$this->cfecha=$_cfecha;
	}
	
	public function setIntervino($_inter){
		$this->intervino=$_inter;
	}
	
	public function setComentario($_comen){
		$this->comentario=$_comen;
	}

	public function getId_oper(){
		return $this->id_oper;
	}
	
	public function getId_prop(){
		return $this->id_prop;
	}
	
	public function getOperacion(){
		return $this->operacion;
	}
	
	public function getCfecha(){
		return $this->cfecha;
	}
	
	public function getIntervino(){
		return $this->intervino;
	}

	public function getComentario(){
		return $this->comentario;
	}
	
}

?>