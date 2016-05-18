<?php

class Telefonos {



	private $id_telefono;
	private $codpais;
	private $codarea;
	private $numero;
	private $interno;
	private $tipotel;
	private $tipocont;
	private $id_cont;
	private $principal;

	public function __construct($id_telefono=0,
	$tipotel='',
	$codpais='',
	$codarea='',
	$numero='',
	$interno='',
	$tipocont='',
	$id_cont=0,
	$principal=0
	){

		Telefonos::setId_telefono($id_telefono);
		Telefonos::setTipotel($tipotel);
		Telefonos::setCodpais($codpais);
		Telefonos::setCodarea($codarea);
		Telefonos::setNumero($numero);
		Telefonos::setInterno($interno);
		Telefonos::setTipocont($tipocont);
		Telefonos::setId_cont($id_cont);
		Telefonos::setPrincipal($principal);
	}


	public function seteaTelefonos($_telefonos){
		$this->setId_telefono($_telefonos->getId_telefono());
		$this->setTipotel($_telefonos->getTipotel());
		$this->setCodpais($_telefonos->getCodpais());
		$this->setCodarea($_telefonos->getCodarea());
		$this->setNumero($_telefonos->getNumero());
		$this->setInterno($_telefonos->getInterno());
		$this->setTipocont($_telefonos->getTipocont());
		$this->setId_cont($_telefonos->getId_cont());
		$this->setPrincipal($_telefonos->getPrincipal());
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

	public function setId_cont($_id){
		$this->id_cont=$_id;
	}

	public function getId_cont(){
		return $this->id_cont;
	}

	public function setTipotel($_tipo){
		$this->tipotel=$_tipo;
	}

	public function getTipotel(){
		return $this->tipotel;
	}

	public function setInterno($_publ){
		$this->interno = $_publ;
	}

	public function getInterno(){
		return $this->interno;
	}

	public function setId_telefono($_id_telefono){
		$this->id_telefono = $_id_telefono;
	}

	public function setCodpais($_pais){
		$this->codpais = $_pais;
	}

	public function setCodarea($codarea){
		$this->codarea=$codarea;
	}

	public function setNumero($col){
		$this->numero=$col;
	}


	public function getId_telefono(){
		return $this->id_telefono;
	}

	public function getCodpais(){
		return $this->codpais;
	}

	public function getCodarea(){
		return $this->codarea;
	}

	public function getNumero(){
		return $this->numero;
	}
	
	public function __toString(){
		$str='';
		$str.='Id_telefono: '.$this->getId_telefono().'; ';
		$str.='Tipotel: '.$this->getTipotel().'; ';
		$str.='Codpais: '.$this->getCodpais().'; ';
		$str.='Codarea: '.$this->getCodarea().'; ';
		$str.='Numero: '.$this->getNumero().'; ';
		$str.='Interno: '.$this->getInterno().'; ';
		$str.='Tipocont: '.$this->getTipocont().'; ';
		$str.='Id_cont: '.$this->getId_cont().'; ';
		$str.='Principal: '.$this->getPrincipal().'; ';
		return $str;
	}
}

?>