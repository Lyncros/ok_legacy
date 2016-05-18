<?php

class Domicilio {



	private $id_dom;
	private $tipodom;
	private $id_ubica;
//	private $id_zona;
//	private $id_loca;
	private $calle;
	private $nro;
	private $piso;
	private $dpto;
	private $entre1;
	private $entre2;
	private $cp;
	private $tipocont;
	private $id_cont;
	private $principal;

	public function __construct($id_dom=0,
	$tipodom='',
	$id_ubica=0,
//	$id_zona=0,
//	$id_loca=0,
	$calle='',
	$nro='',
	$piso='',
	$dpto='',
	$entre1='',
	$entre2='',
	$cp='',
	$tipocont='',
	$id_cont=0,
	$principal=0
	){

		Domicilio::setId_dom($id_dom);
		Domicilio::setTipodom($tipodom);
		Domicilio::setId_ubica($id_ubica);
//		Domicilio::setId_zona($id_zona);
//		Domicilio::setId_loca($id_loca);
		Domicilio::setCalle($calle);
		Domicilio::setNro($nro);
		Domicilio::setPiso($piso);
		Domicilio::setDpto($dpto);
		Domicilio::setEntre1($entre1);
		Domicilio::setEntre2($entre2);
		Domicilio::setCp($cp);
		Domicilio::setTipocont($tipocont);
		Domicilio::setId_cont($id_cont);
		Domicilio::setPrincipal($principal);
	}


	public function seteaDomicilio($_domicilio){
		$this->setId_dom($_domicilio->getId_dom());
		$this->setTipodom($_domicilio->getTipodom());
		$this->setId_ubica($_domicilio->getId_ubica());
//		$this->setId_zona($_domicilio->getId_zona());
//		$this->setId_loca($_domicilio->getId_loca());
		$this->setCalle($_domicilio->getCalle());
		$this->setNro($_domicilio->getNro());
		$this->setPiso($_domicilio->getPiso());
		$this->setDpto($_domicilio->getDpto());
		$this->setEntre1($_domicilio->getEntre1());
		$this->setEntre2($_domicilio->getEntre2());
		$this->setCp($_domicilio->getCp());
		$this->setTipocont($_domicilio->getTipocont());
		$this->setId_cont($_domicilio->getId_cont());
		$this->setPrincipal($_domicilio->getPrincipal());
	}

	public function setCp($_cp){
		$this->cp=$_cp;
	}

	public function getCp(){
		return $this->cp;
	}

	public function setDpto($_dpto){
		return $this->dpto=$_dpto;
	}

	public function getDpto(){
		return $this->dpto;
	}

	public function setEntre1($_entre){
		$this->entre1=$_entre;
	}

	public function getEntre1(){
		return $this->entre1;
	}

	public function setEntre2($_entre){
		$this->entre2=$_entre;
	}

	public function getEntre2(){
		return $this->entre2;
	}

	public function setPiso($_piso){
		$this->piso=$_piso;
	}

	public function getPiso(){
		return $this->piso;
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

	public function setTipodom($_tipo){
		$this->tipodom=$_tipo;
	}

	public function getTipodom(){
		return $this->tipodom;
	}

	public function setNro($_publ){
		$this->nro = $_publ;
	}

	public function getNro(){
		return $this->nro;
	}

	public function setId_dom($_id_dom){
		$this->id_dom = $_id_dom;
	}

	public function setId_ubica($_id_ubica){
		$this->id_ubica = $_id_ubica;
	}
/*	
	public function setId_zona($_pais){
		$this->id_zona = $_pais;
	}

	public function setId_loca($id_loca){
		$this->id_loca=$id_loca;
	}
*/
	public function setCalle($col){
		$this->calle=$col;
	}


	public function getId_dom(){
		return $this->id_dom;
	}

	public function getId_ubica(){
		return $this->id_ubica;
	}
/*	
	public function getId_zona(){
		return $this->id_zona;
	}

	public function getId_loca(){
		return $this->id_loca;
	}
*/
	public function getCalle(){
		return $this->calle;
	}

	public function __toString(){
		$str='';
		$str.='id_dom: '.$this->id_dom.' ;';
		$str.='tipodom: '.$this->tipodom.' ;';
		$str.='id_ubica: '.$this->id_ubica.' ;';
//		$str.='id_zona: '.$this->id_zona.' ;';
//		$str.='id_loca: '.$this->id_loca.' ;';
		$str.='calle: '.$this->calle.' ;';
		$str.='nro: '.$this->nro.' ;';
		$str.='piso: '.$this->piso.' ;';
		$str.='dpto: '.$this->dpto.' ;';
		$str.='entre1: '.$this->entre1.' ;';
		$str.='entre2: '.$this->entre2.' ;';
		$str.='cp: '.$this->cp.' ;';
		$str.='tipocont: '.$this->tipocont.' ;';
		$str.='id_cont: '.$this->id_cont.' ;';
		$str.='principal: '.$this->principal.' ;';
		return $str;
	}


}

?>