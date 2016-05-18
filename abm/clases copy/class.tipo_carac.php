<?php

class Tipo_carac {

	
 
	private $id_tipo_carac;
	private $tipo_carac;
	private $orden;
	private $columnas;
	private $publica;
	
	public function __construct($id_tipo_carac=0,
								$tipo_carac='',
								$orden=0,
								$columnas=0,
								$publica=0
								){
								
		Tipo_carac::setId_tipo_carac($id_tipo_carac);
		Tipo_carac::setTipo_carac($tipo_carac);
		Tipo_carac::setOrden($orden);
		Tipo_carac::setColumnas($columnas);
		Tipo_carac::setPublica($publica);
	}

	
	public function seteaTipo_carac($_tipo_carac){
		$this->setId_tipo_carac($_tipo_carac->getId_tipo_carac());
		$this->setTipo_carac($_tipo_carac->getTipo_carac());
		$this->setOrden($_tipo_carac->getOrden());
		$this->setColumnas($_tipo_carac->getColumnas());
		$this->setPublica($_tipo_carac->getPublica());
	}
	
	
	public function setPublica($_publ){
		$this->publica = $_publ;
	}
	
	public function getPublica(){
		return $this->publica;
	}
	
	public function setId_tipo_carac($_id_tipo_carac){
		$this->id_tipo_carac = $_id_tipo_carac;
	}
	
	public function setTipo_carac($_tipo_carac){
		$this->tipo_carac = $_tipo_carac;
	}
	
	public function setOrden($orden){
		$this->orden=$orden;
	}
	
	public function setColumnas($col){
		$this->columnas=$col;
	}


	public function getId_tipo_carac(){
		return $this->id_tipo_carac;
	}
	
	public function getTipo_carac(){
		return $this->tipo_carac;
	}

	public function getOrden(){
		return $this->orden;
	}
	
	public function getColumnas(){
		return $this->columnas;
	}
}

?>