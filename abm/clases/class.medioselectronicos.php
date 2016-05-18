<?php

class Medioselectronicos {



	private $id_medio;
	private $id_cli;
	private $id_tipomed;
	private $contacto;
        private $comentario;
	private $principal;
       	private $tipocont;

	public function __construct($id_medio=0,
	$id_tipomed='',
	$contacto='',
	$comentario='',
	$tipocont='',
	$id_cli=0,
	$principal=0
	){

		Medioselectronicos::setId_medio($id_medio);
		Medioselectronicos::setId_tipomed($id_tipomed);
		Medioselectronicos::setContacto($contacto);
		Medioselectronicos::setComentario($comentario);
		Medioselectronicos::setTipocont($tipocont);
		Medioselectronicos::setId_cli($id_cli);
		Medioselectronicos::setPrincipal($principal);
	}


	public function seteaMedioselectronicos($_medioselectronicos){
		$this->setId_medio($_medioselectronicos->getId_medio());
		$this->setId_tipomed($_medioselectronicos->getId_tipomed());
		$this->setContacto($_medioselectronicos->getContacto());
		$this->setComentario($_medioselectronicos->getComentario());
		$this->setTipocont($_medioselectronicos->getTipocont());
		$this->setId_cli($_medioselectronicos->getId_cli());
		$this->setPrincipal($_medioselectronicos->getPrincipal());
	}

	public function setPrincipal($_tipo){
		$this->principal=$_tipo;
	}

	public function getPrincipal(){
		return $this->principal;
	}
	
	public function setTipocont($_tipo){
		$this->tipocont=$_tipo;
	}

	public function getTipocont(){
		return $this->tipocont;
	}

	public function setId_cli($_id){
		$this->id_cli=$_id;
	}

	public function getId_cli(){
		return $this->id_cli;
	}

	public function setId_tipomed($_tipo){
		$this->id_tipomed=$_tipo;
	}

	public function getId_tipomed(){
		return $this->id_tipomed;
	}

	public function setId_medio($_id_medio){
		$this->id_medio = $_id_medio;
	}

	public function setContacto($_pais){
		$this->contacto = $_pais;
	}

	public function setComentario($comentario){
		$this->comentario=$comentario;
	}

	public function getId_medio(){
		return $this->id_medio;
	}

	public function getContacto(){
		return $this->contacto;
	}

	public function getComentario(){
		return $this->comentario;
	}

	public function __toString(){
		$str='';
		$str.='Id_medio: '.$this->getId_medio().'; ';
		$str.='Id_tipomed: '.$this->getId_tipomed().'; ';
		$str.='Contacto: '.$this->getContacto().'; ';
		$str.='Comentario: '.$this->getComentario().'; ';
		$str.='Tipocont: '.$this->getTipocont().'; ';
		$str.='Id_cli: '.$this->getId_cli().'; ';
		$str.='Principal: '.$this->getPrincipal().'; ';
		return $str;
	}
}

?>