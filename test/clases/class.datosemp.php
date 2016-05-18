<?php

class Datosemp {

	private $id_emp_carac;
	private $id_emp;
	private $id_carac;
	private $contenido;
	private $comentario;
	private $orden;
	private $titulo;
	private $activa;	
	
	public function __construct($id_emp_carac=0,
								$id_emp=0,
								$id_carac=0,
								$contenido='',
								$comentario='',
								$orden=0,
								$titulo='',
								$activa=0
								){
								
		Datosemp::setId_emp_carac($id_emp_carac);
		
		Datosemp::setId_emp($id_emp);
		
		Datosemp::setId_carac($id_carac);
		
		Datosemp::setContenido($contenido);
		
		Datosemp::setComentario($comentario);
		Datosemp::setActiva($activa);
		
	}

	
	public function seteaDatosemp($_datos){
		$this->setId_emp_carac($_datos->getId_emp_carac());
		$this->setId_emp($_datos->getId_emp());
		$this->setId_carac($_datos->getId_carac());
		$this->setContenido($_datos->getContenido());
		$this->setComentario($_datos->getComentario());
		$this->setActiva($_datos->getActiva());
	}
	

	public function setActiva($activa){
		$this->activa = $activa;
	}

	public function getActiva(){
		return $this->activa;
	}
		
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}
	
	public function setOrden($orden){
		$this->orden=$orden;		
	}
	
	public function setId_emp_carac($id_emp_carac){
		$this->id_emp_carac=$id_emp_carac;
	}
	
	public function setId_emp($id_emp){
		$this->id_emp=$id_emp;
	}
	
	public function setId_carac($id_carac){
		$this->id_carac=$id_carac;
	}
	
	public function setContenido($contenido){
		$this->contenido=$contenido;
	}
	
	public function setComentario($comentario){
		$this->comentario=$comentario;
	}

	public function getTitulo(){
		return $this->titulo;
	}
	
	public function getOrden(){
		return $this->orden;		
	}
	
	
	public function getId_emp_carac(){
		return $this->id_emp_carac;
	}
	
	public function getId_emp(){
		return $this->id_emp;
	}
	
	public function getId_carac(){
		return $this->id_carac;
	}
	
	public function getContenido(){
		return $this->contenido;
	}
	
	public function getComentario(){
		return $this->comentario;
	}
	
}

?>