<?php

class Caracteristica {

	private $id_carac;
	private $id_tipo_carac;
	private $titulo;
	private $tipo;
	private $maximo;
	private $comentario;
	private $lista;
	private $orden;
	private $comparacion;
	private $publica;
	
	public function __construct($id_carac=0,
								$id_tipo_carac=0,
								$titulo='',
								$tipo='CheckBox',  // C: checkbox   N: Numerico List    T: texto
								$maximo=0,
								$comentario='No',  // S: Acepta comentarios     N: NO acepta
								$lista='',
								$orden=0,
								$comparacion='',
								$publica=0
								){
								
		Caracteristica::setId_carac($id_carac);
		Caracteristica::setId_tipo_carac($id_tipo_carac);
		Caracteristica::setTitulo($titulo);
		Caracteristica::setTipo($tipo);
		Caracteristica::setMaximo($maximo);
		Caracteristica::setComentario($comentario);
		Caracteristica::setLista($lista);
		Caracteristica::setOrden($orden);
		Caracteristica::setComparacion($comparacion);
		Caracteristica::setPublica($publica);

	}

	
	public function seteaCaracteristica($_caract){
		$this->setId_carac($_caract->getId_carac());
		$this->setTitulo($_caract->getTitulo());
		$this->setTipo($_caract->getTipo());
		$this->setId_tipo_carac($_caract->getId_tipo_carac());
		$this->setMaximo($_caract->getMaximo());
		$this->setComentario($_caract->getComentario());
		$this->setLista($_caract->getLista());
		$this->setOrden($_caract->getOrden());
		$this->setComparacion($_caract->getComparacion());
		$this->setPublica($_caract->getPublica());
	}
	
	
	public function setPublica($_publ){
		$this->publica = $_publ;
	}
	
	public function getPublica(){
		return $this->publica;
	}
	
	public function setComparacion($_compara){
		$this->comparacion=$_compara;
	}
	
	public function setId_carac($_id_carac){
		$this->id_carac = $_id_carac;
	}
	
	public function setTitulo($_titulo){
		$this->titulo = $_titulo;
	}
	
	public function setId_tipo_carac($id_tipo_carac){
		$this->id_tipo_carac=$id_tipo_carac;
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

	public function getId_tipo_carac(){
		return $this->id_tipo_carac;
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
	
	public function getComparacion(){
		return $this->comparacion;
	}
}

?>