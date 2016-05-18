<?php

class Fotoemp {

	private $id_foto;
	private $id_emp_carac;
	private $foto;
	private $posicion;
	
/*	  id_foto int(10) unsigned NOT NULL auto_increment,
  id_emp_carac int(10) unsigned NOT NULL,
  foto varchar(500) NOT NULL,
  posicion int(10) unsigned NOT NULL,
*/
	public function __construct($id_foto=0,
								$id_emp_carac=0,
								$foto='',
								$posicion=0 ){
								
		Fotoemp::setId_foto($id_foto);
		Fotoemp::setId_emp_carac($id_emp_carac);
		Fotoemp::setFoto($foto);
		Fotoemp::setPosicion($posicion);
	}

	
	public function seteaFotoemp($_foto){
		$this->setId_foto($_foto->getId_foto());
		$this->setId_emp_carac($_foto->getId_emp_carac());
		$this->setFoto($_foto->getFoto());
		$this->setPosicion($_foto->getPosicion());
	}
	
	
	public function setId_foto($_id_foto){
		$this->id_foto = $_id_foto;
	}
	
	public function setId_emp_carac($id_emp_carac){
		$this->id_emp_carac = $id_emp_carac;
	}

	public function setFoto($_foto){
		$this->foto = $_foto;
	}

	public function setPosicion($_posicion){
		$this->posicion = $_posicion;
	}
	
	public function getId_foto(){
		return $this->id_foto;
	}
	
	public function getId_emp_carac(){
		return $this->id_emp_carac;
	}
	
	public function getFoto(){
		return $this->foto;
	}
	
	public function getPosicion(){
		return $this->posicion;
	}
}

?>