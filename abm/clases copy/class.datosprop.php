<?php

class Datosprop {

	private $id_prop_carac;
	private $id_prop;
	private $id_carac;
	private $contenido;
	private $comentario;
	private $tipo;
	private $titulo;
	private $tipo_carac;
	private $orden_tipo;
	private $orden;

	public function __construct($id_prop_carac=0,
	$id_prop=0,
	$id_carac=0,
	$contenido='',
	$comentario='',
	$tipo='',
	$titulo='',
	$tipo_carac='',
	$orden_tipo=0,
	$orden=0
	){

		Datosprop::setId_prop_carac($id_prop_carac);

		Datosprop::setId_prop($id_prop);

		Datosprop::setId_carac($id_carac);

		Datosprop::setContenido($contenido);

		Datosprop::setComentario($comentario);

		Datosprop::setTipo($tipo);

		Datosprop::setTitulo($titulo);

		Datosprop::setTipo_carac($tipo_carac);
		Datosprop::setOrden($orden);
		Datosprop::setOrden_tipo($orden_tipo);

	}


	public function seteaDatosprop($_datos){
		$this->setId_prop_carac($_datos->getId_prop_carac());
		$this->setId_prop($_datos->getId_prop());
		$this->setId_carac($_datos->getId_carac());
		$this->setContenido($_datos->getContenido());
		$this->setComentario($_datos->getComentario());
		$this->setTipo($_datos->getTipo());
		$this->setTitulo($_datos->getTitulo());
		$this->setTipo_carac($_datos->getTipo_carac());
		$this->setOrden($_datos->getOrden());
		$this->setOrden_tipo($_datos->getOrden_tipo());

	}


	public function setOrden_tipo($orden){
		$this->orden_tipo=$orden;
	}
	public function setOrden($orden){
		$this->orden=$orden;
	}

	public function setId_prop_carac($id_prop_carac){
		$this->id_prop_carac=$id_prop_carac;
	}

	public function setId_prop($id_prop){
		$this->id_prop=$id_prop;
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

	public function setTipo($tipo){
		$this->tipo=$tipo;
	}

	public function setTitulo($titulo){
		$this->titulo=$titulo;
	}

	public function setTipo_carac($tipo){
		$this->tipo_carac=$tipo;
	}

	public function getOrden_tipo(){
		return $this->orden_tipo;
	}
	public function getOrden(){
		return $this->orden;
	}

	public function getId_prop_carac(){
		return $this->id_prop_carac;
	}

	public function getId_prop(){
		return $this->id_prop;
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

	public function getTipo(){
		return $this->tipo;
	}

	public function getTitulo(){
		return $this->titulo;
	}

	public function getTipo_carac(){
		return $this->tipo_carac;
	}

	public function __toString(){
		$str='';
		$str.='id_prop_carac : '.$this->id_prop_carac.'; ';
		$str.='id_prop : '.$this->id_prop.'; ';
		$str.='id_carac : '.$this->id_carac.'; ';
		$str.='contenido : '.$this->contenido.'; ';
		$str.='comentario : '.$this->comentario.'; ';
		$str.='tipo : '.$this->tipo.'; ';
		$str.='titulo : '.$this->titulo.'; ';
		$str.='tipo_carac : '.$this->tipo_carac.'; ';
		$str.='orden_tipo : '.$this->orden_tipo.'; ';
		$str.='orden : '.$this->orden.'; ';

		return $str;
	}

}

?>