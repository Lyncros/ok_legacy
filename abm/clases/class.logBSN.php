<?php
/**
 * Clase Propia para la definicion de la logica de negocios.
 * Utiliza dos variables propias de la clase que las hereda llamadas
 * 		"clase" que define la base del nombre, debe tener la Primer letra en Mayuscula y responder a la base de los nombres
 * 					de los metodos propios.
 * 		"objeto" que define el nombre del objeto tipo de la clase, cuyo nombre debe ser igual al de la
 * 					clase pero todo en minuscula
 *
 * En la clase derivada se deben definir metodos que ejecuten
 * 		getId		-> Retorna el Id de la clase
 * 		getClave	-> Retorna la Clave de la clase
 *
 *
 * Ejemplo del uso del retorno de la clase para el armado de un metodo de la clase derivada.
 * 	public function muestra(){
 *		$var='muestra'.$this->getClase();
 *		$this->{$var}();
 *	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.log.php");
include_once("clases/class.logPGDAO.php");
include_once("generic_class/class.cargaConfiguracion.php");


class LogBSN extends BSN {

	protected $clase = "Log";
	protected $nombreId = "id_log";
	protected $log;

	public static  $INICIO ='Comienzo';
	public static  $FIN = 'Finalizacion';
	public static  $ERROR = 'Fallo';
	public static  $RECHAZO = 'Rechazo';

	public function __construct($_id_log=0) {
		LogBSN::seteaMapa();
		if ($_id_log  instanceof Log ) {
			LogBSN::creaObjeto();
			LogBSN::seteaBSN($_id_log);
		} else {
			if (is_numeric($_id_log)) {
				LogBSN::creaObjeto();
				if($_id_log!=0) {
					LogBSN::cargaById($_id_log);
				}
			}
		}

	}

	/**
	 * retorna el ID del objeto
	 *
	 * @return id del objeto
	 */
	public function getId() {
		return $this->log->getId_log();
	}

	/**
	 * Setea e ID del objeto
	 *
	 * @param unknown_type $id
	 */
	public function setId($id) {
		$this->log->setId_log($id);
	}

	public function seteaLog($id_log=0,
	$fecha='',
	$id_user=0,
	$tarea='',
	$id=0,
	$proceso='',
	$estado='',
	$observacion=''
	){
		$log = new Log();
		$log->setId_log($id_log);
		$log->setFecha($fecha);
		$log->setId_user($id_user);
		$log->setTarea($tarea);
		$log->setId($id);
		$log->setProceso($proceso);
		$log->setEstado($estado);
		$log->setObservacion($observacion);
		$this->seteaBSN($log);
	}

	public function cargaRelacionLog($id_relacion=0,$relacion='g'){
		$datoDB = new LogPGDAO($this->getArrayTabla());
		$this->seteaArray($datoDB->colecionRelacionLog($id_relacion,$relacion));
		$array=$this->log;
		$arrayform=array();
		foreach ($array as $registro) {
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}

	public function registrarLog($id_log=0,
	$fecha='',
	$id_user=0,
	$tarea='',
	$id=0,
	$proceso='',
	$estado='',
	$observacion=''
	){
		$this->seteaLog($id_log,$fecha,$id_user,$tarea,$id,$proceso,$estado,$observacion);
		$this->insertaDB();
	}

}


?>