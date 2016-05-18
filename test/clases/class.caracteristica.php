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
        private $tasacion;

        	public function __construct($id_carac=0,
								$id_tipo_carac=0,
								$titulo='',
								$tipo='CheckBox',  // C: checkbox   N: Numerico List    T: texto
								$maximo=0,
								$comentario='No',  // S: Acepta comentarios     N: NO acepta
								$lista='',
								$orden=0,
								$comparacion='',
								$publica=0,
                                                                $tasacion=0
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
                Caracteristica::setTasacion($tasacion);

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
                $this->setTasacion($_caract->getTasacion());
	}
	
	public function getTasacion() {
            return $this->tasacion;
        }

        public function setTasacion($tasacion) {
            $this->tasacion = $tasacion;
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
        
        public function __toString() {
            $str='';
            $str.='id_ipo_carac: '.$this->id_tipo_carac."; ";
            $str.='id_carac: '.$this->id_carac."; ";
            $str.='titulo: '.$this->titulo."; ";
            $str.='tipo: '.$this->tipo."; ";
            $str.='maximo: '.$this->maximo."; ";
            $str.='comentario: '.$this->comentario."; ";
            $str.='lista: '.$this->lista."; ";
            $str.='orden: '.$this->orden."; ";
            $str.='comparacion: '.$this->comparacion."; ";
            $str.='publica: '.$this->publica."; ";
            $str.='tasacion: '.$this->tasacion."; ";
            return $str;
      }
}

?>