<?php
/**
 * Clase Empia para la definicion de la logica de negocios.
 * Utiliza dos variables empias de la clase que las hereda llamadas
 * 		"clase" que define la base del nombre, debe tener la Primer letra en Mayuscula y responder a la base de los nombres
 * 					de los metodos empios.
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
include_once("clases/class.datosemp.php");
include_once("clases/class.datosempPGDAO.php");


class DatosempBSN extends BSN {
	
	protected $clase = "Datosemp";
	protected $nombreId = "Id_emp_carac";
	protected $datosemp;

	
	public function __construct($_Id_emp_carac=0,$_datosemp=''){
		DatosempBSN::seteaMapa();
		if ($_Id_emp_carac  instanceof Datosemp ){
			DatosempBSN::creaObjeto();
			DatosempBSN::seteaBSN($_Id_emp_carac);
		} else {
			if (is_numeric($_Id_emp_carac)){
				DatosempBSN::creaObjeto();
				if($_Id_emp_carac!=0){
					DatosempBSN::cargaById($_Id_emp_carac);
				}
			}
		}	

	}
	
/**
 * retorna el ID del objeto 
 *
 * @return id del objeto
 */
	public function getId(){
		return $this->datosemp->getId_emp_carac();
	}
	
/**
 * Setea e ID del objeto
 *
 * @param unknown_type $id
 */
	public function setId($id){
		$this->datosemp->setId_emp_carac($id);
	}

	public function grabaCaracteristica_Emp($id_emp,$arrayCarac){
		$datos = new Datosemp();
		$datos->setId_emp($id_emp);
		$datosBSN = new DatosempBSN($datos);
		$retorno=$datosBSN->borraDB();
		if($retorno){
			$claves=array_keys($arrayCarac);
			foreach ($claves as $id_carac){
				$datos->setId_carac($id_carac);
				$datos->setContenido($arrayCarac[$id_carac]['contenido']);
				$datos->setComentario($arrayCarac[$id_carac]['comentario']);
				$datosBSN->seteaBSN($datos);
				$retorno=$datosBSN->insertaDB();			
				if(!$retorno){
					break;
				}
			}
		}
		return $retorno;
	}
	
	public function cargaColeccionEmpCarac($id_emp,$id_carac){
		$array=array();
		$datosemp=new Datosemp();
		$datosemp->setId_emp($id_emp);		
		$datosemp->setId_carac($id_carac);
		$arrayTabla=$this->mapa->objTOtabla($datosemp);
		$datoDB = new DatosempPGDAO($arrayTabla);
		$array = $this->leeDBArray($datoDB->findByClave());
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}

	public function coleccionCaracteristicasEmp($id_emp,$activa=-1){
		$datos=new Datosemp();
		$datos->setId_emp($id_emp);
		$datosaux= new DatosempBSN($datos);
		$datosDB = new DatosempPGDAO($datosaux->getArrayTabla());
		$this->seteaArray($datosDB->coleccionCaracteristicasByEmp($activa));
		$array = $this->datosemp;
		$retorno=array();
		foreach ($array as $reg){
			$retorno[]=$this->mapa->tablaTOform($reg);
		}
		return $retorno;
	}	

	public function coleccionCaracteristicasEmpActivas(){
		$datosDB = new DatosempPGDAO();
		$this->seteaArray($datosDB->coleccionCaracteristicasEmpActivas());
		$array = $this->datosemp;
		$retorno=array();
		foreach ($array as $reg){
			$retorno[]=$this->mapa->tablaTOform($reg);
		}
		return $retorno;
	}	
	
	public function publicarCaracEmprendimiento(){
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new DatosempPGDAO($arrayTabla);
		$retorno=$propDB->activar();
		return $retorno;
	}

/**
 * Desactiva la evento para ser publicada
 *
 * @return estado de la finalizacion de la operacion
 */
	public function quitarCaracEmprendimiento(){
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new DatosempPGDAO($arrayTabla);
		$retorno=$propDB->desactivar();
		return $retorno;
	}
	
}

?>