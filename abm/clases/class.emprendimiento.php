<?php

class Emprendimiento {

	private $id_tipo_emp;
	private $nombre;
	private $id_emp;
	private $id_ubica;
	private $ubicacion;
	private $descripcion;
	private $logo;
	private $foto;
	private $comentario;
	private $goglat;
	private $goglong;
	private $activa;
        private $estado;

	public function __construct(
	$id_emp=0,
	$nombre='',
	$id_ubica=0,
	$ubicacion='',
	$descripcion='',
	$id_tipo_emp=0,
        $estado='',
	$logo='',
	$foto='',
	$comentario='',
	$goglat=0,
	$goglong=0,
	$activa=0
	){

		Emprendimiento::setId_tipo_emp($id_tipo_emp);
		Emprendimiento::setNombre($nombre);
		Emprendimiento::setId_emp($id_emp);
		Emprendimiento::setId_ubica($id_ubica);
		Emprendimiento::setUbicacion($ubicacion);
		Emprendimiento::setDescripcion($descripcion);
		Emprendimiento::setLogo($logo);
		Emprendimiento::setComentario($comentario);
		Emprendimiento::setFoto($foto);
		Emprendimiento::setGoglat($goglat);
		Emprendimiento::setGoglong($goglong);
		Emprendimiento::setActiva($activa);
                Emprendimiento::setEstado($estado);
	}


	public function seteaEmprendimiento($_tipo_emp){
		$this->setId_tipo_emp($_tipo_emp->getId_tipo_emp());
		$this->setNombre($_tipo_emp->getNombre());
		$this->setId_emp($_tipo_emp->getId_emp());
		$this->setId_ubica($_tipo_emp->getId_ubica());
		$this->setUbicacion($_tipo_emp->getUbicacion());
		$this->setDescripcion($_tipo_emp->getDescripcion());
		$this->setLogo($_tipo_emp->getLogo());
		$this->setComentario($_tipo_emp->getComentario());
		$this->setFoto($_tipo_emp->getFoto());
		$this->setGoglat($_tipo_emp->getGoglat());
		$this->setGoglong($_tipo_emp->getGoglong());
		$this->setActiva($_tipo_emp->getActiva());
		$this->setEstado($_tipo_emp->getEstado());
	}

        public function getEstado() {
            return $this->estado;
        }

        public function setEstado($estado) {
            $this->estado = $estado;
        }

        	public function setActiva($activa){
		$this->activa = $activa;
	}

	public function getActiva(){
		return $this->activa;
	}

	public function setId_tipo_emp($_id_tipo_emp){
		$this->id_tipo_emp = $_id_tipo_emp;
	}

	public function setNombre($nombre){
		$this->nombre=$nombre;
	}

	public function setId_emp($id_emp){
		$this->id_emp = $id_emp;
	}

	public function setId_ubica($id_ubica){
		$this->id_ubica=$id_ubica;
	}

        public function setGoglat($lat){
		$this->goglat=$lat;
	}

	public function setGoglong($long){
		$this->goglong=$long;
	}

	public function setUbicacion($ubicacion){
		$this->ubicacion=$ubicacion;
	}

	public function setDescripcion($descripcion){
		$this->descripcion=$descripcion;
	}

	public function setLogo($logo){
		$this->logo=$logo;
	}

	public function setComentario($comen){
		$this->comentario=$comen;
	}

	public function setFoto($foto){
		$this->foto=$foto;
	}

	public function getNombre(){
		return $this->nombre;
	}

	public function getGoglat(){
		return $this->goglat;
	}

	public function getGoglong(){
		return $this->goglong;
	}


	public function getId_emp(){
		return $this->id_emp;
	}

	public function getId_ubica(){
		return $this->id_ubica;
	}
	
        public function getUbicacion(){
		return $this->ubicacion;
	}

	public function getDescripcion(){
		return $this->descripcion;
	}


	public function getId_tipo_emp(){
		return $this->id_tipo_emp;
	}

	public function getLogo(){
		return  $this->logo;
	}

	public function getComentario(){
		return $this->comentario;
	}

	public function getFoto(){
		return $this->foto;
	}

	public function __toString(){
		$str='';
		$str.='id_emp: '.$id_emp.', ';
		$str.='id_tipo_emp: '.$id_tipo_emp.', ';
		$str.='estado: '.$estado.', ';
		$str.='nombre: '.$nombre.', ';
		$str.='id_ubica: '.$id_ubica.', ';
		$str.='ubicacion: '.$ubicacion.', ';
		$str.='descripcion: '.$descripcion.', ';
		$str.='logo: '.$logo.', ';
		$str.='foto: '.$foto.', ';
		$str.='comentario: '.$comentario.', ';
		$str.='goglat: '.$goglat.', ';
		$str.='goglong: '.$goglong.', ';
		$str.='activa: '.$activa.', ';

		return $str;
	}

}

?>
