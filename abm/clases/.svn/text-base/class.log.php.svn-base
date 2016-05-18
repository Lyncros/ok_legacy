<?php


class Log {
	protected $id_log;
	protected $fecha;
	protected $tarea;
	protected $id;
	protected $proceso;
	protected $estado;
	protected $observacion;
	protected $id_user;


	public function __construct($id_log=0,
	$fecha='',
	$id_user=0,
	$tarea='',
	$id=0,
	$proceso='',
	$estado='',
	$observacion=''
	){

		Log::setId_log($id_log);
		Log::setFecha($fecha);
		Log::setTarea($tarea);
		Log::setId($id);
		Log::setProceso($proceso);
		Log::setEstado($estado);
		Log::setObservacion($observacion);
		Log::setId_user($id_user);
	}

	public function seteaLog($_log){
		$this->setId_log($_log->getId_log());
		$this->setFecha($_log->getFecha());
		$this->setTarea($_log->getTarea());
		$this->setId($_log->getId());
		$this->setProceso($_log->getProceso());
		$this->setEstado($_log->getEstado());
		$this->setObservacion($_log->getObservacion());
		$this->setId_user($_log->getId_user());
	}

	public function setId_user($id){
		$this->id_user=$id;
	}
	
	public function getId_user(){
		return $this->id_user;
	}
	
	public function setObservacion($_observ){
		$this->observacion=$_observ;
	}
	
	public function getObservacion(){
		return $this->observacion;
	}
	
	public function setTarea($_cont){
		$this->tarea=$_cont;
	}

	public function setId_log($_id_log){
		$this->id_log = $_id_log;
	}

	public function setId($_id){
		$this->id = $_id;
	}

	
	public function setEstado($estado){
		$this->estado = $estado;
	}

	public function setFecha($_fecha){
		$this->fecha = $_fecha;
	}

	public function setProceso($_proceso){
		$this->proceso = $_proceso;
	}

	public function getProceso(){
		return $this->proceso;
	}


	public function getTarea(){
		return $this->tarea;
	}

	public function getId_log(){
		return $this->id_log;
	}

	public function getId(){
		return $this->id;
	}

	public function getEstado(){
		return $this->estado;
	}

	public function getFecha(){
		return $this->fecha;
	}


}

?>