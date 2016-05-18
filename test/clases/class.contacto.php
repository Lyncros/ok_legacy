<?php
//`id_cont``razon``cuit``tipo_responsable``nombre``apellido`, `rubro`, `email``observacion`
class Contacto {
	private $id_cont;
	private $razon;
	private $cuit;
	private $tipo_responsable;
	private $nombre;
	private $apellido;
	private $id_rubro;
	private $email;
	private $observacion;
        private $web;


	public function __construct($id_cont=0,					$apellido="",
								$nombre="",					$email="",
								$razon='',					$cuit='',
								$tipo_responsable='',
								$id_rubro=0,				$observacion='',
                                                                $web=''
								) {
			
		Contacto::setId_cont($id_cont);
		Contacto::setEmail($email);
		Contacto::setRazon($razon);
		Contacto::setCuit($cuit);
		Contacto::setApellido($apellido);
		Contacto::setNombre($nombre);
		Contacto::setObservacion($observacion);
		Contacto::setId_rubro($id_rubro);
		Contacto::setTipo_responsable($tipo_responsable);
                Contacto::setWeb($web);
	}


	/**
	 * Setea los valores del Login Local con los del objeto pasado como parametro
	 *
	 * @param objeto tipo Login
	 */
	public function seteaContacto($_contacto){
		$this->setId_cont($_contacto->getId_cont());
		$this->setEmail($_contacto->getEmail());
		$this->setApellido($_contacto->getApellido());
		$this->setNombre($_contacto->getNombre());
		$this->setObservacion($_contacto->getObservacion());
		$this->setId_rubro($_contacto->getId_rubro());
		$this->setTipo_responsable($_contacto->getTipo_responsable());
		$this->setCuit($_contacto->getCuit());
		$this->setRazon($_contacto->getRazon());
		$this->setWeb($_contacto->getWeb());
	}

        public function getWeb() {
            return $this->web;
        }

        public function setWeb($web) {
            $this->web = $web;
        }

        public function setCuit($_cuit){
		$this->cuit=$_cuit;
	}

	public function getCuit(){
		return $this->cuit;
	}

	public function setRazon($_razon){
		$this->razon = $_razon;
	}

	public function getRazon(){
		return $this->razon;
	}

	public function setTipo_responsable($_tipo_responsable){
		$this->tipo_responsable=$_tipo_responsable;
	}

	public function setId_rubro($_id_rubro){
		$this->id_rubro = $_id_rubro;
	}

	public function setId_cont($_id){
		$this->id_cont=$_id;
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

	public function setObservacion($_observacion){
		$this->observacion=$_observacion;
	}

	public function setProvincia($_provincia){
		$this->provincia=$_provincia;
	}

	public function getId_rubro(){
		return $this->id_rubro;
	}

	public function getId_cont(){
		return $this->id_cont;
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

	public function getObservacion(){
		return $this->observacion;
	}

	public function getTipo_responsable(){
		return $this->tipo_responsable;
	}

	public function __toString(){
		$str="";
		$str.='Usuario: '.$this->getUsuario().'; ';
		$str.='Clave: '.$this->getClave().'; ';
		$str.='Validez: '.$this->getValidez().'; ';
		$str.='Fecha_base: '.$this->getFecha_base().'; ';
		$str.='Errores: '.$this->getErrores().'; ';
		$str.='Nueva_clave: '.$this->getNueva_clave().'; ';
		$str.='Fecha_nueva: '.$this->getFecha_nueva().'; ';
		$str.='Maxfallos: '.$this->getMaxfallos().'; ';
		$str.='Validez_nueva: '.$this->getValidez_nueva().'; ';
		$str.='Id_cont: '.$this->getId_cont().'; ';
		$str.='Razon: '.$this->getRazon().'; ';
		$str.='Email: '.$this->getEmail().'; ';
		$str.='Apellido: '.$this->getApellido().'; ';
		$str.='Nombre: '.$this->getNombre().'; ';
		$str.='Observacion: '.$this->getObservacion().'; ';
		$str.='Id_rubro: '.$this->getId_rubro().'; ';
		$str.='Tipo_responsable: '.$this->getTipo_responsable().'; ';
		$str.='Cuit: '.$this->getCuit().'; ';
                $str.='Web: '.$this->getWeb().'; ';
		return $str;

	}

} // Fin Clase Login User

?>