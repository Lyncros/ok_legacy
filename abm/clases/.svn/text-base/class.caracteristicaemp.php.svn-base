<?php

class Caracteristicaemp {

	private $id_carac;
	private $titulo;
	private $tipo;
	private $maximo;
	private $comentario;
	private $lista;
	private $orden;
	
/*
  id_carac int(10) unsigned NOT NULL auto_increment,
  titulo varchar(150) NOT NULL,
  tipo varchar(8) default NULL,
  maximo int(10) unsigned default NULL,
  comentario char(2) default NULL,
  lista varchar(500) NOT NULL,
  orden int(10) unsigned NOT NULL,	
*/
	
	public function __construct($id_carac=0,
								$titulo='',
								$tipo='Web',  // C: checkbox   N: Numerico List    T: texto
								$maximo=0,
								$comentario='No',  // S: Acepta comentarios     N: NO acepta
								$lista='',
								$orden=0
								){
								
		Caracteristicaemp::setId_carac($id_carac);
		Caracteristicaemp::setTitulo($titulo);
		Caracteristicaemp::setTipo($tipo);
		Caracteristicaemp::setMaximo($maximo);
		Caracteristicaemp::setComentario($comentario);
		Caracteristicaemp::setLista($lista);
		Caracteristicaemp::setOrden($orden);
	}

	
	public function seteaCaracteristicaemp($_caract){
		$this->setId_carac($_caract->getId_carac());
		$this->setTitulo($_caract->getTitulo());
		$this->setTipo($_caract->getTipo());
		$this->setMaximo($_caract->getMaximo());
		$this->setComentario($_caract->getComentario());
		$this->setLista($_caract->getLista());
		$this->setOrden($_caract->getOrden());
	}
	
	
	public function setId_carac($_id_carac){
		$this->id_carac = $_id_carac;
	}
	
	public function setTitulo($_titulo){
		$this->titulo = $_titulo;
	}
	
	public function setTipo($tipo){
		$this->tipo=$tipo;
	}
	
	public function setMaximo($maximo){
		$this->maximo=$maximo;
	}
	
	public function setComentario($comentario){
		$this->comentario=$comentario;
	}
	
	public function setLista($lista){
		$this->lista=$lista;
	}
	
	public function setOrden($orden){
		$this->orden=$orden;
	}

	public function getId_carac(){
		return $this->id_carac;
	}
	
	public function getTitulo(){
		return $this->titulo;
	}
	
	public function getTipo(){
		return $this->tipo;
	}
	
	public function getMaximo(){
		return  $this->maximo;
	}
	
	public function getComentario(){
		return $this->comentario;
	}
	
	public function getLista(){
		return $this->lista;
	}
	
	public function getOrden(){
		return $this->orden;
	}
	
}

?>