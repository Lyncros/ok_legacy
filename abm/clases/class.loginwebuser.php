<?php

include_once("clases/class.login.php");

/*
 create table logon.usuarios (
 id_user int(10) unsigned NOT NULL auto_increment,
 usuario character(10) NOT NULL,
 nombre character varying(50) NOT NULL,
 apellido character varying(50) NOT NULL,
 email character varying(50) NOT NULL,
 telefono character varying(50),
 clave character varying(255) NOT NULL,
 fecha_base date,
 nueva_clave character varying(255),
 fecha_nueva date,
 errores integer NOT NULL DEFAULT 0,
 PRIMARY KEY  (`id_user`)
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

 */
class Loginwebuser extends Login {
	private $id_user;
	private $email;
	private $apellido;
	private $nombre;
	private $telefono;
	private $activa;


	public function __construct($id_user=0,					$usuario="",
	$clave="",					$apellido="",
	$nombre="",					$telefono="",
	$email="",					$activa=0,
	$fecha_base="",				$errores=0,
	$nueva_clave="",			$fecha_nueva="",
	$maxfallos=4,				$validez=0,
	$validez_nueva=15								) {
			
		parent::__construct($id_user,	$usuario,				$clave,
		$fecha_base,			$errores,
		$nueva_clave,			$fecha_nueva,
		$maxfallos,				$validez,
		$validez_nueva);

		//	Loginwebuser::setId_user($id_user);
		Loginwebuser::setEmail($email);
		Loginwebuser::setApellido($apellido);
		Loginwebuser::setNombre($nombre);
		Loginwebuser::setTelefono($telefono);
		Loginwebuser::setActiva($activa);
	}


	/**
	 * Setea los valores del Login Local con los del objeto pasado como parametro
	 *
	 * @param objeto tipo Login
	 */
	public function seteaLoginwebuser($_login){
		$this->setUsuario($_login->getUsuario());
		$this->setClave($_login->getClave());
		$this->setValidez($_login->getValidez());
		$this->setFecha_base($_login->getFecha_base());
		$this->setErrores($_login->getErrores());
		$this->setNueva_clave($_login->getNueva_clave());
		$this->setFecha_nueva($_login->getFecha_nueva());
		$this->setMaxfallos($_login->getMaxfallos());
		$this->setValidez_nueva($_login->getValidez_nueva());
		$this->setId_user($_login->getId_user());
		$this->setEmail($_login->getEmail());
		$this->setApellido($_login->getApellido());
		$this->setNombre($_login->getNombre());
		$this->setTelefono($_login->getTelefono());
		$this->setActiva($_login->getActiva());
	}

	public function setActiva($_activa){
		$this->activa = $_activa;
	}

	public function setId_user($_id){
		$this->id_user=$_id;
	}

	public function setEmail($_email){
		$this->email=$_email;
	}

	public function setApellido($_apellido){
		$this->apellido=$_apellido;
	}

	public function setNombre($_nombre){
		$this->nombre=$_nombre;
	}

	public function setTelefono($_telefono){
		$this->telefono=$_telefono;
	}
	 
	public function setProvincia($_provincia){
		$this->provincia=$_provincia;
	}

	public function getActiva(){
		return $this->activa;
	}
	 
	public function getId_user(){
		return $this->id_user;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getApellido(){
		return $this->apellido;
	}

	public function getNombre(){
		return $this->nombre;
	}

	public function getTelefono(){
		return $this->telefono;
	}

	public function __toString(){
		$str='';
		$str.='Usuario: '.$this->getUsuario().'; ';
		$str.='Clave: '.$this->getClave().'; ';
		$str.='Validez: '.$this->getValidez().'; ';
		$str.='Fecha_base: '.$this->getFecha_base().'; ';
		$str.='Errores: '.$this->getErrores().'; ';
		$str.='Nueva_clave: '.$this->getNueva_clave().'; ';
		$str.='Fecha_nueva: '.$this->getFecha_nueva().'; ';
		$str.='Maxfallos: '.$this->getMaxfallos().'; ';
		$str.='Validez_nueva: '.$this->getValidez_nueva().'; ';
		$str.='Id_user: '.$this->getId_user().'; ';
		$str.='Email: '.$this->getEmail().'; ';
		$str.='Apellido: '.$this->getApellido().'; ';
		$str.='Nombre: '.$this->getNombre().'; ';
		$str.='Telefono: '.$this->getTelefono().'; ';
		$str.='Activa: '.$this->getActiva().'; ';
		return $str;
	}
} // Fin Clase Login User

?>